<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use kartik\time\TimePicker;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno; 
use kartik\select2\Select2;
use app\models\Empleado;
use hail812\adminlte\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(); ?>
                <div class="card-header bg-primary text-white">
                    <h2>CREAR NUEVA SOLICITUD DE PERMISO FUERA DEL TRABAJO</h2>
                   
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                // Mostrar los flash messages
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

<div class="permiso-fuera-trabajo-form">

                                        <div class="card">
                                            <div class="card-header bg-info text-white">Ingrese los siguientes datos</div>
                                            <div class="card-body">


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
    ]
])->label('Hora de salida') ?>

<?= $form->field($model, 'hora_regreso')->widget(TimePicker::classname(), [
    'pluginOptions' => [
        'showMeridian' => true, // Habilitar formato de 12 horas con AM/PM
        'minuteStep' => 1,
        'defaultTime' => false,
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
    ]
])->label('Horario de fecha a reponer') ?>
<?php



// Obtener el ID del usuario que tiene la sesión iniciada
$usuarioId = Yii::$app->user->identity->id;

// Buscar el empleado relacionado con el usuario
$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

$mostrarCampo = true;

if ($empleado) {
    // Buscar el registro en junta_gobierno que corresponde al empleado
    $juntaGobierno = JuntaGobierno::find()
        ->where(['empleado_id' => $empleado->id])
        ->andWhere(['nivel_jerarquico' => ['Director', 'Jefe de unidad']])
        ->one();

    if ($juntaGobierno) {
        // Si se encuentra un registro con nivel jerárquico "Director" o "Jefe de unidad", no mostrar el campo
        $mostrarCampo = false;
    }
}

$direccion = $model->empleado->informacionLaboral->catDireccion;

if ($mostrarCampo && $direccion && in_array($direccion->nombre_direccion, ['2.- ADMINISTRACIÓN', '3.- COMERCIAL', '4.- OPERACIONES', '5.- PLANEACION'])) :
    ?>
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
<?= Html::submitButton('Solicitar  autorización <i class="fa fa-paper-plane fa-md"></i>', [
                        'class' => 'btn btn-success btn-lg float-right', 
                        'id' => 'save-button-personal'
                    ]) ?>
</div>
                                        </div>
                                   


<?php ActiveForm::end(); ?>


                            </div>
                        </div>
                    </div>
                </div>

             
            </div>
        </div>
    </div>
</div>
