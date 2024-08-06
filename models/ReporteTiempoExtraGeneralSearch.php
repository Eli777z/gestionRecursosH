<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ReporteTiempoExtraGeneral;

/**
 * ReporteTiempoExtraGeneralSearch represents the model behind the search form of `app\models\ReporteTiempoExtraGeneral`.
 */
class ReporteTiempoExtraGeneralSearch extends ReporteTiempoExtraGeneral
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id', 'solicitud_id', 'total_horas'], 'integer'],
            [['created_at'], 'safe'],
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
        $query = ReporteTiempoExtraGeneral::find();

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
            'solicitud_id' => $this->solicitud_id,
            'total_horas' => $this->total_horas,
            'created_at' => $this->created_at,
        ]);

        return $dataProvider;
    }
}
