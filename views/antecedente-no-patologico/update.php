<?php

/* @var $this yii\web\View */
/* @var $model app\models\AntecedenteNoPatologico */

$this->title = 'Update Antecedente No Patologico: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Antecedente No Patologicos', 'url' => ['index']];
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