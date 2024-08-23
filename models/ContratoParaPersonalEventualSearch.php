<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ContratoParaPersonalEventual;

/**
 * ContratoParaPersonalEventualSearch represents the model behind the search form of `app\models\ContratoParaPersonalEventual`.
 */
class ContratoParaPersonalEventualSearch extends ContratoParaPersonalEventual
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numero_contrato', 'duracion', 'empleado_id', 'solicitud_id'], 'integer'],
            [['fecha_inicio', 'fecha_termino', 'modalidad', 'actividades_realizar', 'origen_contrato'], 'safe'],
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
        $query = ContratoParaPersonalEventual::find();

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
            'numero_contrato' => $this->numero_contrato,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_termino' => $this->fecha_termino,
            'duracion' => $this->duracion,
            'empleado_id' => $this->empleado_id,
            'solicitud_id' => $this->solicitud_id,
        ]);

        $query->andFilterWhere(['like', 'modalidad', $this->modalidad])
            ->andFilterWhere(['like', 'actividades_realizar', $this->actividades_realizar])
            ->andFilterWhere(['like', 'origen_contrato', $this->origen_contrato]);

        return $dataProvider;
    }
}
