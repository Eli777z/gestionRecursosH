<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\Empleado;
use Yii;
/**
 * EmpleadoSearch represents the model behind the search form of `app\models\Empleado`.
 */
class EmpleadoSearch extends Empleado
{
    public $cat_departamento_id;
    public $cat_direccion_id;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'numero_empleado', 'usuario_id', 'informacion_laboral_id', 'cat_nivel_estudio_id', 'parametro_formato_id', 'edad', 'sexo', 'estado_civil', 'numero_casa', 'codigo_postal', 'relacion_contacto_emergencia'], 'integer'],
            [['nombre', 'apellido', 'fecha_nacimiento', 'foto', 'telefono', 'email', 'colonia', 'calle', 'nombre_contacto_emergencia', 'telefono_contacto_emergencia', 'institucion_educativa', 'titulo_grado'], 'safe'],
            [['cat_departamento_id', 'cat_direccion_id'], 'integer'],
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
    $query = Empleado::find()->joinWith('usuario')->where(['usuario.status' => 10]); // Solo empleados activos

 //   $query = Empleado::find();
    $query->alias('e'); // Alias para la tabla empleado
    $query->joinWith(['informacionLaboral il']); // Alias para la tabla informacion_laboral
    $query->joinWith(['informacionLaboral.catDepartamento cd']); // Alias para la tabla cat_departamento
    $query->joinWith(['informacionLaboral.catDireccion cdir']); // Alias para la tabla cat_direccion
    $query->joinWith(['expedienteMedico em']); // Alias para la tabla expediente_medico

    // Crear el DataProvider con la lógica para ordenar y paginar según el rol del usuario
    if (Yii::$app->user->can('medico')) {
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([
                'em.primera_revision' => SORT_ASC, // Cambiado a 'em.primera_revision'
            ]),
            'pagination' => [
                'pageSize' => 50,
            ],
        ]);
    } else {
        $dataProvider = new ActiveDataProvider([
            'query' => $query->orderBy([
                'e.apellido' => SORT_ASC,
            ]),
            'pagination' => [
                'pageSize' => 25,
            ],
        ]);
    }

    $this->load($params);

    if (!$this->validate()) {
        return $dataProvider;
    }

    // Aplicar filtros según los valores ingresados en el formulario de búsqueda
    $query->andFilterWhere([
        'e.id' => $this->id,
        'e.numero_empleado' => $this->numero_empleado,
        'e.usuario_id' => $this->usuario_id,
        'e.informacion_laboral_id' => $this->informacion_laboral_id,
        'e.cat_nivel_estudio_id' => $this->cat_nivel_estudio_id,
        'e.parametro_formato_id' => $this->parametro_formato_id,
        'e.fecha_nacimiento' => $this->fecha_nacimiento,
        'e.edad' => $this->edad,
        'e.sexo' => $this->sexo,
        'e.estado_civil' => $this->estado_civil,
        'e.numero_casa' => $this->numero_casa,
        'e.codigo_postal' => $this->codigo_postal,
        'e.relacion_contacto_emergencia' => $this->relacion_contacto_emergencia,
        'il.cat_departamento_id' => $this->cat_departamento_id,
        'il.cat_direccion_id' => $this->cat_direccion_id,
    ]);

    $query->andFilterWhere(['like', 'e.nombre', $this->nombre])
        ->andFilterWhere(['like', 'e.apellido', $this->apellido])
        ->andFilterWhere(['like', 'e.foto', $this->foto])
        ->andFilterWhere(['like', 'e.telefono', $this->telefono])
        ->andFilterWhere(['like', 'e.email', $this->email])
        ->andFilterWhere(['like', 'e.colonia', $this->colonia])
        ->andFilterWhere(['like', 'e.calle', $this->calle])
        ->andFilterWhere(['like', 'e.nombre_contacto_emergencia', $this->nombre_contacto_emergencia])
        ->andFilterWhere(['like', 'e.telefono_contacto_emergencia', $this->telefono_contacto_emergencia])
        ->andFilterWhere(['like', 'e.institucion_educativa', $this->institucion_educativa])
        ->andFilterWhere(['like', 'e.titulo_grado', $this->titulo_grado]);

    return $dataProvider;
}

}
