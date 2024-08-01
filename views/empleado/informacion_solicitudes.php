<?php

use yii\helpers\Html;

use yii\grid\GridView;
use yii\widgets\Pjax;

use yii\jui\DatePicker;

use kartik\select2\Select2;


?>
<div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <?php if (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>

                                            <h3>HISTORIAL DE SOLICITUDES DE INCIDENCIAS: </h3>



                                        <?php } elseif (Yii::$app->user->can('ver-solicitudes-medicas')) { ?>
                                            <h3>HISTORIAL DE SOLICITUDES MEDICAS: </h3>



                                        <?php } ?>
                                    </div>

                                    <li class="dropdown-divider"></li>
                                    <?php Pjax::begin(); ?>

                                    <?php if (Yii::$app->user->can('solicitudes-medicas-view-medico')) { ?>
<div class="row">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn',
                'header' => '',
                'format' => 'raw',
                'value' => function ($model) {
                    if ($model->status === 'Nueva') {
                        return '<i class="fa fa-fire" aria-hidden="true" style="color: #ea4242 "></i>';
                    } elseif ($model->status === 'Visto') {
                        return '<i class="fa fa-check-double" aria-hidden="true" style="color: #4678fc"></i>';
                      
                    } else {
                        return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                    }
                },
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'empleado_id',
                'label' => 'Empleado',
                'value' => function ($model) {
                    return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'fecha_creacion',
                'format' => 'raw',
                'value' => function ($model) {
                    setlocale(LC_TIME, "es_419.UTF-8");
                    return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'fecha_creacion',
                    'language' => 'es',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                    ],
                    'clientOptions' => [
                        'changeYear' => true,
                        'changeMonth' => true,
                        'yearRange' => '-100:+0',
                    ],
                ]),
            ],
        
            [
                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash"></i>', ['solicitud/delete', 'id' => $model->id], [
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
        ],
    ]); ?>
 
</div>

<?php } elseif (Yii::$app->user->can('solicitudes-medicas-view-empleado')) { ?>
<div class="row">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
           
            [
                'attribute' => 'empleado_id',
                'label' => 'Empleado',
                'value' => function ($model) {
                    return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'fecha_creacion',
                'format' => 'raw',
                'value' => function ($model) {
                    setlocale(LC_TIME, "es_419.UTF-8");
                    return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'fecha_creacion',
                    'language' => 'es',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                    ],
                    'clientOptions' => [
                        'changeYear' => true,
                        'changeMonth' => true,
                        'yearRange' => '-100:+0',
                    ],
                ]),
            ],
            [
                'attribute' => 'nombre_formato',
                'label' => 'Tipo de solicitud',
                'value' => function ($model) {
                    return $model->nombre_formato;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'nombre_formato',
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'),
                    'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_KRAJEE_BS3,
                ]),
            ],
            [
                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                    },
                ],
            ],
        ],
        'summaryOptions' => ['class' => 'summary mb-2'],
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager',
        ],
    ]); ?>
  
</div>

<?php } elseif (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>
<div class="row">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'class' => 'yii\grid\DataColumn',
                'header' => '',
                'format' => 'raw',
                
                'value' => function ($model) {
                    if ($model->status === 'Nueva') {
                        return '<i class="fa fa-fire" aria-hidden="true" style="color: #ea4242 "></i>';
                    } elseif ($model->status === 'Visto') {
                        return '<i class="fa fa-check-double" aria-hidden="true" style="color: #4678fc"></i>';
                      
                    } else {
                        return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                    }
                },
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'attribute' => 'empleado_id',
                'label' => 'Empleado',
                'value' => function ($model) {
                    return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                },
                'filter' => false,
            ],
            [
                'attribute' => 'fecha_creacion',
                'format' => 'raw',
                'value' => function ($model) {
                    setlocale(LC_TIME, "es_419.UTF-8");
                    return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                },
                'filter' => DatePicker::widget([
                    'model' => $searchModel,
                    'attribute' => 'fecha_creacion',
                    'language' => 'es',
                    'dateFormat' => 'php:Y-m-d',
                    'options' => [
                        'class' => 'form-control',
                        'autocomplete' => 'off',
                    ],
                    'clientOptions' => [
                        'changeYear' => true,
                        'changeMonth' => true,
                        'yearRange' => '-100:+0',
                    ],
                ]),
            ],
            [
                'attribute' => 'nombre_formato',
                'label' => 'Tipo de solicitud',
                'value' => function ($model) {
                    return $model->nombre_formato;
                },
                'filter' => Select2::widget([
                    'model' => $searchModel,
                    'attribute' => 'nombre_formato',
                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'),
                    'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_KRAJEE_BS3,
                ]),
            ],
            [
                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                'template' => '{view} {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                    },
                    'delete' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-trash"></i>', ['solicitud/delete', 'id' => $model->id], [
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
        ],
    ]); ?>
 
</div>
<?php } ?>
<?php Pjax::end(); ?>

                                </div>
                            </div>
                            