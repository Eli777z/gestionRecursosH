<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_actividad_fisica".
 *
 * @property int $id
 * @property string $nombre_actvidad
 */
class CatActividadFisica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_actividad_fisica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_actvidad'], 'required'],
            [['nombre_actvidad'], 'string', 'max' => 85],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_actvidad' => 'Nombre Actvidad',
        ];
    }
}
