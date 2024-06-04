<?php

use app\models\CatDepartamento;

use app\models\CatDireccion;
use app\models\CatDptoCargo;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use hail812\adminlte3\yii\grid\ActionColumn;
use hail812\adminlte\widgets\Alert;
use yii\helpers\Html;
//use yii\widgets\DetailView;
use kartik\file\FileInput;

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

$this->title = 'Empleado ' . $model->numero_empleado;
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$activeTab = Yii::$app->request->get('tab', 'info_p');
$currentDate = date('Y-m-d');

?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header gradient-info text-white">
                    <h3><?= Html::encode($this->title) ?></h3>
                    <?php
                    $this->registerJs("
                               // Función para abrir el cuadro de diálogo de carga de archivos cuando se hace clic en la imagen
                               $('#foto').click(function() {
                                   $('#foto-input').trigger('click');
                               });
                               
                               // Función para enviar el formulario cuando se selecciona una nueva imagen
                               $('#foto-input').change(function() {
                                   $('#foto-form').submit();
                               });
                               ", View::POS_READY);
                    ?>

                    <div id="foto" title="Cambiar foto de perfil" style="position: relative;">
                        <?php if ($model->foto) : ?>
                            <?= Html::img(['empleado/foto-empleado', 'id' => $model->id], ['class' => 'mr-3', 'style' => 'width: 100px; height: 100px;']) ?>
                        <?php else : ?>
                            <?= Html::tag('div', 'No hay foto disponible', ['class' => 'mr-3']) ?>
                        <?php endif; ?>
                        <i class="fas fa-edit" style="position: absolute; bottom: 5px; right: 5px; cursor: pointer;"></i>
                    </div>



                    <?php $form = ActiveForm::begin(['id' => 'foto-form', 'options' => ['enctype' => 'multipart/form-data', 'action' => ['cambio', 'id' => $model->id]]]); ?>

                    <?= $form->field($model, 'foto')->fileInput(['id' => 'foto-input', 'style' => 'display:none'])->label(false) ?>


                    <?php ActiveForm::end(); ?>

                    <h3 class="mb-0"> Empleado: <?= $model->nombre ?> <?= $model->apellido ?></h3>
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
                            <?php $this->registerJs("
    $('#pjax-update-info').on('pjax:success', function(event, data, status, xhr) {
        var response = JSON.parse(xhr.responseText);
        if (response.success) {
            alert(response.message); // Muestra un mensaje de éxito
            $.pjax.reload({container: '#pjax-update-info'}); // Recarga el contenido del contenedor Pjax
        } else {
            alert(response.message); // Muestra un mensaje de error
        }
    });
    "); ?>
                            <?php Pjax::begin([
                                'id' => 'pjax-update-info',
                                'options' => ['pushState' => false], 
                            ]); ?>

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
                                    <?= $form->field($model, 'sexo')->dropDownList(['Masculino' => 'Masculino', 'Femenino' => 'Femenino'], ['prompt' => 'Seleccionar Sexo', 'disabled' => true]); ?>
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
                                </div>
                                <?php ActiveForm::end(); ?>

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
                                            <button type="button" id="edit-button" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                            <button type="button" id="cancel-button" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>

                                            <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3', 'id' => 'save-button', 'style' => 'display:none;']) ?>
                                        </div>
                                        <div class="card-body">
                                            <?= $form->field($model, 'email')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'telefono')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'colonia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'calle')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'numero_casa')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'codigo_postal')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>
                                    <script>
                                        document.getElementById('edit-button').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#contact-info-form input');
                                            fields.forEach(function(field) {
                                                field.readOnly = false;
                                                // field.classList.remove('form-control-plaintext');
                                                field.classList.add('form-control');
                                            });
                                            document.getElementById('edit-button').style.display = 'none';
                                            document.getElementById('save-button').style.display = 'block';
                                            document.getElementById('cancel-button').style.display = 'block';
                                        });

                                        document.getElementById('cancel-button').addEventListener('click', function() {
                                            var fields = document.querySelectorAll('#contact-info-form input');
                                            fields.forEach(function(field) {
                                                field.readOnly = true;
                                                //   field.classList.add('form-control-plaintext');
                                                field.classList.add('form-control');
                                                field.value = field.defaultValue; 
                                            });
                                            document.getElementById('edit-button').style.display = 'block';
                                            document.getElementById('save-button').style.display = 'none';
                                            document.getElementById('cancel-button').style.display = 'none';
                                        });
                                    </script>


                                </div>
                                <div class="col-md-6">


                                    <div class="card">
                                        <?php $form = ActiveForm::begin([
                                            'action' => ['actualizar-informacion-contacto', 'id' => $model->id],
                                            'options' => ['id' => 'emergency-contact-form']
                                        ]); ?>
                                        <div class="card-header bg-secondary text-white">
                                            <h3>Información de contacto de emergencia</h3>
                                            <button type="button" id="edit-button-emergency" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
                                            <button type="button" id="cancel-button-emergency" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
                                            <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-info float-right  mr-3', 'id' => 'save-button-emergency', 'style' => 'display:none;']) ?>
                                        </div>
                                        <div class="card-body">
                                            <?= $form->field($model, 'nombre_contacto_emergencia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                            <?= $form->field($model, 'relacion_contacto_emergencia')->dropDownList([
                                                'Padre' => 'Padre',
                                                'Madre' => 'Madre',
                                                'Esposo/a' => 'Esposo/a',
                                                'Hijo/a' => 'Hijo/a',
                                                'Hermano/a' => 'Hermano/a',
                                                'Compañero/a de trabajo' => 'Compañero/a de trabajo',
                                                'Tio/a' => 'Tio/a'
                                            ], ['prompt' => 'Seleccionar parentesco', 'disabled' => true]); ?>
                                            <?= $form->field($model, 'telefono_contacto_emergencia')->textInput(['maxlength' => true, 'readonly' => true, 'class' => 'form-control']); ?>
                                        </div>
                                        <?php ActiveForm::end(); ?>
                                    </div>

                                </div>
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
                            </script>

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
                                    return $model->profesion . ' ' . $model->empleado->nombre . ' ' . $model->empleado->apellido;
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
                                    ]) ?>
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
                                    ]) ?>

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
                                    ]) ?>
                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_inicio')->input('time', ['disabled' => true]) ?>

                                    <?= $form->field($model->informacionLaboral, 'horario_laboral_fin')->input('time', ['disabled' => true]) ?>



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
                                    ]) ?>



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
                                    ]) ?>

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
                                    ]) ?>

                                    <div class="form-group">
                                        <label class="control-label">Director de dirección</label>
                                        <input type="text" class="form-control" readonly value="<?= $juntaDirectorDireccion ? $juntaDirectorDireccion->profesion . ' ' . $juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido : 'No Asignado' ?>">
                                    </div>



                                    <?php ActiveForm::end(); ?>
                                </div>
                            </div>
                            <script>
                                document.getElementById('edit-button-laboral').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select');
                                    fields.forEach(function(field) {
                                        field.readOnly = false;
                                        field.disabled = false; 
                                    });
                                    $('.select2-hidden-accessible').select2('enable'); 
                                    document.getElementById('edit-button-laboral').style.display = 'none';
                                    document.getElementById('save-button-laboral').style.display = 'block';
                                    document.getElementById('cancel-button-laboral').style.display = 'block';
                                });

                                document.getElementById('cancel-button-laboral').addEventListener('click', function() {
                                    var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select');
                                    fields.forEach(function(field) {
                                        field.readOnly = true;
                                        field.disabled = true; 
                                        field.value = field.defaultValue; 
                                    });
                                    $('.select2-hidden-accessible').select2('enable', false);
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
            <h3>SOLICITUDES: </h3>
        </div>

        <li class="dropdown-divider"></li>

        <div class="row">
        <?php Pjax::begin(['id' => 'pjax-container']);?>
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
                        'attribute' => 'status',
                        'format' => 'raw',
                        'label' => 'Estatus',
                        'value' => function ($model) {
                            $status = '';
                            switch ($model->status) {
                                case 'Aprobado':
                                    $status = '<span class="badge badge-success">' . $model->status . '</span>';
                                    break;
                                case 'En Proceso':
                                    $status = '<span class="badge badge-warning">' . $model->status . '</span>';
                                    break;
                                case 'Rechazado':
                                    $status = '<span class="badge badge-danger">' . $model->status . '</span>';
                                    break;
                                default:
                                    $status = '<span class="badge badge-secondary">' . $model->status . '</span>';
                                    break;
                            }
                            return $status;
                        },
                        'filter' => Html::activeDropDownList($searchModel, 'status', ['Aprobado' => 'Aprobado', 'En Proceso' => 'En Proceso', 'Rechazado' => 'Rechazado'], ['class' => 'form-control', 'prompt' => 'Todos']),
                    ],
                    [
                        'attribute' => 'comentario',
                        'format' => 'ntext',
                        'filter' => false,
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

            $script = <<< JS
                setInterval(function(){
                    $.pjax.reload({container:'#pjax-container'});
                }, 60000);
            JS;
            
            $this->registerJs($script);
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
                            <div class="card">

                                <div class="card-header bg-info text-white">
                                    <h3>Expediente del empleado</h3>

                                </div>
                                <div class="card-body">
                                    <div class="documento-form">

                                        <?php $form = ActiveForm::begin(['action' => ['documento/create', 'empleado_id' => $model->id], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']]); ?>

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
                                                'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '0x',); }",
                                            ],
                                        ])->label('Tipo de Documento') ?>


                                        <?= $form->field($documentoModel, 'nombre')->textInput([
                                            'maxlength' => true,
                                            'id' => 'nombre-archivo',
                                            'style' => 'display:none',
                                            'placeholder' => 'Ingrese el nombre del documento'
                                        ])->label(false) ?>

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

                                        <div class="form-group">
                                            <?= Html::submitButton('Subir archivo <i class="fa fa-upload"></i>', ['class' => 'btn btn-warning float-right', 'id' => 'save-button-personal']) ?>
                                        </div>

                                        <?php ActiveForm::end(); ?>

                                    </div>

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

                                </div>


                            </div>

                            <?php Pjax::end(); ?>
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