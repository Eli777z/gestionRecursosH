<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "documento_medico".
 *
 * @property int $id
 * @property string|null $nombre
 * @property string|null $ruta
 * @property string|null $fecha_subida
 * @property string|null $observacion
 * @property int|null $empleado_id
 *
 * @property Empleado $empleado
 */
class DocumentoMedico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'documento_medico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_subida'], 'safe'],
            [['observacion'], 'string'],
            [['empleado_id'], 'integer'],
            [['nombre'], 'string', 'max' => 85],
            [['ruta'], 'string', 'max' => 255],
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
            'nombre' => 'Nombre',
            'ruta' => 'Ruta',
            'fecha_subida' => 'Fecha Subida',
            'observacion' => 'Observacion',
            'empleado_id' => 'Empleado ID',
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
}
