<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
/* @var $this yii\web\View */
/* @var $model app\models\Solicitud */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Solicituds', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container-fluid">
    <div class="row justify-content-center"> <!-- Centra el contenido horizontalmente -->
        <div class="col-md-8"> <!-- Div principal con ancho personalizado -->
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <div class="d-flex justify-content-between"> 
                        
                        <div class="form-group mr-2">
                            <?= Html::label('Añadir Comentarios:', null, ['class' => 'control-label']) ?>
                            <?= Html::textInput('comentario', $model->comentario, ['class' => 'form-control']) ?>
                        </div>
                        <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
                        <div class="form-group mr-2 mb-2">
                            <?= Html::submitButton(Html::tag('i', '  Aprobar', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-success', 'title' => 'Aceptar']) ?>
                        </div>
                        <div class="form-group mr-2 mb-2">
                            <?= Html::submitButton(Html::tag('i', '  Rechazar', ['class' => 'fas fa-times']), ['name' => 'status', 'value' => 'Rechazado', 'class' => 'btn btn-danger', 'title' => 'Rechazar']) ?>
                        </div>
                        
                        <?= Html::endForm() ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                echo Alert::widget([
                                    'options' => ['class' => 'alert-' . $type],
                                    'body' => $message,
                                ]);
                            } ?>
                            <?= DetailView::widget([
                                'model' => $model,
                                'attributes' => [
                                    [
                                        'label' => 'Número de empleado',
                                        'value' => function ($model) {
                                            return $model->empleado->numero_empleado;
                                        },
                                    ],
                                    [
                                        'label' => 'Nombre',
                                        'value' => function ($model) {
                                            return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                                        },
                                    ],
                                    [
                                        'attribute' => 'fecha_creacion',
                                        'format' => 'raw',
                                        'value' => function ($model) {
                                            setlocale(LC_TIME, "es_419");
                                            return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                                        },
                                    ],

                                    [
                                        'label' => 'Aprobó',
                                        'value' => function ($model) {
                                            return $model->nombre_aprobante;
                                        },
                                    ],
                                    // 'nombre_aprobante',
                                    [
                                        'label' => 'Tipo de solicitud',
                                        'value' => function ($model) {
                                            return $model->nombre_formato;
                                        },
                                    ],
                                    'status',
                                    'comentario:ntext',
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
        <!--.col-md-6-->
    </div>
    <!--.row justify-content-center-->
</div>
<!--.container-fluid-->
