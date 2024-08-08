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
    public static function tableName()
    {
        return 'parametro_formato';
    }

    public function rules()
    {
        return [
            [['limite_anual', 'cat_tipo_contrato_id'], 'integer'],
            [['tipo_permiso'], 'string', 'max' => 255],
            [['cat_tipo_contrato_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatTipoContrato::class, 'targetAttribute' => ['cat_tipo_contrato_id' => 'id']],

            [['tipo_permiso', 'cat_tipo_contrato_id'], 'unique', 'targetAttribute' => ['tipo_permiso', 'cat_tipo_contrato_id'], 'message' => 'Ya se configuró este formato para este tipo de contrato.'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_permiso' => 'Tipo Permiso',
            'limite_anual' => 'Límite Anual',
            'cat_tipo_contrato_id' => 'Tipo de Contrato',
        ];
    }

    public function getCatTipoContrato()
    {
        return $this->hasOne(CatTipoContrato::class, ['id' => 'cat_tipo_contrato_id']);
    }
}
