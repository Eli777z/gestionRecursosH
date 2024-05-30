<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno; 
use kartik\select2\Select2;
use app\models\Empleado;
use hail812\adminlte\widgets\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\CambioPeriodoVacacional */
/* @var $form yii\bootstrap4\ActiveForm */
$currentDate = date('Y-m-d');

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(); ?>
                <div class="card-header bg-primary text-white">
                    <h2>CREAR NUEVA SOLICITUD DE CAMBIO DE PERIODO VACANIONAL</h2>
                   
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
<div class="cambio-periodo-vacacional-form">

<div class="card">
                                            <div class="card-header bg-info text-white">Ingrese los siguientes datos</div>
                                            <div class="card-body">




    

    <?= $form->field($model, 'motivo')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'primera_vez')->dropDownList(
        ['Sí' => 'Sí', 'No' => 'No'],
        ['prompt' => 'Seleccionar opción']
    ) ?>
    <?= $form->field($model, 'año')->dropDownList(
    array_combine(range(date('Y'), 2000), range(date('Y'), 2000)), // Genera una lista de años desde el actual hasta 2000
    ['prompt' => 'Seleccionar Año']
) ?>



<?= $form->field($model, 'numero_periodo')->dropDownList(
        ['1ero' => '1ero', '2do' => '2do'],
        ['prompt' => 'Seleccionar opción']
    ) ?>
  
<?= $form->field($model, 'dateRange', [
        'options' => ['class' => 'form-group col-md-6'],
    ])->widget(DateRangePicker::classname(), [
        'convertFormat' => true,
        'pluginOptions' => [
            'locale' => [
                'format' => 'Y-m-d',
                'separator' => ' a ',
            ],
            'opens' => 'left',
            'singleDatePicker' => false,
            'showDropdowns' => true,
            'alwaysShowCalendars' => true,
            'minDate' => '2000-01-01',  // Ajusta según tu necesidad
            'maxDate' => '2100-12-31',  // Ajusta según tu necesidad
            'startDate' => $currentDate, // Fecha de inicio predeterminada
            'endDate' => $currentDate, // Fecha de fin predeterminada
            'autoApply' => true,
        ]
    ])->label('Rango de Fechas') ?>

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
