<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\AntecedentePatologico */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="antecedente-patologico-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'descripcion_antecedentes')->textarea(['rows' => 6]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
