<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ExpedienteMedico */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="expediente-medico-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'empleado_id')->textInput() ?>

    <?= $form->field($model, 'consulta_medica_id')->textInput() ?>

    <?= $form->field($model, 'documento_id')->textInput() ?>

    <?= $form->field($model, 'antecedente_hereditario_id')->textInput() ?>

    <?= $form->field($model, 'antecedente_patologico_id')->textInput() ?>

    <?= $form->field($model, 'antecedente_no_patologico_id')->textInput() ?>

    <?= $form->field($model, 'medicacion_necesitada')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'alergias')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'no_seguridad_social')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
