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
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true, 'id' => 'nombre-archivo'])->label(false) ?>

    <?= $form->field($model, 'ruta')->widget(FileInput::classname(), [
    'options' => ['accept' => 'file/*'],
    'pluginEvents' => [
        'fileclear' => "function() {
            $('#nombre-archivo').val('');
            $('#tipo-archivo').val('');
        }",
    ],
]) ?>

    <?= $form->field($model, 'tipo')->textInput(['maxlength' => true, 'id' => 'tipo-archivo']) ?>
    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success', 'id' => 'btn-agregar-expediente']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

<script>
$(document).ready(function() {
    // Cuando se arrastra y suelta un archivo en el área del widget de entrada de archivo
    $('#expediente-ruta').on('filebatchselected', function(event, files) {
        // Se asume que solo se selecciona un archivo a la vez
        var file = files[0];
        
        // Obtener el nombre del archivo sin la extensión
        var fileName = file.name.replace(/\.[^/.]+$/, "");
        $('#nombre-archivo').val(fileName);

        // Obtener la extensión del archivo
        var fileExt = file.name.split('.').pop();
        $('#tipo-archivo').val(fileExt);
    });
});


</script>

</div>