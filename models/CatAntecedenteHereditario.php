<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_antecedente_hereditario".
 *
 * @property int $id
 * @property string|null $nombre
 *
 * @property AntecedenteHereditario[] $antecedenteHereditarios
 */
class CatAntecedenteHereditario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_antecedente_hereditario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre'], 'string', 'max' => 85],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
        ];
    }

    /**
     * Gets query for [[AntecedenteHereditarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedenteHereditarios()
    {
        return $this->hasMany(AntecedenteHereditario::class, ['cat_antecedente_hereditario_patologico_id' => 'id']);
    }
}
