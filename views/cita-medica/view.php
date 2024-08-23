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
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
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
} else if (Yii::$app->user->can('gestor-rh')) {
    // Redirige al historial del navegador
    echo Html::a('<i class="fa fa-chevron-left"></i> Volver', 'javascript:void(0);', [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
        'onclick' => 'window.history.back(); return false;',
    ]);
}

 else {
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
                'solicitud_id',
                // otros atributos...
                [
                   // 'class' => 'yii\grid\DataColumn',
                   'label' => 'Estatus de la aprobación',
                    'attribute' => 'aprobacion',
                    
                    'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                        return $model->solicitud->aprobacion;
                        
                    },
                    'contentOptions' => ['class' => 'text-center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],
                [
                    'label' => 'Aprobado en',
                    'attribute' => 'fecha_aprobacion',
                    'value' => function ($model) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($model->solicitud->fecha_aprobacion);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],

                [
                    'label' => 'Aprobante',
                    'attribute' => 'nombre_aprobante',
                    'value' => function ($model) {
                        return $model->solicitud->nombre_aprobante;
                    },
                ],
                [
                    'label' => 'Comentario',
                    'attribute' => 'comentario',
                    'value' => function ($model) {
                        return $model->solicitud->comentario;
                    },
                ],
            ],
        ]) ?>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                           // 'id',

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