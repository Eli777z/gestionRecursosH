<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "cita_medica".
 *
 * @property int $id
 * @property int|null $empleado_id
 * @property int|null $solicitud_id
 * @property string|null $fecha_para_cita
 * @property string|null $comentario
 * @property string|null $horario_inicio
 * @property string|null $horario_finalizacion
 *
 * @property Empleado $empleado
 * @property Solicitud $solicitud
 */
class CitaMedica extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'cita_medica';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['empleado_id', 'solicitud_id'], 'integer'],
            [['fecha_para_cita', 'horario_inicio', 'horario_finalizacion'], 'safe'],
            [['comentario'], 'string'],
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
            'solicitud_id' => 'Solicitud ID',
            'fecha_para_cita' => 'Fecha Para Cita',
            'comentario' => 'Comentario',
            'horario_inicio' => 'Horario Inicio',
            'horario_finalizacion' => 'Horario Finalizacion',
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
}
