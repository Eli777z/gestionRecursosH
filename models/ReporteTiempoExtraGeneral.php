<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "reporte_tiempo_extra_general".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $total_horas
 * @property string|null $created_at
 *
 * @property ActividadReporteTiempoExtraGeneral[] $actividadReporteTiempoExtraGenerals
 * @property Empleado $empleado
 * @property Solicitud $solicitud
 */
class ReporteTiempoExtraGeneral extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'reporte_tiempo_extra_general';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'total_horas'], 'integer'],
            [['created_at'], 'safe'],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
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
            'solicitud_id' => 'ID de solicitud',
            'total_horas' => 'Total Horas',
            'created_at' => 'Created At',
        ];
    }

    /**
     * Gets query for [[ActividadReporteTiempoExtraGenerals]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getActividadReporteTiempoExtraGenerals()
    {
        return $this->hasMany(ActividadReporteTiempoExtraGeneral::class, ['reporte_tiempo_extra_general_id' => 'id']);
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
     * Gets query for [[Solicitud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(Solicitud::class, ['id' => 'solicitud_id']);
    }
}
