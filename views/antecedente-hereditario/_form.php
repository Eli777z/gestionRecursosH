<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AntecedenteHereditario */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="antecedente-hereditario-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat_antecedente_hereditario_id')->textInput() ?>

    <?= $form->field($model, 'abuelos')->textInput() ?>

    <?= $form->field($model, 'hermanos')->textInput() ?>

    <?= $form->field($model, 'padre')->textInput() ?>

    <?= $form->field($model, 'madre')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
