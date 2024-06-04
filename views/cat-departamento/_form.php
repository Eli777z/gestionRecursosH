<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatDepartamento */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="cat-departamento-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_departamento')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'cat_direccion_id')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
