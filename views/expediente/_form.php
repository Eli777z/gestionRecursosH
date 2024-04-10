<?php
use kartik\file\FileInput;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\widgets\Pjax;;
/* @var $this yii\web\View */
/* @var $model app\models\Expediente */
/* @var $form yii\bootstrap4\ActiveForm */
?>


<div class="container-fluid">
   
<div class="expediente-form">

    <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

    <!-- Campo oculto para el nombre del archivo -->
    <?= $form->field($model, 'nombre')->hiddenInput(['maxlength' => true, 'id' => 'nombre-archivo'])->label(false) ?>

    <?= $form->field($model, 'ruta')->widget(FileInput::classname(), ['options' => ['accept' => 'file/*'],]) ?>
    <?= $form->field($model, 'tipo')->hiddenInput(['maxlength' => true, 'id' => 'tipo-archivo'])-> label(false) ?>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btn-agregar-expediente']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
$(document).ready(function() {
    // Cuando se selecciona un archivo, establece el nombre del archivo y la extensión en los campos correspondientes
    $('#expediente-ruta').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $('#nombre-archivo').val(fileName);
        // Obtener la extensión del archivo
        var fileExt = fileName.split('.').pop();
        $('#tipo-archivo').val(fileExt);
    });
});
</script>
</div>