<?php 
//IMPORTACIONES
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;

?>

                            <div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <h3>Total de dias vacacionales: <?= $model->informacionLaboral->vacaciones->total_dias_vacaciones ?></h3>
                                    </div>

                                    <li class="dropdown-divider"></li>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="card">
                                                <?php
                                                //FORMULARIO PARA REGISTRAR LA INFORMACIÓN VACACIONAL DEL EMPLEADO
                                                $form = ActiveForm::begin([
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
                                                    <?= $form->field($model->informacionLaboral->vacaciones->periodoVacacional, 'año')->textInput(['type' => 'number', 'disabled' => true, 'autocomplete'=>"off",]) ?>
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
                                                        'options' => ['disabled' => true,'autocomplete'=>"off",
                                                    ],
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
                                            //SCRIPT PARA HABILITAR LA EDICION DE LOS CAMPOS
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
                                                    <?= $form->field($model->informacionLaboral->vacaciones->segundoPeriodoVacacional, 'año')->textInput(['type' => 'number', 'disabled' => true, 'autocomplete'=>"off",]) ?>
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

                                                        'options' => ['disabled' => true, 'autocomplete'=>"off",],
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
                                             //SCRIPT PARA HABILITAR LA EDICION DE LOS CAMPOS
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
                                             //SCRIPT PARA MOSTRAL EL HISTORIAL DE CAMBIOS EN LA INFORMACIÓN VACACIONAL
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

                       