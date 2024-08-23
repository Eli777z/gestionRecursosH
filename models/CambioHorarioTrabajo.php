<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cambio_horario_trabajo".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property int|null $motivo_fecha_permiso_id
 * @property string|null $turno
 * @property string|null $horario_inicio
 * @property string|null $horario_fin
 * @property string|null $fecha_inicio
 * @property string|null $fecha_termino
 * @property string|null $nombre_jefe_departamento
 *
 * @property Empleado $empleado
 * @property MotivoFechaPermiso $motivoFechaPermiso
 * @property Solicitud $solicitud
 */
class CambioHorarioTrabajo extends \yii\db\ActiveRecord
{
    public $jefe_departamento_id;
    public $dateRange;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cambio_horario_trabajo';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['horario_inicio', 'horario_fin', 'fecha_inicio', 'fecha_termino'], 'safe'],
            [['horario_inicio', 'turno', 'horario_fin'], 'required'],
            [['turno'], 'string', 'max' => 12],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['motivo_fecha_permiso_id'], 'exist', 'skipOnError' => true, 'targetClass' => MotivoFechaPermiso::class, 'targetAttribute' => ['motivo_fecha_permiso_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'], 
            [['dateRange'], 'required', 'message' => 'El rango de fechas no puede estar vacío.'], // Regla y mensaje personalizado para dateRange
            ['empleado_id', 'validarLimiteAnual'], // VALIDA EL LIMITE ANUAL ESTABLECIDO

        
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
            'motivo_fecha_permiso_id' => 'Motivo Fecha Permiso ID',
            'turno' => 'Turno',
            'horario_inicio' => 'Inicio de horario',
            'horario_fin' => 'Fin de horario',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_termino' => 'Fecha Termino',
            'nombre_jefe_departamento' => 'Nombre Jefe Departamento',
            'jefe_departamento_id' => 'Jefe de Departamento',
            'dateRange' => 'Rango de Fechas',
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
     * Gets query for [[MotivoFechaPermiso]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMotivoFechaPermiso()
    {
        return $this->hasOne(MotivoFechaPermiso::class, ['id' => 'motivo_fecha_permiso_id']);
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

    // FUNCIÓN QUE PERMITE RECIBIR FECHAS EN RANGO DESDE INPUS DE DATE RANGE
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->dateRange) {
                list($this->fecha_inicio, $this->fecha_termino) = explode(' a ', $this->dateRange);
            }
            
            return true;
        } else {
            return false;
        }
    }

//FUNCION QUE VALIDA EL LIMITE DE REGISTROS ANUALES,
//SE INDENTIFICA EL AÑO PRESENTE, EL TIPO DE PERMISO  Y EL TIPO DE CONTRATO DEL EMPLEADO
//Y SABER LA CANTIDAD DE PERMISOS QUE TIENE SU TIPO DE CONTRATO
    public function validarLimiteAnual($attribute, $params)
    {
        $añoActual = date('Y');
        $empleado = Empleado::findOne($this->empleado_id);
        $tipoContratoId = $empleado->informacionLaboral->cat_tipo_contrato_id;
    
        // Contar los registros de ComisionEspecial del año actual, basándose en la fecha de MotivoFechaPermiso
        $contadorPermisos = static::find()
            ->joinWith('motivoFechaPermiso') // Unir con la tabla motivo_fecha_permiso
            ->where(['empleado_id' => $this->empleado_id])
            ->andWhere(['between', 'motivo_fecha_permiso.fecha_permiso', "$añoActual-01-01", "$añoActual-12-31"])
            ->count();
    
        $limiteAnual = ParametroFormato::find()
            ->where(['tipo_permiso' => 'CAMBIO DE HORARIO DE TRABAJO', 'cat_tipo_contrato_id' => $tipoContratoId])
            ->one()->limite_anual;
    
        if ($contadorPermisos >= $limiteAnual) {
            $this->addError($attribute, 'Has alcanzado el límite anual de cambio de horario de trabajo para tu tipo de contrato.');
        }
    }
}
