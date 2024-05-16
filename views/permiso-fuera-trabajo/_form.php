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


    <?= $form->field($model, 'hora_salida')->widget(TimePicker::classname(), [
    'pluginOptions' => [
        'showMeridian' => true, // Habilitar formato de 12 horas con AM/PM
        'minuteStep' => 1,
        'defaultTime' => false,
        // Otros ajustes según tus necesidades
    ]
    
])->label('Hora de salida') ?>


<?= $form->field($model, 'hora_regreso')->widget(TimePicker::classname(), [
    'pluginOptions' => [
        'showMeridian' => true, // Habilitar formato de 12 horas con AM/PM
        'minuteStep' => 1,
        'defaultTime' => false,
        // Otros ajustes según tus necesidades
    ]
    
])->label('Hora de regreso') ?>




<?= $form->field($model, 'fecha_a_reponer')->widget(DateRangePicker::classname(), [
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
    ])->label('Fecha a reponer') ?>
   
   
   <?= $form->field($model, 'horario_fecha_a_reponer')->widget(TimePicker::classname(), [
    'pluginOptions' => [
        'showMeridian' => true, // Habilitar formato de 12 horas con AM/PM
        'minuteStep' => 1,
        'defaultTime' => false,
        // Otros ajustes según tus necesidades
    ]
    
])->label('Horario de fecha a reponer') ?>


   

    <div class="form-group">
        <?= Html::submitButton('Solicitar autorización', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
