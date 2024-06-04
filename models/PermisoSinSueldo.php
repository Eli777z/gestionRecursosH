<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permiso_sin_sueldo".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $motivo_fecha_permiso_id
 * @property string|null $fecha_permiso_anterior
 * @property int|null $no_permiso_anterior
 * @property string|null $nombre_jefe_departamento
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 * @property Solicitud $solicitud
 */
class PermisoSinSueldo extends \yii\db\ActiveRecord
{
    public $jefe_departamento_id;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permiso_sin_sueldo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id', 'no_permiso_anterior'], 'integer'],
            [['fecha_permiso_anterior'], 'safe'],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'], 

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
            'fecha_permiso_anterior' => 'Fecha Permiso Anterior',
            'no_permiso_anterior' => 'No Permiso Anterior',
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
