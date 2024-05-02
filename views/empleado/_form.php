<?php

use app\models\CatDepartamento;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use kartik\select2\Select2;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
           
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
<div class="empleado-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($usuario, 'rol')->dropDownList([
    1 => 'Trabajador',
    2 => 'Gestor de recursos humanos',
]) ?>

<?= $form->field($model, 'numero_empleado')->textInput() ?>




<?= $form->field($informacion_laboral, 'cat_departamento_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
    'language' => 'es', // Ajusta el idioma según tus necesidades
    'options' => ['placeholder' => 'Seleccione departamento'],
    'pluginOptions' => [
        'allowClear' => true // Esto permite borrar la selección actual
    ],
]) ?>


  
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

  

   
    

    <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
    'options' => ['accept' => 'file/*'],
    'pluginOptions' => [
        'showUpload' => false,
    ],
]) ?>

    <?= $form->field($model, 'telefono')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

  
    <?= $form->field($informacion_laboral, 'fecha_ingreso')->widget(\yii\jui\DatePicker::className(), [
    'language' => 'es',
    'dateFormat' => 'php:Y-m-d',
    'options' => ['class' => 'form-control'],
    'clientOptions' => [
        'changeYear' => true,
        'changeMonth' => true,
        'yearRange' => '-100:+0',
    ],
]) ?>


    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    </div>
 </div>
                        <!--.col-md-12-->
                        
                    </div>
                    <!--.row-->
                    
                </div>
                <!--.card-body-->
                
          
            <!--.card-->
         
        </div>
        <!--.col-md-10-->
    </div>
    <!--.row-->
</div>
<!--.container-fluid-->
