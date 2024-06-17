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
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

//$activeTab = Yii::$app->request->get('tab', 'info_p');

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerJsFile('@web/js/municipios.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/select_estados.js', ['depends' => [\yii\web\JqueryAsset::class]]);

$this->title = 'Empleado: ' . $model->numero_empleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$activeTab = Yii::$app->request->get('tab', 'info_p');
$currentDate = date('Y-m-d');

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
            <div class="card">
                <div class="card-header gradient-info text-white">

                    <div class="d-flex align-items-center position-relative">
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
                        <div class="ml-3">
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
                                        'active' => true,
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
                                'encodeLabels' => false,
                            ]);
                            ?>


                            <?php $this->beginBlock('expediente'); ?>
                            <div class="row">
    <div class="col-md-6">
        <div class="card">
    <div class="card-header bg-info text-white">
        <h3>Expediente del empleado</h3>
    </div>
    <div class="card-body">

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
            <?php



/*echo $form->field($documentoModel, 'observacion')->widget(Quill::className(), [
    'theme' => 'snow', // 'snow' or 'bubble'
    'toolbarOptions' => [
        ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
        ['blockquote', 'code-block'],
        [['header' => 1], ['header' => 2]],               // custom button values
        [['list' => 'ordered'], ['list' => 'bullet']],
        [['script' => 'sub'], ['script' => 'super']],      // superscript/subscript
        [['indent' => '-1'], ['indent' => '+1']],          // outdent/indent
        [['direction' => 'rtl']],                         // text direction
        [['size' => ['small', false, 'large', 'huge']]],  // custom dropdown
        [['header' => [1, 2, 3, 4, 5, 6, false]]],
        [['color' => []], ['background' => []]],          // dropdown with defaults from theme
        [['font' => []]],
        [['align' => []]],
        ['clean']                                         // remove formatting button
    ],
])->label('Observaciones');
*/?>

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
    </div>
            <?php ActiveForm::end(); ?>
    </div>

    </div>  <?php
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
                    nombreArchivoInput.hide().val('');
                }
            });
        ");
        ?>

        <br>
        <div class="col-md-6">
        <div class="card">
       
    <div class="card-body">
        <?php
        $searchModel = new DocumentoSearch();
        $params = Yii::$app->request->queryParams;
        $params[$searchModel->formName()]['empleado_id'] = $model->id;
        $dataProvider = $searchModel->search($params);
        ?>

            <?php Pjax::begin(); ?>
            <br>
            <br>
            <li class="dropdown-divider"></li>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'nombre',
                        'value' => 'nombre',
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
                        // 'options' => ['style' => 'width: 15%;'], //ancho de la columna
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
                            </div>

                            <?php $this->endBlock(); ?>



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
                                    'active' => true,
                                    'options' => [
                                        'id' => 'datos',
                                    ],


                                ],
                                [
                                    'label' => 'Expediente',
                                    'content' => $this->blocks['expediente'],
                                    'options' => [
                                        'id' => 'expediente',
                                    ],

                                ],


                            ],
                            'position' => TabsX::POS_ABOVE,
                            'align' => TabsX::ALIGN_CENTER,
                            // 'bordered'=>true,
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