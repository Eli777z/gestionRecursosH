<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\CatActividadFisica */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="cat-actividad-fisica-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre_actvidad')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
