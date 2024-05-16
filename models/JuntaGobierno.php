<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "junta_gobierno".
 *
 * @property int $id
 * @property int $cat_direccion_id
 * @property int $cat_departamento_id
 * @property int $empleado_id
 * @property string|null $nivel_jerarquico
 *
 * @property CatDepartamento $catDepartamento
 * @property CatDireccion $catDireccion
 * @property Empleado $empleado
 */
class JuntaGobierno extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'junta_gobierno';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_direccion_id', 'cat_departamento_id', 'empleado_id'], 'required'],
            [['cat_direccion_id', 'cat_departamento_id', 'empleado_id'], 'integer'],
            [['nivel_jerarquico'], 'string', 'max' => 45],
            [['profesion'], 'string', 'max' => 5],
            [['cat_departamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDepartamento::class, 'targetAttribute' => ['cat_departamento_id' => 'id']],
            [['cat_direccion_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDireccion::class, 'targetAttribute' => ['cat_direccion_id' => 'id']],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_direccion_id' => 'Cat Direccion ID',
            'cat_departamento_id' => 'Cat Departamento ID',
            'empleado_id' => 'Empleado ID',
            'nivel_jerarquico' => 'Nivel Jerarquico',
            'profesion' => 'Profesión',
        ];
    }

    /**
     * Gets query for [[CatDepartamento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatDepartamento()
    {
        return $this->hasOne(CatDepartamento::class, ['id' => 'cat_departamento_id']);
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

    /**
     * Gets query for [[Empleado]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleado()
    {
        return $this->hasOne(Empleado::class, ['id' => 'empleado_id']);
    }
}
