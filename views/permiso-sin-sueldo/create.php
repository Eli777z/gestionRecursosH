<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoSinSueldo */

$this->title = 'Create Permiso Sin Sueldo';
$this->params['breadcrumbs'][] = ['label' => 'Permiso Sin Sueldos', 'url' => ['index']];
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
                         'noPermisoAnterior' => $noPermisoAnterior, // Asegúrate de pasar $noPermisoAnterior a la vista
                         'fechaPermisoAnterior' => $fechaPermisoAnterior, // Asegúrate de pasar $fechaPermisoAnterior a la vista
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>