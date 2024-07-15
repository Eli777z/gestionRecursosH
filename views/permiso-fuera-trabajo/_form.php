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
                <div class="card-header bg-info text-white">
                    <h2>PERMISO FUERA DEL TRABAJO</h2>
                   
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>
                            </div>

<div class="permiso-fuera-trabajo-form">

                                        <div class="card bg-light">
                                           
                                            <div class="card-body">
                                                <div class="row">


                                            <div class="col-6 col-sm-2">

<?= $form->field($motivoFechaPermisoModel, 'fecha_permiso')->input('date')->label('Fecha de permiso') ?>
                                            </div>
<?= $form->field($motivoFechaPermisoModel, 'motivo')->textarea(['rows' => 4]) ?>
<div class="col-6 col-sm-2">

<?= $form->field($model, 'hora_salida')->input('time')->label('Hora de salida') ?>
</div>
<div class="col-6 col-sm-2">

<?= $form->field($model, 'hora_regreso')->input('time')->label('Hora de regreso') ?>
</div>
<div class="w-100"></div>
<div class="col-6 col-sm-2">



<?= $form->field($model, 'fecha_a_reponer')->input('date')->label('Fecha a reponer') ?>
</div>
<div class="col-6 col-sm-3">


<?= $form->field($model, 'horario_fecha_a_reponer')->input('time')->label('Horario de fecha a reponer') ?>
</div>
<?php




$usuarioId = Yii::$app->user->identity->id;

$empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

$mostrarCampo = true;

if ($empleado) {
    $juntaGobierno = JuntaGobierno::find()
        ->where(['empleado_id' => $empleado->id])
        ->andWhere(['nivel_jerarquico' => ['Director', 'Jefe de unidad']])
        ->one();

    if ($juntaGobierno) {
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
                return $model->empleado->profesion . ' ' . $model->empleado->nombre . ' ' . $model->empleado->apellido;
            }
        ),
        'options' => ['placeholder' => 'Seleccionar Jefe de Departamento'],
        'pluginOptions' => [
            'allowClear' => true,
        ],
    ])->label('Jefe de Departamento') ?>

    <?= $form->field($model, 'nombre_jefe_departamento')->hiddenInput()->label(false) ?>
<?php endif; ?>

</div>

<?= Html::submitButton('Generar <i class="fa fa-check"></i>', [
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
