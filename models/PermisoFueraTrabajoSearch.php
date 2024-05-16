<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PermisoFueraTrabajo;

/**
 * PermisoFueraTrabajoSearch represents the model behind the search form of `app\models\PermisoFueraTrabajo`.
 */
class PermisoFueraTrabajoSearch extends PermisoFueraTrabajo
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'empleado_id', 'solicitud_id', 'motivo_fecha_permiso_id'], 'integer'],
            [['hora_salida', 'hora_regreso', 'fecha_a_reponer', 'horario_fecha_a_reponer'], 'safe'],
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
        $query = PermisoFueraTrabajo::find();

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
            'motivo_fecha_permiso_id' => $this->motivo_fecha_permiso_id,
            'hora_salida' => $this->hora_salida,
            'hora_regreso' => $this->hora_regreso,
            'fecha_a_reponer' => $this->fecha_a_reponer,
            'horario_fecha_a_reponer' => $this->horario_fecha_a_reponer,
        ]);



        return $dataProvider;
    }
}
