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
                <div class="card-header bg-primary">
                    <h2>CREAR NUEVA SOLICITUD DE CITA MEDICA</h2>
                    <?php if (Yii::$app->user->can('crear-cita-medica')) { ?>

                    <?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/view', 'id' => $model->empleado->id], [
    'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
   
    'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }else{ ?>
    <?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['site/portalempleado'], [
    'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
   
    'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

    <?php }?>


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

<div class="card">
                                            <div class="card-header bg-info text-white">Ingrese los siguientes datos</div>
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

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

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

