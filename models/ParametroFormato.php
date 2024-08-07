<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "parametro_formato".
 *
 * @property int $id
 * @property string|null $tipo_permiso
 * @property int|null $limite_anual
 */
class ParametroFormato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'parametro_formato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['limite_anual'], 'integer'],
            [['tipo_permiso'], 'string', 'max' => 255],
            [['tipo_permiso'], 'unique', 'message' => 'Ya se configuró este formato.'],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_permiso' => 'Tipo Permiso',
            'limite_anual' => 'Limite Anual',
        ];
    }
}
