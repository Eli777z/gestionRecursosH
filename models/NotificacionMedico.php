<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "notificacion_medico".
 *
 * @property int $id
 * @property int|null $usuario_id
 * @property string|null $mensaje
 * @property string|null $fecha_creacion
 *
 * @property Usuario $usuario
 */
class NotificacionMedico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'notificacion_medico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['usuario_id','leida'], 'integer'],
            [['mensaje'], 'string'],
            [['fecha_creacion'], 'safe'],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
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
            'fecha_creacion' => 'Fecha Creacion',
            'leida' => 'leida'
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

    public static function countUnread($usuario_id)
    {
        return self::find()->where(['usuario_id' => $usuario_id, 'leida' => 0])->count();
    }
}
