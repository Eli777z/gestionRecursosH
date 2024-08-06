<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "permiso_fuera_trabajo".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $motivo_fecha_permiso_id
 * @property string|null $hora_salida
 * @property string|null $hora_regreso
 * @property string|null $fecha_a_reponer
 * @property string|null $horario_fecha_a_reponer
 * @property string|null $nota
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 */
class PermisoFueraTrabajo extends \yii\db\ActiveRecord
{ 
    public $fecha_hora_reponer; 
    public $jefe_departamento_id;


   
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'permiso_fuera_trabajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            // ...
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['hora_salida', 'hora_regreso', 'fecha_a_reponer', 'horario_fecha_a_reponer'], 'safe'],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'],
            [['fecha_hora_reponer'], 'safe'],
            [['motivo_fecha_permiso_id', 'hora_salida', 'hora_regreso', 'fecha_a_reponer', 'horario_fecha_a_reponer'], 'required'],
            ['empleado_id', 'validarLimiteAnual'], // Nueva regla de validación
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
            'hora_salida' => 'Hora de salida',
            'hora_regreso' => 'Hora de regreso',
            'fecha_a_reponer' => 'Fecha a reponer',
            'horario_fecha_a_reponer' => 'Horario de fecha a reponer',
            'jefe_departamento_id' => 'Jefe de Departamento',
            'nombre_jefe_departamento' => 'Jefe de Departamento'
           
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

    public function getSolicitud()
    {
        return $this->hasOne(Solicitud::class, ['id' => 'solicitud_id']);
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
   
    
    

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->fecha_hora_reponer) {
                $dateTime = \DateTime::createFromFormat('Y-m-d h:i A', $this->fecha_hora_reponer);
                if ($dateTime) {
                    $this->fecha_salida = $dateTime->format('Y-m-d');
                    $this->horario_fecha_a_reponer = $dateTime->format('H:i:s');
                }
            }
            return true;
        }
        return false;
    }


    public function validarLimiteAnual($attribute, $params)
{
    $añoActual = date('Y');
    $contadorPermisos = static::find()
        ->where(['empleado_id' => $this->empleado_id])
        ->andWhere(['between', 'fecha_a_reponer', "$añoActual-01-01", "$añoActual-12-31"])
        ->count();

    $limiteAnual = ParametroFormato::find()
        ->where(['tipo_permiso' => 'PERMISO FUERA DEL TRABAJO'])
        ->one()->limite_anual;

    if ($contadorPermisos >= $limiteAnual) {
        $this->addError($attribute, 'Has alcanzado el límite anual de permisos fuera del trabajo.');
    }
}


}
