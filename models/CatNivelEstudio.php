<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_nivel_estudio".
 *
 * @property int $id
 * @property string $nivel_estudio
 */
class CatNivelEstudio extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_nivel_estudio';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nivel_estudio'], 'required'],
            [['nivel_estudio'], 'string', 'max' => 85],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nivel_estudio' => 'Nivel Estudio',
        ];
    }
}
