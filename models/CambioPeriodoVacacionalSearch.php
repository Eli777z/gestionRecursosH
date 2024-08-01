<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\CambioPeriodoVacacional;

/**
 * CambioPeriodoVacacionalSearch represents the model behind the search form of `app\models\CambioPeriodoVacacional`.
 */
class CambioPeriodoVacacionalSearch extends CambioPeriodoVacacional
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id', 'solicitud_id'], 'integer'],
            [['motivo', 'primera_vez', 'nombre_jefe_departamento', 'numero_periodo', 'fecha_inicio_periodo', 'fecha_fin_periodo'], 'safe'],
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
        $query = CambioPeriodoVacacional::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([
                'id' => SORT_DESC,
            ]),
            'pagination' => [
                'pageSize' => 50,
            ],
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
            'solicitud_id' => $this->solicitud_id,
            'fecha_inicio_periodo' => $this->fecha_inicio_periodo,
            'fecha_fin_periodo' => $this->fecha_fin_periodo,
        ]);

        $query->andFilterWhere(['like', 'motivo', $this->motivo])
            ->andFilterWhere(['like', 'primera_vez', $this->primera_vez])
            ->andFilterWhere(['like', 'nombre_jefe_departamento', $this->nombre_jefe_departamento])
            ->andFilterWhere(['like', 'numero_periodo', $this->numero_periodo]);

        return $dataProvider;
    }
}
