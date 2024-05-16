<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\JuntaGobierno;

/**
 * JuntaGobiernoSearch represents the model behind the search form of `app\models\JuntaGobierno`.
 */
class JuntaGobiernoSearch extends JuntaGobierno
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'cat_direccion_id', 'cat_departamento_id', 'empleado_id'], 'integer'],
            [['nivel_jerarquico', 'profesion'], 'safe'],
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
        $query = JuntaGobierno::find();

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
            'cat_direccion_id' => $this->cat_direccion_id,
            'cat_departamento_id' => $this->cat_departamento_id,
            'empleado_id' => $this->empleado_id,
            
        ]);

        $query->andFilterWhere(['like', 'nivel_jerarquico', $this->nivel_jerarquico]);

        return $dataProvider;
    }
}
