<?php

/* @var $this yii\web\View */
/* @var $model app\models\JuntaGobierno */

$this->title = 'Update Junta Gobierno: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Junta Gobiernos', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id, 'cat_direccion_id' => $model->cat_direccion_id, 'cat_departamento_id' => $model->cat_departamento_id, 'empleado_id' => $model->empleado_id]];
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