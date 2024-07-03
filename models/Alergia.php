<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "alergia".
 *
 * @property int $id
 * @property string|null $p_alergia
 * @property int|null $expediente_medico_id
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class Alergia extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'alergia';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_alergia'], 'string'],
            [['expediente_medico_id'], 'integer'],
            [['expediente_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpedienteMedico::class, 'targetAttribute' => ['expediente_medico_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'p_alergia' => 'P Alergia',
            'expediente_medico_id' => 'Expediente Medico ID',
        ];
    }

    /**
     * Gets query for [[ExpedienteMedico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedico()
    {
        return $this->hasOne(ExpedienteMedico::class, ['id' => 'expediente_medico_id']);
    }

    /**
     * Gets query for [[ExpedienteMedicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedicos()
    {
        return $this->hasMany(ExpedienteMedico::class, ['alergia_id' => 'id']);
    }
}
