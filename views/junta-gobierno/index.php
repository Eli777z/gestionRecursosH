<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\JuntaGobiernoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Junta Gobiernos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Create Junta Gobierno', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        'id',
        [
            'attribute' => 'cat_direccion_id',
            'value' => function($model) {
                return $model->catDireccion->nombre_direccion; // Reemplaza 'nombre' con el nombre del atributo que deseas mostrar
            },
        ],
        [
            'attribute' => 'cat_departamento_id',
            'value' => function($model) {
                return $model->catDepartamento->nombre_departamento; // Reemplaza 'nombre' con el nombre del atributo que deseas mostrar
            },
        ],
        [
            'attribute' => 'empleado_id',
            'value' => function($model) {
                return $model->empleado->nombre. ' '.$model->empleado->apellido ; // Reemplaza 'nombreCompleto' con el nombre del método que devuelve el nombre completo

            },
        ],
        'nivel_jerarquico',
        'profesion',
        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ]
]); ?>


                    <?php Pjax::end(); ?>

                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
