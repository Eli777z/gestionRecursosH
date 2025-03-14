<?php
// IMPORTACIONES
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
                                        
                                        <?php 
                                        //PERMISO QUE MUESTRA TODAS LAS SOLICITUDES
                                        if (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>

                                            <h3>HISTORIAL DE SOLICITUDES DE INCIDENCIAS: </h3>
<?php if (Yii::$app->user->can('gestor-rh')) {?>
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
    <?=  Html::a('REPORTE DE TIEMPO EXTRA GENERAL', Url::to(['reporte-tiempo-extra-general/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0'])?>

   <?php if ($model->informacionLaboral->catTipoContrato->nombre_tipo === 'Eventual'): ?>
    <?= Html::a('SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL', Url::to(['contrato-para-personal-eventual/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) ?>
<?php endif; ?>
   
  </div>
</div>
<?php }?>
                                        <?php } elseif (Yii::$app->user->can('ver-solicitudes-medicas')) { ?>
                                        
                                            <h3>HISTORIAL DE SOLICITUDES MEDICAS: </h3>



                                        <?php } ?>
                                    </div>

                                    <li class="dropdown-divider"></li>
                                  

                                    <?php
                                    //PERMISO QUE MUESTRA SOLO EL HISTORIAL DE SOLICITUDES MEDICAS
                                    if (Yii::$app->user->can('solicitudes-medicas-view-medico')) { ?>
<div class="row">
<?php Pjax::begin(); ?>
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            
            [
                'class' => 'yii\grid\DataColumn',
                'header' => '',
                'format' => 'raw',
                'value' => function ($model) {// MUESTRA EL ESTATUS DE SOLICITUD PARA INDENTIFICAR SI ESTA ES NUEVA
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
                'class' => 'yii\grid\DataColumn',
                'header' => 'Aprobación',
                'format' => 'raw',
                
                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                    if ($model->aprobacion === 'PENDIENTE') {
                        return '<i class="fas fa-stopwatch" aria-hidden="true" style="color: #4678fc"></i> PENDIENTE';
                    } elseif ($model->aprobacion === 'APROBADO') {
                        return '<i class="fas fa-check" aria-hidden="true" style="color: #2fcf04"></i> APROBADO';
                    } elseif ($model->aprobacion === 'RECHAZADO') {
                        return '<i class="fa fa-times" aria-hidden="true" style="color: #d91e1e"></i> RECHAZADO';
                   
                    }else{
                        return 'No aplica';



                    }
                    
                },
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
        
            [
                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                'template' => '{view} ',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-outline-info btn-sm']);
                    },
              //      'delete' => function ($url, $model, $key) {
                //        return Html::a('<i class="fa fa-trash"></i>', ['solicitud/delete', 'id' => $model->id], [
                  //          'title' => 'Eliminar',
                    //        'class' => 'btn btn-danger btn-xs',
                      //      'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                            'data-method' => 'post',
                      //  ]);
                   // },
                ],
            ],
        ],
        'summaryOptions' => ['class' => 'summary mb-2'],
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager',
        ],
    ]); ?>     <?php Pjax::end(); ?>
    
 
</div>

<?php } elseif (Yii::$app->user->can('solicitudes-medicas-view-empleado')) { //MUESTRA TODO TIPO DE SOLICITUDES?>
<div class="row">
<?php Pjax::begin(); ?>
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
                'class' => 'yii\grid\DataColumn',
                'header' => 'Aprobación',
                'format' => 'raw',
                
                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                    if ($model->aprobacion === 'PENDIENTE') {
                        return '<i class="fas fa-stopwatch" aria-hidden="true" style="color: #4678fc"></i> PENDIENTE';
                    } elseif ($model->aprobacion === 'APROBADO') {
                        return '<i class="fas fa-check" aria-hidden="true" style="color: #2fcf04"></i> APROBADO';
                    } elseif ($model->aprobacion === 'RECHAZADO') {
                        return '<i class="fa fa-times" aria-hidden="true" style="color: #d91e1e"></i> RECHAZADO';
                   
                    }else{
                        return 'No aplica';



                    }
                    
                },
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
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
    <?php Pjax::end(); ?>
  
</div>

<?php } elseif (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>
<div class="row">
<?php Pjax::begin(); ?>
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
                'class' => 'yii\grid\DataColumn',
                'header' => 'Aprobación',
                'format' => 'raw',
                
                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                    if ($model->aprobacion === 'PENDIENTE') {
                        return '<i class="fas fa-stopwatch" aria-hidden="true" style="color: #4678fc"></i> PENDIENTE';
                    } elseif ($model->aprobacion === 'APROBADO') {
                        return '<i class="fas fa-check" aria-hidden="true" style="color: #2fcf04"></i> APROBADO';
                    } elseif ($model->aprobacion === 'RECHAZADO') {
                        return '<i class="fa fa-times" aria-hidden="true" style="color: #d91e1e"></i> RECHAZADO';
                   
                    }else{
                        return 'No aplica';



                    }
                    
                },
                'contentOptions' => ['class' => 'text-center'],
                'headerOptions' => ['class' => 'text-center'],
            ],
            [
                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                'template' => '{view}',// {delete}',
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
     <?php Pjax::end(); ?>
</div>
<?php } ?>


                                </div>
                            </div>
                            