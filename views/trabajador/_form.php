<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="trabajador-form">

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
<?= $form->field($user, 'rol')->dropDownList([
        1 => 'Trabajador',
        2 => 'Gestor de recursos humanos',
    ]) ?>
    <?= $form->field($model, 'nombre')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'apellido')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'fecha_nacimiento')->widget(\yii\jui\DatePicker::className(), [
        'language' => 'es',
        'dateFormat' => 'php:Y-m-d',
        'options' => ['class' => 'form-control'],
        'clientOptions' => [
            'changeYear' => true,
            'changeMonth' => true,
            'yearRange' => '-100:+0',
        ],
    ]) ?>

    <?= $form->field($model, 'codigo_postal')->textInput() ?>

    <?= $form->field($model, 'calle')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'numero_casa')->textInput() ?>

    <?= $form->field($model, 'telefono')->textInput() ?>

    <?= $form->field($model, 'colonia')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'foto')->fileInput(['class'=>'form-control']) ?>

   

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
