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
    public static function tableName()
    {
        return 'actividad_reporte_tiempo_extra_general';
    }

    public function rules()
    {
        return [
            [['numero_empleado'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['numero_empleado' => 'numero_empleado'], 'message' => 'El número de empleado no existe.'],
            [['numero_empleado', 'reporte_tiempo_extra_general_id', 'no_horas'], 'integer'],
            [['fecha', 'hora_inicio', 'hora_fin'], 'safe'],
            [['numero_empleado','fecha', 'hora_inicio', 'hora_fin' ], 'required'],
            [['actividad'], 'string'],
            [['reporte_tiempo_extra_general_id'], 'exist', 'skipOnError' => true, 'targetClass' => ReporteTiempoExtraGeneral::class, 'targetAttribute' => ['reporte_tiempo_extra_general_id' => 'id']],
            [['numero_empleado'], 'validateEmpleadoPertenencia'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_empleado' => 'Número de empleado',
            'fecha' => 'Fecha',
            'hora_inicio' => 'Hora de inicio',
            'hora_fin' => 'Hora de finalización',
            'actividad' => 'Actividad',
            'reporte_tiempo_extra_general_id' => 'Reporte Tiempo Extra General ID',
            'no_horas' => 'No Horas',
        ];
    }

    public function validateEmpleadoPertenencia($attribute, $params)
    {
        $usuarioActual = Yii::$app->user->identity;
        $empleadoActual = $usuarioActual->empleado;

        // Obtén el departamento o área del empleado actual
        $departamentoActual = $empleadoActual->informacionLaboral->catDepartamento->nombre_departamento;

        // Verifica si el empleado ingresado pertenece al mismo departamento o área
        $empleado = Empleado::findOne(['numero_empleado' => $this->$attribute]);
        if ($empleado === null) {
            $this->addError($attribute, 'El número de empleado no existe.');
        } else {
            $departamentoEmpleado = $empleado->informacionLaboral->catDepartamento->nombre_departamento;
            if ($departamentoEmpleado !== $departamentoActual) {
                $this->addError($attribute, 'El empleado no pertenece a su departamento.');
            }
        }
    }

    public function getReporteTiempoExtraGeneral()
    {
        return $this->hasOne(ReporteTiempoExtraGeneral::class, ['id' => 'reporte_tiempo_extra_general_id']);
    }
}
