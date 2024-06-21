<?php

use app\models\CatDepartamento;
use marqu3s\summernote\Summernote;

use bizley\quill\Quill;
use app\models\CatDireccion;
use app\models\CatDptoCargo;
use app\models\CatNivelEstudio;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\redactor\widgets\Redactor;

//use yii\widgets\DetailView;
use kartik\file\FileInput;
use dosamigos\ckeditor\CKEditor;
use yii\helpers\Url;
use yii\bootstrap5\Tabs;
use yii\grid\GridView;
use yii\bootstrap4\Modal;
use yii\web\YiiAsset;
use yii\widgets\Pjax;
use yii\web\View;
use kartik\detail\DetailView;
use app\models\ExpedienteSearch;
//use yii\bootstrap4\ActiveForm;
use kartik\form\ActiveForm;
use yii\jui\DatePicker;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\Departamento;
use app\models\Documento;
use kartik\tabs\TabsX;
use app\models\DocumentoSearch;
use app\models\JuntaGobierno;
use yii\web\JsExpression;
use kartik\select2\Select2;
use app\models\CatTipoDocumento;
use app\models\ExpedienteMedicoSearch;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

//$activeTab = Yii::$app->request->get('tab', 'info_p');

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->registerJsFile('@web/js/municipios.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/select_estados.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->title = 'Empleado: ' . $model->numero_empleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$activeTab = Yii::$app->request->get('tab', 'info_p');
$currentDate = date('Y-m-d');
$antecedentesExistentes = [];
$observacionGeneral = '';
$descripcionAntecedentes = '';
$modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();

if ($antecedentes) {
    foreach ($antecedentes as $antecedente) {
        $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
        if (empty($observacionGeneral)) {
            $observacionGeneral = $antecedente->observacion;
        }
    }
}

// Si ya existe un antecedente patológico, obtenemos su descripción
if ($expedienteMedico) {
    $antecedentePatologico = \app\models\AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
    if ($antecedentePatologico) {
        $descripcionAntecedentes = $antecedentePatologico->descripcion_antecedentes;
    }

    // Obtener antecedentes no patológicos
    $modelAntecedenteNoPatologico = \app\models\AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
    if (!$modelAntecedenteNoPatologico) {
        $modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
        $modelAntecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
    }
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
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-header gradient-info text-white">

                    <div class="d-flex align-items-center position-relative ml-4">
                        <div class="bg-light p-1 rounded-circle custom-shadow" style="width: 140px; height: 140px; position: relative;">
                            <?= Html::img(['empleado/foto-empleado', 'id' => $model->id], [
                                'class' => 'rounded-circle',
                                'style' => 'width: 130px; height: 130px;'
                            ]) ?>
                            <?= Html::button('<i class="fas fa-edit"></i>', [
                                'class' => 'btn btn-dark position-absolute',
                                'style' => 'top: 5px; right: 5px; padding: 5px 10px;',
                                'data-toggle' => 'modal',
                                'data-target' => '#changePhotoModal'
                            ]) ?>
                        </div>
                        <div class="ml-4">
                            <div class="alert alert-light mb-0" role="alert">
                                <h3><?= Html::encode($this->title) ?></h3>
                                <h3 class="mb-0"><?= $model->nombre ?> <?= $model->apellido ?></h3>


                            </div>
                        </div>
                    </div>
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
        <button type="button" id="edit-button-personal" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
        <button type="button" id="cancel-button-personal" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-personal', 'style' => 'display:none;']) ?>
    </div>
    <div class="card-body">
        <?= $form->field($model, 'numero_empleado')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
        <?= $form->field($model, 'nombre')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
        <?= $form->field($model, 'apellido')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
        <?= $form->field($model, 'fecha_nacimiento')->widget(DatePicker::class, [
            'language' => 'es',
            'dateFormat' => 'yyyy-MM-dd',
            'options' => [
                'class' => 'form-control',
                'autocomplete' => 'off',
                'readonly' => true
            ],
            'clientOptions' => [
                'changeYear' => true,
                'changeMonth' => true,
                'yearRange' => '-100:+0',
            ],
        ]); ?>
       
        <?= $form->field($model, 'edad')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
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
        <?= $form->field($model, 'curp')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
        <?= $form->field($model, 'nss')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
        <?= $form->field($model, 'rfc')->textInput(['readonly' => true, 'class' => 'form-control']); ?>
    </div>
    <?php ActiveForm::end(); ?>
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

    function checkEmptyFieldsPersonal() {
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
    });
</script>




    <div class="col-md-6">

<div class="card">
    <?php $form = ActiveForm::begin([
      'action' => ['actualizar-informacion', 'id' => $model->id],
        'options' => ['id' => 'educational-info-form']
    ]); ?>
    <div class="card-header gradient-verde  text-white">
        <h3>Información Educacional</h3>
        <button type="button" id="edit-button-educational" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
        <button type="button" id="cancel-button-educational" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-educational', 'style' => 'display:none;']) ?>
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
</div>
</div>

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

    function checkEmptyFieldsPersonal() {
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
    });
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
                <button type="button" id="edit-button-contact" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                <button type="button" id="cancel-button-contact" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button-contact', 'style' => 'display:none;']) ?>
            </div>
            <div class="card-body">
                <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'colonia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'calle')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'numero_casa')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'codigo_postal')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                <?= $form->field($model, 'estado')->widget(Select2::classname(), [
                    'data' => [], // Inicialmente vacío, se llenará con JS
                    'options' => ['placeholder' => 'Selecciona un estado', 'id' => 'estado-dropdown', 'disabled' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ])->label('Estado'); ?>
                <?= $form->field($model, 'municipio')->widget(Select2::classname(), [
                    'data' => [], // Inicialmente vacío
                    'options' => ['placeholder' => 'Selecciona un municipio', 'id' => 'municipio-dropdown', 'disabled' => true],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_BOOTSTRAP,
                ])->label('Municipio'); ?>
            </div>
            <?php ActiveForm::end(); ?>
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

            function checkEmptyFieldsContact() {
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
            });
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
                <button type="button" id="edit-button-emergency" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                <button type="button" id="cancel-button-emergency" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-info float-right  mr-3', 'id' => 'save-button-emergency', 'style' => 'display:none;']) ?>
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

            function checkEmptyFieldsEmergency() {
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
            });
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
                                    <button type="button" id="edit-button-laboral" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                    <button type="button" id="cancel-button-laboral" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right  mr-3', 'id' => 'save-button-laboral', 'style' => 'display:none;']) ?>
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
                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_inicio')->input('time', ['disabled' => true]) ->label('Hora de entrada')  ?>

                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_fin')->input('time', ['disabled' => true]) ->label('Hora de salida') ?>
                                    <?= $form->field($model->informacionLaboral, 'numero_cuenta')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                    <?= $form->field($model->informacionLaboral, 'salario')->textInput([
                                        'type' => 'number',
                                        'step' => '0.01',
                                        'readonly' => true,
                                        'class' => 'form-control'
                                    ]); ?>



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
                                    ]) ->label('Dirección') ?>

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
                                    ]) ->label('Jefe inmediato') ?>

                                    <div class="form-group">
                                        <label class="control-label">Director de dirección</label>
                                        <input type="text" class="form-control" readonly value="<?= $juntaDirectorDireccion ?  $juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido : 'No Asignado' ?>">
                                    </div>



                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <script>
    document.getElementById('edit-button-laboral').addEventListener('click', function() {
        var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select, #dias-laborales-checkboxes input');
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

    document.getElementById('cancel-button-laboral').addEventListener('click', function() {
        var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select, #dias-laborales-checkboxes input');
        fields.forEach(function(field) {
            field.readOnly = true;
            field.disabled = true;
            field.value = field.defaultValue;
        });
        document.getElementById('dias-laborales-display').style.display = 'block';
        document.getElementById('dias-laborales-checkboxes').style.display = 'none';
        document.getElementById('edit-button-laboral').style.display = 'block';
        document.getElementById('save-button-laboral').style.display = 'none';
        document.getElementById('cancel-button-laboral').style.display = 'none';
    });

    function checkEmptyFieldsLaboral() {
        var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select');
        var emptyFields = Array.from(fields).filter(function(field) {
            return field.value.trim() === '' && field.type !== 'hidden';
        });

        if (emptyFields.length > 0) {
            showAlert('Falta completar datos laborales', 'Por favor, complete todos los campos.');
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        checkEmptyFieldsLaboral();
    });

    $('#pjax-update-info').on('pjax:end', function() {
        checkEmptyFieldsLaboral();
    });
</script>

                            <script>
                                document.getElementById('edit-button-laboral').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select, #dias-laborales-checkboxes input');
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

                                document.getElementById('cancel-button-laboral').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select, #dias-laborales-checkboxes input');
                                    fields.forEach(function(field) {
                                        field.readOnly = true;
                                        field.disabled = true;
                                        field.value = field.defaultValue;
                                    });
                                    document.getElementById('dias-laborales-display').style.display = 'block';
                                    document.getElementById('dias-laborales-checkboxes').style.display = 'none';
                                    document.getElementById('edit-button-laboral').style.display = 'block';
                                    document.getElementById('save-button-laboral').style.display = 'none';
                                    document.getElementById('cancel-button-laboral').style.display = 'none';
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
                                                    <button type="button" id="edit-button-first-period" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                    <button type="button" id="cancel-button-first-period" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-dark float-right mr-3', 'id' => 'save-button-first-period', 'style' => 'display:none;']) ?>
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
                                                    <button type="button" id="edit-button-period" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                                    <button type="button" id="cancel-button-period" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-info float-right  mr-3', 'id' => 'save-button-period', 'style' => 'display:none;']) ?>
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
                                        <h3>HISTORIAL DE SOLICITUDES DE INCIDENCIAS: </h3>
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
                      //                          [
                        //                            'attribute' => 'status',
                          //                          'format' => 'raw',
                            //                        'label' => 'Estatus',
                              //                      'value' => function ($model) {
                                //                        $status = '';
                                  //                      switch ($model->status) {
                                    //                        case 'Aprobado':
                                      //                          $status = '<span class="badge badge-success">' . $model->status . '</span>';
                                        //                        break;
                                          //                  case 'En Proceso':
                                            //                    $status = '<span class="badge badge-warning">' . $model->status . '</span>';
                                              //                  break;
                                                //            case 'Rechazado':
                                                  //              $status = '<span class="badge badge-danger">' . $model->status . '</span>';
                                                    //            break;
                                                      //      default:
                                                        //        $status = '<span class="badge badge-secondary">' . $model->status . '</span>';
                                           //                     break;
                                             //           }
                                               //         return $status;
                                                 //   },
                                                   // 'filter' => Html::activeDropDownList($searchModel, 'status', ['Aprobado' => 'Aprobado', 'En Proceso' => 'En Proceso', 'Rechazado' => 'Rechazado'], ['class' => 'form-control', 'prompt' => 'Todos']),
                                           //     ],
                                           //     [
                                             //       'attribute' => 'comentario',
                                               //     'format' => 'ntext',
                                                 //   'filter' => false,
                                             //   ],
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


                            echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => [
                                    [
                                        'label' => 'Información personal',
                                        'content' => $this->blocks['info_p'],
                                      //  'active' => true,
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
                                        'label' => 'Vacaciones',
                                        'content' => $this->blocks['info_vacacional'],
                                        'options' => [
                                            'id' => 'informacion_vacaciones',
                                        ],

                                    ],

                                    [
                                        'label' => 'Solicitudes',
                                        'content' => $this->blocks['info_solicitudes'],
                                        'options' => [
                                            'id' => 'informacion_solicitudes',
                                        ],

                                    ],
                                ],
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_CENTER,
                              //  'bordered'=>true,
                                'encodeLabels' => false,
                            ]);
                            ?>


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
                    <?= $form->field($documentoModel, 'observacion')->textarea() ?>
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
                            'filter' => false,
                            'options' => ['style' => 'width: 30%;'],
                        ],
                        [
                            'attribute' => 'observacion',
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
                    'rowOptions' => function($model, $key, $index, $grid) {
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
            <div class="card-body">
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
                                    <?php foreach ($catAntecedentes as $catAntecedente): ?>
                                        <tr>
                                            <td><?= Html::encode($catAntecedente->nombre) ?></td>
                                            <?php foreach (['Abuelos', 'Hermanos', 'Madre', 'Padre'] as $parentezco): ?>
                                                <td>
                                                    <?= Html::checkbox("AntecedenteHereditario[{$catAntecedente->id}][$parentezco]", isset($antecedentesExistentes[$catAntecedente->id][$parentezco]), [
                                                        'value' => 1,
                                                        'label' => '',
                                                    ]) ?>
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
                                'rows' => 30,
                                'style' => 'width: 100%;',
                            ]) ?>
                        </div>
                        <br>
                        <div class="form-group mt-auto d-flex justify-content-end">
                            <?= Html::submitButton('Guardar &nbsp; &nbsp;  <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
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
<?php $form = ActiveForm::begin(['action' => ['empleado/patologicos', 'id' => $model->id]]); ?>
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header gradient-blue text-white text-center">
                <h2>Antecedentes Patológicos</h2>
            </div>
            <div class="card-body">

                <div class="form-group">
                    <?= Html::label('Descripción de Antecedentes Patológicos', 'descripcion_antecedentes') ?>
                    <?= Html::textarea('descripcion_antecedentes', $descripcionAntecedentes, [
                        'class' => 'form-control',
                        'rows' => 15,
                        'style' => 'width: 100%;',
                    ]) ?>
                </div>
                <div class="form-group text-right">
                    <?= Html::submitButton('Guardar &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                </div>
                <div class="alert alert-white custom-alert" role="alert">
                <i class="fa fa-exclamation-circle" aria-hidden="true"></i>                Peso al nacer, anormalidades perinatales, desarrollo físico y mental, y el esquema básico de vacunación.
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
            </div>
            <div class="card-body">
            <div class="container">
            <div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>ACTIVIDAD FISICA</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-8">
                <div class="row">
                    <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_ejercicio]', $antecedenteNoPatologico->p_ejercicio, [
                                'class' => 'custom-control-input',
                                'id' => 'p_ejercicio'
                            ]) ?>
                            <?= Html::label('¿Realiza ejercicio?', 'p_ejercicio', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                        <?= Html::label('Minutos al día', 'p_minutos_x_dia_ejercicio') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_minutos_x_dia_ejercicio]', $antecedenteNoPatologico->p_minutos_x_dia_ejercicio, ['class' => 'form-control']) ?>
                    </div>
                    <div class="w-100"></div>
                    <br>
                    <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_deporte]', $modelAntecedenteNoPatologico->p_deporte, [
                                'class' => 'custom-control-input',
                                'id' => 'p_deporte'
                            ]) ?>
                            <?= Html::label('¿Realiza algún deporte?', 'p_deporte', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>
                    <div class="col-6 col-sm-4">
                        <?= Html::label('¿Cuál deporte?', 'p_a_deporte') ?>
                        <?= Html::textInput('AntecedenteNoPatologico[p_a_deporte]', $modelAntecedenteNoPatologico->p_a_deporte, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-6 col-sm-4">
                        <?= Html::label('Frecuencia con la que practica', 'p_frecuencia_deporte') ?>
                        <?= Html::textInput('AntecedenteNoPatologico[p_frecuencia_deporte]', $modelAntecedenteNoPatologico->p_frecuencia_deporte, ['class' => 'form-control']) ?>
                    </div>
                    <div class="w-100"></div>
                    <br>
                    <div class="col-6 col-sm-4">
                        <?= Html::label('Horas que duerme por día', 'p_horas_sueño') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_horas_sueño]', $antecedenteNoPatologico->p_horas_sueño, ['class' => 'form-control']) ?>
                    </div>
                    <div class="col-6 col-sm-6">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_dormir_dia]', $modelAntecedenteNoPatologico->p_dormir_dia, [
                                'class' => 'custom-control-input',
                                'id' => 'p_dormir_dia'
                            ]) ?>
                            <?= Html::label('¿Duerme durante el día?', 'p_dormir_dia', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Observaciones', 'observacion_actividad_fisica') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[observacion_actividad_fisica]', $antecedenteNoPatologico->observacion_actividad_fisica, ['class' => 'form-control', 'rows' => 10]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>HABITOS ALIMENTICIOS</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-8">
                <div class="row">
                <div class="col-6 col-sm-3">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_desayuno]', $antecedenteNoPatologico->p_desayuno, [
                                'class' => 'custom-control-input',
                                'id' => 'p_desayuno'
                            ]) ?>
                            <?= Html::label('¿Desayuna?', 'p_desayuno', ['class' => 'custom-control-label']) ?>
                        </div>
                </div>

                <div class="col-6 col-sm-6">
                
                <?= Html::label('Número de comidas al día', 'p_comidas_x_dia') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_comidas_x_dia]', $antecedenteNoPatologico->p_comidas_x_dia, ['class' => 'form-control']) ?>
                    
                        
                </div>

                        <div class="w-100"></div>

                        <br>
                        <div class="col-6 col-sm-3">

                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_cafe]', $antecedenteNoPatologico->p_cafe, [
                                'class' => 'custom-control-input',
                                'id' => 'p_cafe'
                            ]) ?>
                            <?= Html::label('¿Toma café?', 'p_cafe', ['class' => 'custom-control-label']) ?>
                        </div>



                </div>

                <div class="col-6 col-sm-6">
                        <?= Html::label('Tazas de café al día', 'p_tazas_x_dia') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_tazas_x_dia]', $antecedenteNoPatologico->p_tazas_x_dia, ['class' => 'form-control']) ?>
                    </div>

                    <div class="w-100"></div>

<br>
<div class="col-6 col-sm-6">

                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_refresco]', $antecedenteNoPatologico->p_refresco, [
                                'class' => 'custom-control-input',
                                'id' => 'p_refresco'
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
                                'id' => 'p_dieta'
                            ]) ?>
                            <?= Html::label('¿Sigue alguna dieta?', 'p_dieta', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>
                    <div class="col-6 col-sm-6">
                    <div class="form-group">
                    <?= Html::label('Información sobre la dieta', 'p_info_dieta') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[p_info_dieta]', $antecedenteNoPatologico->p_info_dieta, ['class' => 'form-control', 'rows' => 4]) ?>
                </div>
                    </div>

                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Observaciones', 'observacion_comida') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[observacion_comida]', $antecedenteNoPatologico->observacion_comida, ['class' => 'form-control', 'rows' => 10]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>ALCOHOLISMO</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-7">
                <div class="row">
                <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_alcohol]', $antecedenteNoPatologico->p_alcohol, [
                                'class' => 'custom-control-input',
                                'id' => 'p_alcohol'
                            ]) ?>
                            <?= Html::label('¿Consume alcohol?', 'p_alcohol', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>  
                    <div class="col-6 col-sm-6">
  <!-- Campo dropdown -->
  <div class="form-group">
                    <?= Html::label('Frecuencia de Consumo de Alcohol', 'p_frecuencia_alcohol') ?>
                    <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_alcohol]', $antecedenteNoPatologico->p_frecuencia_alcohol, [
                    
                        'Casual' => 'Casual',
                        'Moderado' => 'Moderado',
                        'Intenso' => 'Intenso',
                    ], ['class' => 'form-control']) ?>
                </div>
                    <?= Html::label('Edad a la que comenzó a béber', 'p_edad_alcoholismo') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_alcoholismo]', $antecedenteNoPatologico->p_edad_alcoholismo, ['class' => 'form-control']) ?>
                    
                      
                        <?= Html::label('Copas de licor/vino al día', 'p_copas_x_dia') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_copas_x_dia]', $antecedenteNoPatologico->p_copas_x_dia, ['class' => 'form-control']) ?>
                    
                        <?= Html::label('Número de cervezas al día', 'p_cervezas_x_dia') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_cervezas_x_dia]', $antecedenteNoPatologico->p_cervezas_x_dia, ['class' => 'form-control']) ?>
                    </div>
                 


                    <div class="w-100"></div>
<br>

                    <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_ex_alcoholico]', $antecedenteNoPatologico->p_ex_alcoholico, [
                                'class' => 'custom-control-input',
                                'id' => 'p_ex_alcoholico'
                            ]) ?>
                            <?= Html::label('Ex-alcoholico', 'p_ex_alcoholico', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>  
                    <div class="col-6 col-sm-6">
                    <?= Html::label('Edad en la que dejo de beber', 'p_edad_fin_alcoholismo') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_alcoholismo]', $antecedenteNoPatologico->p_edad_fin_alcoholismo, ['class' => 'form-control']) ?>
                    
                        
                    
                    </div>
                   




                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-5">
                <div class="form-group">
                    <?= Html::label('Observaciones', 'observacion_alcoholismo') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[observacion_alcoholismo]', $antecedenteNoPatologico->observacion_alcoholismo, ['class' => 'form-control', 'rows' => 10]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>TABAQUISMO</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-8">
                <div class="row">
                <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_fuma]', $antecedenteNoPatologico->p_fuma, [
                                'class' => 'custom-control-input',
                                'id' => 'p_fuma'
                            ]) ?>
                            <?= Html::label('¿Fúma?', 'p_fuma', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>  
                    <div class="col-6 col-sm-6">
  <!-- Campo dropdown -->
  <div class="form-group">
                    <?= Html::label('Frecuencia de Consumo de Tabaco', 'p_frecuencia_tabaquismo') ?>
                    <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_tabaquismo]', $antecedenteNoPatologico->p_frecuencia_tabaquismo, [
                    
                        'Casual' => 'Casual',
                        'Moderado' => 'Moderado',
                        'Intenso' => 'Intenso',
                    ], ['class' => 'form-control']) ?>
                </div>
                    <?= Html::label('Edad a la que comenzó a fumar', 'p_edad_tabaquismo') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_tabaquismo]', $antecedenteNoPatologico->p_edad_tabaquismo, ['class' => 'form-control']) ?>
                    
                      
                        <?= Html::label('Número de cigarros al día', 'p_no_cigarros_x_dia') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_no_cigarros_x_dia]', $antecedenteNoPatologico->p_no_cigarros_x_dia, ['class' => 'form-control']) ?>
                    
                                         </div>
                 


                    <div class="w-100"></div>
<br>
                    <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_ex_fumador]', $antecedenteNoPatologico->p_ex_fumador, [
                                'class' => 'custom-control-input',
                                'id' => 'p_ex_fumador'
                            ]) ?>
                            <?= Html::label('Ex-Fumador', 'p_ex_fumador', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div> 
                    <div class="col-6 col-sm-6">
                    <?= Html::label('Edad en la que dejo de fumar', 'p_edad_fin_tabaquismo') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_tabaquismo]', $antecedenteNoPatologico->p_edad_fin_tabaquismo, ['class' => 'form-control']) ?>
                    
                        
                    
                    </div> 
                    <div class="w-100"></div>

                    <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_fumador_pasivo]', $antecedenteNoPatologico->p_fumador_pasivo, [
                                'class' => 'custom-control-input',
                                'id' => 'p_fumador_pasivo'
                            ]) ?>
                            <?= Html::label('Fumador Pasivo', 'p_fumador_pasivo', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div> 

                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-4">
                <div class="form-group">
                    <?= Html::label('Observaciones', 'observacion_tabaquismo') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[observacion_tabaquismo]', $antecedenteNoPatologico->observacion_tabaquismo, ['class' => 'form-control', 'rows' => 10]) ?>
                </div>
            </div>
        </div>
    </div>
</div>


<div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>OTROS</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-6">
                <div class="row">
              
                <div class="form-group">
                    <?= Html::label('¿Qué actividades realiza en sus horas libres?', 'p_act_dias_libres') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[p_act_dias_libres]', $antecedenteNoPatologico->p_act_dias_libres, ['class' => 'form-control', 'rows' => 5]) ?>
                </div>
                <div class="form-group">
                    <?= Html::label('¿Pasa por algunas de estas situaciones?', 'p_situaciones') ?>
                    <?= Html::dropDownList('AntecedenteNoPatologico[p_situaciones]', $antecedenteNoPatologico->p_situaciones, [
                                            'Ninguna' => 'Ninguna',

                        'Duelo' => 'Duelo',
                        'Embarazos' => 'Embarazos',
                        'Divorcio' => 'Divorcio',
                    ], ['class' => 'form-control']) ?>
                </div>
               


                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-6">
            <div class="form-group">
                    <?= Html::label('Descripción de su vivienda (Tiene mascotas, Recursos del hogar, Etc.)', 'datos_vivienda') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[datos_vivienda]', $antecedenteNoPatologico->datos_vivienda, ['class' => 'form-control', 'rows' => 8]) ?>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header custom-nopato text-white text-left">
        <h5>CONSUMO DE DROGAS</h5>
        <div class="form-group">
                    <?= Html::submitButton('  <i class="fa fa-save" style="color: #007bff;"></i>&nbsp; &nbsp; Guardar', ['class' => 'btn btn-light float-right mr-3']) ?>
                </div>
    </div>
    <div class="card-body">
        <div class="row">
            <!-- Columna izquierda con los campos -->
            <div class="col-md-8">
                <div class="row">
                <div class="col-6 col-sm-4">
                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_drogas]', $antecedenteNoPatologico->p_drogas, [
                                'class' => 'custom-control-input',
                                'id' => 'p_drogas'
                            ]) ?>
                            <?= Html::label('¿Consume algún tipo de droga?', 'p_drogas', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div>  

                   
                
                    <div class="col-6 col-sm-6">
  <!-- Campo dropdown -->
  <div class="form-group">
                    <?= Html::label('Frecuencia de su consumo', 'p_frecuencia_droga') ?>
                    <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_droga]', $antecedenteNoPatologico->p_frecuencia_droga, [
                    
                        'Casual' => 'Casual',
                        'Moderado' => 'Moderado',
                        'Intenso' => 'Intenso',
                    ], ['class' => 'form-control']) ?>
                </div>
                    <?= Html::label('¿A qué edad se inicio el consumo?', 'p_edad_droga') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_droga]', $antecedenteNoPatologico->p_edad_droga, ['class' => 'form-control']) ?>
                    

                        <div class="custom-control custom-checkbox">
                            <?= Html::checkbox('AntecedenteNoPatologico[p_droga_intravenosa]', $antecedenteNoPatologico->p_droga_intravenosa, [
                                'class' => 'custom-control-input',
                                'id' => 'p_droga_intravenosa'
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
                                'id' => 'p_ex_adicto'
                            ]) ?>
                            <?= Html::label('Ex-Adicto', 'p_ex_adicto', ['class' => 'custom-control-label']) ?>
                        </div>
                    </div> 
                    <div class="col-6 col-sm-6">
                    <?= Html::label('¿A qué edad dejo de consumir?', 'p_edad_fin_droga') ?>
                        <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_fin_droga]', $antecedenteNoPatologico->p_edad_fin_droga, ['class' => 'form-control']) ?>
                    
                        
                    
                    </div> 
               


                </div>
            </div>
            <!-- Columna derecha con el textarea -->
            <div class="col-md-4">
            <div class="form-group">
                    <?= Html::label('Observaciones', 'observacion_droga') ?>
                    <?= Html::textarea('AntecedenteNoPatologico[observacion_droga]', $antecedenteNoPatologico->observacion_droga, ['class' => 'form-control', 'rows' => 10]) ?>
                </div>
            </div>
        </div>
    </div>
</div>






            </div>
               

               
                <br>
             
                <!-- Agrega aquí el resto de los campos siguiendo el mismo patrón -->
             
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<?php $this->endBlock(); ?>


        
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
                                        'id' => 'no_patologicos',
                                    ],


                                ],
                          
                              
                               

                            ],
                          //  'position' => TabsX::POS_ABOVE,
                           'align' => TabsX::ALIGN_CENTER,
                       //     'bordered'=>true,
                            'encodeLabels' => false


                        ]);

                        ?>



                            <?php $this->endBlock(); ?>


                            <br><br>
                            <?php echo TabsX::widget([
                            'enableStickyTabs' => true,
                            'options' => ['class' => 'nav-tabs-custom'],
                            'items' => [
                                [
                                    'label' => 'Antecedentes',
                                    'content' => $this->blocks['antecedentes'],
                                   // 'active' => true,
                                    'options' => [
                                        'id' => 'antecedentes',
                                    ],


                                ],
                               
                           
                              
                               

                            ],
                           'position' => TabsX::POS_ABOVE,
                           'align' => TabsX::ALIGN_LEFT,
                       //     'bordered'=>true,
                            'encodeLabels' => false


                        ]);

                        ?>



                            <?php $this->endBlock(); ?>

<!-- antecedentes-->


                            <?php $this->endBlock(); ?>






                        </div>
                        <!--.col-md-12-->
                        <?php echo TabsX::widget([
                            'enableStickyTabs' => true,
                            'options' => ['class' => 'custom-tabs-2'],
                            'items' => [
                                [
                                    'label' => 'Información',
                                    'content' => $this->blocks['datos'],
                                 //   'active' => true,
                                    'options' => [
                                        'id' => 'datos',
                                    ],


                                ],
                                [
                                    'label' => 'Documentos',
                                    'content' => $this->blocks['expediente'],
                                    'options' => [
                                        'id' => 'documentos',
                                    ],

                                ],

                                [
                                    'label' => 'Expediente Medico',
                                    'content' => $this->blocks['expediente_medico'],
                                    'options' => [
                                        'id' => 'expediente_medico',
                                    ],

                                ],


                            ],
                            'position' => TabsX::POS_ABOVE,
                         'align' => TabsX::ALIGN_LEFT,
                         //   'bordered'=>true,
                            'encodeLabels' => false


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
</div>
<!--.container-fluid-->