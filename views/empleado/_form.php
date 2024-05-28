<?php

use app\models\CatDepartamento;
use app\models\CatDireccion;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use kartik\select2\Select2;
use hail812\adminlte\widgets\Alert;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

            <div class="card-header bg-primary text-white">
                                    <h2>AÑADIR NUEVO EMPLEADO</h2>
                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-primary float-right mr-3', 'id' => 'save-button-personal']) ?>

                                </div>
                                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                        <div class="d-flex align-items-center mb-3">




<?php
// Mostrar los flash messages



// En tu vista donde deseas mostrar los mensajes de flash:
foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
    if ($type === 'error') {
        // Muestra los mensajes de error en rojo
        echo Alert::widget([
            'options' => ['class' => 'alert-danger'],
            'body' => $message,
        ]);
    } else {
        // Muestra los demás mensajes de flash con estilos predeterminados
        echo Alert::widget([
            'options' => ['class' => 'alert-' . $type],
            'body' => $message,
        ]);
    }
}
?>
</div>
<div class="empleado-form">


<?= $form->field($usuario, 'rol')->dropDownList([
    1 => 'Trabajador',
    2 => 'Gestor de recursos humanos',
]) ?>

<?= $form->field($model, 'numero_empleado')->textInput() ?>




<?= $form->field($informacion_laboral, 'cat_departamento_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
    'language' => 'es', 
    'options' => ['placeholder' => 'Seleccione departamento'],
    'pluginOptions' => [
        'allowClear' => true 
    ],
    'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                                        ],
]) ?>


<?= $form->field($informacion_laboral, 'cat_direccion_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'),
    'language' => 'es', 
    'options' => ['placeholder' => 'Seleccione la dirección'],
    'pluginOptions' => [
        'allowClear' => true 
    ],
    'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
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

  

    <?= $form->field($informacion_laboral, 'fecha_ingreso')->input('date') ?>


  

    <?php ActiveForm::end(); ?>

    </div>
 </div>
                    <!--.row-->



                </div>
                <!--.card-body-->


            </div>
            <!--.card-->


        </div>
        <!--.col-md-10-->

    </div>
    <!--.row-->
</div>
<!--.container-fluid-->
</div>
