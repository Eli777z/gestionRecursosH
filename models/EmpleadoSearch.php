<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empleado;

/**
 * EmpleadoSearch represents the model behind the search form of `app\models\Empleado`.
 */
class EmpleadoSearch extends Empleado
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numero_empleado', 'usuario_id', 'informacion_laboral_id', 'cat_nivel_estudio_id', 'parametro_formato_id', 'edad', 'sexo', 'estado_civil', 'numero_casa', 'codigo_postal', 'relacion_contacto_emergencia'], 'integer'],
            [['nombre', 'apellido', 'fecha_nacimiento', 'foto', 'telefono', 'email', 'colonia', 'calle', 'nombre_contacto_emergencia', 'telefono_contacto_emergencia', 'institucion_educativa', 'titulo_grado'], 'safe'],
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
        $query = Empleado::find();

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
            'numero_empleado' => $this->numero_empleado,
            'usuario_id' => $this->usuario_id,
            'informacion_laboral_id' => $this->informacion_laboral_id,
            'cat_nivel_estudio_id' => $this->cat_nivel_estudio_id,
            'parametro_formato_id' => $this->parametro_formato_id,
            'fecha_nacimiento' => $this->fecha_nacimiento,
            'edad' => $this->edad,
            'sexo' => $this->sexo,
            'estado_civil' => $this->estado_civil,
            'numero_casa' => $this->numero_casa,
            'codigo_postal' => $this->codigo_postal,
            'relacion_contacto_emergencia' => $this->relacion_contacto_emergencia,
        ]);

        $query->andFilterWhere(['like', 'nombre', $this->nombre])
            ->andFilterWhere(['like', 'apellido', $this->apellido])
            ->andFilterWhere(['like', 'foto', $this->foto])
            ->andFilterWhere(['like', 'telefono', $this->telefono])
            ->andFilterWhere(['like', 'email', $this->email])
            ->andFilterWhere(['like', 'colonia', $this->colonia])
            ->andFilterWhere(['like', 'calle', $this->calle])
            ->andFilterWhere(['like', 'nombre_contacto_emergencia', $this->nombre_contacto_emergencia])
            ->andFilterWhere(['like', 'telefono_contacto_emergencia', $this->telefono_contacto_emergencia])
            ->andFilterWhere(['like', 'institucion_educativa', $this->institucion_educativa])
            ->andFilterWhere(['like', 'titulo_grado', $this->titulo_grado]);

        return $dataProvider;
    }
}
