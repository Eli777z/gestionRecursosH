<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_patologico".
 *
 * @property int $id
 * @property string|null $descripcion_antecedentes
 */
class AntecedentePatologico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_patologico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['descripcion_antecedentes'], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'descripcion_antecedentes' => 'Descripcion Antecedentes',
        ];
    }
}
