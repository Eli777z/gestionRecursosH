<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "solicitud".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property string|null $fecha_creacion
 * @property int|null $status
 * @property string|null $comentario
 * @property string|null $fecha_aprobacion
 * @property string|null $nombre_aprobante
 * @property string|null $nombre_formato
 *
 * @property Empleado $empleado
 * @property PermisoFueraTrabajo[] $permisoFueraTrabajos
 */
class Solicitud extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'solicitud';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id'], 'integer'],
            [['fecha_creacion', 'fecha_aprobacion'], 'safe'],
            [['comentario'], 'string'],
            [['nombre_aprobante'], 'string', 'max' => 90],
            [['status'], 'string', 'max' => 30],
            [['nombre_formato'], 'string', 'max' => 50],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
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
            'fecha_creacion' => 'Fecha Creacion',
            'status' => 'Status',
            'comentario' => 'Comentario',
            'fecha_aprobacion' => 'Fecha Aprobacion',
            'nombre_aprobante' => 'Nombre Aprobante',
            'nombre_formato' => 'Nombre Formato',
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
     * Gets query for [[PermisoFueraTrabajos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermisoFueraTrabajos()
    {
        return $this->hasMany(PermisoFueraTrabajo::class, ['solicitud_id' => 'id']);
    }
}
