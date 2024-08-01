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
        // Crear la consulta base
        if (Yii::$app->user->can('ver-solicitudes-medicas')) {
            $query = Solicitud::find()->where(['nombre_formato' => 'CITA MEDICA']);
        } elseif (Yii::$app->user->can('ver-solicitudes-formatos') || Yii::$app->user->can('solicitudes-medicas-view-empleado')) {
            $query = Solicitud::find();
        } else {
            // En caso de que el usuario no tenga ninguno de los permisos
            $query = Solicitud::find()->where('0=1');
        }
    
        // Crear el proveedor de datos con la consulta
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([
                'id' => SORT_DESC,
            ]),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    
        // Cargar los parámetros de búsqueda
        $this->load($params);
    
        // Validar los parámetros de búsqueda
        if (!$this->validate()) {
            // Si la validación falla, devuelve el proveedor de datos sin aplicar filtros adicionales
            return $dataProvider;
        }
    
        // Aplicar condiciones de filtrado a la consulta
        $query->andFilterWhere([
            'id' => $this->id,
            'empleado_id' => $this->empleado_id,
            'fecha_creacion' => $this->fecha_creacion,
            'status' => $this->status,
            'fecha_aprobacion' => $this->fecha_aprobacion,
        ]);
    
        $query->andFilterWhere(['like', 'nombre_aprobante', $this->nombre_aprobante])
              ->andFilterWhere(['like', 'nombre_formato', $this->nombre_formato])
              ->andFilterWhere(['like', 'status', $this->status]);
    
        return $dataProvider;
    }
    
}
