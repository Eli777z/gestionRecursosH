<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno; 
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $model app\models\CambioDiaLaboral */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="cambio-dia-laboral-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($motivoFechaPermisoModel, 'fecha_permiso')->widget(DateRangePicker::classname(), [
    'convertFormat' => true,
    'pluginOptions' => [
        'singleDatePicker' => true,
        'showDropdowns' => true,
        'autoUpdateInput' => true,
        'locale' => [
            'format' => 'Y-m-d',
        ],
        'opens' => 'right',
    ],
    'options' => [
        'placeholder' => 'Selecciona una fecha...',
    ],
    'value' => date('Y-m-d'), // Establecer la fecha de hoy como valor inicial
])->label('Fecha de permiso') ?>
<?= $form->field($motivoFechaPermisoModel, 'motivo')->textarea(['rows' => 4]) ?>

  
<?= $form->field($model, 'fecha_a_laborar')->widget(DateRangePicker::classname(), [
    'convertFormat' => true,
    'pluginOptions' => [
        'singleDatePicker' => true,
        'showDropdowns' => true,
        'autoUpdateInput' => true,
        'locale' => [
            'format' => 'Y-m-d',
        ],
        'opens' => 'right',
    ],
    'options' => [
        'placeholder' => 'Selecciona una fecha...',
    ],
    'value' => date('Y-m-d'), // Establecer la fecha de hoy como valor inicial
])->label('Fecha a laborar') ?>

<?php 
$direccion = $model->empleado->informacionLaboral->catDireccion;

if ($direccion && in_array($direccion->nombre_direccion, ['2.- ADMINISTRACIÓN', '3.- COMERCIAL', '4.- OPERACIONES', '5.- PLANEACION'])) : ?>
    <?= $form->field($model, 'jefe_departamento_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(
            JuntaGobierno::find()
                ->where(['nivel_jerarquico' => 'Jefe de departamento'])
                ->andWhere(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
                ->all(),
            'id',
            function ($model) {
                return $model->profesion . ' ' . $model->empleado->nombre . ' ' . $model->empleado->apellido;
            }
        ),
        'options' => ['placeholder' => 'Seleccionar Jefe de Departamento'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Jefe de Departamento') ?>

    <?= $form->field($model, 'nombre_jefe_departamento')->hiddenInput()->label(false) ?>
<?php endif; ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
