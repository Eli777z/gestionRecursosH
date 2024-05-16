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
?><div class="container-fluid">
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
                <?php  foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
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
                        [
                            'label' => 'Empleado',
                            'value' => function($model) {
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
                        'status',
                        'comentario:ntext',
                        'fecha_aprobacion',
                        'nombre_aprobante',
                        'nombre_formato',
                    ],
                ]) ?>

                <div>
                    <h4>Modificar Solicitud</h4>
                    <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
                    <div class="form-group mr-2 mb-2">
                        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-success', 'title' => 'Aceptar']) ?>
                    </div>
                    <div class="form-group mr-2 mb-2">
                        <?= Html::submitButton(Html::tag('i', '', ['class' => 'fas fa-times']), ['name' => 'status', 'value' => 'Rechazado', 'class' => 'btn btn-danger', 'title' => 'Rechazar']) ?>
                    </div>
                    <div class="form-group mr-2">
                        <?= Html::label('Nuevo Comentario:', null, ['class' => 'control-label']) ?>
                        <?= Html::textInput('comentario', $model->comentario, ['class' => 'form-control']) ?>
                    </div>
                    <?= Html::endForm() ?>
                </div>
            </div>
            <!--.col-md-12-->
        </div>
        <!--.row-->
    </div>
    <!--.card-body-->
</div>
<!--.card-->
</div>
