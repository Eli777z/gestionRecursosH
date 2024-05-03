<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_puesto".
 *
 * @property int $id
 * @property string $nombre_puesto
 */
class CatPuesto extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_puesto';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_puesto'], 'required'],
            [['nombre_puesto'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_puesto' => 'Nombre Puesto',
        ];
    }
}
