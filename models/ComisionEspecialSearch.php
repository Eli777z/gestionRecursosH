<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\ComisionEspecial;

/**
 * ComisionEspecialSearch represents the model behind the search form of `app\models\ComisionEspecial`.
 */
class ComisionEspecialSearch extends ComisionEspecial
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id', 'status'], 'integer'],
            [['nombre_jefe_departamento'], 'safe'],
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
        $query = ComisionEspecial::find();

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
            'motivo_fecha_permiso_id' => $this->motivo_fecha_permiso_id,
        ]);

        $query->andFilterWhere(['like', 'nombre_jefe_departamento', $this->nombre_jefe_departamento]);

        return $dataProvider;
    }
}
