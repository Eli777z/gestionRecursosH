<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_departamento".
 *
 * @property int $id
 * @property string $nombre_departamento
 *
 * @property InformacionLaboral[] $informacionLaborals
 */
class CatDepartamento extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_departamento';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_departamento'], 'required'],
            [['nombre_departamento'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_departamento' => 'Nombre Departamento',
        ];
    }

    /**
     * Gets query for [[InformacionLaborals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionLaborals()
    {
        return $this->hasMany(InformacionLaboral::class, ['cat_departamento_id' => 'id']);
    }
}
