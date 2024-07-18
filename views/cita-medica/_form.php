<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use hail812\adminlte\widgets\Alert;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\CitaMedica */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(); ?>
                <div class="card-header bg-info">
                    <h2>CITA MEDICA</h2>
                    <?php
// Obtener el ID del usuario actual
$usuarioActual = Yii::$app->user->identity;
$empleadoActual = $usuarioActual->empleado;

// Comparar el ID del empleado actual con el ID del empleado para el cual se está creando el registro
if ($empleadoActual->id === $empleado->id) {
    // El empleado está creando un registro para sí mismo
    echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
    ]);
} else {
    // El empleado está creando un registro para otro empleado
    if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) {
        echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    } else {
        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    }
}
?>


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
<div class="comision-especial-form">

<div class="card bg-light">
                                            <div class="card-body">
<div class="row">

    <?= $form->field($model, 'empleado_id')->textInput() ?>

    <div class="col-6 col-sm-2">

    <?= $form->field($model, 'fecha_para_cita')->input('date') ?>
    </div>
    <div class="col-6 col-sm-2">


    <?= $form->field($model, 'horario_inicio')->input('time') ?>
    </div>
    <div class="col-6 col-sm-2">

    <?= $form->field($model, 'horario_finalizacion')->input('time') ?>
    </div>

    <?= $form->field($model, 'comentario')->textarea(['rows' => 6]) ?>
    
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

