<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "vacaciones".
 *
 * @property int $id
 * @property int|null $periodo_vacacional_id
 * @property int|null $empleado_id
 * @property int|null $dias_vacaciones
 * @property int|null $total_dias_vacaciones
 * @property int|null $segundo_periodo_vacacional_id
 *
 * @property Empleado $empleado
 * @property InformacionLaboral[] $informacionLaborals
 * @property PeriodoVacacional $periodoVacacional
 * @property SegundoPeriodoVacacional $segundoPeriodoVacacional
 */
class Vacaciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacaciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['periodo_vacacional_id', 'empleado_id', 'dias_vacaciones', 'total_dias_vacaciones', 'segundo_periodo_vacacional_id'], 'integer'],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['periodo_vacacional_id'], 'exist', 'skipOnError' => true, 'targetClass' => PeriodoVacacional::class, 'targetAttribute' => ['periodo_vacacional_id' => 'id']],
            [['segundo_periodo_vacacional_id'], 'exist', 'skipOnError' => true, 'targetClass' => SegundoPeriodoVacacional::class, 'targetAttribute' => ['segundo_periodo_vacacional_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'periodo_vacacional_id' => 'Periodo Vacacional ID',
            'empleado_id' => 'Empleado ID',
            'dias_vacaciones' => 'Dias Vacaciones',
            'total_dias_vacaciones' => 'Total Dias Vacaciones',
            'segundo_periodo_vacacional_id' => 'Segundo Periodo Vacacional ID',
        ];
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

    /**
     * Gets query for [[InformacionLaborals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionLaborals()
    {
        return $this->hasMany(InformacionLaboral::class, ['vacaciones_id' => 'id']);
    }

    /**
     * Gets query for [[PeriodoVacacional]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPeriodoVacacional()
    {
        return $this->hasOne(PeriodoVacacional::class, ['id' => 'periodo_vacacional_id']);
    }

    /**
     * Gets query for [[SegundoPeriodoVacacional]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSegundoPeriodoVacacional()
    {
        return $this->hasOne(SegundoPeriodoVacacional::class, ['id' => 'segundo_periodo_vacacional_id']);
    }
}
