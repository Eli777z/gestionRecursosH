<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Solicitud;

/**
 * SolicitudSearch represents the model behind the search form of `app\models\Solicitud`.
 */
class SolicitudSearch extends Solicitud
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id'], 'integer'],
            [['fecha_creacion', 'comentario', 'fecha_aprobacion', 'nombre_aprobante', 'nombre_formato', 'status'], 'safe'],
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
        $query = Solicitud::find();

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
            'fecha_creacion' => $this->fecha_creacion,
            'status' => $this->status,
            'fecha_aprobacion' => $this->fecha_aprobacion,
        ]);

        $query->
andFilterWhere(['like', 'nombre_aprobante', $this->nombre_aprobante])
            ->andFilterWhere(['like', 'nombre_formato', $this->nombre_formato])

            ->andFilterWhere(['like', 'status', $this->status]);

        return $dataProvider;
    }
}
