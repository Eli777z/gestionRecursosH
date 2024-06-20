<?php

use app\models\CatDepartamento;
use app\models\CatDireccion;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use kartik\select2\Select2;
use hail812\adminlte\widgets\Alert;
use yii\helpers\ArrayHelper;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);
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

                            <div class="empleado-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">Información del Empleado</div>
                                            <div class="card-body">
                                                <?= $form->field($usuario, 'rol')->dropDownList([
                                                    1 => 'Trabajador',
                                                    2 => 'Gestor de recursos humanos',
                                                    3 => 'Medico'
                                                ])->label('Seleccione el rol del empleado:') ?>

                                                <?= $form->field($model, 'numero_empleado')->textInput()->label('Número de empleado:') ?>

                                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label('Nombre del empleado:') ?>

                                                <?= $form->field($model, 'apellido')->textInput(['maxlength' => true])->label('Apellido del empleado:') ?>

                                                <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email del empleado:') ?>

                                                <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                                                    'options' => ['accept' => 'file/*'],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                        'showCancel' => false, 

                                                    ],
                                                ])->label('Foto del empleado:') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header gradient-verde text-white">Información Laboral</div>
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

<?= $form->field($informacion_laboral, 'cat_puesto_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatPuesto::find()->all(), 'id', 'nombre_puesto'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione puesto del empleado'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ])->label('Puesto del empleado:') ?>

                                              

                                                <?= $form->field($informacion_laboral, 'cat_tipo_contrato_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione el tipo de contrato'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ])->label('Tipo de contrato del empleado:') ?>


     <?= $form->field($juntaGobiernoModel, 'nivel_jerarquico')->dropDownList([
        'Comun' => 'Comun',
                                        'Director' => 'Director',
                                        'Jefe de unidad' => 'Jefe de unidad',
                                        'Jefe de departamento' => 'Jefe de departamento',
                                    ], ['prompt' => 'Selecciona el nivel jerárquico...'])->label('Tipo de empleado:') ?>
  <?= $form->field($model, 'profesion')->dropDownList([
                                        'No tiene' => 'No tiene',
                                        'ING.' => 'ING.',
                                        'LIC.' => 'LIC.',
                                        'PROF.' => 'PROF.',
                                        'ARQ.' => 'ARQ.',
                                        'C.' => 'C.',
                                        'DR.' => 'DR.',
                                        'DRA.' => 'DRA.',
                                        'TEC.' => 'TEC.',

                                    ], ['prompt' => 'Selecciona el nivel académico...'])->label('Profesión:') ?>

                               
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
