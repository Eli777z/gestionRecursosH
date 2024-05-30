<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CambioPeriodoVacacionalSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="row mt-2">
    <div class="col-md-12">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
        'options' => [
            'data-pjax' => 1
        ],
    ]); ?>

    <?= $form->field($model, 'id') ?>

    <?= $form->field($model, 'empleado_id') ?>

    <?= $form->field($model, 'solicitud_id') ?>

    <?= $form->field($model, 'motivo') ?>

    <?= $form->field($model, 'primera_vez') ?>

    <?php // echo $form->field($model, 'nombre_jefe_departamento') ?>

    <?php // echo $form->field($model, 'numero_periodo') ?>

    <?php // echo $form->field($model, 'fecha_inicio_periodo') ?>

    <?php // echo $form->field($model, 'fecha_fin_periodo') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    <!--.col-md-12-->
</div>
