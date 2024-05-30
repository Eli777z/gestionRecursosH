<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CambioPeriodoVacacional */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cambio Periodo Vacacionals', 'url' => ['index']];
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
                    </p>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                           // 'id',
                            //'empleado_id',
                            //'solicitud_id',
                            'motivo:ntext',
                            'año',
                            'primera_vez',
                           // 'nombre_jefe_departamento',
                            'numero_periodo',
                          //  'fecha_inicio_periodo',
                            [
                                'label' => 'Fecha de Inicio',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaInicio= strtotime($model->fecha_inicio_periodo);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Fecha de Fin',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaInicio= strtotime($model->fecha_fin_periodo);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            //'fecha_fin_periodo',
                            [
                                'label' => 'Status',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $status = $model->solicitud->status;
                                    
                                
                                    return $status;
                                },
                            ],
                    
                    
                            [
                                'label' => 'Aprobó',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->nombre_aprobante;
                                    
                                
                                    return $aprobante;
                                },
                            ],
                    
                            [
                                'label' => 'Se aprobó',
                                'value' => function ($model) {
                                   
                        
                                    
                                    $aprobante = $model->solicitud->fecha_aprobacion;
                                    
                                
                                    return $aprobante;
                                },
                            ],
                    
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