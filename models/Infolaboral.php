<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "infolaboral".
 *
 * @property int $id
 * @property int $numero_trabajador
 * @property string $fecha_inicio
 * @property int $iddepartamento
 *
 * @property Departamento $iddepartamento0
 * @property Trabajador[] $trabajadors
 */
class Infolaboral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'infolaboral';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_trabajador', 'fecha_inicio', 'iddepartamento'], 'required'],
            [['numero_trabajador', 'iddepartamento'], 'integer'],
            [['fecha_inicio'], 'safe'],
            [['iddepartamento'], 'exist', 'skipOnError' => true, 'targetClass' => Departamento::class, 'targetAttribute' => ['iddepartamento' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_trabajador' => 'Numero Trabajador',
            'fecha_inicio' => 'Fecha Inicio',
            'iddepartamento' => 'Iddepartamento',
        ];
    }

    /**
     * Gets query for [[Iddepartamento0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIddepartamento0()
    {
        return $this->hasOne(Departamento::class, ['id' => 'iddepartamento']);
    }

    /**
     * Gets query for [[Trabajadors]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTrabajadors()
    {
        return $this->hasMany(Trabajador::class, ['idinfolaboral' => 'id']);
    }
}
