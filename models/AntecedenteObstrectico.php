<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_obstrectico".
 *
 * @property int $id
 * @property string|null $p_f_p_p
 * @property int|null $p_gestacion
 * @property int|null $p_aborto
 * @property int|null $p_parto
 * @property int|null $p_cesarea
 * @property int|null $p_nacidos_vivo
 * @property int|null $p_nacidos_muerto
 * @property int|null $p_viven
 * @property int|null $p_muerto_primera_semana
 * @property int|null $p_muerto_despues_semana
 * @property int|null $p_intergenesia
 * @property int|null $p_malformaciones
 * @property int|null $p_atencion_prenatal
 * @property int|null $p_parto_prematuro
 * @property int|null $p_isoinmunizacion
 * @property int|null $p_no_consultas
 * @property string|null $p_medicacion_gestacional
 * @property string|null $observacion
 * @property int|null $expediente_medico_id
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class AntecedenteObstrectico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_obstrectico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_f_p_p'], 'safe'],
            [['p_gestacion', 'p_aborto', 'p_parto', 'p_cesarea', 'p_nacidos_vivo', 'p_nacidos_muerto', 'p_viven', 'p_muerto_primera_semana', 'p_muerto_despues_semana', 'p_intergenesia', 'p_malformaciones', 'p_atencion_prenatal', 'p_parto_prematuro', 'p_isoinmunizacion', 'p_no_consultas', 'expediente_medico_id'], 'integer'],
            [['p_medicacion_gestacional', 'observacion'], 'string'],
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
            'p_f_p_p' => 'P F P P',
            'p_gestacion' => 'P Gestacion',
            'p_aborto' => 'P Aborto',
            'p_parto' => 'P Parto',
            'p_cesarea' => 'P Cesarea',
            'p_nacidos_vivo' => 'P Nacidos Vivo',
            'p_nacidos_muerto' => 'P Nacidos Muerto',
            'p_viven' => 'P Viven',
            'p_muerto_primera_semana' => 'P Muerto Primera Semana',
            'p_muerto_despues_semana' => 'P Muerto Despues Semana',
            'p_intergenesia' => 'P Intergenesia',
            'p_malformaciones' => 'P Malformaciones',
            'p_atencion_prenatal' => 'P Atencion Prenatal',
            'p_parto_prematuro' => 'P Parto Prematuro',
            'p_isoinmunizacion' => 'P Isoinmunizacion',
            'p_no_consultas' => 'P No Consultas',
            'p_medicacion_gestacional' => 'P Medicacion Gestacional',
            'observacion' => 'Observacion',
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
        return $this->hasMany(ExpedienteMedico::class, ['antecedente_obstrectico_id' => 'id']);
    }
}
