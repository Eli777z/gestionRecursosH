<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatPuesto */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="cat-puesto-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'nombre_puesto')->textInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
