<?php

use app\models\CatDepartamento;
use app\models\CatDireccion;
use app\models\CatDptoCargo;
use app\models\CatNivelEstudio;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use yii\bootstrap5\Alert;
use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;
use kartik\file\FileInput;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use kartik\tabs\TabsX;
use app\models\DocumentoSearch;
use app\models\JuntaGobierno;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\CatTipoDocumento;
use app\models\DocumentoMedicoSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

//$activeTab = Yii::$app->request->get('tab', 'info_p');

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->registerJsFile('@web/js/municipios.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/select_estados.js', ['depends' => [\yii\web\JqueryAsset::class]]);
if (Yii::$app->user->can('medico') || Yii::$app->user->can('gestor-rh')) :


    $this->title = 'Empleado: ' . $model->nombre . ' ' . $model->apellido;
    $this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
endif;
$this->params['breadcrumbs'][] = $this->title;

\yii\web\YiiAsset::register($this);
$currentDate = date('Y-m-d');
$antecedentesExistentes = [];
$observacionGeneral = '';
$descripcionAntecedentes = '';
$modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
$modelExploracionFisica = new \app\models\ExploracionFisica();
$editable = Yii::$app->user->can('editar-expediente-medico');


if ($antecedentes) {
    foreach ($antecedentes as $antecedente) {
        $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
        if (empty($observacionGeneral)) {
            $observacionGeneral = $antecedente->observacion;
        }
    }
}

// Si ya existe un antecedente patológico, obtenemos su descripción
$modelAntecedentePatologico = \app\models\AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePatologico) {
    $modelAntecedentePatologico = new \app\models\AntecedentePatologico();
    $modelAntecedentePatologico->expediente_medico_id = $expedienteMedico->id;
}

// Obtener antecedentes no patológicos
$modelAntecedenteNoPatologico = \app\models\AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteNoPatologico) {
    $modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
    $modelAntecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
}


$modelExploracionFisica = \app\models\ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelExploracionFisica) {
    $modelExploracionFisica = new \app\models\ExploracionFisica();
    $modelExploracionFisica->expediente_medico_id = $expedienteMedico->id;
}

$modelInterrogatorioMedico = \app\models\InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelInterrogatorioMedico) {
    $modelInterrogatorioMedico = new \app\models\InterrogatorioMedico();
    $modelInterrogatorioMedico->expediente_medico_id = $expedienteMedico->id;
}
// Obtener antecedentes no patológicos
$modelAntecedentePerinatal = \app\models\AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePerinatal) {
    $modelAntecedentePerinatal = new \app\models\AntecedentePerinatal();
    $modelAntecedentePerinatal->expediente_medico_id = $expedienteMedico->id;
}


$modelAntecedenteGinecologico = \app\models\AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteGinecologico) {
    $modelAntecedenteGinecologico = new \app\models\AntecedenteGinecologico();
    $modelAntecedenteGinecologico->expediente_medico_id = $expedienteMedico->id;
}

$modelAntecedenteObstrectico = \app\models\AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteObstrectico) {
    $modelAntecedenteObstrectico = new \app\models\AntecedenteObstrectico();
    $modelAntecedenteObstrectico->expediente_medico_id = $expedienteMedico->id;
}

$modelAlergia = \app\models\Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAlergia) {
    $modelAlergia = new \app\models\Alergia();
    $modelAlergia->expediente_medico_id = $expedienteMedico->id;
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showAlert(title, text) {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
        });
    }
</script>
<!-- Include Bootstrap Multiselect CSS and JS -->
<link rel="stylesheet" href="https://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/css/bootstrap-multiselect.css">
<script src="https://cdn.rawgit.com/davidstutz/bootstrap-multiselect/master/dist/js/bootstrap-multiselect.js"></script>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card bg-light">
                <div class="card-header gradient-info text-white">
                    <div class="d-flex align-items-center position-relative ml-4">
                        <div class="bg-light p-1 rounded-circle custom-shadow" style="width: 140px; height: 140px; position: relative;">
                            <?= Html::img(['empleado/foto-empleado', 'id' => $model->id], [
                                'class' => 'rounded-circle',
                                'style' => 'width: 130px; height: 130px;'
                            ]) ?>
                            <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>
                                <?= Html::button('<i class="fas fa-edit"></i>', [
                                    'class' => 'btn btn-dark position-absolute',
                                    'style' => 'top: 5px; right: 5px; padding: 5px 10px;',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#changePhotoModal'
                                ]) ?>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
                            <div class="alert alert-light mb-0" role="alert">

                                <h3 class="mb-0"><?= $model->nombre ?> <?= $model->apellido ?></h3>
                                <h5 class="mb-0">Numero de empleado: <?= $model->numero_empleado ?></h5>


                            </div>
                        </div>

                    </div>
                    <?php if (Yii::$app->user->can('crear-consulta-medica')) : ?>

                        <?php if ($model->expedienteMedico) : ?>
                            <?= Html::a('Nueva consulta <i class="fa fa-user-md" ></i>', ['consulta-medica/create', 'expediente_medico_id' => $model->expedienteMedico->id], [
                                'class' => 'btn btn-dark  float-right fa-lg'
                            ]) ?>


                        <?php endif; ?>



                        <?php if ($model->id) : ?>

                            <?= Html::a('Nueva cita medica <i class="fa fa-plus-square" ></i>', ['cita-medica/create', 'empleado_id' => $model->id], [
                                'class' => 'btn btn-light mr-3 float-right fa-lg'
                            ]) ?>



                        <?php endif; ?>


                    <?php endif; ?>
                </div>





                <!-- Modal -->
                <div class="modal fade" id="changePhotoModal" tabindex="-1" role="dialog" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePhotoModalLabel">Cambiar Foto de Perfil</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php $form = ActiveForm::begin([
                                    'action' => ['empleado/change-photo', 'id' => $model->id],
                                    'options' => ['enctype' => 'multipart/form-data']
                                ]); ?>

                                <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showCancel' => false,
                                        'browseClass' => 'btn btn-primary btn-block',
                                        'browseLabel' => 'Seleccionar Foto'
                                    ]
                                ]); ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Subir', ['class' => 'btn btn-primary']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3 float-right">




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
                            <?php $this->beginBlock('datos'); ?>
                            <?php $this->beginBlock('info_p'); ?>
                            <br>

                            <?php Pjax::begin([
                                'id' => 'pjax-update-info',
                                'options' => ['pushState' => false],
                            ]); ?>
                            <div class="row">

                                <div class="col-md-6">

                                    <div class="card">
                                        <?php $form = ActiveForm::begin([
                                            'action' => ['actualizar-informacion', 'id' => $model->id],
                                            'options' => ['id' => 'personal-info-form']
                                        ]); ?>
                                        <div class="card-header bg-info text-white">
                                            <h3>Información personal</h3>
                                            <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                <button type="button" id="edit-button-personal" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                <button type="button" id="cancel-button-personal" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>

                                                <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-personal', 'style' => 'display:none;']) ?>
                                                <div id="loading-spinner" style="display: none;">
                                                    <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <?= $form->field($model, 'numero_empleado')->input('number', ['readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'nombre')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'apellido')->textInput(['readonly' => true, 'class' => 'form-control']); ?>

                                            <?php //echo Html::label($model->expedienteMedico->antecedenteNoPatologico, 'religion'); 
                                            ?>
                                            <?= $form->field($model, 'fecha_nacimiento')->input('date', ['disabled' => true]) ?>
                                            <?= $form->field($model, 'edad')->hiddenInput()->label(false); ?>

                                            <div class="form-group">
                                                <label class="control-label">Edad:</label>
                                                <p id="edad-label"><?= Html::encode($model->edad); ?></p>
                                            </div>

                                            <?= $form->field($model, 'sexo')->widget(Select2::className(), [
                                                'data' => [
                                                    'Masculino' => 'Masculino',
                                                    'Femenino' => 'Femenino',
                                                ],
                                                'options' => ['prompt' => 'Seleccionar Sexo', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                            ]); ?>
                                            <?= $form->field($model, 'estado_civil')->widget(Select2::className(), [
                                                'data' => [
                                                    'Masculino' => [
                                                        'Soltero' => 'Soltero',
                                                        'Casado' => 'Casado',
                                                        'Separado' => 'Separado',
                                                        'Viudo' => 'Viudo',
                                                    ],
                                                    'Femenino' => [
                                                        'Soltera' => 'Soltera',
                                                        'Casada' => 'Casada',
                                                        'Separada' => 'Separada',
                                                        'Viuda' => 'Viuda',
                                                    ],
                                                ],
                                                'options' => ['prompt' => 'Seleccionar Estado Civil', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                            ]); ?>
                                            <?= $form->field($model, 'curp')->textInput(['readonly' => true, 'maxlength' => 18, 'class' => 'form-control'])->label('CURP:') ?>

                                            <?= $form->field($model, 'nss')->input('number', [
                                                'maxlength' => 11,
                                                'class' => 'form-control',
                                                'id' => 'nss-input',
                                                'readonly' => true,
                                            ])->label('NSS:') ?>





                                            <?= $form->field($model, 'rfc')->textInput(['readonly' => true, 'maxlength' => 13, 'class' => 'form-control'])->label('RFC:') ?>


                                        </div>
                                        <?php ActiveForm::end(); ?>
                                        <?php
                                        $script = <<< JS
    $('#personal-info-form').on('beforeSubmit', function() {
        var button = $('#save-button-personal');
        var spinner = $('#loading-spinner');

        button.prop('disabled', true); // Deshabilita el botón
        spinner.show(); // Muestra el spinner

        return true; // Permite que el formulario se envíe
    });
JS;
                                        $this->registerJs($script);
                                        ?>
                                    </div>

                                </div>

                                <script>
                                    document.getElementById('edit-button-personal').addEventListener('click', function() {
                                        var fields = document.querySelectorAll('#personal-info-form .form-control');
                                        fields.forEach(function(field) {
                                            field.readOnly = false;
                                            field.disabled = false;
                                        });
                                        document.getElementById('edit-button-personal').style.display = 'none';
                                        document.getElementById('save-button-personal').style.display = 'block';
                                        document.getElementById('cancel-button-personal').style.display = 'block';
                                    });

                                    document.getElementById('cancel-button-personal').addEventListener('click', function() {
                                        var fields = document.querySelectorAll('#personal-info-form .form-control');
                                        fields.forEach(function(field) {
                                            field.readOnly = true;
                                            field.disabled = true;
                                            field.value = field.defaultValue;
                                        });
                                        document.getElementById('edit-button-personal').style.display = 'block';
                                        document.getElementById('save-button-personal').style.display = 'none';
                                        document.getElementById('cancel-button-personal').style.display = 'none';
                                    });

                                    /*function checkEmptyFieldsPersonal() {
                                        var fields = document.querySelectorAll('#personal-info-form .form-control');
                                        var emptyFields = Array.from(fields).filter(function(field) {
                                            return field.value.trim() === '';
                                        });

                                        if (emptyFields.length > 0) {
                                            showAlert('Falta completar datos del empleado', 'Por favor, complete todos los campos.');
                                        }
                                    }

                                    document.addEventListener('DOMContentLoaded', function() {
                                        checkEmptyFieldsPersonal();
                                    });

                                    $('#pjax-update-info').on('pjax:end', function() {
                                        checkEmptyFieldsPersonal();
                                    });*/
                                </script>


                                <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>


                                    <div class="col-md-6">

                                        <div class="card">
                                            <?php $form = ActiveForm::begin([
                                                'action' => ['actualizar-informacion', 'id' => $model->id],
                                                'options' => ['id' => 'educational-info-form']
                                            ]); ?>
                                            <div class="card-header gradient-verde  text-white">
                                                <h3>Información Educacional</h3>
                                                <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                    <button type="button" id="edit-button-educational" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                    <button type="button" id="cancel-button-educational" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-educational', 'style' => 'display:none;']) ?>
                                                    <div id="loading-spinner-educacion" style="display: none;">
                                                        <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="card-body">
                                                <?= $form->field($model, 'cat_nivel_estudio_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatNivelEstudio::find()->all(), 'id', 'nivel_estudio'),
                                                    'options' => ['placeholder' => 'Seleccionar Nivel de Estudio', 'disabled' => true],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                    'pluginEvents' => [
                                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                                    ],
                                                ])->label('Nivel de estudios') ?>
                                                <?= $form->field($model, 'institucion_educativa')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
                                                <?= $form->field($model, 'profesion')->widget(Select2::className(), [
                                                    'data' => [
                                                        'No tiene' => 'No tiene',
                                                        'ING.' => 'ING.',
                                                        'LIC.' => 'LIC.',
                                                        'PROF.' => 'PROF.',
                                                        'ARQ.' => 'ARQ.',
                                                        'C.' => 'C.',
                                                        'DR.' => 'DR.',
                                                        'DRA.' => 'DRA.',
                                                        'TEC.' => 'TEC.',
                                                    ],
                                                    'options' => ['prompt' => 'Seleccionar Profesión', 'disabled' => true],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                ]); ?>
                                            </div>
                                            <?php ActiveForm::end(); ?>
                                            <?php
                                            $script = <<< JS
    $('#educational-info-form').on('beforeSubmit', function() {
        var button = $('#save-button-educational');
        var spinner = $('#loading-spinner-educacion');

        button.prop('disabled', true); // Deshabilita el botón
        spinner.show(); // Muestra el spinner

        return true; // Permite que el formulario se envíe
    });
JS;
                                            $this->registerJs($script);
                                            ?>
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <script>
                                document.getElementById('edit-button-educational').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#educational-info-form .form-control');
                                    fields.forEach(function(field) {
                                        field.readOnly = false;
                                        field.disabled = false;
                                    });
                                    document.getElementById('edit-button-educational').style.display = 'none';
                                    document.getElementById('save-button-educational').style.display = 'block';
                                    document.getElementById('cancel-button-educational').style.display = 'block';
                                });

                                document.getElementById('cancel-button-educational').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#educational-info-form .form-control');
                                    fields.forEach(function(field) {
                                        field.readOnly = true;
                                        field.disabled = true;
                                        field.value = field.defaultValue;
                                    });
                                    document.getElementById('edit-button-educational').style.display = 'block';
                                    document.getElementById('save-button-educational').style.display = 'none';
                                    document.getElementById('cancel-button-educational').style.display = 'none';
                                });

                                /*function checkEmptyFieldsPersonal() {
                                    var fields = document.querySelectorAll('#educational-info-form .form-control');
                                    var emptyFields = Array.from(fields).filter(function(field) {
                                        return field.value.trim() === '';
                                    });

                                    if (emptyFields.length > 0) {
                                        showAlert('Falta completar datos del empleado', 'Por favor, complete todos los campos.');
                                    }
                                }

                                document.addEventListener('DOMContentLoaded', function() {
                                    checkEmptyFieldsPersonal();
                                });

                                $('#pjax-update-info').on('pjax:end', function() {
                                    checkEmptyFieldsPersonal();
                                });*/
                            </script>


                            <?php Pjax::end(); ?>
                            <?php $this->endBlock(); ?>



                            <?php $this->beginBlock('info_c'); ?>
                            <br>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="card">
                                        <?php $form = ActiveForm::begin([
                                            'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                            'options' => ['id' => 'contact-info-form']
                                        ]); ?>
                                        <div class="card-header bg-info text-white">
                                            <h3>Información de contacto</h3>
                                            <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                <button type="button" id="edit-button-contact" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                <button type="button" id="cancel-button-contact" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-contact', 'style' => 'display:none;']) ?>
                                                <div id="loading-spinner-contacto" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
    </div>
                                            
                                                <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'telefono')->textInput(['maxlength' => 15, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'colonia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'calle')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'numero_casa')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'codigo_postal')->input('number', [
                                                'maxlength' => 6,
                                                'class' => 'form-control',
                                                'id' => 'nss-input',
                                                'readonly' => true,
                                            ])->label('Codigo Postal:') ?>
                                            <?= $form->field($model, 'estado')->widget(Select2::classname(), [
                                                'data' => [], // Inicialmente vacío, se llenará con JS
                                                'options' => ['placeholder' => 'Selecciona un estado', 'id' => 'estado-dropdown', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                            ])->label('Estado:'); ?>
                                            <?= $form->field($model, 'municipio')->widget(Select2::classname(), [
                                                'data' => [], // Inicialmente vacío
                                                'options' => ['placeholder' => 'Selecciona un municipio', 'id' => 'municipio-dropdown', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                            ])->label('Municipio:'); ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                        <?php
                                            $script = <<< JS
    $('#contact-info-form').on('beforeSubmit', function() {
        var button = $('#save-button-contact');
        var spinner = $('#loading-spinner-contacto');

        button.prop('disabled', true); // Deshabilita el botón
        spinner.show(); // Muestra el spinner

        return true; // Permite que el formulario se envíe
    });
JS;
                                            $this->registerJs($script);
                                            ?>
                                    </div>

                                    <script>
                                        document.getElementById('edit-button-contact').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#contact-info-form .form-control');
                                            fields.forEach(function(field) {
                                                field.readOnly = false;
                                                field.disabled = false;
                                            });
                                            document.getElementById('edit-button-contact').style.display = 'none';
                                            document.getElementById('save-button-contact').style.display = 'block';
                                            document.getElementById('cancel-button-contact').style.display = 'block';
                                        });

                                        document.getElementById('cancel-button-contact').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#contact-info-form .form-control');
                                            fields.forEach(function(field) {
                                                field.readOnly = true;
                                                field.disabled = true;
                                                field.value = field.defaultValue;
                                            });
                                            document.getElementById('edit-button-contact').style.display = 'block';
                                            document.getElementById('save-button-contact').style.display = 'none';
                                            document.getElementById('cancel-button-contact').style.display = 'none';
                                        });

                                        /*  function checkEmptyFieldsContact() {
                                              var fields = document.querySelectorAll('#contact-info-form .form-control');
                                              var emptyFields = Array.from(fields).filter(function(field) {
                                                  return field.value.trim() === '';
                                              });

                                              if (emptyFields.length > 0) {
                                                  showAlert('Falta completar datos de contacto', 'Por favor, complete todos los campos.');
                                              }
                                          }

                                          document.addEventListener('DOMContentLoaded', function() {
                                              checkEmptyFieldsContact();
                                          });

                                          $('#pjax-update-info').on('pjax:end', function() {
                                              checkEmptyFieldsContact();
                                          });*/
                                    </script>
                                </div>
                                <div class="col-md-6">
                                    <div class="card">
                                        <?php $form = ActiveForm::begin([
                                            'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                            'options' => ['id' => 'emergency-contact-form']
                                        ]); ?>
                                        <div class="card-header gradient-verde text-white">
                                            <h3>Información de contacto de emergencia</h3>
                                            <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                <button type="button" id="edit-button-emergency" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                <button type="button" id="cancel-button-emergency" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right  mr-3', 'id' => 'save-button-emergency', 'style' => 'display:none;']) ?>
                                                <div id="loading-spinner-contacto-emergencia" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
    </div>
                                            
                                                <?php endif; ?>
                                        </div>
                                        <div class="card-body">
                                            <?= $form->field($model, 'nombre_contacto_emergencia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'relacion_contacto_emergencia')->widget(Select2::className(), [
                                                'data' => [
                                                    'Padre' => 'Padre',
                                                    'Madre' => 'Madre',
                                                    'Esposo/a' => 'Esposo/a',
                                                    'Hijo/a' => 'Hijo/a',
                                                    'Hermano/a' => 'Hermano/a',
                                                    'Compañero/a de trabajo' => 'Compañero/a de trabajo',
                                                    'Tio/a' => 'Tio/a'
                                                ],
                                                'options' => ['prompt' => 'Seleccionar Parentesco', 'disabled' => true],
                                                'pluginOptions' => [
                                                    'allowClear' => true
                                                ],
                                                'theme' => Select2::THEME_BOOTSTRAP,
                                            ]); ?>
                                            <?= $form->field($model, 'telefono_contacto_emergencia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                        <?php
                                            $script = <<< JS
    $('#emergency-contact-form').on('beforeSubmit', function() {
        var button = $('#save-button-emergency');
        var spinner = $('#loading-spinner-contacto-emergencia');

        button.prop('disabled', true); // Deshabilita el botón
        spinner.show(); // Muestra el spinner

        return true; // Permite que el formulario se envíe
    });
JS;
                                            $this->registerJs($script);
                                            ?>
                                    </div>

                                    <script>
                                        document.getElementById('edit-button-emergency').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#emergency-contact-form .form-control');
                                            fields.forEach(function(field) {
                                                field.readOnly = false;
                                                field.disabled = false;
                                            });
                                            document.getElementById('edit-button-emergency').style.display = 'none';
                                            document.getElementById('save-button-emergency').style.display = 'block';
                                            document.getElementById('cancel-button-emergency').style.display = 'block';
                                        });

                                        document.getElementById('cancel-button-emergency').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#emergency-contact-form .form-control');
                                            fields.forEach(function(field) {
                                                field.readOnly = true;
                                                field.disabled = true;
                                                field.value = field.defaultValue;
                                            });
                                            document.getElementById('edit-button-emergency').style.display = 'block';
                                            document.getElementById('save-button-emergency').style.display = 'none';
                                            document.getElementById('cancel-button-emergency').style.display = 'none';
                                        });

                                        /* function checkEmptyFieldsEmergency() {
                                             var fields = document.querySelectorAll('#emergency-contact-form .form-control');
                                             var emptyFields = Array.from(fields).filter(function(field) {
                                                 return field.value.trim() === '';
                                             });

                                             if (emptyFields.length > 0) {
                                                 showAlert('Falta completar datos de contacto de emergencia', 'Por favor, complete todos los campos.');
                                             }
                                         }

                                         document.addEventListener('DOMContentLoaded', function() {
                                             checkEmptyFieldsEmergency();
                                         });

                                         $('#pjax-update-info').on('pjax:end', function() {
                                             checkEmptyFieldsEmergency();
                                         });*/
                                    </script>
                                </div>
                            </div>
                            <?php $this->endBlock(); ?>
                            <!-- INFOLABORAL-->
                            <?php $this->beginBlock('info_l'); ?>
                            <br>

                            <?php
                            $juntaDirectorDireccion = JuntaGobierno::find()
                                ->where(['nivel_jerarquico' => 'Director'])
                                ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id])
                                ->one();

                            $jefesDirectores = ArrayHelper::map(
                                JuntaGobierno::find()
                                    ->where(['nivel_jerarquico' => 'Jefe de unidad'])
                                    ->orWhere(['nivel_jerarquico' => 'Jefe de departamento'])
                                    ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id])
                                    ->all(),
                                'id',
                                function ($model) {
                                    return $model->empleado->nombre . ' ' . $model->empleado->apellido;
                                }
                            );





                            ?>
                            <div class="card">
                                <?php $form = ActiveForm::begin([
                                    'action' => ['actualizar-informacion-laboral', 'id' => $model->id],
                                    'options' => ['id' => 'laboral-info-form']
                                ]); ?>
                                <div class="card-header bg-info text-white">
                                    <h3>Información Laboral</h3>
                                    <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                        <button type="button" id="edit-button-laboral" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                        <button type="button" id="cancel-button-laboral" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right  mr-3', 'id' => 'save-button-laboral', 'style' => 'display:none;']) ?>
                                    <?php endif; ?>
                                </div>
                                <div class="card-body">

                                    <div class="form-group">
                                        <label class="font-weight-bold">Días Laborales</label><br>
                                        <div id="dias-laborales-display" class="alert alert-warning">
                                            <p><strong>Días laborales actuales:</strong> <?= Html::encode(implode(', ', explode(', ', $model->informacionLaboral->dias_laborales))) ?></p>
                                        </div>
                                        <div id="dias-laborales-checkboxes" style="display: none;">
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Lunes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Lunes', 'class' => 'form-check-input', 'id' => 'lunes']) ?>
                                                <?= Html::label('Lunes', 'lunes', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Martes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Martes', 'class' => 'form-check-input', 'id' => 'martes']) ?>
                                                <?= Html::label('Martes', 'martes', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Miércoles', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Miércoles', 'class' => 'form-check-input', 'id' => 'miercoles']) ?>
                                                <?= Html::label('Miércoles', 'miercoles', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Jueves', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Jueves', 'class' => 'form-check-input', 'id' => 'jueves']) ?>
                                                <?= Html::label('Jueves', 'jueves', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Viernes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Viernes', 'class' => 'form-check-input', 'id' => 'viernes']) ?>
                                                <?= Html::label('Viernes', 'viernes', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Sábado', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Sábado', 'class' => 'form-check-input', 'id' => 'sabado']) ?>
                                                <?= Html::label('Sábado', 'sabado', ['class' => 'form-check-label']) ?>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Domingo', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Domingo', 'class' => 'form-check-input', 'id' => 'domingo']) ?>
                                                <?= Html::label('Domingo', 'domingo', ['class' => 'form-check-label']) ?>
                                            </div>
                                        </div>
                                    </div>



                                    <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>

                                        <?= $form->field($model->informacionLaboral, 'cat_tipo_contrato_id')->widget(Select2::classname(), [
                                            'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
                                            'options' => ['placeholder' => 'Seleccionar Tipo de Contrato', 'disabled' => true],
                                            'pluginOptions' => [
                                                'allowClear' => true
                                            ],
                                            'theme' => Select2::THEME_BOOTSTRAP,
                                            'pluginEvents' => [
                                                'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                            ],
                                            'pluginEvents' => [
                                                'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                            ],
                                        ])->label('Tipo de contrato') ?>
                                    <?php endif; ?>
                                    <?= $form->field($model->informacionLaboral, 'fecha_ingreso')->input('date', ['disabled' => true]) ?>

                                    <?= $form->field($model->informacionLaboral, 'cat_departamento_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
                                        'options' => ['placeholder' => 'Seleccionar Departamento', 'disabled' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                        ],
                                    ])->label('Departamento')  ?>

                                    <?= $form->field($model->informacionLaboral, 'cat_dpto_cargo_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDptoCargo::find()->all(), 'id', 'nombre_dpto'),
                                        'options' => ['placeholder' => 'Selecciona el DPTO', 'disabled' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                        ],
                                    ])->label('Cargo')  ?>
                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_inicio')->input('time', ['disabled' => true])->label('Hora de entrada')  ?>

                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_fin')->input('time', ['disabled' => true])->label('Hora de salida') ?>
                                    <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>

                                        <?= $form->field($model->informacionLaboral, 'numero_cuenta')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                    <?php endif; ?>
                                    <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>

                                        <?= $form->field($model->informacionLaboral, 'salario')->textInput([
                                            'type' => 'number',
                                            'step' => '0.01',
                                            'readonly' => true,
                                            'class' => 'form-control'
                                        ]); ?>
                                    <?php endif; ?>


                                    <?= $form->field($model->informacionLaboral, 'cat_puesto_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatPuesto::find()->all(), 'id', 'nombre_puesto'),
                                        'options' => ['placeholder' => 'Seleccionar Puesto', 'disabled' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                        ],
                                    ])->label('Nombramiento')  ?>



                                    <?= $form->field($model->informacionLaboral, 'cat_direccion_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'),
                                        'options' => ['placeholder' => 'Seleccionar Dirección', 'disabled' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                        ],
                                    ])->label('Dirección') ?>

                                    <?= $form->field($model->informacionLaboral, 'junta_gobierno_id')->widget(Select2::classname(), [
                                        'data' => $jefesDirectores,
                                        'options' => ['placeholder' => 'Seleccionar Jefe o director a cargo', 'disabled' => true],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_BOOTSTRAP,
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                        ],
                                    ])->label('Jefe inmediato') ?>

                                    <div class="form-group">
                                        <label class="control-label">Director de dirección</label>
                                        <input type="text" class="form-control" readonly value="<?= $juntaDirectorDireccion ?  $juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido : 'No Asignado' ?>">
                                    </div>



                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>


                            <script>
                                document.addEventListener("DOMContentLoaded", function() {
                                    // Al cargar la página, guardar los valores originales de los campos
                                    var originalValues = {};
                                    var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select');
                                    fields.forEach(function(field) {
                                        originalValues[field.id] = field.value;
                                    });

                                    // Botón Editar
                                    document.getElementById('edit-button-laboral').addEventListener('click', function() {
                                        fields.forEach(function(field) {
                                            field.readOnly = false;
                                            field.disabled = false;
                                        });
                                        document.getElementById('dias-laborales-display').style.display = 'none';
                                        document.getElementById('dias-laborales-checkboxes').style.display = 'block';
                                        document.getElementById('edit-button-laboral').style.display = 'none';
                                        document.getElementById('save-button-laboral').style.display = 'block';
                                        document.getElementById('cancel-button-laboral').style.display = 'block';
                                    });

                                    // Botón Cancelar
                                    document.getElementById('cancel-button-laboral').addEventListener('click', function() {
                                        fields.forEach(function(field) {
                                            field.readOnly = true;
                                            field.disabled = true;
                                            // Restaurar al valor original
                                            field.value = originalValues[field.id];
                                        });
                                        document.getElementById('dias-laborales-display').style.display = 'block';
                                        document.getElementById('dias-laborales-checkboxes').style.display = 'none';
                                        document.getElementById('edit-button-laboral').style.display = 'block';
                                        document.getElementById('save-button-laboral').style.display = 'none';
                                        document.getElementById('cancel-button-laboral').style.display = 'none';
                                    });
                                });
                            </script>


                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('info_vacacional'); ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <h3>Total de dias vacacionales: <?= $model->informacionLaboral->vacaciones->total_dias_vacaciones ?></h3>
                                    </div>

                                    <li class="dropdown-divider"></li>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <?php $form = ActiveForm::begin([
                                                    'action' => ['actualizar-primer-periodo', 'id' => $model->id],
                                                    'options' => ['id' => 'first-period-form']
                                                ]); ?>
                                                <div class="card-header bg-info text-white">
                                                    <h3>PRIMER PERIODO</h3>
                                                    <?php setlocale(LC_TIME, "es_419.UTF-8"); ?>
                                                    <div class="alert alert-warning text-center" role="alert">
                                                        <label class="control-label small">
                                                            <?php if ($model->informacionLaboral->vacaciones->periodoVacacional && $model->informacionLaboral->vacaciones->periodoVacacional->fecha_inicio && $model->informacionLaboral->vacaciones->periodoVacacional->fecha_final) : ?>
                                                                <?= mb_strtoupper(strftime('%A, %d de %B de %Y', strtotime($model->informacionLaboral->vacaciones->periodoVacacional->fecha_inicio))) ?>
                                                                ---
                                                                <?= mb_strtoupper(strftime('%A, %d de %B de %Y', strtotime($model->informacionLaboral->vacaciones->periodoVacacional->fecha_final))) ?>
                                                            <?php else : ?>
                                                                Aún no se ha definido el periodo
                                                            <?php endif; ?>
                                                        </label>
                                                    </div>
                                                    <label>Dias de vacaciones disponibles: <?= $model->informacionLaboral->vacaciones->periodoVacacional->dias_vacaciones_periodo ?></label><br>
                                                    <label>Dias de vacaciones restantes: <span id="dias-disponibles"><?= $model->informacionLaboral->vacaciones->periodoVacacional->dias_disponibles ?></span></label><br>
                                                    <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                        <button type="button" id="edit-button-first-period" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                        <button type="button" id="cancel-button-first-period" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>

                                                        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-dark float-right mr-3', 'id' => 'save-button-first-period', 'style' => 'display:none;']) ?>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="card-body">
                                                    <?= $form->field($model->informacionLaboral->vacaciones->periodoVacacional, 'año')->textInput(['type' => 'number', 'disabled' => true]) ?>
                                                    <?= $form->field($model->informacionLaboral->vacaciones->periodoVacacional, 'dateRange')->widget(DateRangePicker::class, [
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
                                                            'minDate' => '2000-01-01',
                                                            'maxDate' => '2100-12-31',
                                                            'startDate' => $currentDate,
                                                            'endDate' => $currentDate,
                                                            'autoApply' => true,
                                                        ],
                                                        'options' => ['disabled' => true],
                                                        'pluginEvents' => [
                                                            "apply.daterangepicker" => new JsExpression("function(ev, picker) {
                                    var startDate = picker.startDate.format('YYYY-MM-DD');
                                    var endDate = picker.endDate.format('YYYY-MM-DD');
                                    var diasSeleccionados = picker.endDate.diff(picker.startDate, 'days') + 1;

                                    if (diasSeleccionados > {$model->informacionLaboral->vacaciones->periodoVacacional->dias_vacaciones_periodo}) {
                                        alert('No puede seleccionar un rango de fechas que exceda los días disponibles.');
                                        picker.startDate = picker.oldStartDate;
                                        picker.endDate = picker.oldEndDate;
                                        picker.updateView();
                                        picker.renderCalendar();
                                        $('#first-period-form').data('daterangepicker').setStartDate(picker.oldStartDate);
                                        $('#first-period-form').data('daterangepicker').setEndDate(picker.oldEndDate);
                                    } else {
                                        $('#dias-disponibles').text(diasDisponibles);
                                    }
                                }"),
                                                        ],
                                                    ])->label('Seleccionar rango de fechas del primer periodo:') ?>
                                                    <?= $form->field($model->informacionLaboral->vacaciones->periodoVacacional, 'original')->dropDownList(['Si' => 'Si', 'No' => 'No'], ['prompt' => 'Selecciona una opción...', 'disabled' => true]) ?>
                                                </div>
                                                <?php ActiveForm::end(); ?>
                                            </div>
                                        </div>





                                        <script>
                                            document.getElementById('edit-button-first-period').addEventListener('click', function() {
                                                var fields = document.querySelectorAll('#first-period-form input, #first-period-form select');
                                                fields.forEach(function(field) {
                                                    field.disabled = false;
                                                });
                                                $('.select2-hidden-accessible').select2('enable');
                                                document.getElementById('edit-button-first-period').style.display = 'none';
                                                document.getElementById('save-button-first-period').style.display = 'block';
                                                document.getElementById('cancel-button-first-period').style.display = 'block';
                                            });

                                            document.getElementById('cancel-button-first-period').addEventListener('click', function() {
                                                var fields = document.querySelectorAll('#first-period-form input, #first-period-form select');
                                                fields.forEach(function(field) {
                                                    field.disabled = true;
                                                    if (field.tagName !== 'SELECT') {
                                                        field.value = field.defaultValue;
                                                    }
                                                });
                                                $('.select2-hidden-accessible').select2('enable', false);
                                                document.getElementById('edit-button-first-period').style.display = 'block';
                                                document.getElementById('save-button-first-period').style.display = 'none';
                                                document.getElementById('cancel-button-first-period').style.display = 'none';
                                            });
                                        </script>



                                        <div class="col-md-6">
                                            <div class="card">
                                                <?php $form = ActiveForm::begin([
                                                    'action' => ['actualizar-segundo-periodo', 'id' => $model->id],
                                                    'options' => ['id' => 'second-period-form']
                                                ]); ?>
                                                <div class="card-header bg-secondary text-white">
                                                    <h3>SEGUNDO PERIODO</h3>
                                                    <?php setlocale(LC_TIME, "es_419.UTF-8"); ?>
                                                    <div class="alert alert-warning text-center" role="alert">
                                                        <label class="control-label small">
                                                            <?php if ($model->informacionLaboral->vacaciones->segundoPeriodoVacacional && $model->informacionLaboral->vacaciones->segundoPeriodoVacacional->fecha_inicio && $model->informacionLaboral->vacaciones->segundoPeriodoVacacional->fecha_final) : ?>
                                                                <?= mb_strtoupper(strftime('%A, %d de %B de %Y', strtotime($model->informacionLaboral->vacaciones->segundoPeriodoVacacional->fecha_inicio))) ?>
                                                                ---
                                                                <?= mb_strtoupper(strftime('%A, %d de %B de %Y', strtotime($model->informacionLaboral->vacaciones->segundoPeriodoVacacional->fecha_final))) ?>
                                                            <?php else : ?>
                                                                Aún no se ha definido el periodo
                                                            <?php endif; ?>
                                                        </label>
                                                    </div>


                                                    <label> Dias de vacaciones disponibles: <?= $model->informacionLaboral->vacaciones->segundoPeriodoVacacional->dias_vacaciones_periodo ?>
                                                    </label><br>
                                                    <label> Dias de vacaciones restantes: <?= $model->informacionLaboral->vacaciones->segundoPeriodoVacacional->dias_disponibles ?>
                                                    </label>

                                                    <br>
                                                    <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

                                                        <button type="button" id="edit-button-period" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                        <button type="button" id="cancel-button-period" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-info float-right  mr-3', 'id' => 'save-button-period', 'style' => 'display:none;']) ?>
                                                    <?php endif; ?>

                                                </div>
                                                <div class="card-body">
                                                    <?= $form->field($model->informacionLaboral->vacaciones->segundoPeriodoVacacional, 'año')->textInput(['type' => 'number', 'disabled' => true]) ?>
                                                    <?= $form->field($model->informacionLaboral->vacaciones->segundoPeriodoVacacional, 'dateRange')->widget(DateRangePicker::class, [
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
                                                            'minDate' => '2000-01-01',
                                                            'maxDate' => '2100-12-31',
                                                            'startDate' => $currentDate,
                                                            'endDate' => $currentDate,
                                                            'autoApply' => true,
                                                        ],

                                                        'options' => ['disabled' => true],
                                                        'pluginEvents' => [
                                                            "apply.daterangepicker" => new JsExpression("function(ev, picker) {
                                                                var startDate = picker.startDate.format('YYYY-MM-DD');
                                                                var endDate = picker.endDate.format('YYYY-MM-DD');
                                                                var diasSeleccionados = picker.endDate.diff(picker.startDate, 'days') + 1;
                                                               
                                        
                                                                if (diasSeleccionados > {$model->informacionLaboral->vacaciones->segundoPeriodoVacacional->dias_vacaciones_periodo}) {
                                                                    alert('No puede seleccionar un rango de fechas que exceda los días disponibles.');
                                                                    picker.startDate = picker.oldStartDate;
                                                                    picker.endDate = picker.oldEndDate;
                                                                    picker.updateView();
                                                                    picker.renderCalendar();
                                                                    $('#first-period-form').data('daterangepicker').setStartDate(picker.oldStartDate);
                                                                    $('#first-period-form').data('daterangepicker').setEndDate(picker.oldEndDate);
                                                                } else {
                                                                    $('#dias-disponibles').text(diasDisponibles);
                                                                }
                                                            }"),
                                                        ],
                                                    ])->label('Seleccionar rango de fechas del segundo periodo:') ?>
                                                    <?= $form->field($model->informacionLaboral->vacaciones->segundoPeriodoVacacional, 'original')->dropDownList(['Si' => 'Si', 'No' => 'No'], ['prompt' => 'Selecciona una opción...', 'disabled' => true]) ?>
                                                </div>
                                                <?php ActiveForm::end(); ?>
                                            </div>


                                        </div>
                                        <script>
                                            document.getElementById('edit-button-period').addEventListener('click', function() {
                                                var fields = document.querySelectorAll('#second-period-form input, #second-period-form select');
                                                fields.forEach(function(field) {
                                                    field.disabled = false;
                                                });
                                                document.getElementById('edit-button-period').style.display = 'none';
                                                document.getElementById('save-button-period').style.display = 'block';
                                                document.getElementById('cancel-button-period').style.display = 'block';
                                            });

                                            document.getElementById('cancel-button-period').addEventListener('click', function() {
                                                var fields = document.querySelectorAll('#second-period-form input, #second-period-form select');
                                                fields.forEach(function(field) {
                                                    field.disabled = true;
                                                    if (!field.type === 'select-one') {
                                                        field.value = field.defaultValue;
                                                    }
                                                });
                                                document.getElementById('edit-button-period').style.display = 'block';
                                                document.getElementById('save-button-period').style.display = 'none';
                                                document.getElementById('cancel-button-period').style.display = 'none';
                                            });
                                        </script>

                                        <div class="col-md-6">
                                            <div class="card">
                                                <div class="card-header bg-success text-white">
                                                    <h3>Historial de cambios de Periodos</h3>
                                                    <button type="button" id="toggle-historial-button" class="btn btn-light float-right"><i class="fa fa-eye"></i> Mostrar Historial</button>
                                                </div>
                                                <div class="card-body" id="historial-content" style="display: none;">
                                                    <ul class="list-group">
                                                        <?php foreach ($historial as $item) : ?>
                                                            <li class="list-group-item">
                                                                <strong><?= ucfirst($item->periodo) ?>:</strong><br>
                                                                <?= mb_strtoupper(strftime('Fecha inicio: %A, %d de %B de %Y', strtotime($item->fecha_inicio))) ?><br>
                                                                <?= mb_strtoupper(strftime('Fecha final: %A, %d de %B de %Y', strtotime($item->fecha_final))) ?><br>



                                                                Año: <?= $item->año ?><br>
                                                                Días Disponibles: <?= $item->dias_disponibles ?><br>
                                                                Original: <?= $item->original ?><br>

                                                            </li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.getElementById('toggle-historial-button').addEventListener('click', function() {
                                                var historialContent = document.getElementById('historial-content');
                                                if (historialContent.style.display === 'none') {
                                                    historialContent.style.display = 'block';
                                                    this.innerHTML = '<i class="fa fa-eye-slash"></i> Ocultar Historial';
                                                } else {
                                                    historialContent.style.display = 'none';
                                                    this.innerHTML = '<i class="fa fa-eye"></i> Mostrar Historial';
                                                }
                                            });
                                        </script>

                                    </div>

                                </div>
                            </div>

                            <?php $this->endBlock(); ?>

                            <?php $this->beginBlock('info_solicitudes'); ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <?php if (Yii::$app->user->can('ver-solicitudes-formatos')) { ?>

                                            <h3>HISTORIAL DE SOLICITUDES DE INCIDENCIAS: </h3>



                                        <?php } elseif (Yii::$app->user->can('ver-solicitudes-medicas')) { ?>
                                            <h3>HISTORIAL DE SOLICITUDES MEDICAS: </h3>



                                        <?php } ?>
                                    </div>

                                    <li class="dropdown-divider"></li>

                                    <div class="row">
                                        <?php Pjax::begin(['id' => 'pjax-container']); ?>
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProvider,
                                            'filterModel' => $searchModel,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],
                                                [
                                                    'attribute' => 'empleado_id',
                                                    'label' => 'Empleado',
                                                    'value' => function ($model) {
                                                        return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                                                    },
                                                    'filter' => false,
                                                ],
                                                [
                                                    'attribute' => 'fecha_creacion',
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        setlocale(LC_TIME, "es_419.UTF-8");
                                                        return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                                                    },
                                                    'filter' => DatePicker::widget([
                                                        'model' => $searchModel,
                                                        'attribute' => 'fecha_creacion',
                                                        'language' => 'es',
                                                        'dateFormat' => 'php:Y-m-d',
                                                        'options' => [
                                                            'class' => 'form-control',
                                                            'autocomplete' => 'off',
                                                        ],
                                                        'clientOptions' => [
                                                            'changeYear' => true,
                                                            'changeMonth' => true,
                                                            'yearRange' => '-100:+0',
                                                        ],
                                                    ]),
                                                ],

                                                [
                                                    'attribute' => 'nombre_formato',
                                                    'label' => 'Tipo de solicitud',
                                                    'value' => function ($model) {
                                                        return $model->nombre_formato;
                                                    },
                                                    'filter' => Select2::widget([
                                                        'model' => $searchModel,
                                                        'attribute' => 'nombre_formato',
                                                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'),
                                                        'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                                                        'pluginOptions' => [
                                                            'allowClear' => true
                                                        ],
                                                        'theme' => Select2::THEME_KRAJEE_BS3,
                                                    ]),
                                                ],
                                                [
                                                    'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                    'template' => '{view}  {delete}',
                                                    'buttons' => [
                                                        'view' => function ($url, $model, $key) {
                                                            return Html::a('<i class="fa fa-eye"></i>', ['solicitud/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                                                        },

                                                        'delete' => function ($url, $model, $key) {
                                                            return Html::a('<i class="fa fa-trash"></i>', ['solicitud/delete', 'id' => $model->id], [
                                                                'title' => 'Eliminar',
                                                                'class' => 'btn btn-danger btn-xs',
                                                                'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                                                                'data-method' => 'post',
                                                            ]);
                                                        },
                                                    ],
                                                ],
                                            ],
                                            'summaryOptions' => ['class' => 'summary mb-2'],
                                            'pager' => [
                                                'class' => 'yii\bootstrap4\LinkPager',
                                            ],
                                        ]);
                                        Pjax::end();

                                        //  $script = <<< JS
                                        //  setInterval(function(){
                                        //    $.pjax.reload({container:'#pjax-container'});
                                        // }, 60000);
                                        //JS;

                                        // $this->registerJs($script);





                                        ?>




                                    </div>
                                </div>
                            </div>
                            <?php $this->endBlock(); ?>





                            <?php
                            $tabs = [
                                [
                                    'label' => 'Información personal',
                                    'content' => $this->blocks['info_p'],
                                    'options' => [
                                        'id' => 'informacion_personal',
                                    ],
                                ],
                                [
                                    'label' => 'Información de contacto',
                                    'content' => $this->blocks['info_c'],
                                    'options' => [
                                        'id' => 'informacion_contacto',
                                    ],
                                ],
                                [
                                    'label' => 'Información laboral',
                                    'content' => $this->blocks['info_l'],
                                    'options' => [
                                        'id' => 'informacion_laboral',
                                    ],
                                ],
                                [
                                    'label' => 'Solicitudes',
                                    'content' => $this->blocks['info_solicitudes'],
                                    'options' => [
                                        'id' => 'informacion_solicitudes',
                                    ],
                                ],

                            ];

                            if (Yii::$app->user->can('ver-informacion-vacacional')) {
                                $tabs[] = [
                                    'label' => 'Vacaciones',
                                    'content' => $this->blocks['info_vacacional'],
                                    'options' => [
                                        'id' => 'informacion_vacaciones',
                                    ],
                                ];
                            }

                            echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => $tabs,
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_CENTER,
                                'encodeLabels' => false,
                            ]);
                            ?>


                            <?php $this->beginBlock('info_consultas'); ?>
                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <h3>HISTORIAL DE CONSULTAS MEDICAS: </h3>
                                    </div>
                                    <li class="dropdown-divider"></li>
                                    <div class="row">
                                        <?php Pjax::begin(); ?>
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProviderConsultas,
                                            'filterModel' => $searchModelConsultas,
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],



                                                [
                                                    'label' => 'Fecha de la consula',
                                                    'attribute' => 'created_at',
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        setlocale(LC_TIME, "es_419.UTF-8");
                                                        return strftime('%A, %d de %B de %Y', strtotime($model->created_at));
                                                    },
                                                    'filter' => DatePicker::widget([
                                                        'model' => $searchModelConsultas,
                                                        'attribute' => 'created_at',
                                                        'language' => 'es',
                                                        'dateFormat' => 'php:Y-m-d',
                                                        'options' => [
                                                            'class' => 'form-control',
                                                            'autocomplete' => 'off',
                                                        ],
                                                        'clientOptions' => [
                                                            'changeYear' => true,
                                                            'changeMonth' => true,
                                                            'yearRange' => '-100:+0',
                                                        ],

                                                    ]),
                                                ],

                                                [
                                                    'label' => 'Motivo de la consulta',
                                                    'attribute' => 'motivo',
                                                    'format' => 'html',
                                                    'value' => function ($model) {
                                                        return \yii\helpers\Html::decode($model->motivo);
                                                    },
                                                    'filter' => false,
                                                    'options' => ['style' => 'width: 65%;'],
                                                ],
                                                // Otras columnas que desees mostrar...
                                                [
                                                    'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                    'template' => '{view} {delete}',
                                                    'buttons' => [
                                                        'view' => function ($url, $model, $key) {
                                                            return Html::a('<i class="fa fa-eye"></i>', ['consulta-medica/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                                                        },
                                                        'delete' => function ($url, $model, $key) {
                                                            return Html::a('<i class="fa fa-trash"></i>', ['consulta-medica/delete', 'id' => $model->id], [
                                                                'title' => 'Eliminar',
                                                                'class' => 'btn btn-danger btn-xs',
                                                                'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                                                                'data-method' => 'post',
                                                            ]);
                                                        },
                                                    ],
                                                ],
                                            ],
                                            'summaryOptions' => ['class' => 'summary mb-2'],
                                            'pager' => [
                                                'class' => 'yii\bootstrap4\LinkPager',
                                            ],
                                        ]); ?>
                                        <?php Pjax::end(); ?>
                                    </div>
                                </div>
                            </div>
                            <?php $this->endBlock(); ?>




                            <?php $this->beginBlock('expediente'); ?>
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h3>Documentos del empleado</h3>
                                            <button type="button" id="toggle-expediente-button" class="btn btn-dark float-right">
                                                <i class="fa fa-upload"></i>&nbsp; &nbsp; Agregar nuevo archivo
                                            </button>

                                        </div>



                                        <script>
                                            document.getElementById('toggle-expediente-button').addEventListener('click', function() {
                                                var expedienteContent = document.getElementById('expediente-content');
                                                if (expedienteContent.style.display === 'none') {
                                                    expedienteContent.style.display = 'block';
                                                    this.innerHTML = '<i class="fa fa-ban"></i> &nbsp;  Cancelar';
                                                } else {
                                                    expedienteContent.style.display = 'none';
                                                    this.innerHTML = '<i class="fa fa-upload"></i> &nbsp; &nbsp; Agregrar nuevo archivo';
                                                }
                                            });
                                        </script>

                                        <div class="card-body" id="expediente-content" style="display: none;">

                                            <?php $form = ActiveForm::begin([
                                                'action' => ['documento/create', 'empleado_id' => $model->id],
                                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']
                                            ]); ?>

                                            <div class="form-group">
                                                <?= $form->field($documentoModel, 'cat_tipo_documento_id')->widget(Select2::classname(), [
                                                    'data' => ArrayHelper::map(CatTipoDocumento::find()->all(), 'id', 'nombre_tipo'),
                                                    'language' => 'es',
                                                    'options' => ['placeholder' => 'Seleccione el tipo de documento', 'id' => 'tipo-documento'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_BOOTSTRAP,
                                                    'pluginEvents' => [
                                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '0px'); }",
                                                    ],
                                                ])->label('Tipo de Documento') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoModel, 'nombre')->textInput([
                                                    'maxlength' => true,
                                                    'id' => 'nombre-archivo',
                                                    'style' => 'display:none',
                                                    'placeholder' => 'Ingrese el nombre del documento'
                                                ])->label(false) ?>
                                            </div>
                                            <div class="form-group">





                                                <?= $form->field($documentoModel, 'observacion')->widget(FroalaEditorWidget::className(), [

                                                    'clientOptions' => [
                                                        'toolbarInline' => false,
                                                        'theme' => 'royal', // optional: dark, red, gray, royal
                                                        'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                        'height' => 150,
                                                        'pluginsEnabled' => [
                                                            'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                            'draggable', 'entities', 'fontFamily',
                                                            'fontSize', 'fullscreen', 'inlineStyle',
                                                            'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                            'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                        ]
                                                    ]
                                                ]) ?>


                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoModel, 'ruta')->widget(FileInput::classname(), [
                                                    'options' => ['accept' => 'file/*'],
                                                    'pluginEvents' => [
                                                        'fileclear' => "function() {
                                $('#nombre-archivo').val('');
                                $('#tipo-archivo').val('');
                            }",
                                                    ],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                        'showCancel' => false,
                                                    ],
                                                ])->label('Archivo') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= Html::submitButton('Subir archivo <i class="fa fa-upload"></i>', ['class' => 'btn btn-warning float-right', 'id' => 'save-button-personal']) ?>
                                            </div>

                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-12 mt-3">
                                    <div class="card">

                                        <div class="card-body">
                                            <?php
                                            $searchModel = new DocumentoSearch();
                                            $params = Yii::$app->request->queryParams;
                                            $params[$searchModel->formName()]['empleado_id'] = $model->id;
                                            $dataProvider = $searchModel->search($params);
                                            ?>

                                            <?php Pjax::begin(); ?>


                                            <?= GridView::widget([
                                                'dataProvider' => $dataProvider,
                                                'filterModel' => $searchModel,
                                                'columns' => [
                                                    ['class' => 'yii\grid\SerialColumn'],
                                                    [
                                                        'attribute' => 'nombre',
                                                        'value' => 'nombre',
                                                        'filter' => false,

                                                        'options' => ['style' => 'width: 30%;'],
                                                    ],
                                                    [
                                                        'attribute' => 'fecha_subida',
                                                        'format' => 'raw',
                                                        'value' => function ($model) {
                                                            setlocale(LC_TIME, "es_419.UTF-8");
                                                            return strftime('%A, %d de %B de %Y', strtotime($model->fecha_subida));
                                                        },
                                                        'filter' => DatePicker::widget([
                                                            'model' => $searchModel,
                                                            'attribute' => 'fecha_subida',
                                                            'language' => 'es',
                                                            'dateFormat' => 'php:Y-m-d',
                                                            'options' => [
                                                                'class' => 'form-control',
                                                                'autocomplete' => 'off',
                                                            ],
                                                            'clientOptions' => [
                                                                'changeYear' => true,
                                                                'changeMonth' => true,
                                                                'yearRange' => '-100:+0',
                                                            ],
                                                        ]),
                                                        'options' => ['style' => 'width: 30%;'],

                                                    ],
                                                    [
                                                        'attribute' => 'observacion',
                                                        'format' => 'html',
                                                        'value' => function ($model) {
                                                            return \yii\helpers\Html::decode($model->observacion);
                                                        },
                                                        'filter' => false,
                                                        'options' => ['style' => 'width: 30%;'],
                                                    ],
                                                    [
                                                        'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                        'template' => '{view} {delete} {download}',
                                                        'buttons' => [
                                                            'view' => function ($url, $model) {
                                                                return Html::a('<i class="far fa-eye"></i>', ['documento/open', 'id' => $model->id], [
                                                                    'target' => '_blank',
                                                                    'title' => 'Ver archivo',
                                                                    'class' => 'btn btn-info btn-xs',
                                                                    'data-pjax' => "0"
                                                                ]);
                                                            },
                                                            'delete' => function ($url, $model) {
                                                                return Html::a('<i class="fas fa-trash"></i>', ['documento/delete', 'id' => $model->id, 'empleado_id' => $model->empleado_id], [
                                                                    'title' => Yii::t('yii', 'Eliminar'),
                                                                    'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                                                                    'data-method' => 'post',
                                                                    'class' => 'btn btn-danger btn-xs',
                                                                ]);
                                                            },
                                                            'download' => function ($url, $model) {
                                                                return Html::a('<i class="fas fa-download"></i>', ['documento/download', 'id' => $model->id], [
                                                                    'title' => 'Descargar archivo',
                                                                    'class' => 'btn btn-success btn-xs',
                                                                    'data-pjax' => "0"
                                                                ]);
                                                            },
                                                        ],
                                                    ],
                                                ],
                                                'summaryOptions' => ['class' => 'summary mb-2'],
                                                'pager' => [
                                                    'class' => 'yii\bootstrap4\LinkPager',
                                                ],
                                                'tableOptions' => ['class' => 'no-style-gridview'],
                                                'rowOptions' => function ($model, $key, $index, $grid) {
                                                    return ['class' => 'no-style-gridview'];
                                                },
                                            ]); ?>

                                            <?php Pjax::end(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php
                            $this->registerJs("
    $('#tipo-documento').change(function(){
        var tipoDocumentoId = $(this).val();
        var nombreArchivoInput = $('#nombre-archivo');

        // Obtener el nombre del tipo de documento seleccionado
        var tipoDocumentoNombre = $('#tipo-documento option:selected').text();

        // Verificar si se seleccionó 'OTRO'
        if (tipoDocumentoNombre == 'OTRO') {
            // Mostrar el campo de nombre y limpiar su valor
            nombreArchivoInput.show().val('').focus();
        } else {
            // Ocultar el campo de nombre y asignar el nombre del tipo de documento seleccionado
            nombreArchivoInput.hide().val(tipoDocumentoNombre);
        }
    });
");
                            ?>


                            <?php $this->endBlock(); ?>
                            <?php $this->beginBlock('expediente_medico'); ?>

                            <?php $this->beginBlock('documento-medico'); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h3>Documentos médicos del empleado</h3>
                                            <?php if (Yii::$app->user->can('acciones-documentos-medicos')) { ?>

                                                <button type="button" id="toggle-expediente-medico-button" class="btn btn-dark float-right">
                                                    <i class="fa fa-upload"></i>&nbsp; &nbsp; Agregar nuevo archivo
                                                </button>

                                            <?php } ?>
                                        </div>

                                        <script>
                                            document.getElementById('toggle-expediente-medico-button').addEventListener('click', function() {
                                                var expedienteMedicoContent = document.getElementById('expediente-medico-content');
                                                if (expedienteMedicoContent.style.display === 'none') {
                                                    expedienteMedicoContent.style.display = 'block';
                                                    this.innerHTML = '<i class="fa fa-ban"></i> &nbsp; Cancelar';
                                                } else {
                                                    expedienteMedicoContent.style.display = 'none';
                                                    this.innerHTML = '<i class="fa fa-upload"></i> &nbsp; &nbsp; Agregar nuevo archivo';
                                                }
                                            });
                                        </script>

                                        <div class="card-body" id="expediente-medico-content" style="display: none;">
                                            <?php $form = ActiveForm::begin([
                                                'action' => ['documento-medico/create', 'empleado_id' => $model->id],
                                                'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']
                                            ]); ?>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'nombre')->textInput([
                                                    'maxlength' => true,
                                                    'placeholder' => 'Ingrese el nombre del documento'
                                                ])->label('Nombre del documento') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                    'clientOptions' => [
                                                        'toolbarInline' => false,
                                                        'theme' => 'royal',
                                                        'language' => 'es',
                                                        'height' => 150,
                                                        'pluginsEnabled' => [
                                                            'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                            'draggable', 'entities', 'fontFamily',
                                                            'fontSize', 'fullscreen', 'inlineStyle',
                                                            'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                            'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                        ]
                                                    ]
                                                ]) ?>
                                            </div>

                                            <div class="form-group">
                                                <?= $form->field($documentoMedicoModel, 'ruta')->widget(FileInput::classname(), [
                                                    //'options' => ['accept' => 'pdf'],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                        'showCancel' => false,
                                                        'allowedFileExtensions' => ['pdf', 'jpg', 'xlsx'],

                                                    ],
                                                ])->label('Archivo') ?>
                                            </div>

                                            <div class="form-group">
                                                <?= Html::submitButton('Subir archivo <i class="fa fa-upload"></i>', ['class' => 'btn btn-warning float-right']) ?>
                                            </div>

                                            <?php ActiveForm::end(); ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="row justify-content-center">

                                    <div class="col-md-12 mt-3">
                                        <div class="card">
                                            <div class="card-body">
                                                <?php
                                                $searchModel = new DocumentoMedicoSearch();
                                                $params = Yii::$app->request->queryParams;
                                                $params[$searchModel->formName()]['empleado_id'] = $model->id;
                                                $dataProvider = $searchModel->search($params);
                                                ?>

                                                <?php Pjax::begin(); ?>

                                                <?= GridView::widget([
                                                    'dataProvider' => $dataProvider,
                                                    'filterModel' => $searchModel,
                                                    'columns' => [
                                                        ['class' => 'yii\grid\SerialColumn'],
                                                        [
                                                            'attribute' => 'nombre',
                                                            'value' => 'nombre',
                                                            'filter' => false,
                                                            'options' => ['style' => 'width: 20%;'],
                                                        ],

                                                        [
                                                            'attribute' => 'fecha_subida',
                                                            'format' => 'raw',
                                                            'value' => function ($model) {
                                                                setlocale(LC_TIME, "es_419.UTF-8");
                                                                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_subida));
                                                            },
                                                            'filter' => DatePicker::widget([
                                                                'model' => $searchModel,
                                                                'attribute' => 'fecha_subida',
                                                                'language' => 'es',
                                                                'dateFormat' => 'php:Y-m-d',
                                                                'options' => [
                                                                    'class' => 'form-control',
                                                                    'autocomplete' => 'off',
                                                                ],
                                                                'clientOptions' => [
                                                                    'changeYear' => true,
                                                                    'changeMonth' => true,
                                                                    'yearRange' => '-100:+0',
                                                                ],
                                                            ]),
                                                        ],
                                                        [
                                                            'attribute' => 'observacion',
                                                            'format' => 'html',
                                                            'value' => function ($model) {
                                                                return \yii\helpers\Html::decode($model->observacion);
                                                            },
                                                            'filter' => false,
                                                            'options' => ['style' => 'width: 51%;'],
                                                        ],
                                                        [
                                                            'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                            //'template' => '{view} {delete} {download}',
                                                            'template' => Yii::$app->user->can('acciones-documentos-medicos') ? '{view} {delete} {download}' : '{view} {download}',

                                                            'buttons' => [
                                                                'view' => function ($url, $model) {
                                                                    // Verificar si el archivo es PDF o imagen
                                                                    $extensionsToShowEye = ['pdf', 'jpg', 'jpeg', 'png', 'gif'];
                                                                    $fileExtension = pathinfo($model->ruta, PATHINFO_EXTENSION);

                                                                    if (in_array(strtolower($fileExtension), $extensionsToShowEye)) {
                                                                        return Html::a('<i class="far fa-eye"></i>', ['documento-medico/open', 'id' => $model->id], [
                                                                            'target' => '_blank',
                                                                            'title' => 'Ver archivo',
                                                                            'class' => 'btn btn-info btn-xs',
                                                                            'data-pjax' => "0"
                                                                        ]);
                                                                    } else {
                                                                        // Si no es un archivo con extensión permitida, no mostrar el ícono del ojo
                                                                        return '';
                                                                    }
                                                                },
                                                                'delete' => function ($url, $model) {
                                                                    return Html::a('<i class="fas fa-trash"></i>', ['documento-medico/delete', 'id' => $model->id, 'empleado_id' => $model->empleado_id], [
                                                                        'title' => Yii::t('yii', 'Eliminar'),
                                                                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                                                                        'data-method' => 'post',
                                                                        'class' => 'btn btn-danger btn-xs',
                                                                    ]);
                                                                },
                                                                'download' => function ($url, $model) {
                                                                    return Html::a('<i class="fas fa-download"></i>', ['documento-medico/download', 'id' => $model->id], [
                                                                        'title' => 'Descargar archivo',
                                                                        'class' => 'btn btn-success btn-xs',
                                                                        'data-pjax' => "0"
                                                                    ]);
                                                                },
                                                            ],

                                                        ],
                                                    ],
                                                    'summaryOptions' => ['class' => 'summary mb-2'],
                                                    'pager' => [
                                                        'class' => 'yii\bootstrap4\LinkPager',
                                                    ],
                                                    'tableOptions' => ['class' => 'no-style-gridview'],
                                                    'rowOptions' => function ($model, $key, $index, $grid) {
                                                        return ['class' => 'no-style-gridview'];
                                                    },
                                                ]); ?>

                                                <?php Pjax::end(); ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php $this->endBlock(); ?>




                            <?php $this->beginBlock('antecedentes'); ?>

                            <!-- Bloque de antecedentes hereditarios -->
                            <?php $this->beginBlock('hereditarios'); ?>
                            <?php $form = ActiveForm::begin(['action' => ['empleado/view', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedentes Hereditarios</h2>
                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Enfermedad</th>
                                                                    <th>Abuelos</th>
                                                                    <th>Hermanos</th>
                                                                    <th>Madre</th>
                                                                    <th>Padre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($catAntecedentes as $catAntecedente) : ?>
                                                                    <tr>
                                                                        <td><?= Html::encode($catAntecedente->nombre) ?></td>
                                                                        <?php foreach (['Abuelos', 'Hermanos', 'Madre', 'Padre'] as $parentezco) : ?>
                                                                            <td>
                                                                                <?php
                                                                                // Verifica si el usuario tiene permiso para editar

                                                                                // Si es editable, muestra el checkbox para editar
                                                                                // Si no es editable, muestra solo el estado actual sin checkbox
                                                                                if ($editable) {
                                                                                    echo Html::checkbox("AntecedenteHereditario[{$catAntecedente->id}][$parentezco]", isset($antecedentesExistentes[$catAntecedente->id][$parentezco]), [
                                                                                        'value' => 1,
                                                                                        'label' => '',
                                                                                    ]);
                                                                                } else {
                                                                                    // Muestra solo el estado actual sin checkbox
                                                                                    $checked = isset($antecedentesExistentes[$catAntecedente->id][$parentezco]) && $antecedentesExistentes[$catAntecedente->id][$parentezco] == 1;
                                                                                    echo $checked ? 'X' : '';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        <?php endforeach; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex flex-column justify-content-between" style="height: 100%;">
                                                    <div class="form-group text-center">
                                                        <?= Html::label('Observaciones', 'observacion_general') ?>
                                                        <?= Html::textarea('observacion_general', $observacionGeneral, [
                                                            'class' => 'form-control',
                                                            'rows' => 5,
                                                            'style' => 'width: 100%;',
                                                            'readonly' => !$editable, // Hace que el campo sea de solo lectura si no tiene permiso
                                                        ]) ?>
                                                    </div>
                                                    <br>
                                                    <div class="form-group mt-auto d-flex justify-content-end">
                                                        <?php
                                                        // Si tiene permiso, muestra el botón de guardar
                                                        if ($editable) {
                                                            echo Html::submitButton('Guardar &nbsp; &nbsp;  <i class="fa fa-save"></i>', ['class' => 'btn btn-success']);
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="alert alert-white custom-alert" role="alert">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Causas de muerte, malformaciones congénitas, diabetes, cardiopatías, hipertensión arterial, infartos, ateroesclerosis, accidentes vasculares, neuropatías, tuberculosis, artropatías, hemopatías, sida, sífilis, hemopatías, neoplasias, consanguinidad, alcoholismo, toxicomanías.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?php $this->endBlock(); ?>


                            <!-- Bloque de antecedentes patológicos -->
                            <?php $this->beginBlock('patologicos'); ?>
                            <?php $form = ActiveForm::begin(['action' => ['empleado/antecedente-patologico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedentes Patológicos</h2>
                                        </div>
                                        <div class="card-body bg-light">
                                            <?php
                                            // Verifica si el usuario tiene permiso para editar
                                            $editable = Yii::$app->user->can('editar-expediente-medico');
                                            ?>

                                            <?php if ($editable) : ?>
                                                <div class="form-group">
                                                    <?= $form->field($antecedentePatologico, 'descripcion_antecedentes')->widget(FroalaEditorWidget::className(), [
                                                        'clientOptions' => [
                                                            'toolbarInline' => false,
                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                            'height' => 400,
                                                            'pluginsEnabled' => [
                                                                'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                'fontSize', 'fullscreen', 'inlineStyle',
                                                                'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                            ],
                                                        ]
                                                    ])->label(false) ?>
                                                </div>

                                                <div class="form-group text-right">
                                                    <?= Html::submitButton('Guardar &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success fa-lg']) ?>
                                                </div>
                                            <?php else : ?>
                                                <div class="form-group">
                                                    <?= Html::label('Descripción de Antecedentes Patológicos', 'descripcion_antecedentes') ?>
                                                    <?php
                                                    $contenidoHTML = Html::decode($antecedentePatologico->descripcion_antecedentes);
                                                    echo HtmlPurifier::process($contenidoHTML);
                                                    ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="alert alert-white custom-alert" role="alert">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Peso al nacer, anormalidades perinatales, desarrollo físico y mental, y el esquema básico de vacunación.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('no_patologicos'); ?>

                            <?php $form = ActiveForm::begin(['action' => ['empleado/no-patologicos', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedentes No Patológicos</h2>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>

                                            <?php  } ?>

                                        </div>
                                        <div class="card-body">
                                            <div class="container">
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ACTIVIDAD FISICA</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_ejercicio]', $antecedenteNoPatologico->p_ejercicio, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_ejercicio',
                                                                                'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                            ]) ?>
                                                                            <?= Html::label('¿Realiza ejercicio?', 'p_ejercicio', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="ejercicio-minutos-container">
                                                                        <?= Html::label('Minutos al día', 'p_minutos_x_dia_ejercicio') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_minutos_x_dia_ejercicio]', $antecedenteNoPatologico->p_minutos_x_dia_ejercicio, [
                                                                            'class' => 'form-control',
                                                                            'id' => 'p_minutos_x_dia_ejercicio',
                                                                            'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                        ]) ?>
                                                                    </div>
                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_deporte]', $antecedenteNoPatologico->p_deporte, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_deporte',
                                                                                'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                            ]) ?>
                                                                            <?= Html::label('¿Realiza algún deporte?', 'p_deporte', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-4" id="deporte-cual-container">
                                                                        <?= Html::label('¿Cuál deporte?', 'p_a_deporte') ?>
                                                                        <?= Html::textInput('AntecedenteNoPatologico[p_a_deporte]', $antecedenteNoPatologico->p_a_deporte, [
                                                                            'class' => 'form-control',
                                                                            'id' => 'p_a_deporte',
                                                                            'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                        ]) ?>
                                                                    </div>
                                                                    <div class="col-6 col-sm-4" id="deporte-frecuencia-container">
                                                                        <?= Html::label('Frecuencia con la que practica', 'p_frecuencia_deporte') ?>
                                                                        <?= Html::textInput('AntecedenteNoPatologico[p_frecuencia_deporte]', $antecedenteNoPatologico->p_frecuencia_deporte, [
                                                                            'class' => 'form-control',
                                                                            'id' => 'p_frecuencia_deporte',
                                                                            'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                        ]) ?>
                                                                    </div>
                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-4">
                                                                        <?= Html::label('Horas que duerme por día', 'p_horas_sueño') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_horas_sueño]', $antecedenteNoPatologico->p_horas_sueño, [
                                                                            'class' => 'form-control',
                                                                            'id' => 'p_horas_sueño',
                                                                            'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                        ]) ?>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_dormir_dia]', $antecedenteNoPatologico->p_dormir_dia, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_dormir_dia',
                                                                                'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                            ]) ?>
                                                                            <?= Html::label('¿Duerme durante el día?', 'p_dormir_dia', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->
                                                            <div class="w-100"></div>
                                                            <br>

                                                            <div class="form-group">
                                                                <?= Html::label('Observaciones', 'observacion_actividad_fisica') ?>
                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_actividad_fisica')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],
                                                                        'clientOptions' => [
                                                                            'toolbarInline' => false,
                                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                            'height' => 150,
                                                                            'pluginsEnabled' => [
                                                                                'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                                'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                                'fontSize', 'fullscreen', 'inlineStyle',
                                                                                'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                                'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                            ]
                                                                        ]
                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_actividad_fisica);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="alert alert-white custom-alert" role="alert">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Peso al nacer, anormalidades perinatales, desarrollo físico y mental, y el esquema básico de vacunación.
                                                </div>


                                                <?php
                                                $script = <<< JS
$(document).ready(function() {
    function toggleEjercicioFields() {
        if ($('#p_ejercicio').is(':checked')) {
            $('#ejercicio-minutos-container').show();
        } else {
            $('#ejercicio-minutos-container').hide();
        }
    }

    function toggleDeporteFields() {
        if ($('#p_deporte').is(':checked')) {
            $('#deporte-cual-container').show();
            $('#deporte-frecuencia-container').show();
        } else {
            $('#deporte-cual-container').hide();
            $('#deporte-frecuencia-container').hide();
        }
    }

    // Initial toggle based on the current state
    toggleEjercicioFields();
    toggleDeporteFields();

    // Toggle fields on checkbox change
    $('#p_ejercicio').change(function() {
        toggleEjercicioFields();
    });

    $('#p_deporte').change(function() {
        toggleDeporteFields();
    });
});
JS;
                                                $this->registerJs($script);
                                                ?>



                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>HABITOS ALIMENTICIOS</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <div class="col-6 col-sm-3">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_desayuno]', $antecedenteNoPatologico->p_desayuno, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_desayuno',
                                                                                'disabled' => !$editable // Deshabilitar si no tiene permiso


                                                                            ]) ?>
                                                                            <?= Html::label('¿Desayuna?', 'p_desayuno', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>

                                                                    <div class="col-6 col-sm-6">

                                                                        <?= Html::label('Número de comidas al día', 'p_comidas_x_dia') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_comidas_x_dia]', $antecedenteNoPatologico->p_comidas_x_dia, [
                                                                            'class' => 'form-control', 'disabled' => !$editable // Deshabilitar si no tiene permiso
                                                                        ]) ?>


                                                                    </div>

                                                                    <div class="w-100"></div>

                                                                    <br>
                                                                    <div class="col-6 col-sm-3">

                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_cafe]', $antecedenteNoPatologico->p_cafe, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_cafe',
                                                                                'disabled' => !$editable // Deshabilitar si no tiene permiso

                                                                            ]) ?>
                                                                            <?= Html::label('¿Toma café?', 'p_cafe', ['class' => 'custom-control-label']) ?>
                                                                        </div>



                                                                    </div>

                                                                    <div class="col-6 col-sm-6" id="cafe-x-dia-container">
                                                                        <?= Html::label('Tazas de café al día', 'p_tazas_x_dia') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_tazas_x_dia]', $antecedenteNoPatologico->p_tazas_x_dia, ['class' => 'form-control', 'disabled' => !$editable]) ?>
                                                                    </div>

                                                                    <div class="w-100"></div>

                                                                    <br>
                                                                    <div class="col-6 col-sm-6">

                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_refresco]', $antecedenteNoPatologico->p_refresco, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_refresco',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('¿Toma refresco?', 'p_refresco', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>



                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-3">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_dieta]', $antecedenteNoPatologico->p_dieta, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_dieta',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('¿Sigue alguna dieta?', 'p_dieta', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="info-dieta-container">
                                                                        <div class="form-group">
                                                                            <?= Html::label('Información sobre la dieta', 'p_info_dieta') ?>
                                                                            <?= Html::textarea('AntecedenteNoPatologico[p_info_dieta]', $antecedenteNoPatologico->p_info_dieta, ['class' => 'form-control', 'rows' => 6,  'disabled' => !$editable]) ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="w-100"></div>


                                                            <br>


                                                            <div class="form-group">
                                                                <?= Html::label('Observaciones', 'observacion_comida') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_comida')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_comida);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>




                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                                $script = <<< JS
$(document).ready(function() {
    function toggleCafeFields() {
        if ($('#p_cafe').is(':checked')) {
            $('#cafe-x-dia-container').show();
        } else {
            $('#cafe-x-dia-container').hide();
        }
    }

    function toggleDietaFields() {
        if ($('#p_dieta').is(':checked')) {
            $('#info-dieta-container').show();
           
        } else {
            $('#info-dieta-container').hide();
          
        }
    }

    // Initial toggle based on the current state
    toggleCafeFields();
    toggleDietaFields();

    // Toggle fields on checkbox change
    $('#p_cafe').change(function() {
        toggleCafeFields();
    });

    $('#p_dieta').change(function() {
        toggleDietaFields();
    });
});
JS;
                                                $this->registerJs($script);
                                                ?>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ALCOHOLISMO</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-7">
                                                                <div class="row">
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_alcohol]', $antecedenteNoPatologico->p_alcohol, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_alcohol',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('¿Consume alcohol?', 'p_alcohol', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="consumo-alcohol-container">
                                                                        <!-- Campo dropdown -->
                                                                        <div class="form-group">
                                                                            <?= Html::label('Frecuencia de Consumo de Alcohol', 'p_frecuencia_alcohol') ?>
                                                                            <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_alcohol]', $antecedenteNoPatologico->p_frecuencia_alcohol, [
                                                                                'Casual' => 'Casual',
                                                                                'Moderado' => 'Moderado',
                                                                                'Intenso' => 'Intenso',
                                                                            ], [
                                                                                'class' => 'form-control',
                                                                                'prompt' => 'Seleccione la frecuencia',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                        </div>

                                                                        <?= Html::label('Edad a la que comenzó a béber', 'p_edad_alcoholismo') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_alcoholismo]', $antecedenteNoPatologico->p_edad_alcoholismo, ['class' => 'form-control',  'disabled' => !$editable]) ?>


                                                                        <?= Html::label('Copas de licor/vino al día', 'p_copas_x_dia') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_copas_x_dia]', $antecedenteNoPatologico->p_copas_x_dia, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                                        <?= Html::label('Número de cervezas al día', 'p_cervezas_x_dia') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_cervezas_x_dia]', $antecedenteNoPatologico->p_cervezas_x_dia, ['class' => 'form-control',  'disabled' => !$editable]) ?>
                                                                    </div>



                                                                    <div class="w-100"></div>
                                                                    <br>

                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_ex_alcoholico]', $antecedenteNoPatologico->p_ex_alcoholico, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_ex_alcoholico',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('Ex-alcoholico', 'p_ex_alcoholico', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="ex-alcoholico-container">
                                                                        <?= Html::label('Edad en la que dejo de beber', 'p_edad_fin_alcoholismo') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_alcoholismo]', $antecedenteNoPatologico->p_edad_fin_alcoholismo, ['class' => 'form-control',  'disabled' => !$editable]) ?>



                                                                    </div>





                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="w-100"></div>
                                                            <br>




                                                            <div class="form-group">
                                                                <?= Html::label('Observaciones', 'observacion_alcoholismo') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_alcoholismo')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_alcoholismo);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>


                                                        </div>
                                                    </div>
                                                </div>

                                                <?php
                                                $script = <<< JS
$(document).ready(function() {
    function toggleAlcoholFields() {
        if ($('#p_alcohol').is(':checked')) {
            $('#consumo-alcohol-container').show();
        } else {
            $('#consumo-alcohol-container').hide();
        }
    }

    function toggleExAlcoholicoFields() {
        if ($('#p_ex_alcoholico').is(':checked')) {
            $('#ex-alcoholico-container').show();
           
        } else {
            $('#ex-alcoholico-container').hide();
          
        }
    }

    // Initial toggle based on the current state
    toggleAlcoholFields();
    toggleExAlcoholicoFields();

    // Toggle fields on checkbox change
    $('#p_alcohol').change(function() {
        toggleAlcoholFields();
    });

    $('#p_ex_alcoholico').change(function() {
        toggleExAlcoholicoFields();
    });
});
JS;
                                                $this->registerJs($script);
                                                ?>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>TABAQUISMO</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_fuma]', $antecedenteNoPatologico->p_fuma, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_fuma',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('¿Fúma?', 'p_fuma', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="tabaquismo-container">
                                                                        <!-- Campo dropdown -->
                                                                        <div class="form-group">
                                                                            <?= Html::label('Frecuencia de Consumo de Tabaco', 'p_frecuencia_tabaquismo') ?>
                                                                            <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_tabaquismo]', $antecedenteNoPatologico->p_frecuencia_tabaquismo, [

                                                                                'Casual' => 'Casual',
                                                                                'Moderado' => 'Moderado',
                                                                                'Intenso' => 'Intenso',
                                                                            ], [
                                                                                'class' => 'form-control',
                                                                                'prompt' => 'Seleccione la frecuencia',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                        </div>
                                                                        <?= Html::label('Edad a la que comenzó a fumar', 'p_edad_tabaquismo') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_tabaquismo]', $antecedenteNoPatologico->p_edad_tabaquismo, ['class' => 'form-control', 'disabled' => !$editable]) ?>


                                                                        <?= Html::label('Número de cigarros al día', 'p_no_cigarros_x_dia') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_no_cigarros_x_dia]', $antecedenteNoPatologico->p_no_cigarros_x_dia, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                                    </div>



                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_ex_fumador]', $antecedenteNoPatologico->p_ex_fumador, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_ex_fumador',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('Ex-Fumador', 'p_ex_fumador', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="ex-fumador-container">
                                                                        <?= Html::label('Edad en la que dejo de fumar', 'p_edad_fin_tabaquismo') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_tabaquismo]', $antecedenteNoPatologico->p_edad_fin_tabaquismo, ['class' => 'form-control', 'disabled' => !$editable]) ?>



                                                                    </div>
                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_fumador_pasivo]', $antecedenteNoPatologico->p_fumador_pasivo, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_fumador_pasivo',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('Fumador Pasivo', 'p_fumador_pasivo', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="w-100"></div>
                                                            <br>



                                                            <div class="form-group">
                                                                <?= Html::label('Observaciones', 'observacion_tabaquismo') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_tabaquismo')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_tabaquismo);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <?php
                                                $script = <<< JS
$(document).ready(function() {
    function toggleTabaquismoFields() {
        if ($('#p_fuma').is(':checked')) {
            $('#tabaquismo-container').show();
        } else {
            $('#tabaquismo-container').hide();
        }
    }

    function toggleExFumadorFields() {
        if ($('#p_ex_fumador').is(':checked')) {
            $('#ex-fumador-container').show();
           
        } else {
            $('#ex-fumador-container').hide();
          
        }
    }

    // Initial toggle based on the current state
    toggleTabaquismoFields();
    toggleExFumadorFields();

    // Toggle fields on checkbox change
    $('#p_fuma').change(function() {
        toggleTabaquismoFields();
    });

    $('#p_ex_fumador').change(function() {
        toggleExFumadorFields();
    });
});
JS;
                                                $this->registerJs($script);
                                                ?>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>OTROS</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-6">
                                                                <div class="row">
                                                                    <div class="form-group">
                                                                        <?= Html::label('Religión a la que pertenece', 'religion') ?>
                                                                        <?= Html::dropDownList('AntecedenteNoPatologico[religion]', $antecedenteNoPatologico->religion, [
                                                                            'Ninguna' => 'Ninguna',
                                                                            'Católica' => 'Católica',
                                                                            'Cristiana Evangélica' => 'Cristiana Evangélica',
                                                                            'Testigos de Jehová' => 'Testigos de Jehová',
                                                                            'Mormona' => 'Mormona',
                                                                            'Bautista' => 'Bautista',
                                                                            'Pentecostal' => 'Pentecostal',
                                                                            'Adventista del Séptimo Día' => 'Adventista del Séptimo Día',
                                                                            'Judía' => 'Judía',
                                                                            'Budista' => 'Budista',
                                                                            'Hinduista' => 'Hinduista',
                                                                            'Musulmana' => 'Musulmana',
                                                                            'Ateísta' => 'Ateísta',
                                                                            'Agnóstica' => 'Agnóstica',
                                                                            'Otra' => 'Otra'
                                                                        ], [
                                                                            'class' => 'form-control',         'prompt' => 'Seleccione la religión',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                    </div>

                                                                    <div class="form-group">
                                                                        <?= Html::label('¿Qué actividades realiza en sus horas libres?', 'p_act_dias_libres') ?>
                                                                        <?= Html::textarea('AntecedenteNoPatologico[p_act_dias_libres]', $antecedenteNoPatologico->p_act_dias_libres, ['class' => 'form-control', 'rows' => 5,  'disabled' => !$editable]) ?>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <?= Html::label('¿Pasa por algunas de estas situaciones?', 'p_situaciones') ?>
                                                                        <?= Html::dropDownList('AntecedenteNoPatologico[p_situaciones]', $antecedenteNoPatologico->p_situaciones, [
                                                                            'Ninguna' => 'Ninguna',

                                                                            'Duelo' => 'Duelo',
                                                                            'Embarazos' => 'Embarazos',
                                                                            'Divorcio' => 'Divorcio',
                                                                        ], [
                                                                            'class' => 'form-control',         'prompt' => 'Seleccione una opción',  'disabled' => !$editable
                                                                        ]) ?>
                                                                    </div>
                                                                    <div class="form-group">
                                                                        <?= Html::label('Tipo de Sangre', 'tipo_sangre') ?>
                                                                        <?= Html::dropDownList('AntecedenteNoPatologico[tipo_sangre]', $antecedenteNoPatologico->tipo_sangre, [

                                                                            'A+' => 'A+',
                                                                            'B+' => 'B+',
                                                                            'O+' => 'O+',
                                                                            'A-' => 'A-',
                                                                            'B-' => 'B-',
                                                                            'O-' => 'O-',
                                                                            'AB+' => 'AB+',
                                                                            'AB-' => 'AB-',
                                                                        ], [
                                                                            'class' => 'form-control',         'prompt' => 'Seleccione el tipo de sangre',  'disabled' => !$editable
                                                                        ]) ?>
                                                                    </div>



                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->




                                                            <div class="form-group">
                                                                <?= Html::label('Descripción de su vivienda (Tiene mascotas, Recursos del hogar, Etc.)', 'datos_vivienda') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'datos_vivienda')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->datos_vivienda);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>




                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CONSUMO DE DROGAS</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">
                                                            <!-- Columna izquierda con los campos -->
                                                            <div class="col-md-8">
                                                                <div class="row">
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_drogas]', $antecedenteNoPatologico->p_drogas, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_drogas',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('¿Consume algún tipo de droga?', 'p_drogas', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>



                                                                    <div class="col-6 col-sm-6" id="droga-container">
                                                                        <!-- Campo dropdown -->
                                                                        <div class="form-group">
                                                                            <?= Html::label('Frecuencia de su consumo', 'p_frecuencia_droga') ?>
                                                                            <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_droga]', $antecedenteNoPatologico->p_frecuencia_droga, [

                                                                                'Casual' => 'Casual',
                                                                                'Moderado' => 'Moderado',
                                                                                'Intenso' => 'Intenso',
                                                                            ], [
                                                                                'class' => 'form-control',         'prompt' => 'Seleccione la frecuencia', 'disabled' => !$editable
                                                                            ]) ?>
                                                                        </div>
                                                                        <?= Html::label('¿A qué edad se inicio el consumo?', 'p_edad_droga') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_droga]', $antecedenteNoPatologico->p_edad_droga, ['class' => 'form-control', 'disabled' => !$editable]) ?>


                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_droga_intravenosa]', $antecedenteNoPatologico->p_droga_intravenosa, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_droga_intravenosa', 'disabled' => !$editable
                                                                            ]) ?>
                                                                            <br>
                                                                            <?= Html::label('¿Usa drogas intravenosa?', 'p_droga_intravenosa', ['class' => 'custom-control-label']) ?>
                                                                        </div>

                                                                    </div>

                                                                    <div class="w-100"></div>
                                                                    <br>
                                                                    <div class="col-6 col-sm-4">
                                                                        <div class="custom-control custom-checkbox">
                                                                            <?= Html::checkbox('AntecedenteNoPatologico[p_ex_adicto]', $antecedenteNoPatologico->p_ex_adicto, [
                                                                                'class' => 'custom-control-input',
                                                                                'id' => 'p_ex_adicto',
                                                                                'disabled' => !$editable
                                                                            ]) ?>
                                                                            <?= Html::label('Ex-Adicto', 'p_ex_adicto', ['class' => 'custom-control-label']) ?>
                                                                        </div>
                                                                    </div>
                                                                    <div class="col-6 col-sm-6" id="ex-adicto-container">
                                                                        <?= Html::label('¿A qué edad dejo de consumir?', 'p_edad_fin_droga') ?>
                                                                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_droga]', $antecedenteNoPatologico->p_edad_fin_droga, ['class' => 'form-control', 'disabled' => !$editable]) ?>



                                                                    </div>



                                                                </div>
                                                            </div>
                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="w-100"></div>
                                                            <br>


                                                            <div class="form-group">
                                                                <?= Html::label('Observaciones', 'observacion_droga') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_droga')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'content'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_droga);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>



                                                        </div>
                                                    </div>
                                                </div>


                                                <?php
                                                $script = <<< JS
$(document).ready(function() {
    function toggleDrogasFields() {
        if ($('#p_drogas').is(':checked')) {
            $('#droga-container').show();
        } else {
            $('#droga-container').hide();
        }
    }

    function toggleExAdictoFields() {
        if ($('#p_ex_adicto').is(':checked')) {
            $('#ex-adicto-container').show();
           
        } else {
            $('#ex-adicto-container').hide();
          
        }
    }

    // Initial toggle based on the current state
    toggleDrogasFields();
    toggleExAdictoFields();

    // Toggle fields on checkbox change
    $('#p_drogas').change(function() {
        toggleDrogasFields();
    });

    $('#p_ex_adicto').change(function() {
        toggleExAdictoFields();
    });
});
JS;
                                                $this->registerJs($script);
                                                ?>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>OBSERVACION GENEREAL / OTRAS OBSERVACIONES</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group">
                                                                <?= Html::label('Observación General', 'observacion_general') ?>

                                                                <?php
                                                                if ($editable) {
                                                                    echo $form->field($antecedenteNoPatologico, 'observacion_general')->widget(FroalaEditorWidget::className(), [
                                                                        'clientOptions' => [
                                                                            'toolbarInline' => false,
                                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                            'height' => 300,
                                                                            'pluginsEnabled' => [
                                                                                'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                                'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                                'fontSize', 'fullscreen', 'inlineStyle',
                                                                                'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                                'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                            ]
                                                                        ]
                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($antecedenteNoPatologico->observacion_general);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>


                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>
                                                    <div class="form-group">

                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>
                                                <?php } ?>



                                            </div>



                                            <br>

                                            <!-- Agrega aquí el resto de los campos siguiendo el mismo patrón -->

                                        </div>

                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>

                            <?php $this->endBlock(); ?>

                            <?php $this->beginBlock('perinatales'); ?>
                            <?php $form = ActiveForm::begin(['action' => ['antecedente-perinatal', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedente Perinatal</h2>
                                            <i class="fa fa-medkit fa-2x"></i>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="container">


                                                <div class="row">
                                                    <!-- Columna izquierda con los campos -->




                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Hora de Nacimiento', 'p_hora_nacimiento') ?>
                                                        <?= Html::input('time', 'AntecedentePerinatal[p_hora_nacimiento]', $AntecedentePerinatal->p_hora_nacimiento, ['class' => 'form-control', 'disabled' => !$editable]) ?>
                                                    </div>
                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('No. de Gestación', 'p_no_gestacion') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_no_gestacion]', $AntecedentePerinatal->p_no_gestacion, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Edad Gestacional', 'p_edad_gestacional') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_edad_gestacional]', $AntecedentePerinatal->p_edad_gestacional, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2 bg-white rounded p-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedentePerinatal[p_parto]', $AntecedentePerinatal->p_parto, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_parto',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Parto', 'p_parto', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedentePerinatal[p_cesarea]', $AntecedentePerinatal->p_cesarea, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_cesarea',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Cesarea', 'p_cesarea', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>


                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-8">
                                                        <?= Html::label('Sitio de atención del parto', 'p_sitio_parto') ?>
                                                        <?= Html::textInput('AntecedentePerinatal[p_sitio_parto]', $modelAntecedentePerinatal->p_sitio_parto, ['class' => 'form-control', 'id' => 'p_sitio_parto', 'disabled' => !$editable]) ?>
                                                    </div>


                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-4">
                                                        <?= Html::label('Peso al nacer', 'p_peso_nacer') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_peso_nacer]', $modelAntecedentePerinatal->p_peso_nacer, [
                                                            'class' => 'form-control',
                                                            'id' => 'p_peso_nacer',
                                                            'step' => '0.01', // Define el paso para permitir decimales
                                                            'disabled' => !$editable
                                                        ]) ?>
                                                    </div>
                                                    <div class="col-6 col-sm-4">
                                                        <?= Html::label('Talla', 'p_talla') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_talla]', $modelAntecedentePerinatal->p_talla, [
                                                            'class' => 'form-control',
                                                            'id' => 'p_talla',
                                                            'step' => '0.01', // Define el paso para permitir decimales
                                                            'disabled' => !$editable
                                                        ]) ?>
                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>

                                                    <div class="col-6 col-sm-4">
                                                        <h4>Perimetros (cm)</h4>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Cefálico', 'p_cefalico') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_cefalico]', $modelAntecedentePerinatal->p_cefalico, [
                                                            'class' => 'form-control',
                                                            'id' => 'p_cefalico',
                                                            'step' => '0.01', // Define el paso para permitir decimales
                                                            'disabled' => !$editable
                                                        ]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Toracico', 'p_toracico') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_toracico]', $modelAntecedentePerinatal->p_toracico, [
                                                            'class' => 'form-control',
                                                            'id' => 'p_toracico',
                                                            'step' => '0.01', // Define el paso para permitir decimales
                                                            'disabled' => !$editable
                                                        ]) ?>
                                                    </div>
                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Abdominal', 'p_abdominal') ?>
                                                        <?= Html::input('number', 'AntecedentePerinatal[p_abdominal]', $modelAntecedentePerinatal->p_abdominal, [
                                                            'class' => 'form-control',
                                                            'id' => 'p_abdominal',
                                                            'step' => '0.01', // Define el paso para permitir decimales
                                                            'disabled' => !$editable
                                                        ]) ?>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="dropdown-divider"></div>

                                                    <br>
                                                    <div class="col-6 col-sm-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedentePerinatal[test]', $AntecedentePerinatal->test, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'test',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('¿Cuenta con evaluaciones neonatales?', 'test', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <br>
                                                    <br>
                                                    <div class="row" id="test-container">
                                                        <div class="col-6 col-sm-2">
                                                            <?= Html::label('Apgar', 'p_apgar') ?>
                                                            <?= Html::input('number', 'AntecedentePerinatal[p_apgar]', $AntecedentePerinatal->p_apgar, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                        </div>
                                                        <div class="col-6 col-sm-2">
                                                            <?= Html::label('Ballard', 'p_ballard') ?>
                                                            <?= Html::input('number', 'AntecedentePerinatal[p_ballard]', $AntecedentePerinatal->p_ballard, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                        </div>

                                                        <div class="col-6 col-sm-2">
                                                            <?= Html::label('Silverman', 'p_silverman') ?>
                                                            <?= Html::input('number', 'AntecedentePerinatal[p_silverman]', $AntecedentePerinatal->p_silverman, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                        </div>
                                                        <div class="col-6 col-sm-2">
                                                            <?= Html::label('Capurro', 'p_capurro') ?>
                                                            <?= Html::input('number', 'AntecedentePerinatal[p_capurro]', $AntecedentePerinatal->p_capurro, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                        </div>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-8">
                                                        <div class="form-group">
                                                            <?= Html::label('Complicaciones', 'p_complicacion') ?>
                                                            <?= Html::textarea('AntecedentePerinatal[p_complicacion]', $AntecedentePerinatal->p_complicacion, ['class' => 'form-control', 'rows' => 2, 'id' => 'p_complicacion', 'disabled' => !$editable]) ?>
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-4">
                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedentePerinatal[p_anestesia]', $AntecedentePerinatal->p_anestesia, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_anestesia',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Anestesia', 'p_anestesia', ['class' => 'custom-control-label', 'disabled' => !$editable]) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-8" id="anestesia-container">
                                                        <div class="form-group">
                                                            <?= Html::label('Especifique', 'p_especifique_anestecia') ?>
                                                            <?= Html::textarea('AntecedentePerinatal[p_especifique_anestecia]', $AntecedentePerinatal->p_especifique_anestecia, ['class' => 'form-control', 'rows' => 2, 'id' => 'p_especifique_anestecia', 'disabled' => !$editable]) ?>
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="row bg-white rounded p-4">
                                                        <div class="col-6 col-sm-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <?= Html::checkbox('AntecedentePerinatal[p_apnea_neonatal]', $AntecedentePerinatal->p_apnea_neonatal, [
                                                                    'class' => 'custom-control-input',
                                                                    'id' => 'p_apnea_neonatal',
                                                                    'disabled' => !$editable
                                                                ]) ?>
                                                                <?= Html::label('Apnea Neonatal', 'p_apnea_neonatal', ['class' => 'custom-control-label', 'disabled' => !$editable]) ?>
                                                            </div>

                                                        </div>
                                                        <div class="col-6 col-sm-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <?= Html::checkbox('AntecedentePerinatal[p_cianosis]', $AntecedentePerinatal->p_cianosis, [
                                                                    'class' => 'custom-control-input',
                                                                    'id' => 'p_cianosis',
                                                                    'disabled' => !$editable
                                                                ]) ?>
                                                                <?= Html::label('Cianosis', 'p_cianosis', ['class' => 'custom-control-label']) ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-sm-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <?= Html::checkbox('AntecedentePerinatal[p_hemorragias]', $AntecedentePerinatal->p_hemorragias, [
                                                                    'class' => 'custom-control-input',
                                                                    'id' => 'p_hemorragias',
                                                                    'disabled' => !$editable
                                                                ]) ?>
                                                                <?= Html::label('Hemorragias', 'p_hemorragias', ['class' => 'custom-control-label']) ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-6 col-sm-2">
                                                            <div class="custom-control custom-checkbox">
                                                                <?= Html::checkbox('AntecedentePerinatal[p_convulsiones]', $AntecedentePerinatal->p_convulsiones, [
                                                                    'class' => 'custom-control-input',
                                                                    'id' => 'p_convulsiones',
                                                                    'disabled' => !$editable
                                                                ]) ?>
                                                                <?= Html::label('Convulsiones', 'p_convulsiones', ['class' => 'custom-control-label']) ?>
                                                            </div>
                                                        </div>

                                                        <div class="col-6 col-sm-2 ">
                                                            <div class="custom-control custom-checkbox">
                                                                <?= Html::checkbox('AntecedentePerinatal[p_ictericia]', $AntecedentePerinatal->p_ictericia, [
                                                                    'class' => 'custom-control-input',
                                                                    'id' => 'p_ictericia',
                                                                    'disabled' => !$editable
                                                                ]) ?>
                                                                <?= Html::label('Ictericia', 'p_ictericia', ['class' => 'custom-control-label']) ?>
                                                            </div>
                                                        </div>
                                                    </div>




                                                </div>
                                                <!-- Columna derecha con el textarea -->
                                                <br>





                                                <div class="form-group">
                                                    <?= Html::label('Observación General') ?>


                                                    <?php

                                                    if ($editable) {
                                                        echo $form->field($AntecedentePerinatal, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                            'clientOptions' => [
                                                                'toolbarInline' => false,
                                                                'theme' => 'royal', // optional: dark, red, gray, royal
                                                                'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                'height' => 300,
                                                                'pluginsEnabled' => [
                                                                    'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                    'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                    'fontSize', 'fullscreen', 'inlineStyle',
                                                                    'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                    'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                ]
                                                            ]
                                                        ])->label(false);
                                                    } else {
                                                        // Si no tiene permiso, mostrar el texto plano
                                                        $htmlcont = Html::decode($AntecedentePerinatal->observacion);
                                                        echo HtmlPurifier::process($htmlcont);
                                                    }
                                                    ?>
                                                </div>



                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>

                                                <?php } ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>


                            <?php ActiveForm::end(); ?>
                            <?php
                            $script = <<< JS
$(document).ready(function() {
    function toggleTestFields() {
        if ($('#test').is(':checked')) {
            $('#test-container').show();
        } else {
            $('#test-container').hide();
        }
    }

    function toggleAnestesiaFields() {
        if ($('#p_anestesia').is(':checked')) {
            $('#anestesia-container').show();
           
        } else {
            $('#anestesia-container').hide();
          
        }
    }

    // Initial toggle based on the current state
    toggleTestFields();
    toggleAnestesiaFields();

    // Toggle fields on checkbox change
    $('#test').change(function() {
        toggleTestFields();
    });

    $('#p_anestesia').change(function() {
        toggleAnestesiaFields();
    });
});
JS;
                            $this->registerJs($script);
                            ?>

                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('ginecologicos'); ?>
                            <?php $form = ActiveForm::begin(['action' => ['antecedente-ginecologico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedente Ginecologico</h2>
                                            <i class="fa fa-medkit fa-2x"></i>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="container">


                                                <div class="row">
                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Menarca', 'p_menarca') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_menarca]', $AntecedenteGinecologico->p_menarca, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('Menopausia', 'p_menopausia') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_menopausia]', $AntecedenteGinecologico->p_menopausia, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('F.U.M', 'p_f_u_m') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_f_u_m]', $AntecedenteGinecologico->p_f_u_m, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-2">
                                                        <?= Html::label('F.U. Citología', 'p_f_u_m') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_f_u_citologia]', $AntecedenteGinecologico->p_f_u_citologia, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>





                                                    <div class="w-100"></div>

                                                    <br>
                                                    <hr class="solid">
                                                    <div class="col-6 col-sm-4">
                                                        <h4>Alteraciones de la menstruación</h4>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Frecuencia', 'p_alteracion_frecuencia') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_frecuencia]', $AntecedenteGinecologico->p_alteracion_frecuencia, [

                                                                'Amenorrea' => 'Amenorrea',
                                                                'Polimenorrea' => 'Polimenorrea',
                                                                'Oligomenorrea' => 'Oligomenorrea',
                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>
                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Duración', 'p_alteracion_duracion') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_duracion]', $AntecedenteGinecologico->p_alteracion_duracion, [

                                                                'Menometrorragia' => 'Menometrorragia',

                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>

                                                    <div class="col-6 col-sm-3">

                                                        <div class="form-group">
                                                            <?= Html::label('de Cantidad', 'p_alteracion_cantidad') ?>
                                                            <?= Html::dropDownList('AntecedenteGinecologico[p_alteracion_cantidad]', $AntecedenteGinecologico->p_alteracion_cantidad, [

                                                                'Hipermenorrea' => 'Hipermenorrea',
                                                                'Hipomenorrea' => 'Hipomenorrea',
                                                            ], [
                                                                'class' => 'form-control',         'prompt' => 'Seleccione si tiene alguna', 'disabled' => !$editable
                                                            ]) ?>
                                                        </div>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <hr class="solid">

                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Inicio vida sexual', 'p_inicio_vida_s') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_inicio_vida_s]', $AntecedenteGinecologico->p_inicio_vida_s, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-2 ">
                                                        <?= Html::label('Número de parejas', 'p_no_parejas') ?>
                                                        <?= Html::input('number', 'AntecedenteGinecologico[p_no_parejas]', $AntecedenteGinecologico->p_no_parejas, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>

                                                    <div class="col-6 bg-white rounded p-4">
                                                        <div class="list-container" style="max-height: 150px; overflow-y: auto;">
                                                            <ul class="list-unstyled">
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vaginits]', $AntecedenteGinecologico->p_vaginits, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vaginits',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Vaginitis', 'p_vaginits', ['class' => 'custom-control-label',]) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_cervicitis_mucopurulenta]', $AntecedenteGinecologico->p_cervicitis_mucopurulenta, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_cervicitis_mucopurulenta',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Cervicitis Mucopurulenta', 'p_cervicitis_mucopurulenta', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_chancroide]', $AntecedenteGinecologico->p_chancroide, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_chancroide',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Chancroide', 'p_chancroide', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_clamidia]', $AntecedenteGinecologico->p_clamidia, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_clamidia',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Clamidia', 'p_clamidia', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_eip]', $AntecedenteGinecologico->p_eip, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_eip',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Enfermedad Inflamatoria Pélvica (E.I.P.)', 'p_eip', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_gonorrea]', $AntecedenteGinecologico->p_gonorrea, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_gonorrea',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Gonorrea', 'p_gonorrea', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_hepatitis]', $AntecedenteGinecologico->p_hepatitis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_hepatitis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Hepatitis', 'p_hepatitis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_herpes]', $AntecedenteGinecologico->p_herpes, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_herpes',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Herpes', 'p_herpes', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_lgv]', $AntecedenteGinecologico->p_lgv, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_lgv',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Linfogranuloma venéreo (LGV)', 'p_lgv', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_molusco_cont]', $AntecedenteGinecologico->p_molusco_cont, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_molusco_cont',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Molusco Contagioso', 'p_molusco_cont', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_ladillas]', $AntecedenteGinecologico->p_ladillas, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_ladillas',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Piojos "ladillas" pubicos', 'p_ladillas', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_sarna]', $AntecedenteGinecologico->p_sarna, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_sarna',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Sarna', 'p_sarna', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_sifilis]', $AntecedenteGinecologico->p_sifilis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_sifilis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Sifilis', 'p_sifilis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_tricomoniasis]', $AntecedenteGinecologico->p_tricomoniasis, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_tricomoniasis',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Tricomoniasis', 'p_tricomoniasis', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vb]', $AntecedenteGinecologico->p_vb, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vb',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Vaginosis Bacteriana', 'p_vb', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>

                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vih]', $AntecedenteGinecologico->p_vih, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vih',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('VIH', 'p_vih', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                                <li>
                                                                    <div class="custom-control custom-checkbox">
                                                                        <?= Html::checkbox('AntecedenteGinecologico[p_vph]', $AntecedenteGinecologico->p_vph, [
                                                                            'class' => 'custom-control-input',
                                                                            'id' => 'p_vph',
                                                                            'disabled' => !$editable
                                                                        ]) ?>
                                                                        <?= Html::label('Virus del papiloma humano (VPH)', 'p_vph', ['class' => 'custom-control-label']) ?>
                                                                    </div>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-4">
                                                        <h4>Anticoncepción</h4>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <div class="col-6 col-sm-6">
                                                        <?= Html::label('Tipo', 'p_tipo_anticoncepcion') ?>
                                                        <?= Html::textInput('AntecedenteGinecologico[p_tipo_anticoncepcion]', $modelAntecedenteGinecologico->p_tipo_anticoncepcion, ['class' => 'form-control', 'id' => 'p_tipo_anticoncepcion', 'disabled' => !$editable]) ?>
                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3">
                                                        <?= Html::label('Inicio', 'p_inicio_anticoncepcion') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_inicio_anticoncepcion]', $AntecedenteGinecologico->p_inicio_anticoncepcion, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3">
                                                        <?= Html::label('Suspensión', 'p_suspension_anticoncepcion') ?>
                                                        <?= Html::input('date', 'AntecedenteGinecologico[p_suspension_anticoncepcion]', $AntecedenteGinecologico->p_suspension_anticoncepcion, ['class' => 'form-control', 'disabled' => !$editable]) ?>

                                                    </div>




                                                </div>
                                                <!-- Columna derecha con el textarea -->
                                                <br>
                                                <hr class="solid">





                                                <div class="form-group">
                                                    <?= Html::label('Observación General') ?>


                                                    <?php

                                                    if ($editable) {
                                                        echo $form->field($AntecedenteGinecologico, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                            'clientOptions' => [
                                                                'toolbarInline' => false,
                                                                'theme' => 'royal', // optional: dark, red, gray, royal
                                                                'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                'height' => 300,
                                                                'pluginsEnabled' => [
                                                                    'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                    'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                    'fontSize', 'fullscreen', 'inlineStyle',
                                                                    'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                    'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                ]
                                                            ]
                                                        ])->label(false);
                                                    } else {
                                                        // Si no tiene permiso, mostrar el texto plano
                                                        $htmlcont = Html::decode($AntecedenteGinecologico->observacion);
                                                        echo HtmlPurifier::process($htmlcont);
                                                    }
                                                    ?>
                                                </div>

                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>


                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('obstrecticos'); ?>
                            <?php $form = ActiveForm::begin(['action' => ['antecedente-obstrectico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedente Obstrectico</h2>
                                            <i class="fa fa-medkit fa-2x"></i>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="container">


                                                <div class="row">
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Fecha probable de parto.', 'p_f_p_p') ?>
                                                        <?= Html::input('date', 'AntecedenteObstrectico[p_f_p_p]', $AntecedenteObstrectico->p_f_p_p, ['class' => 'form-control',  'disabled' => !$editable]) ?>



                                                    </div>
                                                    <!-- Columna derecha con el textarea -->
                                                    <div class="w-100"></div>

                                                    <br>
                                                    <hr class="solid">

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Gestación', 'p_gestacion') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_gestacion]', $AntecedenteObstrectico->p_gestacion, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Aborto', 'p_aborto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_aborto]', $AntecedenteObstrectico->p_aborto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Parto', 'p_parto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_parto]', $AntecedenteObstrectico->p_parto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Cesarea', 'p_cesarea') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_cesarea]', $AntecedenteObstrectico->p_cesarea, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Nacidos vivos', 'p_nacidos_vivo') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_nacidos_vivo]', $AntecedenteObstrectico->p_nacidos_vivo, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Viven', 'p_viven') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_viven]', $AntecedenteObstrectico->p_viven, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Nacidos Muertos', 'p_nacidos_muerto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_nacidos_muerto]', $AntecedenteObstrectico->p_nacidos_muerto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Muerto - 1ra semana', 'p_muerto_primera_semana') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_mmuerto_primera_semana]', $AntecedenteObstrectico->p_muerto_primera_semana, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3 ">

                                                    </div>

                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Muerto despues - 1ra semana', 'p_muerto_despues_semana') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_mmuerto_despues_semana]', $AntecedenteObstrectico->p_muerto_despues_semana, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>

                                                    <br>




                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_intergenesia]', $AntecedenteObstrectico->p_intergenesia, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_intergenesia',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Intergenesia', 'p_intergenesia', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_malformaciones]', $AntecedenteObstrectico->p_malformaciones, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_malformaciones',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Malformaciones', 'p_malformaciones', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_atencion_prenatal]', $AntecedenteObstrectico->p_atencion_prenatal, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_atencion_prenatal',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Atención prenatal', 'p_atencion_prenatal', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_parto_prematuro]', $AntecedenteObstrectico->p_parto_prematuro, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_parto_prematuro',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Parto prematuro', 'p_parto_prematuro', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_isoinmunizacion]', $AntecedenteObstrectico->p_isoinmunizacion, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_isoinmunizacion',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Isoinmunización', 'p_isoinmunizacion', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>


                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-8 ">

                                                        <div class="form-group">
                                                            <?= Html::label('Medicación Gestacional', 'p_medicacion_gestacional') ?>
                                                            <?= Html::textarea('AntecedenteObstrectico[p_medicacion_gestacional]', $AntecedenteObstrectico->p_medicacion_gestacional, ['class' => 'form-control', 'rows' => 2, 'id' => 'p_medicacion_gestacional',  'disabled' => !$editable]) ?>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <?= Html::label('Observación General') ?>


                                                    <?php

                                                    if ($editable) {
                                                        echo $form->field($AntecedenteObstrectico, 'observacion')->widget(FroalaEditorWidget::className(), [
                                                            'clientOptions' => [
                                                                'toolbarInline' => false,
                                                                'theme' => 'royal', // optional: dark, red, gray, royal
                                                                'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                'height' => 300,
                                                                'pluginsEnabled' => [
                                                                    'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                    'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                    'fontSize', 'fullscreen', 'inlineStyle',
                                                                    'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                    'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                ]
                                                            ]
                                                        ])->label(false);
                                                    } else {
                                                        // Si no tiene permiso, mostrar el texto plano
                                                        $htmlcont = Html::decode($AntecedenteObstrectico->observacion);
                                                        echo HtmlPurifier::process($htmlcont);
                                                    }
                                                    ?>
                                                </div>


                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success fa-lg']) ?>
                                                    </div>

                                                <?php } ?>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                            <?php $this->endBlock(); ?>








                            <!-- TABS EXPEDIENTE MEDICO / ANTECEDENTES SUBTAB -->

                            <?php echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => [
                                    [
                                        'label' => 'Hereditarios',
                                        'content' => $this->blocks['hereditarios'],
                                        //'active' => true,
                                        'options' => [
                                            'id' => 'hereditarios',
                                        ],


                                    ],
                                    [
                                        'label' => 'Patologicos',
                                        'content' => $this->blocks['patologicos'],

                                        'options' => [
                                            'id' => 'patologicos',
                                        ],


                                    ],
                                    [
                                        'label' => 'No Patologicos',
                                        'content' => $this->blocks['no_patologicos'],

                                        'options' => [
                                            'id' => 'nopatologicos',
                                        ],


                                    ],
                                    [
                                        'label' => 'Perinatales',
                                        'content' => $this->blocks['perinatales'],

                                        'options' => [
                                            'id' => 'perinatales',
                                        ],


                                    ],

                                    [
                                        'label' => 'Ginecologicos',
                                        'content' => $this->blocks['ginecologicos'],

                                        'options' => [
                                            'id' => 'ginecologicos',
                                        ],


                                    ],

                                    [
                                        'label' => 'Obstrecticos',
                                        'content' => $this->blocks['obstrecticos'],

                                        'options' => [
                                            'id' => 'obstrecticos',
                                        ],


                                    ],




                                ],
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_CENTER,
                                //     'bordered'=>true,
                                'encodeLabels' => false


                            ]);

                            ?>



                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('alergias'); ?>
                            <div class="row">
                                <?php $form = ActiveForm::begin(['action' => ['alergia', 'id' => $model->id]]); ?>

                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Alergias</h2>
                                            <i class="fa fa-medkit fa-2x"></i>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="container">


                                                <div class="row">




                                                    <div class="form-group">
                                                        <?= Html::label('Observación General') ?>


                                                        <?php

                                                        if ($editable) {
                                                            echo $form->field($Alergia, 'p_alergia')->widget(FroalaEditorWidget::className(), [
                                                                'clientOptions' => [
                                                                    'toolbarInline' => false,
                                                                    'theme' => 'royal', // optional: dark, red, gray, royal
                                                                    'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                    'height' => 300,
                                                                    'pluginsEnabled' => [
                                                                        'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                        'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                        'fontSize', 'fullscreen', 'inlineStyle',
                                                                        'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                        'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                    ]
                                                                ]
                                                            ])->label(false);
                                                        } else {
                                                            // Si no tiene permiso, mostrar el texto plano
                                                            $htmlcont = Html::decode($Alergia->p_alergia);
                                                            echo HtmlPurifier::process($htmlcont);
                                                        }
                                                        ?>
                                                    </div>




                                                </div>
                                                <div class="alert alert-white custom-alert" role="alert">
                                                    <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Escriba NEGADAS o deje en blanco si el paciente no tiene nignuna alergia.
                                                </div>
                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>
                                                <?php } ?>
                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                            <?php $this->endBlock(); ?>



                            <?php $this->beginBlock('exploracion_fisica'); ?>


                            <?php $form = ActiveForm::begin(['action' => ['exploracion-fisica', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Exploracion Fisica</h2>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>
                                        </div>
                                        <div class="card-body">
                                            <div class="container">

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>HABITUS EXTERIOR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>

                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_habitus_exterior')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],
                                                                        'clientOptions' => [
                                                                            'toolbarInline' => false,
                                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                            'height' => 200,
                                                                            'pluginsEnabled' => [
                                                                                'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                                'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                                'fontSize', 'fullscreen', 'inlineStyle',
                                                                                'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                                'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                                            ]
                                                                        ]
                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_habitus_exterior);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Edad aparente, biotipo, estado de conciencia, orientación en tiempo, espacio y persona, facies, postura, marcha, movimientos anormales, estado y color de tegumentos, actitud.
                                                        </div>
                                                    </div>




                                                </div>
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CABEZA</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_cabeza')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_cabeza);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Craneo: tipo, forma, volumen, cabello, exostosis, hundimientos, fontanelas.

                                                            Cara: tinte ojos, reflejos pupilares,fondo de ojos, conjuntivas, cornea.

                                                            Nariz: obstruccion, mucosa. Boca: desviacion de las comisuras, aliento, labios y paladar.

                                                            Oidos: conducto auditivo, y timpano.

                                                            Faringe: uvula, secreciones, amigdalas, adenoides.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CUELLO</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->




                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_cuello')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_cuello);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>


                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Forma, movilidad, contracturas; arterias: pulsos, soplos venosos, fremitos. Traquea, tiroides, cadenas linfaticas, huecos supraclaviculares.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>TORAX</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_torax')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_torax);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspección, forma, volumen, simetría, tiros, red venosa y puntos dolorosos, campos pulmonares, movimientos de amplexion y amplexacion, vibraciones vocales, ganglios satélites y nodulos.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ABDOMEN</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_abdomen')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_abdomen);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN GINECOLOGICA</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_exploración_ginecologica')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_exploración_ginecologica);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Exploracion Manual: utero, forma, tamaño, volumen, posicion, consistencia, masas tumorales, fondos de saco y adeherencias.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN DE GENITALES</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_genitales')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_genitales);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspeccion, madurez, tacto vaginal, tacto rectal, secreciones, vesiculas, y ulceras, verrugas, condilomas u otras lesiones.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>COLUMNA VERTEBRAL</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_columna_vertebral')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_columna_vertebral);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspección, posición, dolor, deformaciones, disfunción, alineación, función, simetría, movimiento, flexión, extensión, rotación y lateralidad, curvaturas, lordosis, cifosis, escoliosis, masas musculares, lesiones cutáneas.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXTREMIDADES</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_extremidades')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_extremidades);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Forma, volumen, piel, unas, dedos, articulaciones, tono, fuerza, reflejos tendinosos, movimientos, pulsos arteriales, simetría, amplitud, frecuencia, ritmo, arcos de movilidad, varices, ulceras, flebitis, micosis, marcha, edemas, reflejos: rotuliano, aquiliano y plantar.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN NEUROLOGICA</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_exploracion_neurologica')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_exploracion_neurologica);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Razonamiento, atencion, memoria, ansiedad, depresion, alucinaciones, postura corporal, funciones motoras, movimientos corporales voluntarios e involuntarios, paresias, paralisis, marcha, equilibrio, pares craneales, funcion sensorial.
                                                        </div>
                                                    </div>




                                                </div>












                                                <br>
                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>

                                                <?php } ?>

                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>

                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('interrogatorio_medico'); ?>


                            <?php $form = ActiveForm::begin(['action' => ['interrogatorio-medico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Interrogatorio Medico</h2>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <div class="card-body">
                                            <div class="container">

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CARDIOVASCULAR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_cardiovascular')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_cardiovascular);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Antecedentes de cardiopatías, disnea, tos, hemoptisis, bronquitis frecuente, lipotimias, vértigos, insuficiencia arterial y venosa, sincope, fatiga, palpitaciones, dolor precordial, encuclillamiento, edemas, ascitis, cianosis, estasis venosa, varices.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>DIGESTIVO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_digestivo')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_digestivo);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Apetito, masticación, disfagia, pirosis, regurgitación, distención abdominal, dolor, vomito, hematemesis, evacuaciones diarreicas, melena, pujo y tenesmo, constipación, ictericia, intolerancia a alimentos.
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ENDOCRINO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_endocrino')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_endocrino);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Crecimiento y estatura, perturbaciones somaticas, caracteres sexuales, sensibilidad al calor y al frio y faneras, exoftalmos,diabetes, acne.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>HEMOLINFATICO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_hemolinfatico')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_hemolinfatico);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Anemias, hemolisis, tendencia a hemorragia, adenopatias, menor resistencia a infecciones.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>MAMAS</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_mamas')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_mamas);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Sin descripción.
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- aqui -->


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>PIEL Y ANEXOS</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_piel_anexos')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_piel_anexos);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Mucosas, piel, pelo, unas, prurito, cambios de coloracion, alopecia, erupciones, infestaciones, micosis.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>REPRODUCTOR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_reproductor')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_reproductor);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>



                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Alteraciones menstruales, dolor pelvico, colporrea patologica, alteraciones de la libido, patologia obstetrica. Alteraciones testiculares, trastornos en la ereccion y/o eyaculacion, alteraciones de la libido.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>RESPIRATORIO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_respiratorio')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_respiratorio);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Obstuccion nasal, disfonia, tos, dolor, expectoracion, disnea, cianosis, hemoptisis.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>SISTEMA NERVIOSO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_sistema_nervioso')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_sistema_nervioso);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Perdida del conocimiento, paralisis, paresias, temblores, coordinacion, convulsiones atrofias, hipo o hiperestesias, cefaleas, algias, vision, audicion, equilibrio, olfato, gusto, sueno, alteraciones de la personalidad, depresion, compulsion, exitacion, atencion, atencion, memoria, cambios en la conducta, afectividad, nerviosismo, angustia.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>SISTEMAS GENERALES</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_sistemas_generales')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_sistemas_generales);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Fiebre, escalofrio, diaforesis, astenia, adinamia, anorexia y variaciones de peso.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>URINARIO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_urinario')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_urinario);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Disuria, polaquiuria, tenesmo vesical, hematuria piuria, incontinencia, dolor lumbar, expulsion de calculos, secrecion uretral.
                                                        </div>
                                                    </div>

                                                </div>
                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                    </div>

                                                <?php } ?>
                                            </div>



                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>

                            <?php $this->endBlock(); ?>



                            <!-- TABS PRINCIPAL EXPEDIENTE MEDICO-->
                            <?php

                            // Construir las pestañas
                            $items = [
                                [
                                    'label' => 'Antecedentes',
                                    'content' => $this->blocks['antecedentes'],
                                    'options' => ['id' => 'antecedentes'],
                                ],
                                [
                                    'label' => 'Exploración Física',
                                    'content' => $this->blocks['exploracion_fisica'],
                                    'options' => ['id' => 'exploracion_fisica'],
                                ],
                                [
                                    'label' => 'Interrogatorio Médico',
                                    'content' => $this->blocks['interrogatorio_medico'],
                                    'options' => ['id' => 'interrogatorio_medico'],
                                ],
                                [
                                    'label' => 'Alergias',
                                    'content' => $this->blocks['alergias'],
                                    'options' => ['id' => 'alergias'],
                                ],
                            ];

                            // Agregar la pestaña de Documentos Médicos si el usuario tiene el permiso
                            if (Yii::$app->user->can('ver-documentos-medicos')) {
                                $items[] = [
                                    'label' => 'Documentos Médicos',
                                    'content' => $this->blocks['documento-medico'],
                                    'options' => ['id' => 'documentos-medicos'],
                                ];
                            }

                            // Mostrar el widget de TabsX
                            echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => $items,
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_LEFT,
                                'encodeLabels' => false,
                            ]);
                            ?>

                            <?php $this->endBlock(); ?>

                            <!-- antecedentes-->


                            <?php $this->endBlock(); ?>






                        </div>
                        <!--.col-md-12-->


                        <!-- TABS PRINCIPAL -->
                        <?php
                        $tabs = [
                            [
                                'label' => 'Información',
                                'content' => $this->blocks['datos'],
                                'active' => true,
                                'options' => [
                                    'id' => 'datos',
                                ],
                            ],

                        ];

                        if (Yii::$app->user->can('ver-expediente-medico')) {
                            $tabs[] = [
                                'label' => 'Expediente Medico',
                                'content' => $this->blocks['expediente_medico'],
                                'options' => [
                                    'id' => 'expediente_medico',
                                ],
                            ];
                        }
                        if (Yii::$app->user->can('ver-documentos')) {
                            $tabs[] = [
                                'label' => 'Documentos',
                                'content' => $this->blocks['expediente'],
                                'options' => [
                                    'id' => 'documentos',
                                ],
                            ];
                        }
                        if (Yii::$app->user->can('ver-consultas-medicas')) {
                            $tabs[] = [
                                'label' => 'Consultas medicas',
                                'content' => $this->blocks['info_consultas'],
                                'options' => [
                                    'id' => 'informacion_consultas',
                                ],

                            ];
                        }

                        echo TabsX::widget([
                            'enableStickyTabs' => true,
                            'options' => ['class' => 'nav-tabs-custom'],
                            'items' => $tabs,
                            'position' => TabsX::POS_ABOVE,
                            'align' => TabsX::ALIGN_LEFT,
                            'encodeLabels' => false,
                        ]);
                        ?>

                    </div>
                    <!--.row-->



                </div>
                <!--.card-body-->


            </div>
            <!--.card-->


        </div>
        <!--.col-md-10-->

    </div>
    <!--.row-->
    <script>
        $(document).ready(function() {
            // Function to activate tabs based on the hash in the URL
            function activateTabFromHash() {
                var hash = window.location.hash;

                // Check if the hash exists and it's not empty
                if (hash) {
                    var tabId = hash.replace('#', '');

                    // Find the tab that matches the hash
                    var tabLink = $('a[href="' + hash + '"]');

                    // If the tab exists, activate it
                    if (tabLink.length) {
                        tabLink.tab('show');

                        // Activate parent tabs if the tab is nested
                        tabLink.parents('.tab-pane').each(function() {
                            var parentTabId = $(this).attr('id');
                            $('a[href="#' + parentTabId + '"]').tab('show');
                        });
                    }
                }
            }

            // Activate the tab based on the hash in the URL when the page loads
            activateTabFromHash();

            // Activate the tab based on the hash in the URL when the hash changes
            $(window).on('hashchange', function() {
                activateTabFromHash();
            });

            // Update the URL hash when a tab is clicked
            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                window.location.hash = $(e.target).attr('href');
            });
        });
    </script>
</div>
<!--.container-fluid-->