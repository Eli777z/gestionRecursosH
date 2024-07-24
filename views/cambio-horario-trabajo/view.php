<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Empleado;
use app\models\JuntaGobierno;
use yii\bootstrap5\Alert;


/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = $model->id;

\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
    <div class="card">

    <div class="card-header bg-info text-white">
                    <h2>CAMBIO DE HORARIO DE TRABAJO</h2>
                    <?php
// Obtener el ID del usuario actual
$usuarioActual = Yii::$app->user->identity;
$empleadoActual = $usuarioActual->empleado;
// Comparar el ID del empleado actual con el ID del empleado para el cual se está creando el registro
if ($empleadoActual->id === $empleado->id) {
    // El empleado está creando un registro para sí mismo
    echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
    ]);
} else {
    // El empleado está creando un registro para otro empleado
    if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) {
        echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    } else {
        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    }
}
?>

<?php


$usuarioId = Yii::$app->user->identity->id;

$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    $model->empleado_id = $empleado->id;

    $juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $empleado->id])->one();

        if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')){
          //  echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success']);
            //echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
            echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html', 'id' => $model->id], ['class' => 'btn btn-dark ', 'target' => '_blank']) ;

        } else {
          //  echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-success']);
            //echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
            echo Html::a('<i class="fa fa-print" aria-hidden="true"></i> Vista Previa de Impresión', ['export-html', 'id' => $model->id], ['class' => 'btn btn-dark ', 'target' => '_blank']) ;

        }
    
} else {
    Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
    return $this->redirect(['index']);
}
?>

    </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                
                    <?php 
    // Mostrar los flash messages
    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
        echo Alert::widget([
            'options' => ['class' => 'alert-' . $type],
            'body' => $message,
        ]);
    }
    ?>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                           //'id',
                           // 'empleado_id',
                            'solicitud_id',
                            [
                                'label' => 'Motivo del cambio de horario',
                                'attribute' => 'motivo',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->motivoFechaPermiso->motivo);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 65%;'],
                            ],
                            [
                                'label' => 'Fecha',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($model->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
//                           [
 //                               'label' => 'Status',
  //                           'value' => function ($model) {
    //                               
      //                  
        ///                            
           ///                         $status = $model->solicitud->status;
                                    
                                
              //                      return $status;
                //                },
                  ///          ],
                    
                    
                 //           [
                   //             'label' => 'Aprobó',
                     //           'value' => function ($model) {
                                   
                        
                                    
                       //             $aprobante = $model->solicitud->nombre_aprobante;
                                    
                                
                         //           return $aprobante;
                           //     },
                          //  ],
                    
               //             [
                 //               'label' => 'Se aprobó',
                   //             'value' => function ($model) {
                                   
                        
                                    
                     //               $aprobante = $model->solicitud->fecha_aprobacion;
                                    
                                
                       //             return $aprobante;
                         //       },
                           // ],
                    
                            
                            'turno',
                            [
                                'attribute' => 'horario_inicio',
                                'value' => function ($model) {
                                    $hora = date("g:i A", strtotime($model->horario_inicio));
                                    return $hora;
                                },
                            ],
                            [
                                'attribute' => 'horario_fin',
                                'value' => function ($model) {
                                    $hora = date("g:i A", strtotime($model->horario_fin));
                                    return $hora;
                                },
                            ],
                            [
                                'label' => 'Fecha de Inicio',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaInicio= strtotime($model->fecha_inicio);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Fecha de Termino',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaTermino= strtotime($model->fecha_termino);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaTermino);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                           
                        ],
                    ]) ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>
</div>
</div>