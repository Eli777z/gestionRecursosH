<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_hereditario".
 *
 * @property int $id
 * @property int|null $expediente_medico_id
 * @property int|null $cat_antecedente_hereditario_id
 * @property string|null $parentezco
 * @property string|null $observacion
 *
 * @property CatAntecedenteHereditario $catAntecedenteHereditario
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class AntecedenteHereditario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_hereditario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expediente_medico_id', 'cat_antecedente_hereditario_id'], 'integer'],
            [['parentezco', 'observacion'], 'string'],
            [['cat_antecedente_hereditario_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatAntecedenteHereditario::class, 'targetAttribute' => ['cat_antecedente_hereditario_id' => 'id']],
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
            'expediente_medico_id' => 'Expediente Medico ID',
            'cat_antecedente_hereditario_id' => 'Cat Antecedente Hereditario ID',
            'parentezco' => 'Parentezco',
            'observacion' => 'Observacion',
        ];
    }

    /**
     * Gets query for [[CatAntecedenteHereditario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatAntecedenteHereditario()
    {
        return $this->hasOne(CatAntecedenteHereditario::class, ['id' => 'cat_antecedente_hereditario_id']);
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
        return $this->hasMany(ExpedienteMedico::class, ['antecedente_hereditario_id' => 'id']);
    }
}
