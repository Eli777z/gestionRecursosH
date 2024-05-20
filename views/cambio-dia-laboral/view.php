<?php

use hail812\adminlte\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CambioDiaLaboral */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cambio Dia Laborals', 'url' => ['index']];
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
                                            <?php if ($model->solicitud->status === 'Aprobado'): ?>
                            <?= Html::a('Exportar Excel', ['export', 'id' => $model->id], ['class' => 'btn btn-success']) ?>
                        <?php endif; ?>
                          
                    </p>

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
                            'id',
                            'empleado_id',
                            'solicitud_id',
                            [
                                'label' => 'Motivo',
                                'value' => function ($model) {
                                    return $model->motivoFechaPermiso->motivo; // Reemplaza "nombre_del_atributo_del_motivo" con el nombre del atributo que deseas mostrar
                                },
                            ],
                            [
                                'attribute' => 'fecha_a_laborar',
                                'value' => function ($model) {
                                    setlocale(LC_TIME, 'es_419.UTF-8');
                                    $fechaAlaborar = strtotime($model->fecha_a_laborar);
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaAlaborar);
                                    setlocale(LC_TIME, null);
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419");
                                    
                                    $fechaPermiso = strtotime($model->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
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