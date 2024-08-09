<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "aviso".
 *
 * @property int $id
 * @property string|null $mensaje
 * @property string|null $titulo
 * @property string|null $created_at
 * @property string|null $imagen
 */
class Aviso extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'aviso';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['mensaje'], 'string'],
            [['created_at'], 'safe'],
            [['titulo'], 'string', 'max' => 150],
            [['imagen'], 'string', 'max' => 255],
            [['imagen'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mensaje' => 'Mensaje',
            'titulo' => 'Titulo',
            'created_at' => 'Created At',
            'imagen' => 'Imagen',
        ];
    }
}
