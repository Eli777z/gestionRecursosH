<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ConsultaMedicaSearch */
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

    <?= $form->field($model, 'cita_medica_id') ?>

    <?= $form->field($model, 'motivo') ?>

    <?= $form->field($model, 'sintomas') ?>

    <?= $form->field($model, 'diagnostico') ?>

    <?php // echo $form->field($model, 'tratamiento') ?>

    <?php // echo $form->field($model, 'presion_arterial_minimo') ?>

    <?php // echo $form->field($model, 'presion_arterial_maximo') ?>

    <?php // echo $form->field($model, 'temperatura_corporal') ?>

    <?php // echo $form->field($model, 'aspecto_fisico') ?>

    <?php // echo $form->field($model, 'nivel_glucosa') ?>

    <?php // echo $form->field($model, 'oxigeno_sangre') ?>

    <?php // echo $form->field($model, 'medico_atendio') ?>

    <?php // echo $form->field($model, 'frecuencia_cardiaca') ?>

    <?php // echo $form->field($model, 'frecuencia_respiratoria') ?>

    <?php // echo $form->field($model, 'estatura') ?>

    <?php // echo $form->field($model, 'peso') ?>

    <?php // echo $form->field($model, 'imc') ?>

    <?php // echo $form->field($model, 'expediente_medico_id') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-outline-secondary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
    <!--.col-md-12-->
</div>
