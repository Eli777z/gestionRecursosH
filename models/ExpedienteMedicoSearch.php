<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ExpedienteMedico;

/**
 * ExpedienteMedicoSearch represents the model behind the search form of `app\models\ExpedienteMedico`.
 */
class ExpedienteMedicoSearch extends ExpedienteMedico
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id', 'consulta_medica_id', 'documento_id', 'antecedente_hereditario_id', 'antecedente_patologico_id', 'antecedente_no_patologico_id', 'no_seguridad_social'], 'integer'],
            [['medicacion_necesitada', 'alergias'], 'safe'],
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
        $query = ExpedienteMedico::find();

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
            'empleado_id' => $this->empleado_id,
            'consulta_medica_id' => $this->consulta_medica_id,
            'documento_id' => $this->documento_id,
            'antecedente_hereditario_id' => $this->antecedente_hereditario_id,
            'antecedente_patologico_id' => $this->antecedente_patologico_id,
            'antecedente_no_patologico_id' => $this->antecedente_no_patologico_id,
            'no_seguridad_social' => $this->no_seguridad_social,
        ]);

        $query->andFilterWhere(['like', 'medicacion_necesitada', $this->medicacion_necesitada])
            ->andFilterWhere(['like', 'alergias', $this->alergias]);

        return $dataProvider;
    }
}
