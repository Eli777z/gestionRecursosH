<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\DetailView;

use yii\widgets\Pjax;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PermisoFueraTrabajoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->title = 'Reporte de Tiempo extra';

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-info text-white">
                    <h3>Historial de Reporte de Tiempo Extra</h3>
                   
                    <p>Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
                    <?php if (Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion')) { ?>
                        <?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
                            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                            'encode' => false, // Para que el HTML dentro del enlace no se escape
                        ]) ?>
                    <?php } else  if (Yii::$app->user->can('gestor-rh')) {
    // Redirige al historial del navegador
    echo Html::a('<i class="fa fa-chevron-left"></i> Volver', 'javascript:void(0);', [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
        'onclick' => 'window.history.back(); return false;',
    ]);
}?>
                </div>
                <div class="card-body">
                <div class="d-flex align-items-center mb-3">
                    <?php
                    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                        echo Alert::widget([
                            'options' => ['class' => 'alert-' . $type],
                            'body' => $message,
                        ]);
                    }
                    ?>
                </div>
                    <div class="row mb-2">

                        <div class="col-md-12">
                            <?= GridView::widget([
                                'dataProvider' => $dataProvider,
                                'filterModel' => $searchModel,
                                'columns' => [
                                    ['class' => 'yii\grid\SerialColumn'],
                                    
                                    //'solicitud_id',
                                    [
                                        'label' => 'ID',
                                        'attribute' => 'solicitud_id',
                                        'value' => function ($model) {
                                            return $model->solicitud_id;
                                        },
                                    ],
                                    [
                                        'label' => 'Fecha de creación',
                                        'attribute' => 'created_at',
                                        'value' => function ($model) {
                                           
                                            setlocale(LC_TIME, "es_419.UTF-8");
                                            
                                            $fechaPermiso = strtotime($model->created_at);
                                            
                                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                            
                                            setlocale(LC_TIME, null);
                                            
                                            return $fechaFormateada;
                                        },
                                    ],
                                
                                   
                                    [
                                        'class' => 'yii\grid\DataColumn',
                                        'header' => 'Aprobación',
                                        'format' => 'raw',
                                        
                                        'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                                            if ($model->solicitud->aprobacion === 'PENDIENTE') {
                                                return 'PENDIENTE <i class="fas fa-stopwatch" aria-hidden="true" style="color: #ea4242 "></i>';
                                            } elseif ($model->solicitud->aprobacion  === 'APROBADO') {
                                                return 'APROBADO <i class="fas fa-check" aria-hidden="true" style="color: #4678fc"></i>';
                                            } elseif ($model->solicitud->aprobacion  === 'RECHAZADO') {
                                                return 'RECHAZADO <i class="fa fa-times" aria-hidden="true" style="color: #4678fc"></i>';
                                           
                                            }else{
                                                return 'No aplica';
        
        
        
                                            }
                                            
                                        },
                                        'contentOptions' => ['class' => 'text-center'],
                                        'headerOptions' => ['class' => 'text-center'],
                                    ],
                                    [
                                        'label' => 'Revisado en',
                                        'attribute' => 'fecha_aprobacion',
                                        'value' => function ($model) {
                                           
                                            setlocale(LC_TIME, "es_419.UTF-8");
                                            
                                            $fechaPermiso = strtotime($model->solicitud->fecha_aprobacion);
                                            
                                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                            
                                            setlocale(LC_TIME, null);
                                            
                                            return $fechaFormateada;
                                        },
                                    ],
                                    // otros atributos del reporte...
                                    [
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion')  ? '{view}' : '{view} {delete}',

        'buttons' => [
                       
                       'view' => function ($url, $model) {
                           return Html::a('<i class="far fa-eye"></i>', $url, [
                               'title' => 'Ver solicitud',
                               'class' => 'btn btn-outline-info btn-sm',
                               'data-pjax' => "0"
                           ]);
                       },
                      'delete' => function ($url, $model) {
                           return Html::a('<i class="fas fa-trash"></i>', $url, [
                               'title' => Yii::t('yii', 'Eliminar'),
                               'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                               'data-method' => 'post',
                              'class' => 'btn btn-outline-danger btn-sm'
                           ]);
                       },
   
                   ],
    
                                    ],
                                ],
                            ]); ?>
                           
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
