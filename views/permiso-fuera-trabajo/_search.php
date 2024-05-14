<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajoSearch */
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

    <?= $form->field($model, 'motivo_fecha_permiso_id') ?>

    <?= $form->field($model, 'fecha_salida') ?>

    <?php // echo $form->field($model, 'fecha_regreso') ?>

    <?php // echo $form->field($model, 'fecha_a_reponer') ?>

    <?php // echo $form->field($model, 'horario_fecha_a_reponer') ?>

    <?php // echo $form->field($model, 'nota') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    <!--.col-md-12-->
</div>
