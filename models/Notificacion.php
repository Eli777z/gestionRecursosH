<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "notificacion".
 *
 * @property int $id
 * @property int|null $usuario_id
 * @property string|null $mensaje
 * @property int|null $leido
 * @property string|null $created_at
 *
 * @property Usuario $usuario
 */
class Notificacion extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificacion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id', 'leido'], 'integer'],
            [['mensaje'], 'string'],
            [['created_at'], 'safe'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
            [['permiso_fuera_trabajo_id'], 'exist', 'skipOnError' => true, 'targetClass' => PermisoFueraTrabajo::class, 'targetAttribute' => ['permiso_fuera_trabajo_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_id' => 'Usuario ID',
            'mensaje' => 'Mensaje',
            'leido' => 'Leído',
            'created_at' => 'Fecha de Creación',
        ];
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id' => 'usuario_id']);
    }

    /**
     * {@inheritdoc}
     */
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
     * Marks the notification as read.
     */
    public function markAsRead()
    {
        $this->leido = 1;
        return $this->save(false);
    }

    public function getPermisoFueraTrabajo()
{
    return $this->hasOne(PermisoFueraTrabajo::class, ['id' => 'permiso_fuera_trabajo_id']);
}

}
