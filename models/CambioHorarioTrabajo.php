<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_horario_trabajo".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $motivo_fecha_permiso_id
 * @property string|null $turno
 * @property string|null $horario_inicio
 * @property string|null $horario_fin
 * @property string|null $fecha_inicio
 * @property string|null $fecha_termino
 * @property string|null $nombre_jefe_departamento
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 * @property Solicitud $solicitud
 */
class CambioHorarioTrabajo extends \yii\db\ActiveRecord
{
    public $jefe_departamento_id;
    public $dateRange;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_horario_trabajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['horario_inicio', 'horario_fin', 'fecha_inicio', 'fecha_termino'], 'safe'],
            [['horario_inicio', 'turno', 'horario_fin'], 'required'],
            [['turno'], 'string', 'max' => 12],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'], 
            [['dateRange'], 'required', 'message' => 'El rango de fechas no puede estar vacío.'], // Regla y mensaje personalizado para dateRange
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
            'turno' => 'Turno',
            'horario_inicio' => 'Inicio de horario',
            'horario_fin' => 'Fin de horario',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_termino' => 'Fecha Termino',
            'nombre_jefe_departamento' => 'Nombre Jefe Departamento',
            'jefe_departamento_id' => 'Jefe de Departamento',
            'dateRange' => 'Rango de Fechas',
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

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->dateRange) {
                list($this->fecha_inicio, $this->fecha_termino) = explode(' a ', $this->dateRange);
            }
            
            return true;
        } else {
            return false;
        }
    }
}
