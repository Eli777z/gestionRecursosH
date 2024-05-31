<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "periodo_vacacional_historial".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property string|null $periodo
 * @property string|null $fecha_inicio
 * @property string|null $fecha_final
 * @property string|null $año
 * @property int|null $dias_disponibles
 * @property string|null $original
 * @property string|null $created_at
 *
 * @property Empleado $empleado
 */
class PeriodoVacacionalHistorial extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'periodo_vacacional_historial';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'dias_disponibles'], 'integer'],
            [['fecha_inicio', 'fecha_final', 'created_at'], 'safe'],
            [['periodo'], 'string', 'max' => 20],
            [['año'], 'string', 'max' => 4],
            [['original'], 'string', 'max' => 3],
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
            'empleado_id' => 'Empleado ID',
            'periodo' => 'Periodo',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_final' => 'Fecha Final',
            'año' => 'Año',
            'dias_disponibles' => 'Dias Disponibles',
            'original' => 'Original',
            'created_at' => 'Created At',
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
}
