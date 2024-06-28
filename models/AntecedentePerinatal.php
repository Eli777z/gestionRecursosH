<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_perinatal".
 *
 * @property int $id
 * @property string|null $p_hora_nacimiento
 * @property int|null $p_parto
 * @property int|null $p_cesarea
 * @property int|null $p_no_gestacion
 * @property int|null $p_edad_gestacional
 * @property string|null $p_sitio_parto
 * @property float|null $p_peso_nacer
 * @property float|null $p_talla
 * @property float|null $p_cefalico
 * @property float|null $p_toracico
 * @property float|null $p_abdominal
 * @property int|null $test
 * @property int|null $p_apgar
 * @property int|null $p_ballard
 * @property int|null $p_silverman
 * @property int|null $p_capurro
 * @property string|null $p_complicacion
 * @property int|null $p_anestesia
 * @property string|null $p_especifique_anestecia
 * @property int|null $p_apnea_neonatal
 * @property int|null $p_cianosis
 * @property int|null $p_hemorragias
 * @property int|null $p_ictericia
 * @property int|null $p_convulsiones
 * @property string|null $observacion
 * @property int|null $expediente_medico_id
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class AntecedentePerinatal extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_perinatal';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_hora_nacimiento'], 'safe'],
            [['p_parto', 'p_cesarea', 'p_no_gestacion', 'p_edad_gestacional', 'test', 'p_apgar', 'p_ballard', 'p_silverman', 'p_capurro', 'p_anestesia', 'p_apnea_neonatal', 'p_cianosis', 'p_hemorragias', 'p_ictericia', 'p_convulsiones', 'expediente_medico_id'], 'integer'],
            [['p_peso_nacer', 'p_talla', 'p_cefalico', 'p_toracico', 'p_abdominal'], 'number'],
            [['p_complicacion', 'p_especifique_anestecia', 'observacion'], 'string'],
            [['p_sitio_parto'], 'string', 'max' => 65],
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
            'p_hora_nacimiento' => 'P Hora Nacimiento',
            'p_parto' => 'P Parto',
            'p_cesarea' => 'P Cesarea',
            'p_no_gestacion' => 'P No Gestacion',
            'p_edad_gestacional' => 'P Edad Gestacional',
            'p_sitio_parto' => 'P Sitio Parto',
            'p_peso_nacer' => 'P Peso Nacer',
            'p_talla' => 'P Talla',
            'p_cefalico' => 'P Cefalico',
            'p_toracico' => 'P Toracico',
            'p_abdominal' => 'P Abdominal',
            'test' => 'Test',
            'p_apgar' => 'P Apgar',
            'p_ballard' => 'P Ballard',
            'p_silverman' => 'P Silverman',
            'p_capurro' => 'P Capurro',
            'p_complicacion' => 'P Complicacion',
            'p_anestesia' => 'P Anestesia',
            'p_especifique_anestecia' => 'P Especifique Anestecia',
            'p_apnea_neonatal' => 'P Apnea Neonatal',
            'p_cianosis' => 'P Cianosis',
            'p_hemorragias' => 'P Hemorragias',
            'p_ictericia' => 'P Ictericia',
            'p_convulsiones' => 'P Convulsiones',
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
        return $this->hasMany(ExpedienteMedico::class, ['antecedente_perinatal_id' => 'id']);
    }
}
