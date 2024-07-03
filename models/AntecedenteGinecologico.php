<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_ginecologico".
 *
 * @property int $id
 * @property int|null $expediente_medico_id
 * @property int|null $p_menarca
 * @property int|null $p_menopausia
 * @property string|null $p_f_u_m
 * @property string|null $p_f_u_citologia
 * @property string|null $p_alteracion_frecuencia
 * @property string|null $p_alteracion_duracion
 * @property string|null $p_alteracion_cantidad
 * @property int|null $p_inicio_vida_s
 * @property int|null $p_no_parejas
 * @property int|null $p_vaginits
 * @property int|null $p_cervicitis_mucopurulenta
 * @property int|null $p_chancroide
 * @property int|null $p_clamidia
 * @property int|null $p_eip
 * @property int|null $p_gonorrea
 * @property int|null $p_hepatitis
 * @property int|null $p_herpes
 * @property int|null $p_lgv
 * @property int|null $p_molusco_cont
 * @property int|null $p_ladillas
 * @property int|null $p_sarna
 * @property int|null $p_sifilis
 * @property int|null $p_tricomoniasis
 * @property int|null $p_vb
 * @property int|null $p_vih
 * @property int|null $p_vph
 * @property string|null $p_tipo_anticoncepcion
 * @property string|null $p_inicio_anticoncepcion
 * @property string|null $p_suspension_anticoncepcion
 * @property string|null $observacion
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class AntecedenteGinecologico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_ginecologico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['expediente_medico_id', 'p_menarca', 'p_menopausia', 'p_inicio_vida_s', 'p_no_parejas', 'p_vaginits', 'p_cervicitis_mucopurulenta', 'p_chancroide', 'p_clamidia', 'p_eip', 'p_gonorrea', 'p_hepatitis', 'p_herpes', 'p_lgv', 'p_molusco_cont', 'p_ladillas', 'p_sarna', 'p_sifilis', 'p_tricomoniasis', 'p_vb', 'p_vih', 'p_vph'], 'integer'],
            [['p_f_u_m', 'p_f_u_citologia', 'p_inicio_anticoncepcion', 'p_suspension_anticoncepcion'], 'safe'],
            [['observacion'], 'string'],
            [['p_alteracion_frecuencia', 'p_alteracion_duracion', 'p_alteracion_cantidad'], 'string', 'max' => 25],
            [['p_tipo_anticoncepcion'], 'string', 'max' => 85],
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
            'p_menarca' => 'P Menarca',
            'p_menopausia' => 'P Menopausia',
            'p_f_u_m' => 'P F U M',
            'p_f_u_citologia' => 'P F U Citologia',
            'p_alteracion_frecuencia' => 'P Alteracion Frecuencia',
            'p_alteracion_duracion' => 'P Alteracion Duracion',
            'p_alteracion_cantidad' => 'P Alteracion Cantidad',
            'p_inicio_vida_s' => 'P Inicio Vida S',
            'p_no_parejas' => 'P No Parejas',
            'p_vaginits' => 'P Vaginits',
            'p_cervicitis_mucopurulenta' => 'P Cervicitis Mucopurulenta',
            'p_chancroide' => 'P Chancroide',
            'p_clamidia' => 'P Clamidia',
            'p_eip' => 'P Eip',
            'p_gonorrea' => 'P Gonorrea',
            'p_hepatitis' => 'P Hepatitis',
            'p_herpes' => 'P Herpes',
            'p_lgv' => 'P Lgv',
            'p_molusco_cont' => 'P Molusco Cont',
            'p_ladillas' => 'P Ladillas',
            'p_sarna' => 'P Sarna',
            'p_sifilis' => 'P Sifilis',
            'p_tricomoniasis' => 'P Tricomoniasis',
            'p_vb' => 'P Vb',
            'p_vih' => 'P Vih',
            'p_vph' => 'P Vph',
            'p_tipo_anticoncepcion' => 'P Tipo Anticoncepcion',
            'p_inicio_anticoncepcion' => 'P Inicio Anticoncepcion',
            'p_suspension_anticoncepcion' => 'P Suspension Anticoncepcion',
            'observacion' => 'Observacion',
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
        return $this->hasMany(ExpedienteMedico::class, ['antecedente_ginecologico_id' => 'id']);
    }
}
