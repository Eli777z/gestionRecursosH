<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "informacion_laboral".
 *
 * @property int $id
 * @property int|null $cat_tipo_contrato_id
 * @property int|null $cat_puesto_id
 * @property int $cat_departamento_id
 * @property int|null $vacaciones_id
 * @property int|null $cat_direccion_id
 * @property int|null $junta_gobierno_id
 * @property string $fecha_ingreso
 * @property string|null $horario_laboral_inicio
 * @property string|null $horario_laboral_fin
 *
 * @property CatDepartamento $catDepartamento
 * @property Empleado[] $empleados
 */
class InformacionLaboral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'informacion_laboral';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cat_tipo_contrato_id', 'cat_puesto_id', 'cat_departamento_id', 'vacaciones_id', 'cat_direccion_id', 'junta_gobierno_id'], 'integer'],
            [['cat_departamento_id', 'fecha_ingreso'], 'required'],
            [['fecha_ingreso', 'horario_laboral_inicio', 'horario_laboral_fin'], 'safe'],
            [['cat_departamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDepartamento::class, 'targetAttribute' => ['cat_departamento_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_tipo_contrato_id' => 'Cat Tipo Contrato ID',
            'cat_puesto_id' => 'Cat Puesto ID',
            'cat_departamento_id' => 'Cat Departamento ID',
            'vacaciones_id' => 'Vacaciones ID',
            'cat_direccion_id' => 'Cat Direccion ID',
            'junta_gobierno_id' => 'Junta Gobierno ID',
            'fecha_ingreso' => 'Fecha Ingreso',
            'horario_laboral_inicio' => 'Horario Laboral Inicio',
            'horario_laboral_fin' => 'Horario Laboral Fin',
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
     * Gets query for [[Empleados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleados()
    {
        return $this->hasMany(Empleado::class, ['informacion_laboral_id' => 'id']);
    }
}
