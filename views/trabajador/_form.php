<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
           
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
<div class="trabajador-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

<?= $form->field($user, 'rol')->dropDownList([
    1 => 'Trabajador',
    2 => 'Gestor de recursos humanos',
]) ?>

<?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>
<?= $form->field($model, 'email')->textInput() ?>

<?= $form->field($infolaboral, 'numero_trabajador')->textInput() ?>
<?= $form->field($infolaboral, 'fecha_inicio')->widget(\yii\jui\DatePicker::className(), [
    'language' => 'es',
    'dateFormat' => 'php:Y-m-d',
    'options' => ['class' => 'form-control'],
    'clientOptions' => [
        'changeYear' => true,
        'changeMonth' => true,
        'yearRange' => '-100:+0',
    ],
]) ?>



<?= $form->field($infolaboral, 'iddepartamento')->dropDownList(
    ArrayHelper::map(Departamento::find()->all(), 'id', 'nombre'), 
    ['prompt' => 'Seleccione departamento']
) ?>

<?= $form->field($model, 'telefono')->textInput([
    'maxlength' => true,
    'pattern' => '\d*',
    'inputmode' => 'numeric',
]) ?>

<?= $form->field($model, 'foto')->widget(FileInput::classname(), [
    'options' => ['accept' => 'file/*'],
    'pluginOptions' => [
        'showUpload' => false,
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
