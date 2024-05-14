<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use kartik\time\TimePicker;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="permiso-fuera-trabajo-form">

    <?php $form = ActiveForm::begin(); ?>

    

    

    <?= $form->field($motivoFechaPermisoModel, 'fecha_permiso')->widget(DateRangePicker::classname(), [
        'model' => $model,
        'attribute' => 'fecha_permiso',
        'convertFormat' => true,
        'value' => date('Y-m-d'), // Establecer la fecha de hoy como valor inicial
        'pluginOptions' => [
            'singleDatePicker' => true,
            'showDropdowns' => true,
            'autoUpdateInput' => true, // Mantener el valor actualizado automáticamente
            'locale' => [
                'format' => 'Y-m-d',
            ],
            'opens' => 'right',
        ],
        'options' => [
            'placeholder' => 'Selecciona una fecha...',
        ],
    ])->label('Fecha de permiso') ?>
    
    <?= $form->field($motivoFechaPermisoModel, 'motivo')->textarea(['rows' => 4]) ?>


    <?= $form->field($model, 'fecha_rango')->widget(DateRangePicker::classname(), [
    'model' => $model,
    'attribute' => 'fecha_rango',
    'convertFormat' => true,
    'pluginOptions' => [
        'timePicker' => false, // Oculta la selección de la hora
        'locale' => [
            'format' => 'Y-m-d', // Formato para incluir solo la fecha
            'separator' => ' a ',
        ],
        'opens' => 'right',
        'autoUpdateInput' => true,
        'autoApply' => true,
    ],
    'options' => [
        'placeholder' => 'Selecciona el rango de fechas...',
    ],
])->label('Fecha de Salida y Regreso') ?>



<?= $form->field($model, 'fecha_a_reponer')->widget(DateRangePicker::classname(), [
        'model' => $model,
        'attribute' => 'fecha_a_reponer',
        'convertFormat' => true,
        'value' => date('Y-m-d'), // Establecer la fecha de hoy como valor inicial
        'pluginOptions' => [
            'singleDatePicker' => true,
            'showDropdowns' => true,
            'autoUpdateInput' => true, // Mantener el valor actualizado automáticamente
            'locale' => [
                'format' => 'Y-m-d',
            ],
            'opens' => 'right',
        ],
        'options' => [
            'placeholder' => 'Selecciona una fecha...',
        ],
    ])->label('Fecha a reponer') ?>
    <?= $form->field($model, 'horario_fecha_a_reponer')->widget(TimePicker::classname(), [
    'pluginOptions' => [
        'showMeridian' => true, // Habilitar formato de 12 horas con AM/PM
        'minuteStep' => 1,
        // Otros ajustes según tus necesidades
    ]
    
])->label('Horario de fecha a reponer') ?>


    <?= $form->field($model, 'nota')->textarea(['rows' => 4]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
