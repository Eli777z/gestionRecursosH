<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_tipo_documento".
 *
 * @property int $id
 * @property string $nombre_tipo
 *
 * @property Documento[] $documentos
 */
class CatTipoDocumento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_tipo_documento';
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
            'id' => 'Tipo de documento',
            'nombre_tipo' => 'Nombre Tipo',
        ];
    }

    /**
     * Gets query for [[Documentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentos()
    {
        return $this->hasMany(Documento::class, ['cat_tipo_documento_id' => 'id']);
    }
}
