<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ParametroFormato */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="parametro-formato-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'tipo_permiso')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'limite_anual')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Save'), ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
