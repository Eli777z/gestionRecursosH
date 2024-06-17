<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AntecedenteNoPatologico */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="antecedente-no-patologico-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat_actividad_fisica_id')->textInput() ?>

    <?= $form->field($model, 'tipo_sangre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'tabaquismo')->textInput() ?>

    <?= $form->field($model, 'alcoholismo')->textInput() ?>

    <?= $form->field($model, 'drogas')->textInput() ?>

    <?= $form->field($model, 'actividad_fisica')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
