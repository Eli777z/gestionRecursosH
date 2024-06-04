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
            [['empleado_id', 'solicitud_id'], 'integer'],
            [['motivo'], 'string'],
            [['fecha_inicio_periodo', 'fecha_fin_periodo'], 'safe'],
            [['primera_vez'], 'string', 'max' => 3],
            [['nombre_jefe_departamento'], 'string', 'max' => 90],
            [['numero_periodo'], 'string', 'max' => 12],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['solicitud_id'], 'exist', 'skipOnError' => true, 'targetClass' => Solicitud::class, 'targetAttribute' => ['solicitud_id' => 'id']],
            [['jefe_departamento_id'], 'safe'], 
            [['dateRange'], 'safe'], 
            [['año'], 'string', 'max' => 8],

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
            'solicitud_id' => 'Solicitud ID',
            'motivo' => 'Motivo',
            'primera_vez' => 'Primera Vez',
            'nombre_jefe_departamento' => 'Nombre Jefe Departamento',
            'numero_periodo' => 'Numero Periodo',
            'fecha_inicio_periodo' => 'Fecha Inicio Periodo',
            'fecha_fin_periodo' => 'Fecha Fin Periodo',
            'jefe_departamento_id' => 'Jefe de Departamento',

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
            if ($this->dateRange) {
                list($this->fecha_inicio_periodo, $this->fecha_fin_periodo) = explode(' a ', $this->dateRange);
            }
            
            return true;
        } else {
            return false;
        }
    }
}
