<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ParametroFormato;

/**
 * ParametroFormatoSearch represents the model behind the search form of `app\models\ParametroFormato`.
 */
class ParametroFormatoSearch extends ParametroFormato
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'limite_anual', 'cat_tipo_contrato_id'], 'integer'],
            [['tipo_permiso'], 'safe'],
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
        $query = ParametroFormato::find();

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
            'limite_anual' => $this->limite_anual,
            'cat_tipo_contrato_id' => $this->cat_tipo_contrato_id,
        ]);

        $query->andFilterWhere(['like', 'tipo_permiso', $this->tipo_permiso]);

        return $dataProvider;
    }
}