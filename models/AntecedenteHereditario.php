<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_hereditario".
 *
 * @property int $id
 * @property int|null $cat_antecedente_hereditario_id
 * @property int|null $abuelos
 * @property int|null $hermanos
 * @property int|null $padre
 * @property int|null $madre
 *
 * @property AntecedenteHereditario[] $antecedenteHereditarios
 * @property AntecedenteHereditario $catAntecedenteHereditario
 */
class AntecedenteHereditario extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_hereditario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_antecedente_hereditario_id', 'abuelos', 'hermanos', 'padre', 'madre'], 'integer'],
            [['cat_antecedente_hereditario_id'], 'exist', 'skipOnError' => true, 'targetClass' => AntecedenteHereditario::class, 'targetAttribute' => ['cat_antecedente_hereditario_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_antecedente_hereditario_id' => 'Cat Antecedente Hereditario ID',
            'abuelos' => 'Abuelos',
            'hermanos' => 'Hermanos',
            'padre' => 'Padre',
            'madre' => 'Madre',
        ];
    }

    /**
     * Gets query for [[AntecedenteHereditarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedenteHereditarios()
    {
        return $this->hasMany(AntecedenteHereditario::class, ['cat_antecedente_hereditario_id' => 'id']);
    }

    /**
     * Gets query for [[CatAntecedenteHereditario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatAntecedenteHereditario()
    {
        return $this->hasOne(AntecedenteHereditario::class, ['id' => 'cat_antecedente_hereditario_id']);
    }
}
