<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Empleado;
use app\models\JuntaGobierno;
use hail812\adminlte\widgets\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = $model->id;

\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">

    <div class="card-header bg-info text-white">
                    <h2>PERMISO FUERA TRABAJO</h2>
                    <?php if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) { ?>

<?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }


else{ ?>
<?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['site/portalempleado'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }?>

<?php


$usuarioId = Yii::$app->user->identity->id;

$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    $model->empleado_id = $empleado->id;

    $juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $empleado->id])->one();

        if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')){
            echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success']);
            echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
        } else {
            echo Html::a('<i class="fa fa-file-excel" aria-hidden="true"></i> Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-success']);
            echo Html::a('<i class="fa fa-file-pdf"></i> Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger ml-3']);
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
                                'value' => function ($model) {
                                    return $model->motivoFechaPermiso->motivo; 
                                },
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