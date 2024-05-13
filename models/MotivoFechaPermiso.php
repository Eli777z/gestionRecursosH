<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "motivo_fecha_permiso".
 *
 * @property int $id
 * @property string $fecha_permiso
 * @property string $motivo
 *
 * @property PermisoFueraTrabajo[] $permisoFueraTrabajos
 */
class MotivoFechaPermiso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'motivo_fecha_permiso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha_permiso', 'motivo'], 'required'],
            [['fecha_permiso'], 'safe'],
            [['motivo'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha_permiso' => 'Fecha Permiso',
            'motivo' => 'Motivo',
        ];
    }

    /**
     * Gets query for [[PermisoFueraTrabajos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPermisoFueraTrabajos()
    {
        return $this->hasMany(PermisoFueraTrabajo::class, ['motivo_fecha_permiso_id' => 'id']);
    }
}
