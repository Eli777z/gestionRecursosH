<?php

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

$this->title = 'Update Empleado: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'usuario_id' => $model->usuario_id, 'informacion_laboral_id' => $model->informacion_laboral_id]];
$this->params['breadcrumbs'][] = 'Update';
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                <?=$this->render('_form', [
                         'model' => $model,
                         'usuario' => $usuario, 
                         'informacion_laboral' => $informacion_laboral,
                        // 'departamento' => $departamento,
                     ///    'departamentoDropdownValue' => $departamentoDropdownValue,

                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>