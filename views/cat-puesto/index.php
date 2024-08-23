<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CatPuestoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cat Puestos';
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-8">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Nombramientos:</h3>
                    <?= Html::a('Agregar nuevo <i class="fa fa-plus-circle"></i> ', ['//cat-puesto/create'], ['class' => 'btn btn-dark fa-lg mt-3 ml-3'])?>
                   


                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>

                    <?php Pjax::begin(); ?>

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        //'nombre_puesto',

        [
            'attribute' => 'nombre_puesto',
            'label' => 'Nombramiento:',
            'value' => function ($model) {
                return $model->nombre_puesto;
            },
            'filter' => Select2::widget([
                'model' => $searchModel,
                'attribute' => 'nombre_puesto',
                'data' => \yii\helpers\ArrayHelper::map(\app\models\CatPuesto::find()->select(['nombre_puesto'])->distinct()->all(), 'nombre_puesto', 'nombre_puesto'),
                'options' => ['placeholder' => 'Buscar Nombramiento'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
                'theme' => Select2::THEME_KRAJEE_BS3, 
            ]),
            'contentOptions' => ['class' => 'small-font'], 
        ],
        [
            'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
            'template' => ' {update} {delete}',
            'buttons' => [
             //   'view' => function ($url, $model, $key) {
               //     return Html::a('<i class="fa fa-eye"></i>', ['//cat-puesto/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
               // },
                'update' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-pen"></i>', ['//cat-puesto/update', 'id' => $model->id], ['title' => 'Editar', 'class' => 'btn btn-warning btn-xs']);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::a('<i class="fa fa-trash"></i>', ['//cat-puesto/delete', 'id' => $model->id], [
                        'title' => 'Eliminar',
                        'class' => 'btn btn-danger btn-xs',
                        'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                        'data-method' => 'post',
                    ]);
                },
            ],
        ],
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

</div>
</div>
</div>