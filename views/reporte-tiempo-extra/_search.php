<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ReporteTiempoExtraSearch */
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

    <?= $form->field($model, 'fecha') ?>

    <?= $form->field($model, 'horario_inicio') ?>

    <?php // echo $form->field($model, 'horario_fin') ?>

    <?php // echo $form->field($model, 'no_horas') ?>

    <?php // echo $form->field($model, 'actividad') ?>

    <?php // echo $form->field($model, 'total_horas') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('app', 'Reset'), ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    <!--.col-md-12-->
</div>
