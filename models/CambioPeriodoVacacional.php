<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_periodo_vacacional".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property string|null $motivo
 * @property string|null $primera_vez
 * @property string|null $nombre_jefe_departamento
 * @property string|null $numero_periodo
 * @property string|null $fecha_inicio_periodo
 * @property string|null $fecha_fin_periodo
 *
 * @property Empleado $empleado
 * @property Solicitud $solicitud
 */
class CambioPeriodoVacacional extends \yii\db\ActiveRecord
{
    public $jefe_departamento_id;
    public $dateRange;
    public $dateRangeOriginal;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_periodo_vacacional';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'dias_pendientes'], 'integer'],
            [['motivo'], 'string'],
            [['fecha_inicio_periodo', 'fecha_fin_periodo', 'fecha_inicio_original', 'fecha_fin_original'], 'safe'],
            [['primera_vez'], 'string', 'max' => 3],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['numero_periodo'], 'string', 'max' => 12],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'], 
            [['dateRange', 'dateRangeOriginal'], 'safe'], 
            [['año'], 'string', 'max' => 8],
            [['dateRange', 'dateRangeOriginal'], 'required', 'message' => 'El rango de fechas no puede estar vacío.'], // Regla y mensaje personalizado para dateRange

            [['motivo', 'primera_vez', 'numero_periodo', 'año'], 'required'],
            ['empleado_id', 'validarLimiteAnual'], // Nueva regla de validación


        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'año' => 'Año',

            'empleado_id' => 'Empleado ID',
            'solicitud_id' => 'ID de solicitud',
            'motivo' => 'Motivo',
            'primera_vez' => 'Primera vez',
            'nombre_jefe_departamento' => 'Nombre Jefe Departamento',
            'numero_periodo' => 'Numero de periodo',
            'fecha_inicio_periodo' => 'Fecha Inicio Periodo',
            'fecha_fin_periodo' => 'Fecha Fin Periodo',
            'jefe_departamento_id' => 'Jefe de Departamento',
            'fecha_inicio_original' => 'Fecha Inicio Original',
            'fecha_fin_original' => 'Fecha Fin Original',
            'dias_pendientes' => 'Dias pendientes'
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
     * Gets query for [[Solicitud]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSolicitud()
    {
        return $this->hasOne(Solicitud::class, ['id' => 'solicitud_id']);
    }
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            // Asigna el año actual si no está establecido
            if (empty($this->año)) {
                $this->año = date('Y');
            }
    
            // Procesar dateRange
            if (!empty($this->dateRange) && strpos($this->dateRange, ' a ') !== false) {
                list($this->fecha_inicio_periodo, $this->fecha_fin_periodo) = explode(' a ', $this->dateRange);
                $this->fecha_inicio_periodo = date('Y-m-d', strtotime($this->fecha_inicio_periodo));
                $this->fecha_fin_periodo = date('Y-m-d', strtotime($this->fecha_fin_periodo));
            }
    
            // Procesar dateRangeOriginal
            if (!empty($this->dateRangeOriginal) && strpos($this->dateRangeOriginal, ' a ') !== false) {
                list($this->fecha_inicio_original, $this->fecha_fin_original) = explode(' a ', $this->dateRangeOriginal);
                $this->fecha_inicio_original = date('Y-m-d', strtotime($this->fecha_inicio_original));
                $this->fecha_fin_original = date('Y-m-d', strtotime($this->fecha_fin_original));
            }
    
            // Calcular dias_pendientes
            $empleado = Empleado::findOne($this->empleado_id);
            if ($empleado && $empleado->informacionLaboral && $empleado->informacionLaboral->vacaciones) {
                $totalDiasVacaciones = $empleado->informacionLaboral->vacaciones->total_dias_vacaciones;
                $diasPorPeriodo = $totalDiasVacaciones / 2;
    
                $fechaInicio = new \DateTime($this->fecha_inicio_periodo);
                $fechaFin = new \DateTime($this->fecha_fin_periodo);
                $diasSolicitados = $fechaFin->diff($fechaInicio)->days + 1; // +1 para incluir el día final
    
                $this->dias_pendientes = $diasPorPeriodo - $diasSolicitados;
            }
    
            return true;
        } else {
            return false;
        }
    }
    


    
public function validarLimiteAnual($attribute, $params)
{
    $añoActual = date('Y');
    $empleado = Empleado::findOne($this->empleado_id);
    $tipoContratoId = $empleado->informacionLaboral->cat_tipo_contrato_id;

    $contadorPermisos = static::find()
        ->where(['empleado_id' => $this->empleado_id])
        ->andWhere(['between', 'fecha_fin_periodo', "$añoActual-01-01", "$añoActual-12-31"])
        ->count();

    $limiteAnual = ParametroFormato::find()
        ->where(['tipo_permiso' => 'CAMBIO DE PERIODO VACACIONAL', 'cat_tipo_contrato_id' => $tipoContratoId])
        ->one()->limite_anual;

    if ($contadorPermisos >= $limiteAnual) {
        $this->addError($attribute, 'Has alcanzado el límite anual de cambio de periodo vacacional para tu tipo de contrato.');
    }
}

}
