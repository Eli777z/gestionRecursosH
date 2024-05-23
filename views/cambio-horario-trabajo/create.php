<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = 'Create Cambio Horario Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Cambio Horario Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                      'model' => $model,
                      'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                      'solicitudModel' => $solicitudModel,
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>