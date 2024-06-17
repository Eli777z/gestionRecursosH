<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AntecedenteHereditario;

/**
 * AntecedenteHereditarioSearch represents the model behind the search form of `app\models\AntecedenteHereditario`.
 */
class AntecedenteHereditarioSearch extends AntecedenteHereditario
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cat_antecedente_hereditario_id', 'abuelos', 'hermanos', 'padre', 'madre'], 'integer'],
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
        $query = AntecedenteHereditario::find();

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
            'cat_antecedente_hereditario_id' => $this->cat_antecedente_hereditario_id,
            'abuelos' => $this->abuelos,
            'hermanos' => $this->hermanos,
            'padre' => $this->padre,
            'madre' => $this->madre,
        ]);

        return $dataProvider;
    }
}
