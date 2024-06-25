<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "expediente_medico".
 *
 * @property int $id
 * @property int|null $consulta_medica_id
 * @property int|null $documento_id
 * @property int|null $antecedente_hereditario_id
 * @property int|null $antecedente_patologico_id
 * @property int|null $antecedente_no_patologico_id
 * @property string|null $medicacion_necesitada
 * @property string|null $alergias
 * @property int|null $no_seguridad_social
 * @property int|null $empleado_id
 * @property int|null $exploracion_fisica_id
 * @property int|null $interrogatorio_medico_id


 * @property AntecedenteHereditario $antecedenteHereditario
 * @property AntecedenteHereditario[] $antecedenteHereditarios
 * @property AntecedenteNoPatologico $antecedenteNoPatologico
 * @property AntecedentePatologico $antecedentePatologico
 * @property Documento $documento
 * @property Empleado $empleado
 * @property Empleado[] $empleados
  * @property InterrogatorioMedico $interrogatorioMedico
 * @property InterrogatorioMedico[] $interrogatorioMedicos
  * @property ExploracionFisica $exploracionFisica
 * @property ExploracionFisica[] $exploracionFisicas
 */
class ExpedienteMedico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'expediente_medico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['consulta_medica_id', 'documento_id', 'antecedente_hereditario_id', 'antecedente_patologico_id', 'antecedente_no_patologico_id', 'no_seguridad_social', 'empleado_id'], 'integer'],
            [['medicacion_necesitada', 'alergias'], 'string'],
            [['antecedente_no_patologico_id'], 'exist', 'skipOnError' => true, 'targetClass' => AntecedenteNoPatologico::class, 'targetAttribute' => ['antecedente_no_patologico_id' => 'id']],
            [['antecedente_hereditario_id'], 'exist', 'skipOnError' => true, 'targetClass' => AntecedenteHereditario::class, 'targetAttribute' => ['antecedente_hereditario_id' => 'id']],
            [['antecedente_patologico_id'], 'exist', 'skipOnError' => true, 'targetClass' => AntecedentePatologico::class, 'targetAttribute' => ['antecedente_patologico_id' => 'id']],
            [['documento_id'], 'exist', 'skipOnError' => true, 'targetClass' => Documento::class, 'targetAttribute' => ['documento_id' => 'id']],
            [['empleado_id'], 'exist', 'skipOnError' => true, 'targetClass' => Empleado::class, 'targetAttribute' => ['empleado_id' => 'id']],
            [['exploracion_fisica_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExploracionFisica::class, 'targetAttribute' => ['exploracion_fisica_id' => 'id']],
            [['interrogatorio_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => InterrogatorioMedico::class, 'targetAttribute' => ['interrogatorio_medico_id' => 'id']],

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'consulta_medica_id' => 'Consulta Medica ID',
            'documento_id' => 'Documento ID',
            'antecedente_hereditario_id' => 'Antecedente Hereditario ID',
            'antecedente_patologico_id' => 'Antecedente Patologico ID',
            'antecedente_no_patologico_id' => 'Antecedente No Patologico ID',
            'medicacion_necesitada' => 'Medicacion Necesitada',
            'alergias' => 'Alergias',
            'no_seguridad_social' => 'No Seguridad Social',
            'empleado_id' => 'Empleado ID',
            'exploracion_fisica_id' => 'Exploracion Fisica ID',
            'interrogatorio_medico_id' => 'Interrogatorio Medico ID',

        ];
    }

    /**
     * Gets query for [[AntecedenteHereditario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedenteHereditario()
    {
        return $this->hasOne(AntecedenteHereditario::class, ['id' => 'antecedente_hereditario_id']);
    }

    /**
     * Gets query for [[AntecedenteHereditarios]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedenteHereditarios()
    {
        return $this->hasMany(AntecedenteHereditario::class, ['expediente_medico_id' => 'id']);
    }

    /**
     * Gets query for [[AntecedenteNoPatologico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedenteNoPatologico()
    {
        return $this->hasOne(AntecedenteNoPatologico::class, ['id' => 'antecedente_no_patologico_id']);
    }

    /**
     * Gets query for [[AntecedentePatologico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAntecedentePatologico()
    {
        return $this->hasOne(AntecedentePatologico::class, ['id' => 'antecedente_patologico_id']);
    }

    /**
     * Gets query for [[Documento]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDocumento()
    {
        return $this->hasOne(Documento::class, ['id' => 'documento_id']);
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
     * Gets query for [[Empleados]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getEmpleados()
    {
        return $this->hasMany(Empleado::class, ['expediente_medico_id' => 'id']);
    }

    public function getExploracionFisica()
    {
        return $this->hasOne(ExploracionFisica::class, ['id' => 'exploracion_fisica_id']);
    }

    /**
     * Gets query for [[ExploracionFisicas]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExploracionFisicas()
    {
        return $this->hasMany(ExploracionFisica::class, ['expediente_medico_id' => 'id']);
    }


 /**
     * Gets query for [[InterrogatorioMedico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInterrogatorioMedico()
    {
        return $this->hasOne(InterrogatorioMedico::class, ['id' => 'interrogatorio_medico_id']);
    }

    /**
     * Gets query for [[InterrogatorioMedicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInterrogatorioMedicos()
    {
        return $this->hasMany(InterrogatorioMedico::class, ['expediente_medico_id' => 'id']);
    }

    
}
