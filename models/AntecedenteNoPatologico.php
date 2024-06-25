<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "antecedente_no_patologico".
 *
 * @property int $id
 * @property int|null $p_ejercicio
 * @property int|null $p_deporte
 * @property string|null $p_a_deporte
 * @property string|null $p_frecuencia_deporte
 * @property int|null $p_dormir_dia
 * @property float|null $p_horas_sueño
 * @property int|null $p_desayuno
 * @property int|null $p_cafe
 * @property int|null $p_refresco
 * @property int|null $p_dieta 
 *  * @property int|null $p_minutos_x_dia_ejercicio
 * @property string|null $p_info_dieta
  * @property string|null $tipo_sangre
  * @property string|null $relgion
 * @property int|null $p_comidas_x_dia
 * @property int|null $p_tazas_x_dia
 * @property string|null $observacion_comida
 * @property int|null $p_alcohol
 * @property int|null $p_ex_alcoholico
 * @property int|null $p_edad_alcoholismo
 * @property int|null $p_edad_fin_alcoholismo
 * @property int|null $p_copas_x_dia
 * @property int|null $p_cervezas_x_dia
 * @property string|null $p_frecuencia_alcohol
  * @property string|null $p_frecuencia_droga

 * @property string|null $observacion_alcoholismo
 * @property int|null $p_fuma
 * @property int|null $p_ex_fumador
 * @property int|null $p_fumador_pasivo
 * @property int|null $p_edad_tabaquismo
 * @property int|null $p_no_cigarros_x_dia
 * @property int|null $p_edad_fin_tabaquismo
 * @property string|null $p_frecuencia_tabaquismo
 * @property string|null $observacion_tabaquismo
 * @property string|null $p_act_dias_libres
 * @property string|null $p_situaciones
 * @property int|null $p_drogas
 * @property int|null $p_ex_adicto
 * @property int|null $p_droga_intravenosa
 * @property int|null $p_edad_droga
 * @property int|null $p_edad_fin_droga
 * @property string|null $observacion_droga
 * @property string|null $observacion_actividad_fisica

 * @property string|null $observacion_general
 * @property string|null $datos_vivienda
 * @property int|null $expediente_medico_id
 *
 * @property ExpedienteMedico $expedienteMedico
 * @property ExpedienteMedico[] $expedienteMedicos
 */
class AntecedenteNoPatologico extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'antecedente_no_patologico';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['p_ejercicio', 'p_deporte', 'p_dormir_dia', 'p_desayuno', 'p_cafe', 'p_refresco', 'p_dieta', 'p_comidas_x_dia', 'p_tazas_x_dia', 'p_alcohol', 'p_ex_alcoholico', 'p_edad_alcoholismo', 'p_edad_fin_alcoholismo', 'p_copas_x_dia', 'p_cervezas_x_dia', 'p_fuma', 'p_ex_fumador', 'p_fumador_pasivo', 'p_edad_tabaquismo', 'p_no_cigarros_x_dia', 'p_edad_fin_tabaquismo', 'p_drogas', 'p_ex_adicto', 'p_droga_intravenosa', 'p_edad_droga', 'p_edad_fin_droga', 'expediente_medico_id', 'p_minutos_x_dia_ejercicio'], 'integer'],
            [['p_horas_sueño'], 'number'],
            [['p_info_dieta', 'observacion_comida', 'observacion_alcoholismo', 'observacion_tabaquismo', 'observacion_droga', 'observacion_general', 'datos_vivienda', 'observacion_actividad_fisica', 'p_act_dias_libres'], 'string'],
            [['p_a_deporte', 'p_frecuencia_deporte'], 'string', 'max' => 65],
            [['p_frecuencia_alcohol', 'p_frecuencia_tabaquismo', 'p_frecuencia_droga'], 'string', 'max' => 25],
          //  [['p_act_dias_libres'], 'string', 'max' => 150],
            [['p_situaciones'], 'string', 'max' => 20],
            [['religion'], 'string', 'max' => 45],
            [['tipo_sangre'], 'string', 'max' => 6],

            [['expediente_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpedienteMedico::class, 'targetAttribute' => ['expediente_medico_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'tipo_sangre' => 'Tipo Sangre', //checkbox para marcar, marcado es 1, sin marcar 0

            'p_ejercicio' => 'P Ejercicio', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_deporte' => 'P Deporte',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_a_deporte' => 'P A Deporte', // inputtext
            'p_frecuencia_deporte' => 'P Frecuencia Deporte',// inputtext
            'p_dormir_dia' => 'P Dormir Dia',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_horas_sueño' => 'P Horas Sueño', //campo number
            'p_desayuno' => 'P Desayuno',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_cafe' => 'P Cafe',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_refresco' => 'P Refresco',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_dieta' => 'P Dieta',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_info_dieta' => 'P Info Dieta', //textarea
            'p_comidas_x_dia' => 'P Comidas X Dia', //campo number
            'p_tazas_x_dia' => 'P Tazas X Dia', //campo number
            'observacion_comida' => 'Observacion Comida', //textarea
            'p_alcohol' => 'P Alcohol', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_ex_alcoholico' => 'P Ex Alcoholico', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_edad_alcoholismo' => 'P Edad Alcoholismo',//campo number
            'p_edad_fin_alcoholismo' => 'P Edad Fin Alcoholismo', //campo number
            'p_copas_x_dia' => 'P Copas X Dia', //campo number
            'p_cervezas_x_dia' => 'P Cervezas X Dia', //campo number
            'p_frecuencia_alcohol' => 'P Frecuencia Alcohol', //dropdowns opciones: Nunca, Casual, Moderado, Intenso
            'observacion_alcoholismo' => 'Observacion Alcoholismo', //textarea
            'p_fuma' => 'P Fuma', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_ex_fumador' => 'P Ex Fumador', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_fumador_pasivo' => 'P Fumador Pasivo', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_edad_tabaquismo' => 'P Edad Tabaquismo', //campo number
            'p_no_cigarros_x_dia' => 'P No Cigarros X Dia', //campo number
            'p_edad_fin_tabaquismo' => 'P Edad Fin Tabaquismo', //campo number
            'p_frecuencia_tabaquismo' => 'P Frecuencia Tabaquismo', //dropdowns opciones: Nunca, Casual, Moderado, Intenso
            'observacion_tabaquismo' => 'Observacion Tabaquismo', //textarea
            'p_act_dias_libres' => 'P Act Dias Libres', //input text
            'p_situaciones' => 'P Situaciones', //dropdowns opciones: Duelo, Embarazos, Divorcio
            'p_drogas' => 'P Drogas',  //checkbox para marcar, marcado es 1, sin marcar 0
            'p_ex_adicto' => 'P Ex Adicto', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_droga_intravenosa' => 'P Droga Intravenosa', //checkbox para marcar, marcado es 1, sin marcar 0
            'p_edad_droga' => 'P Edad Droga', //campo number
            'p_edad_fin_droga' => 'P Edad Fin Droga', //campo number
            'observacion_droga' => 'Observacion Droga', //textarea 
            'observacion_general' => 'Observacion General', //textarea
            'datos_vivienda' => 'Datos Vivienda', //textarea
            'expediente_medico_id' => 'Expediente Medico ID', //llave foranea
            'p_minutos_x_dia_ejercicio' => 'Minutos al dia',
            'observacion_actividad_fisica' => 'Observacion Actividad Fisica', //textarea
            'p_frecuencia_droga' => 'P Frecuencia Droga', //dropdowns opciones: Nunca, Casual, Moderado, Intenso
            'religion' => 'Religion'

        ];
    }

    /**
     * Gets query for [[ExpedienteMedico]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedico()
    {
        return $this->hasOne(ExpedienteMedico::class, ['id' => 'expediente_medico_id']);
    }

    /**
     * Gets query for [[ExpedienteMedicos]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedienteMedicos()
    {
        return $this->hasMany(ExpedienteMedico::class, ['antecedente_no_patologico_id' => 'id']);
    }
}
