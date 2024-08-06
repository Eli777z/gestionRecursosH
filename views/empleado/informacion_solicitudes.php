<?php

use yii\helpers\Html;

use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\jui\DatePicker;

use kartik\select2\Select2;


?>
<div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <?php if (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>

                                            <h3>HISTORIAL DE SOLICITUDES DE INCIDENCIAS: </h3>

                                            <div class="dropdown float-right">
  <button class="btn btn-dark dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
    FORMATOS DE INCIDENCIAS
  </button>
  <div class="dropdown-menu">
  <?= Html::a('CITA MEDICA', Url::to(['cita-medica/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>

    <?= Html::a('PERMISO FUERA DEL TRABAJO', Url::to(['permiso-fuera-trabajo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
   

    <?= Html::a('COMISIÓN ESPECIAL', Url::to(['comision-especial/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    
    <?= Html::a('CAMBIO DE DÍA LABORAL', Url::to(['cambio-dia-laboral/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    <?=  Html::a('CAMBIO DE HORARIO DE TRABAJO', Url::to(['cambio-horario-trabajo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    <?=  Html::a('PERMISO ECONÓMICO', Url::to(['permiso-economico/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    <?= Html::a('PERMISO SIN GOCE DE SUELDO', Url::to(['permiso-sin-sueldo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    <?= Html::a('CAMBIO DE PERIODO VACACIONAL', Url::to(['cambio-periodo-vacacional/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
    <?=  Html::a('REPORTE DE TIEMPO EXTRA', Url::to(['reporte-tiempo-extra/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0'])?>
    <?= Html::a('REPORTE DE TIEMPO EXTRA GENERAL', Url::to(['reporte-tiempo-extra-general/index']), ['class' => 'dropdown-item text-primary']) ?>

   
  </div>
</div>

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
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-outline-info btn-sm']);
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
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-outline-info btn-sm']);
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
                'template' => '{view}',// {delete}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-outline-info btn-sm']);
                    },
               //     'delete' => function ($url, $model, $key) {
                 //       return Html::a('<i class="fa fa-trash"></i>', ['solicitud/delete', 'id' => $model->id], [
                   //         'title' => 'Eliminar',
                     //       'class' => 'btn btn-danger btn-xs',
                       //     'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                         //   'data-method' => 'post',
              //          ]);
                //    },
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
                            