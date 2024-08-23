<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "contrato_para_personal_eventual".
 *
 * @property int $id
 * @property int|null $numero_contrato
 * @property string|null $fecha_inicio
 * @property string|null $fecha_termino
 * @property int|null $duracion
 * @property string|null $modalidad
 * @property string|null $actividades_realizar
 * @property string|null $origen_contrato
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 *
 * @property Empleado $empleado
 * @property Solicitud $solicitud
 */
class ContratoParaPersonalEventual extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'contrato_para_personal_eventual';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_contrato', 'duracion', 'empleado_id', 'solicitud_id'], 'integer'],

            [['numero_contrato', 'fecha_inicio', 'fecha_termino', 'modalidad', 'actividades_realizar', 'origen_contrato'], 'required'],
            [['fecha_inicio', 'fecha_termino'], 'safe'],
            [['modalidad'], 'string', 'max' => 85],
            [['actividades_realizar', 'origen_contrato'], 'string', 'max' => 255],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_contrato' => 'Numero Contrato',
            'fecha_inicio' => 'Fecha de inicio',
            'fecha_termino' => 'Fecha de termino',
            'duracion' => 'Días del contrato',
            'modalidad' => 'Modalidad',
            'actividades_realizar' => 'Actividades a realizar',
            'origen_contrato' => 'Origen de contrato',
            'empleado_id' => 'Empleado ID',
            'solicitud_id' => 'ID de solicitud',
        ];
    }

    /**
     * Gets query for [[Empleado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::class, ['id' => 'empleado_id']);
    }

    /**
     * Gets query for [[Solicitud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(Solicitud::class, ['id' => 'solicitud_id']);
    }
}
