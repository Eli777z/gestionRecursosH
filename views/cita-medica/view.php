<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap4\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\CitaMedica */

$this->title = $model->id;

\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
<div class="row justify-content-center">
        <div class="col-md-10">
    <div class="card">
    <div class="card-header bg-info text-white">
                    <h2>CITA MEDICA</h2>
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



                          
                
                </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    
                    <div class="d-flex align-items-center mb-3">
                                <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>
                            </div>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            //'empleado_id',
                          //  'solicitud_id',
                            //'fecha_para_cita',
                            [
                                'label' => 'Fecha de la cita',
                                'attribute' => 'fecha_para_cita',
                                'value' => function ($model) {
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    $fechaAreponer = strtotime($model->fecha_para_cita);
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaAreponer);
                                    setlocale(LC_TIME, null);
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Motivo',
                                'attribute' => 'comentario',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->comentario);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 65%;'],
                            ],
                            //'horario_inicio',
                            [
                                'label' => 'Hora de inicio',
                                'attribute' => 'horario_inicio',
                                'value' => function ($model) {
                                    $hora = date("g:i A", strtotime($model->horario_inicio));
                                    return $hora;
                                },
                            ],
                           // 'horario_finalizacion',
                            [
                                'label' => 'Hora de finalización',
                                'attribute' => 'horario_finalizacion',
                                'value' => function ($model) {
                                    $hora = date("g:i A", strtotime($model->horario_finalizacion));
                                    return $hora;
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