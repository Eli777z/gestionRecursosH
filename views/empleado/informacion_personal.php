<?php


use app\models\CatNivelEstudio;

use yii\helpers\Html;

use yii\widgets\Pjax;

use kartik\form\ActiveForm;

use yii\helpers\ArrayHelper;

use kartik\select2\Select2;


?>
<br>

                            <?php Pjax::begin([
                                'id' => 'pjax-update-info',
                                'options' => ['pushState' => false],
                            ]); ?>



                            <?php $form = ActiveForm::begin([
                                'action' => ['actualizar-informacion', 'id' => $model->id],
                                'options' => ['id' => 'personal-info-form']
                            ]); ?>

<div id="loading-spinner" style="color: #000000;">
    <i class="fa fa-spinner fa-spin fa-2x" style="color: #000000;"></i> Procesando...
</div>
<?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>
    <div id="floating-buttons">
        <button type="button" id="edit-button-personal" class="btn btn-warning"><i class="fa fa-edit"></i></button>
        <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success', 'id' => 'save-button-personal', 'style' => 'display:none;']) ?>
        <button type="button" id="cancel-button-personal" class="btn btn-danger" style="display:none;"><i class="fa fa-times"></i></button>

       
    </div>
<?php endif; ?>

                            

                            <div class="card bg-light">
                                <div class="card-header bg-info text-white">
                                    <h6>Personal</h6>
                                   
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-6 col-sm-2">
                                            <?= $form->field($model, 'numero_empleado')->input('number', [
                                                'readonly' => true,
                                                'maxlength' => 4,
                                                'class' => 'form-control',
                                                'autocomplete'=>"off",
                                                'title' => $model->numero_empleado
                                            ])->label('No. Empleado:', [
                                                'title' => $model->numero_empleado
                                            ]) ?>
                                        </div>

                                        <div class="col-6 col-sm-4">
                                            <?= $form->field($model, 'nombre')->textInput([
                                                'readonly' => true,
                                                'class' => 'form-control',
                                                'title' => $model->nombre,
                                                'autocomplete'=>"off"
                                            ])->label('Nombre', [
                                                'title' => $model->nombre
                                            ]) ?>

                                            <?= $form->field($model, 'apellido')->textInput([
                                                'readonly' => true,
                                                'class' => 'form-control',
                                                'title' => $model->apellido,
                                                'autocomplete'=>"off"
                                            ])->label('Apellido', [
                                                'title' => $model->apellido
                                            ]) ?>
                                        </div>


                                        <?php //echo Html::label($model->expedienteMedico->antecedenteNoPatologico, 'religion'); 
                                        ?>
                                        <div class="col-6 col-sm-2">
                                            <?= $form->field($model, 'fecha_nacimiento')->input('date', [
                                                'disabled' => true,
                                                'title' => $model->fecha_nacimiento
                                            ])->label('Fecha Nacimiento', [
                                                'title' => $model->fecha_nacimiento
                                            ]) ?>

                                            <?= $form->field($model, 'edad')->hiddenInput([
                                                'title' => $model->edad
                                            ])->label(false); ?>

                                            <div class="form-group">
                                                <label class="control-label" title="<?= Html::encode($model->edad); ?>">Edad:</label>
                                                <p id="edad-label" title="<?= Html::encode($model->edad); ?>"><?= Html::encode($model->edad); ?></p>
                                            </div>
                                        </div>

                                        <div class="col-6 col-sm-3">
                                            <?= $form->field($model, 'sexo')->dropDownList([
                                                'Masculino' => 'Masculino',
                                                'Femenino' => 'Femenino',
                                            ], [
                                                'prompt' => 'Seleccionar sexo',
                                                'disabled' => true,
                                                'class' => 'form-control',
                                                'title' => $model->sexo
                                            ])->label('Sexo', [
                                                'title' => $model->sexo
                                            ]) ?>

                                            <?= $form->field($model, 'estado_civil')->dropDownList([
                                                'Soltero/a' => 'Soltero/a',
                                                'Casado/a' => 'Casado/a',
                                                'Separado/a' => 'Separado/a',
                                                'Viudo/a' => 'Viudo/a',
                                            ], [
                                                'prompt' => 'Seleccionar estado civil',
                                                'disabled' => true,
                                                'class' => 'form-control',
                                                'title' => $model->estado_civil
                                            ])->label('Estado civil', [
                                                'title' => $model->estado_civil
                                            ]) ?>
                                        </div>

                                        <div class="w-100"></div>
                                        <div class="col-6 col-sm-3">
                                            <?= $form->field($model, 'curp')->textInput([
                                                'readonly' => true,
                                                'maxlength' => 18,
                                                'class' => 'form-control',
                                                'title' => $model->curp,
                                                'autocomplete'=>"off"
                                            ])->label('CURP:', [
                                                'title' => $model->curp
                                            ]) ?>
                                        </div>

                                        <div class="col-6 col-sm-3">
                                            <?= $form->field($model, 'nss')->input('number', [
                                                'maxlength' => 11,
                                                'class' => 'form-control',
                                                'id' => 'nss-input',
                                                'readonly' => true,
                                                'title' => $model->nss,
                                                'autocomplete'=>"off"
                                            ])->label('NSS:', [
                                                'title' => $model->nss
                                            ]) ?>
                                        </div>

                                        <div class="col-6 col-sm-3">
                                            <?= $form->field($model, 'rfc')->textInput([
                                                'readonly' => true,
                                                'maxlength' => 13,
                                                'class' => 'form-control',
                                                'title' => $model->rfc,
                                                'autocomplete'=>"off"
                                            ])->label('RFC:', [
                                                'title' => $model->rfc
                                            ]) ?>
                                        </div>

                                        <div class="w-100"></div>
                                        <div class="col-6 col-sm-4">
    <?= $form->field($model, 'cat_nivel_estudio_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatNivelEstudio::find()->all(), 'id', 'nivel_estudio'),
        'options' => [
            'placeholder' => 'Seleccionar Nivel de Estudio', 
            'disabled' => true,
            'title' => $model->cat_nivel_estudio_id ? 
                CatNivelEstudio::findOne($model->cat_nivel_estudio_id)->nivel_estudio : ''
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP,
        'pluginEvents' => [
            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
        ],
    ])->label('Nivel de estudios', [
        'title' => $model->cat_nivel_estudio_id ? 
            CatNivelEstudio::findOne($model->cat_nivel_estudio_id)->nivel_estudio : ''
    ]) ?>
</div>

<div class="col-6 col-sm-4">
    <?= $form->field($model, 'institucion_educativa')->textInput([
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->institucion_educativa,
        'autocomplete'=>"off"
    ])->label('Institución Educativa', [
        'title' => $model->institucion_educativa
    ]) ?>
</div>

<div class="col-6 col-sm-2">
    <?= $form->field($model, 'profesion')->widget(Select2::classname(), [
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
        'options' => [
            'prompt' => 'Seleccionar Profesión', 
            'disabled' => true,
            'title' => $model->profesion
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP,
    ])->label('Profesión', [
        'title' => $model->profesion
    ]) ?>
</div>

                                    </div>
                                </div>



                            </div>


                            <div class="card bg-light">
                                <div class="card-header bg-success text-white">
                                    <h6>Contacto</h6>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                    <div class="col-6 col-sm-3">
    <?= $form->field($model, 'email')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->email,
         'autocomplete'=>"off"
    ])->label('Email', [
        'title' => $model->email
    ]) ?>
</div>

<div class="col-6 col-sm-2">
    <?= $form->field($model, 'telefono')->textInput([
        'maxlength' => 15, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->telefono,
         'autocomplete'=>"off"

    ])->label('Teléfono', [
        'title' => $model->telefono
    ]) ?>
</div>

<div class="col-6 col-sm-3">
    <?= $form->field($model, 'colonia')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->colonia,
         'autocomplete'=>"off"
    ])->label('Colonia', [
        'title' => $model->colonia
    ]) ?>
</div>

<div class="col-6 col-sm-3">
    <?= $form->field($model, 'calle')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->calle,
         'autocomplete'=>"off"
    ])->label('Calle', [
        'title' => $model->calle
    ]) ?>
</div>

<div class="w-100"></div>

<div class="col-6 col-sm-2">
    <?= $form->field($model, 'numero_casa')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->numero_casa,
         'autocomplete'=>"off"
    ])->label('No. Casa:', [
        'title' => $model->numero_casa
    ]) ?>
</div>

<div class="col-6 col-sm-2">
    <?= $form->field($model, 'codigo_postal')->input('number', [
        'maxlength' => 6,
        'class' => 'form-control',
        'readonly' => true,
        'title' => $model->codigo_postal,
         'autocomplete'=>"off"
    ])->label('Código Postal:', [
        'title' => $model->codigo_postal
    ]) ?>
</div>
<div class="col-6 col-sm-4">
    <?= $form->field($model, 'estado')->widget(Select2::classname(), [
        'data' => [], // Inicialmente vacío, se llenará con JS
        'options' => [
            'placeholder' => 'Selecciona un estado', 
            'id' => 'estado-dropdown', 
            'disabled' => true,
            'title' => $model->estado ? $model->estado : 'No seleccionado'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP,
    ])->label('Estado:', [
        'title' => $model->estado ? $model->estado : 'No seleccionado'
    ]) ?>
</div>

<div class="col-6 col-sm-4">
    <?= $form->field($model, 'municipio')->widget(Select2::classname(), [
        'data' => [], // Inicialmente vacío
        'options' => [
            'placeholder' => 'Selecciona un municipio', 
            'id' => 'municipio-dropdown', 
            'disabled' => true,
            'title' => $model->municipio ? $model->municipio : 'No seleccionado'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP,
    ])->label('Municipio:', [
        'title' => $model->municipio ? $model->municipio : 'No seleccionado'
    ]) ?>
</div>


                                        <h5 class="card-title">Información de Contacto de Emergencia</h5>
                                        <br><br>
                                        <div class="col-6 col-sm-4">
    <?= $form->field($model, 'nombre_contacto_emergencia')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->nombre_contacto_emergencia ? $model->nombre_contacto_emergencia : 'No disponible',
         'autocomplete'=>"off"
    ])->label('Nombre:', [
        'title' => $model->nombre_contacto_emergencia ? $model->nombre_contacto_emergencia : 'No disponible'
    ]) ?>
</div>

<div class="col-6 col-sm-4">
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
        'options' => [
            'prompt' => 'Seleccionar Parentesco', 
            'disabled' => true,
            'title' => $model->relacion_contacto_emergencia ? $model->relacion_contacto_emergencia : 'No disponible'
        ],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP,
    ])->label('Relación:', [
        'title' => $model->relacion_contacto_emergencia ? $model->relacion_contacto_emergencia : 'No disponible'
    ]) ?>
</div>

<div class="col-6 col-sm-3">
    <?= $form->field($model, 'telefono_contacto_emergencia')->textInput([
        'maxlength' => true, 
        'readonly' => true, 
        'class' => 'form-control',
        'title' => $model->telefono_contacto_emergencia ? $model->telefono_contacto_emergencia : 'No disponible',
         'autocomplete'=>"off"
    ])->label('Teléfono:', [
        'title' => $model->telefono_contacto_emergencia ? $model->telefono_contacto_emergencia : 'No disponible'
    ]) ?>
</div>

                                    </div>
                                </div>



                            </div>






                            <!--- principales--->

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

                            <script>
                                // Objeto para almacenar los valores originales de los campos
                                var originalValues = {};

                                // Función para almacenar los valores originales de los campos
                                function storeOriginalValues() {
                                    var fields = document.querySelectorAll('#personal-info-form .form-control');
                                    fields.forEach(function(field) {
                                        originalValues[field.id] = field.value;
                                    });
                                }

                                // Función para restaurar los valores originales de los campos
                                function restoreOriginalValues() {
                                    var fields = document.querySelectorAll('#personal-info-form .form-control');
                                    fields.forEach(function(field) {
                                        if (originalValues.hasOwnProperty(field.id)) {
                                            field.value = originalValues[field.id];
                                        }
                                    });
                                }

                                document.getElementById('edit-button-personal').addEventListener('click', function() {
                                    storeOriginalValues(); // Almacenar los valores originales al iniciar la edición
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
                                    restoreOriginalValues(); // Restaurar los valores originales al cancelar la edición
                                    var fields = document.querySelectorAll('#personal-info-form .form-control');
                                    fields.forEach(function(field) {
                                        field.readOnly = true;
                                        field.disabled = true;
                                    });
                                    document.getElementById('edit-button-personal').style.display = 'block';
                                    document.getElementById('save-button-personal').style.display = 'none';
                                    document.getElementById('cancel-button-personal').style.display = 'none';
                                    location.reload();
                                });
                            </script>







                            <?php Pjax::end(); ?>