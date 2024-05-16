<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatDireccion;
use kartik\select2\Select2;
use app\models\CatDepartamento;
use app\models\Empleado;
/* @var $this yii\web\View */
/* @var $model app\models\JuntaGobierno */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="junta-gobierno-form">
    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'cat_direccion_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona una dirección...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($model, 'cat_departamento_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona un departamento...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

<?= $form->field($model, 'empleado_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Empleado::find()->all(), 'id', function($model) {
        return $model->nombre . ' ' . $model->apellido;
    }),
    'options' => ['placeholder' => 'Selecciona un empleado...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>


<?= $form->field($model, 'nivel_jerarquico')->dropDownList([
    'Director' => 'Director',
    'Jefe de unidad' => 'Jefe de unidad',
], ['prompt' => 'Selecciona el nivel jerárquico...']) ?>


<?= $form->field($model, 'profesion')->dropDownList([
    'ING.' => 'ING.',
    'LIC.' => 'LIC.',
    'PROF.' => 'PROF.',
    'ARQ.' => 'ARQ.',
    'C.' => 'C.',
    'DR.' => 'DR.',
    'DRA.' => 'DRA.',
    'TEC.' => 'TEC.',
], ['prompt' => 'Selecciona el nivel académico...']) ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>

