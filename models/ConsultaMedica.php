<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
/**
 * This is the model class for table "consulta_medica".
 *
 * @property int $id
 * @property int|null $cita_medica_id
 * @property string|null $motivo
 * @property string|null $sintomas
 * @property string|null $diagnostico
 * @property string|null $tratamiento
 * @property float|null $presion_arterial_minimo
 * @property float|null $presion_arterial_maximo
 * @property float|null $temperatura_corporal
 * @property string|null $aspecto_fisico
 * @property float|null $nivel_glucosa
 * @property float|null $oxigeno_sangre
 * @property string|null $medico_atendio
 * @property float|null $frecuencia_cardiaca
 * @property float|null $frecuencia_respiratoria
 * @property float|null $estatura
 * @property float|null $peso
 * @property float|null $imc
 * @property int|null $expediente_medico_id
 * @property string|null $fecha_consulta
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class ConsultaMedica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'consulta_medica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cita_medica_id', 'expediente_medico_id'], 'integer'],
            [['motivo', 'sintomas', 'diagnostico', 'tratamiento', 'aspecto_fisico'], 'string'],
            [['presion_arterial_minimo', 'presion_arterial_maximo', 'temperatura_corporal', 'nivel_glucosa', 'oxigeno_sangre', 'frecuencia_cardiaca', 'frecuencia_respiratoria', 'estatura', 'peso', 'imc'], 'number'],
            [['created_at'], 'safe'],
            [['medico_atendio'], 'string', 'max' => 45],
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
            'cita_medica_id' => 'Cita Medica ID',
            'motivo' => 'Motivo',
            'sintomas' => 'Sintomas',
            'diagnostico' => 'Diagnostico',
            'tratamiento' => 'Tratamiento',
            'presion_arterial_minimo' => 'Presion Arterial Minimo',
            'presion_arterial_maximo' => 'Presion Arterial Maximo',
            'temperatura_corporal' => 'Temperatura Corporal',
            'aspecto_fisico' => 'Aspecto Fisico',
            'nivel_glucosa' => 'Nivel Glucosa',
            'oxigeno_sangre' => 'Oxigeno Sangre',
            'medico_atendio' => 'Medico Atendio',
            'frecuencia_cardiaca' => 'Frecuencia Cardiaca',
            'frecuencia_respiratoria' => 'Frecuencia Respiratoria',
            'estatura' => 'Estatura',
            'peso' => 'Peso',
            'imc' => 'Imc',
            'expediente_medico_id' => 'Expediente Medico ID',
            'created_at' => 'Fecha de Creación',

        ];
    }


    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::class,
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
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
        return $this->hasMany(ExpedienteMedico::class, ['consulta_medica_id' => 'id']);
    }
}
