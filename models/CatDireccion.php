<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_direccion".
 *
 * @property int $id
 * @property string $nombre_direccion
 */
class CatDireccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_direccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_direccion'], 'required'],
            [['nombre_direccion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_direccion' => 'Nombre Direccion',
        ];
    }
}
