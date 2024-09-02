<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Solicitud;
use Yii;

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
            [['fecha_creacion', 'comentario', 'fecha_aprobacion', 'nombre_aprobante', 'nombre_formato', 'status', 'aprobacion'], 'safe'],
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
        // Crear la consulta base
        if (Yii::$app->user->can('ver-solicitudes-medicas')) {
            $query = Solicitud::find()->where(['nombre_formato' => 'CITA MEDICA']);
        } elseif (Yii::$app->user->can('ver-solicitudes-formatos') || Yii::$app->user->can('solicitudes-medicas-view-empleado')) {
            $query = Solicitud::find();
        } else {
            $query = Solicitud::find()->where('0=1');
        }
    
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy(['id' => SORT_DESC]),
            'pagination' => ['pageSize' => 50],
        ]);
    
        $this->load($params);
    
        if (!$this->validate()) {
            return $dataProvider;
        }
    
        $query->andFilterWhere([
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'status' => $this->status, // Esta línea filtra por status
            'fecha_aprobacion' => $this->fecha_aprobacion,
            'aprobacion' => $this->aprobacion,
        ]);
    
        $query->andFilterWhere(['like', 'nombre_aprobante', $this->nombre_aprobante])
              ->andFilterWhere(['like', 'nombre_formato', $this->nombre_formato]);
    
        if (!empty($this->fecha_creacion) && strpos($this->fecha_creacion, ' - ') !== false) {
            list($start_date, $end_date) = explode(' - ', $this->fecha_creacion);
            $query->andFilterWhere(['between', 'fecha_creacion', $start_date, $end_date]);
        }
    
        return $dataProvider;
    }
    
    
}
