<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "actividad_reporte_tiempo_extra_general".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property string|null $fecha
 * @property string|null $hora_inicio
 * @property string|null $hora_fin
 * @property string|null $actividad
 * @property int|null $reporte_tiempo_extra_general_id
 * @property int|null $no_horas
 *
 * @property Empleado $empleado
 * @property ReporteTiempoExtraGeneral $reporteTiempoExtraGeneral
 */
class ActividadReporteTiempoExtraGeneral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'actividad_reporte_tiempo_extra_general';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'reporte_tiempo_extra_general_id', 'no_horas'], 'integer'],
            [['fecha', 'hora_inicio', 'hora_fin'], 'safe'],
            [['actividad'], 'string'],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['reporte_tiempo_extra_general_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReporteTiempoExtraGeneral::class, 'targetAttribute' => ['reporte_tiempo_extra_general_id' => 'id']],
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
            'fecha' => 'Fecha',
            'hora_inicio' => 'Hora Inicio',
            'hora_fin' => 'Hora Fin',
            'actividad' => 'Actividad',
            'reporte_tiempo_extra_general_id' => 'Reporte Tiempo Extra General ID',
            'no_horas' => 'No Horas',
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
     * Gets query for [[ReporteTiempoExtraGeneral]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getReporteTiempoExtraGeneral()
    {
        return $this->hasOne(ReporteTiempoExtraGeneral::class, ['id' => 'reporte_tiempo_extra_general_id']);
    }
}
