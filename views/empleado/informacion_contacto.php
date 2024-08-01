<?php

use yii\helpers\Html;

use kartik\form\ActiveForm;

use kartik\select2\Select2;

?>
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
                           