<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_dia_laboral".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $motivo_fecha_permiso_id
 * @property string|null $fecha_a_laborar
 * @property string|null $nombre_jefe_departamento
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 * @property Solicitud $solicitud
 */
class CambioDiaLaboral extends \yii\db\ActiveRecord
{
    public $jefe_departamento_id;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_dia_laboral';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['fecha_a_laborar'], 'safe'],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
       
            [['jefe_departamento_id'], 'safe'], // Añadir jefe_departamento_id como seguro para asignación masiva

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'empleado_id' => 'Empleado ID',
            'solicitud_id' => 'Solicitud ID',
            'motivo_fecha_permiso_id' => 'Motivo Fecha Permiso ID',
            'fecha_a_laborar' => 'Fecha A Laborar',
            'nombre_jefe_departamento' => 'Nombre Jefe Departamento',
            'jefe_departamento_id' => 'Jefe de Departamento',

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
     * Gets query for [[MotivoFechaPermiso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotivoFechaPermiso()
    {
        return $this->hasOne(MotivoFechaPermiso::class, ['id' => 'motivo_fecha_permiso_id']);
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
