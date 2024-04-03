<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="trabajador-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_nacimiento')->widget(\yii\jui\DatePicker::className(), [
    'language' => 'es', // Asegúrate de establecer el idioma adecuado
    'dateFormat' => 'php:Y-m-d', // Formato de la fecha
    'options' => ['class' => 'form-control'], // Clase CSS para el estilo
    'clientOptions' => [
        
        'changeYear' => true, // Permite cambiar el año
        'changeMonth' => true, // Permite cambiar el mes
        'yearRange' => '-100:+0', // Rango de años disponibles
    ],
]) ?>
    

    <?= $form->field($model, 'codigo_postal')->textInput() ?>

    <?= $form->field($model, 'calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numero_casa')->textInput() ?>

    <?= $form->field($model, 'colonia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->fileInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'idusuario')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
