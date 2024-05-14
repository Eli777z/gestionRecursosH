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
 * @property string|null $fecha_salida
 * @property string|null $fecha_regreso
 * @property string|null $fecha_a_reponer
 * @property string|null $horario_fecha_a_reponer
 * @property string|null $nota
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 */
class PermisoFueraTrabajo extends \yii\db\ActiveRecord
{
    public $fecha_rango; // Este es el atributo temporal para el rango de fechas
    public $fecha_hora_reponer; // Este es el atributo temporal para la fecha y hora combinada

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
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['fecha_salida', 'fecha_regreso', 'fecha_a_reponer', 'horario_fecha_a_reponer'], 'safe'],
            [['nota'], 'string'],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['fecha_rango'], 'string'], // Cambia a una regla de tipo string
        [['fecha_rango'], 'required'], // Asegúrate de que el campo sea requerido si lo necesitas
        // Otros atributos y reglas...
            [['fecha_hora_reponer'], 'safe'], // Agrega esta regla

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
            'fecha_salida' => 'Fecha Salida',
            'fecha_regreso' => 'Fecha Regreso',
            'fecha_a_reponer' => 'Fecha A Reponer',
            'horario_fecha_a_reponer' => 'Horario Fecha A Reponer',
            'nota' => 'Nota',
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

    public function afterValidate()
    {
        parent::afterValidate();
    
        if ($this->fecha_rango) {
            list($fechaSalida, $fechaRegreso) = explode(' a ', $this->fecha_rango);
            // Convertir las fechas a formato Y-m-d H:i:s
            $this->fecha_salida = date('Y-m-d H:i:s', strtotime($fechaSalida));
            $this->fecha_regreso = date('Y-m-d H:i:s', strtotime($fechaRegreso));
        }
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
}
