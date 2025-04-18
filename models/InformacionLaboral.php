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
 * @property string|null $horario_dias_trabajo
 *
 * @property CatDepartamento $catDepartamento
 * @property CatDireccion $catDireccion
 * @property CatPuesto $catPuesto
 * @property CatTipoContrato $catTipoContrato
 * @property Empleado[] $empleados
 * @property JuntaGobierno $juntaGobierno
 *  @property Vacaciones $vacaciones
 */
class InformacionLaboral extends \yii\db\ActiveRecord
{
    const SCENARIO_UPDATE = 'update';
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
            [['cat_tipo_contrato_id', 'cat_puesto_id', 'cat_departamento_id', 'vacaciones_id', 'cat_direccion_id', 'junta_gobierno_id', 'horas_extras'], 'integer'],
            [[ 'fecha_ingreso',
            'cat_departamento_id',
            'cat_puesto_id',
            'cat_tipo_contrato_id'
        
        
        ], 'required'],
            [['fecha_ingreso', 'horario_laboral_inicio', 'horario_laboral_fin'], 'safe'],
            [['horario_dias_trabajo'], 'string', 'max' => 150],
            ['dias_laborales', 'safe'], // Asegúrate de que safe permite array
            [['numero_cuenta'], 'string', 'max' => 25],
            [['salario'], 'number'],
           
            [['numero_cuenta'], 'match', 'pattern' => '/^\d{18}$/', 'message' => 'El número de cuenta debe contener exactamente 18 dígitos.'],

            [['cat_tipo_contrato_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatTipoContrato::class, 'targetAttribute' => ['cat_tipo_contrato_id' => 'id']],
            [['cat_departamento_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDepartamento::class, 'targetAttribute' => ['cat_departamento_id' => 'id']],
            [['cat_direccion_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDireccion::class, 'targetAttribute' => ['cat_direccion_id' => 'id']],
            [['cat_puesto_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatPuesto::class, 'targetAttribute' => ['cat_puesto_id' => 'id']],
            [['cat_dpto_cargo_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatDptoCargo::class, 'targetAttribute' => ['cat_dpto_cargo_id' => 'id']],

            [['junta_gobierno_id'], 'exist', 'skipOnError' => true, 'targetClass' => JuntaGobierno::class, 'targetAttribute' => ['junta_gobierno_id' => 'id']],

            [['vacaciones_id'], 'exist', 'skipOnError' => true, 'targetClass' => Vacaciones::class, 'targetAttribute' => ['vacaciones_id' => 'id']],
          //  [['horario_laboral_inicio', 'horario_laboral_fin', 'salario','numero_cuenta'], 'required', 'on' => self::SCENARIO_UPDATE],
        ];
    }

//    public function scenarios()
  //  {
    //    $scenarios = parent::scenarios();
     //   $scenarios[self::SCENARIO_CREATE] = ['numero_empleado', 'usuario_id', 'nombre', 'apellido', 'email', 'profesion'];
      //  $scenarios[self::SCENARIO_UPDATE] = ['horario_laboral_inicio', 'horario_laboral_fin', 'salario','numero_cuenta'];
       // $scenarios[self:: SCENARIO_UPDATE_INFO_EDU] = ['cat_nivel_estudio_id', 'institucion_educativa'];
      //  return $scenarios;
   // }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cat_tipo_contrato_id' => 'Contrato',
            'cat_dpto_cargo_id' => 'Cat Dpto Cargo ID',
            'cat_puesto_id' => 'Puesto',
            'cat_departamento_id' => 'Departamento',
            'vacaciones_id' => 'Vacaciones ID',
            'cat_direccion_id' => 'Cat Direccion ID',
            'junta_gobierno_id' => 'Junta Gobierno ID',
            'fecha_ingreso' => 'Fecha de ingreso',
            'horario_laboral_inicio' => 'Horario Laboral Inicio',
            'horario_laboral_fin' => 'Horario Laboral Fin',
            'horario_dias_trabajo' => 'Horario Dias Trabajo',
            'dias_laborales' => 'Dias laborales',
            'numero_cuenta' => 'Número de cuenta',
            'salario' => 'Salario'
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
    public function getCatDptoCargo()
    {
        return $this->hasOne(CatDptoCargo::class, ['id' => 'cat_dpto_cargo_id']);
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
     * Gets query for [[CatPuesto]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatPuesto()
    {
        return $this->hasOne(CatPuesto::class, ['id' => 'cat_puesto_id']);
    }

    /**
     * Gets query for [[CatTipoContrato]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCatTipoContrato()
    {
        return $this->hasOne(CatTipoContrato::class, ['id' => 'cat_tipo_contrato_id']);
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

  

    /**
     * Gets query for [[JuntaGobierno]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getJuntaGobierno()
    {
        return $this->hasOne(JuntaGobierno::class, ['id' => 'junta_gobierno_id']);
    }

/**
     * Gets query for [[Vacaciones]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getVacaciones()
    {
        return $this->hasOne(Vacaciones::class, ['id' => 'vacaciones_id']);
    }


    public function beforeSave($insert)
{
    if (parent::beforeSave($insert)) {
        if (is_array($this->dias_laborales)) {
            $this->dias_laborales = implode(', ', $this->dias_laborales);
        }
        return true;
    }
    return false;
}
}
