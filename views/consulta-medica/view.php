<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\ConsultaMedica */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Consultas medicas', 'url' => ['consulta-medica/index']];

if ($empleadoId !== null) {
   
    $this->params['breadcrumbs'][] = ['label' => 'Empleado ' . $empleadoId, 'url' => ['empleado/view', 'id' => $empleadoId]];
}
$this->params['breadcrumbs'][] = ['label' => 'Consulta Medica ' . $model->id];
\yii\web\YiiAsset::register($this);
?>


<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
    <div class="card">
    <div class="card-header bg-info text-white">
                    <h2>PERMISO FUERA TRABAJO</h2>
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
                

                          
                
                </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                   

                    <?php 
                    //ALERTA
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
                           // 'id',
                            //'cita_medica_id',
                           // 'motivo:ntext',

                            [
                                'attribute' => 'motivo',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->motivo);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],
                            [
                                'attribute' => 'sintomas',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->sintomas);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],

                            [
                                'attribute' => 'diagnostico',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->diagnostico);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],
                            [
                                'attribute' => 'tratamiento',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->tratamiento);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],
                           
                            
                            'presion_arterial_minimo',
                            'presion_arterial_maximo',
                            'temperatura_corporal',
                            'nivel_glucosa',
                            'oxigeno_sangre',
                            //'medico_atendio',
                            'frecuencia_cardiaca',
                            'frecuencia_respiratoria',
                            'estatura',
                            'peso',
                            'imc',
                            [
                                'attribute' => 'aspecto_fisico',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->aspecto_fisico);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],
                            
                          //  'expediente_medico_id',
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