<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_dpto_cargo".
 *
 * @property int $id
 * @property string $nombre_dpto
 */
class CatDptoCargo extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_dpto_cargo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_dpto'], 'required'],
            [['nombre_dpto'], 'string', 'max' => 6],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_dpto' => 'Nombre Dpto',
        ];
    }
}
