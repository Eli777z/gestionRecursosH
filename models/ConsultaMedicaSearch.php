<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ConsultaMedica;

/**
 * ConsultaMedicaSearch represents the model behind the search form of `app\models\ConsultaMedica`.
 */
class ConsultaMedicaSearch extends ConsultaMedica
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cita_medica_id', 'expediente_medico_id'], 'integer'],
            [['created_at'], 'safe'],

            [['motivo', 'sintomas', 'diagnostico', 'tratamiento', 'aspecto_fisico', 'medico_atendio'], 'safe'],
            [['presion_arterial_minimo', 'presion_arterial_maximo', 'temperatura_corporal', 'nivel_glucosa', 'oxigeno_sangre', 'frecuencia_cardiaca', 'frecuencia_respiratoria', 'estatura', 'peso', 'imc'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = ConsultaMedica::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id,
            'cita_medica_id' => $this->cita_medica_id,
            'presion_arterial_minimo' => $this->presion_arterial_minimo,
            'presion_arterial_maximo' => $this->presion_arterial_maximo,
            'temperatura_corporal' => $this->temperatura_corporal,
            'nivel_glucosa' => $this->nivel_glucosa,
            'oxigeno_sangre' => $this->oxigeno_sangre,
            'frecuencia_cardiaca' => $this->frecuencia_cardiaca,
            'frecuencia_respiratoria' => $this->frecuencia_respiratoria,
            'estatura' => $this->estatura,
            'peso' => $this->peso,
            'imc' => $this->imc,
            'expediente_medico_id' => $this->expediente_medico_id,
            'created_at' => $this->created_at,

        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo])
            ->andFilterWhere(['like', 'sintomas', $this->sintomas])
            ->andFilterWhere(['like', 'diagnostico', $this->diagnostico])
            ->andFilterWhere(['like', 'tratamiento', $this->tratamiento])
            ->andFilterWhere(['like', 'aspecto_fisico', $this->aspecto_fisico])
            ->andFilterWhere(['like', 'medico_atendio', $this->medico_atendio])
            ->andFilterWhere(['like', 'created_at', $this->created_at]);

        return $dataProvider;
    }
}
