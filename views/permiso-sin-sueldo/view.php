<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\Empleado;
use app\models\JuntaGobierno;
use hail812\adminlte\widgets\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\PermisoSinSueldo */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Permiso Sin Sueldos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>

<?php


$usuarioId = Yii::$app->user->identity->id;

$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

if ($empleado) {
    $model->empleado_id = $empleado->id;

    $juntaGobierno = JuntaGobierno::find()->where(['empleado_id' => $empleado->id])->one();

    if ($model->solicitud->status === 'Aprobado') {
        if ($juntaGobierno && ($juntaGobierno->nivel_jerarquico === 'Jefe de unidad' || $juntaGobierno->nivel_jerarquico === 'Director')){
            echo Html::a('Exportar Excel', ['export-segundo-caso', 'id' => $model->id], ['class' => 'btn btn-success']);
            echo Html::a('Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger']);
        } else {
            echo Html::a('Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-primary']);
            echo Html::a('Exportar PDF', ['export-pdf', 'id' => $model->id], ['class' => 'btn btn-danger']);
        }
    }
} else {
    Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
    return $this->redirect(['index']);
}
?>
                    </p>

                    <?php 
    foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
        echo Alert::widget([
            'options' => ['class' => 'alert-' . $type],
            'body' => $message,
        ]);
    }
    ?>
                    </p>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            [
                                'label' => 'Motivo',
                                'value' => function ($model) {
                                    return $model->motivoFechaPermiso->motivo; 
                                },
                            ],
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($model->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
          //                  [
            //                    'label' => 'Status',
              //                  'value' => function ($model) {
                                   
                        
                                    
                //                    $status = $model->solicitud->status;
                                    
                                
                  //                  return $status;
                    //            },
                      //      ],
                    
                    
                     //       [
                       //         'label' => 'Aprobó',
                         //       'value' => function ($model) {
                                   
                        
                                    
                           //         $aprobante = $model->solicitud->nombre_aprobante;
                                    
                                
                             //       return $aprobante;
                               // },
                        //    ],
                    
        //                    [
          //                      'label' => 'Se aprobó',
            //                    'value' => function ($model) {
                                   
                        
                                    
              //                      $aprobante = $model->solicitud->fecha_aprobacion;
                                    
                                
                //                    return $aprobante;
                  //              },
                    //        ],
                    
                            [
                                'label' => 'Comentario',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->comentario;
                                    
                                
                                    return $aprobante;
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