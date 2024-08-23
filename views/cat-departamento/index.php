<?php

use yii\helpers\Html;
use yii\grid\GridView;
use kartik\select2\Select2;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\CatDireccion;
/* @var $this yii\web\View */
/* @var $searchModel app\models\CatDepartamentoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Cat Departamentos';
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-8">
  <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Departamentos:</h3>
                    <?= Html::a('Agregar nuevo <i class="fa fa-plus-circle"></i> ', ['//cat-departamento/create'], ['class' => 'btn btn-dark fa-lg mt-3 ml-3'])?>
                   


                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                          //  'id',
                          ///  'nombre_departamento',

                            [
                                'attribute' => 'nombre_departamento',
                                'label' => 'Departamento:',
                                'value' => function ($model) {
                                    return $model->nombre_departamento;
                                },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'nombre_departamento',
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\CatDepartamento::find()->select(['nombre_departamento'])->distinct()->all(), 'nombre_departamento', 'nombre_departamento'),
                                    'options' => ['placeholder' => 'Buscar Departamento'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                ]),
                                'contentOptions' => ['class' => 'small-font'], 
                            ],
                          ///  'cat_direccion_id',

                            


                          [
                            'attribute' => 'cat_direccion_id',
                            'label' => 'Dirección',
                            'value' => function ($model) {
                                return $model->catDireccion
                                    ? $model->catDireccion->nombre_direccion
                                    : 'N/A';
                            },
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'cat_direccion_id',
                                'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'),
                                'options' => ['placeholder' => 'Dirección'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                                'theme' => Select2::THEME_KRAJEE_BS3, 
                            ]),
                            'contentOptions' => ['class' => 'small-font'], 
                        ],
                          
                           
                            [
                                'attribute' => 'cat_dpto_id',
                                'label' => 'DPTO',
                              
                                'filter' => false,
                                'value' => function ($model) {
                                    
                                    return $model->catDpto->nombre_dpto;
                                },
                            ],

                          

                            [
                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                'template' => '{update} {delete}',
                                'buttons' => [
                                //    'view' => function ($url, $model, $key) {
                                  //      return Html::a('<i class="fa fa-eye"></i>', ['//cat-departamento/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                                   // },
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-pen"></i>', ['//cat-departamento/update', 'id' => $model->id], ['title' => 'Editar', 'class' => 'btn btn-warning btn-xs']);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-trash"></i>', ['//cat-departamento/delete', 'id' => $model->id], [
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
<!--.card-->
</div>
</div>
</div>