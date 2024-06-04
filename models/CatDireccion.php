<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_direccion".
 *
 * @property int $id
 * @property string $nombre_direccion
 *
 * @property CatDepartamento[] $catDepartamentos
 * @property InformacionLaboral[] $informacionLaborals
 * @property JuntaGobierno[] $juntaGobiernos
 */
class CatDireccion extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cat_direccion';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre_direccion'], 'required'],
            [['nombre_direccion'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre_direccion' => 'Nombre Direccion',
        ];
    }

    /**
     * Gets query for [[CatDepartamentos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatDepartamentos()
    {
        return $this->hasMany(CatDepartamento::class, ['cat_direccion_id' => 'id']);
    }

    /**
     * Gets query for [[InformacionLaborals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionLaborals()
    {
        return $this->hasMany(InformacionLaboral::class, ['cat_direccion_id' => 'id']);
    }

    /**
     * Gets query for [[JuntaGobiernos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuntaGobiernos()
    {
        return $this->hasMany(JuntaGobierno::class, ['cat_direccion_id' => 'id']);
    }
}
