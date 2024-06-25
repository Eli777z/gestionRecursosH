<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "interrogatorio_medico".
 *
 * @property int $id
 * @property int|null $expediente_medico_id
 * @property string|null $desc_cardiovascular
 * @property string|null $desc_digestivo
 * @property string|null $desc_endocrino
 * @property string|null $desc_hemolinfatico
 * @property string|null $desc_mamas
 * @property string|null $desc_musculo_esqueletico
 * @property string|null $desc_piel_anexos
 * @property string|null $desc_reproductor
 * @property string|null $desc_respiratorio
 * @property string|null $desc_sistema_nervioso
 * @property string|null $desc_sistemas_generales
 * @property string|null $desc_urinario
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class InterrogatorioMedico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'interrogatorio_medico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expediente_medico_id'], 'integer'],
            [['desc_cardiovascular', 'desc_digestivo', 'desc_endocrino', 'desc_hemolinfatico', 'desc_mamas', 'desc_musculo_esqueletico', 'desc_piel_anexos', 'desc_reproductor', 'desc_respiratorio', 'desc_sistema_nervioso', 'desc_sistemas_generales', 'desc_urinario'], 'string'],
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
            'desc_cardiovascular' => 'Desc Cardiovascular',
            'desc_digestivo' => 'Desc Digestivo',
            'desc_endocrino' => 'Desc Endocrino',
            'desc_hemolinfatico' => 'Desc Hemolinfatico',
            'desc_mamas' => 'Desc Mamas',
            'desc_musculo_esqueletico' => 'Desc Musculo Esqueletico',
            'desc_piel_anexos' => 'Desc Piel Anexos',
            'desc_reproductor' => 'Desc Reproductor',
            'desc_respiratorio' => 'Desc Respiratorio',
            'desc_sistema_nervioso' => 'Desc Sistema Nervioso',
            'desc_sistemas_generales' => 'Desc Sistemas Generales',
            'desc_urinario' => 'Desc Urinario',
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
        return $this->hasMany(ExpedienteMedico::class, ['interrogatorio_medico_id' => 'id']);
    }
}
