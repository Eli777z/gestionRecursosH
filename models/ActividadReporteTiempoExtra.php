<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividad_reporte_tiempo_extra".
 *
 * @property int $id
 * @property string|null $fecha
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property string|null $actividad
 * @property int|null $reporte_tiempo_extra_id
 *
 * @property ReporteTiempoExtra $reporteTiempoExtra
 */
class ActividadReporteTiempoExtra extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividad_reporte_tiempo_extra';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['fecha', 'hora_inicio', 'hora_fin'], 'safe'],
            [['actividad'], 'string'],

            [['fecha', 'hora_inicio', 'hora_fin', 'actividad'], 'required'],

            [['reporte_tiempo_extra_id', 'no_horas'], 'integer'],
            [['reporte_tiempo_extra_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReporteTiempoExtra::class, 'targetAttribute' => ['reporte_tiempo_extra_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'fecha' => 'Fecha',
            'hora_inicio' => 'Hora de inicio',
            'hora_fin' => 'Hora de finalización',
            'actividad' => 'Actividad',
            'reporte_tiempo_extra_id' => 'Reporte Tiempo Extra ID',
            'no_horas' => 'Número de horas'
        ];
    }

    /**
     * Gets query for [[ReporteTiempoExtra]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReporteTiempoExtra()
    {
        return $this->hasOne(ReporteTiempoExtra::class, ['id' => 'reporte_tiempo_extra_id']);
    }
}
