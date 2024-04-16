<?php

/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */

$this->title = 'Update Trabajador: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'idusuario' => $model->idusuario]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                         'model' => $model,
                         'user' => $user, // Pasar el modelo Usuario a la vista
                         'infolaboral' => $infolaboral,
                         'departamento' => $departamento,
                     ///    'departamentoDropdownValue' => $departamentoDropdownValue,

                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>