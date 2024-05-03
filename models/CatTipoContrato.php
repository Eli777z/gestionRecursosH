<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_tipo_contrato".
 *
 * @property int $id
 * @property string $nombre_tipo
 */
class CatTipoContrato extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_tipo_contrato';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_tipo'], 'required'],
            [['nombre_tipo'], 'string', 'max' => 25],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_tipo' => 'Nombre Tipo',
        ];
    }
}
