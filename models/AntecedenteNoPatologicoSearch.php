<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AntecedenteNoPatologico;

/**
 * AntecedenteNoPatologicoSearch represents the model behind the search form of `app\models\AntecedenteNoPatologico`.
 */
class AntecedenteNoPatologicoSearch extends AntecedenteNoPatologico
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cat_actividad_fisica_id', 'tabaquismo', 'alcoholismo', 'drogas', 'actividad_fisica'], 'integer'],
            [['tipo_sangre'], 'safe'],
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
        $query = AntecedenteNoPatologico::find();

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
            'cat_actividad_fisica_id' => $this->cat_actividad_fisica_id,
            'tabaquismo' => $this->tabaquismo,
            'alcoholismo' => $this->alcoholismo,
            'drogas' => $this->drogas,
            'actividad_fisica' => $this->actividad_fisica,
        ]);

        $query->andFilterWhere(['like', 'tipo_sangre', $this->tipo_sangre]);

        return $dataProvider;
    }
}
