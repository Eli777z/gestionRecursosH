<?php

/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = 'Update Cambio Horario Trabajo: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cambio Horario Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>