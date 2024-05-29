<?php

use app\models\CatDepartamento;
use app\models\CatDireccion;
use app\models\CatTipoContrato;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use kartik\select2\Select2;
use hail812\adminlte\widgets\Alert;
use yii\helpers\ArrayHelper;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <div class="card-header bg-primary text-white">
                    <h2>AÑADIR NUEVO EMPLEADO</h2>
                    <?= Html::submitButton('Guardar y enviar datos de usuario <i class="fa fa-save fa-md"></i>', [
                        'class' => 'btn btn-light btn-lg float-right', 
                        'id' => 'save-button-personal'
                    ]) ?>
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

                            <div class="empleado-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">Información del Empleado</div>
                                            <div class="card-body">
                                                <?= $form->field($usuario, 'rol')->dropDownList([
                                                    1 => 'Trabajador',
                                                    2 => 'Gestor de recursos humanos',
                                                ])->label('Seleccione el rol del empleado:') ?>

                                                <?= $form->field($model, 'numero_empleado')->textInput()->label('Número de empleado:') ?>

                                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label('Nombre del empleado:') ?>

                                                <?= $form->field($model, 'apellido')->textInput(['maxlength' => true])->label('Apellido del empleado:') ?>

                                                <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email del empleado:') ?>

                                                <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                                                    'options' => ['accept' => 'file/*'],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                    ],
                                                ])->label('Foto del empleado:') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-secondary text-white">Información Laboral</div>
                                            <div class="card-body">
                                                <?= $form->field($informacion_laboral, 'cat_departamento_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione departamento'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ])->label('Departamento al que pertenece:') ?>

                                                <?= $form->field($informacion_laboral, 'cat_direccion_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione la dirección'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ])->label('Dirección a la que pertenece') ?>

                                                <?= $form->field($informacion_laboral, 'cat_tipo_contrato_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione el tipo de contrato'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ])->label('Tipo de contrato del empleado:') ?>

                                                <?= $form->field($informacion_laboral, 'fecha_ingreso')->input('date')->label('Fecha de contratación del empleado:') ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
