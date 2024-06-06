<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cat_departamento".
 *
 * @property int $id
 * @property string $nombre_departamento
 * @property int|null $cat_direccion_id
 * @property int|null $cat_dpto_id

 * @property CatDireccion $catDireccion
 * @property InformacionLaboral[] $informacionLaborals
 * @property JuntaGobierno[] $juntaGobiernos
  * @property CatDptoCargo $catDpto

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
            [['cat_direccion_id'], 'integer'],
            [['cat_dpto_id'], 'integer'],

            [['nombre_departamento'], 'string', 'max' => 100],
            [['nombre_departamento'], 'unique'],
            [['cat_direccion_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDireccion::class, 'targetAttribute' => ['cat_direccion_id' => 'id']],
            [['cat_dpto_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDptoCargo::class, 'targetAttribute' => ['cat_dpto_id' => 'id']],

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
            'cat_direccion_id' => 'Cat Direccion ID',
        ];
    }

    /**
     * Gets query for [[CatDireccion]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatDireccion()
    {
        return $this->hasOne(CatDireccion::class, ['id' => 'cat_direccion_id']);
    }


    public function getCatDpto()
    {
        return $this->hasOne(CatDptoCargo::class, ['id' => 'cat_dpto_id']);
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

    /**
     * Gets query for [[JuntaGobiernos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuntaGobiernos()
    {
        return $this->hasMany(JuntaGobierno::class, ['cat_departamento_id' => 'id']);
    }
}
