<?php

use app\models\CatTipoDocumento;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Documento */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="documento-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>


  

    <?= $form->field($cat_tipo_documento, 'id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(CatTipoDocumento::find()->all(), 'id', 'nombre_tipo'),
    'language' => 'es', 
    'options' => ['placeholder' => 'Seleccione el tipo de documento', 'id' => 'tipo-documento'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]) ?>

<?= $form->field($model, 'nombre')->textInput([
    'maxlength' => true, 
    'id' => 'nombre-archivo', 
    'style' => 'display:none',
    'placeholder' => 'Ingrese el nombre del documento'
])->label(false) ?>

<?php
$this->registerJs("
    $('#tipo-documento').change(function(){
        var tipoDocumentoId = $(this).val();
        var nombreArchivoInput = $('#nombre-archivo');

        // Obtener el nombre del tipo de documento seleccionado
        var tipoDocumentoNombre = $('#tipo-documento option:selected').text();

        // Verificar si se seleccionó 'OTRO'
        if (tipoDocumentoNombre == 'OTRO') {
            // Mostrar el campo de nombre y limpiar su valor
            nombreArchivoInput.show().val('').focus();
        } else {
            // Ocultar el campo de nombre y asignar el nombre del tipo de documento seleccionado
            nombreArchivoInput.hide().val(tipoDocumentoNombre);
        }
    });
");
?>






    <?= $form->field($model, 'ruta')->widget(FileInput::classname(), [
        'options' => ['accept' => 'file/*'],
        'pluginEvents' => [
            
            'fileclear' => "function() {
                $('#nombre-archivo').val('');
                $('#tipo-archivo').val('');
            }",
        ],
        'pluginOptions' => [
            'showUpload' => false,
        ],
    ]) ?>
 

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
  
</script>
</div>
