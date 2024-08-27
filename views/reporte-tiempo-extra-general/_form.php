<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap5\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ReporteTiempoExtra */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <?php $form = ActiveForm::begin(['options' => ['id' => 'employee-form']]); ?>
                <div class="card-header bg-info text-white">
                    <h2>REPORTE DE TIEMPO EXTRA</h2>
                    <p>Empleado: <?= $empleado->nombre . ' ' . $empleado->apellido ?></p>

                    <?php
                    $usuarioActual = Yii::$app->user->identity;
                    $empleadoActual = $usuarioActual->empleado;

                    if ($empleadoActual->id === $empleado->id) {
                        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
                            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                            'encode' => false,
                        ]);
                    } else {
                        if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) {
                            echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
                                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                                'encode' => false,
                            ]);
                        } else {
                            echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
                                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                                'encode' => false,
                            ]);
                        }
                    }
                    ?>
                    <div id="loading-spinner" style="display: none;">
                        <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12"></div>
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    echo Alert::widget([
                                        'options' => ['class' => 'alert-' . $type],
                                        'body' => $message,
                                    ]);
                                }
                                ?>
                            </div>
                            <div class="permiso-fuera-trabajo-form">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div id="activities-container">
                                            <div class="activity-item">
                                                <div class="row">
                                                <div class="col-6 col-sm-2">
                                                        <?= $form->field($actividadModel, 'numero_empleado[]')->textInput() ?>
                                                    </div>
                                                    <div class="col-6 col-sm-2">
                                                        <?= $form->field($actividadModel, 'fecha[]')->input('date') ?>
                                                    </div>
                                                    <div class="col-6 col-sm-2">
                                                        <?= $form->field($actividadModel, 'hora_inicio[]')->input('time', ['class' => 'form-control horario-inicio']) ?>
                                                    </div>
                                                    <div class="col-6 col-sm-2">
                                                        <?= $form->field($actividadModel, 'hora_fin[]')->input('time', ['class' => 'form-control horario-fin']) ?>
                                                    </div>
                                                    <div class="col-6 col-sm-4">
                                                        <?= $form->field($actividadModel, 'actividad[]')->textarea(['rows' => 2]) ?>
                                                    </div>
                                                   
                                                    <?= $form->field($actividadModel, 'no_horas[]')->hiddenInput(['class' => 'no-horas', 'readonly' => true])->label(false) ?>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <button type="button" id="add-activity" class="btn btn-success">Agregar Actividad</button>
                                        <?= Html::submitButton('Generar <i class="fa fa-check"></i>', ['class' => 'btn btn-success btn-lg float-right', 'id' => 'save-button-personal']) ?>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>

                            <?php
                            $script = <<< JS
                            $('#employee-form').on('beforeSubmit', function() {
                                var button = $('#save-button-personal');
                                var spinner = $('#loading-spinner');
                                button.prop('disabled', true);
                                spinner.show();
                                return true;
                            });

                            $("#add-activity").on("click", function () {
                                var newActivity = `<div class="activity-item">
                                    <hr>
                                    <div class="row">
                                     <div class="col-6 col-sm-2">
                                            <div class="form-group field-reporte-extra-numero_empleado">
                                                <input type="text" class="form-control" name="ActividadReporteTiempoExtraGeneral[numero_empleado][]" />
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-2">
                                            <div class="form-group field-reporte-extra-fecha">
                                                <input type="date" class="form-control" name="ActividadReporteTiempoExtraGeneral[fecha][]" />
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-2">
                                            <div class="form-group field-reporte-extra-hora_inicio">
                                                <input type="time" class="form-control horario-inicio" name="ActividadReporteTiempoExtraGeneral[hora_inicio][]" />
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-2">
                                            <div class="form-group field-reporte-extra-hora_fin">
                                                <input type="time" class="form-control horario-fin" name="ActividadReporteTiempoExtraGeneral[hora_fin][]" />
                                                <input type="hidden" class="no-horas" name="ActividadReporteTiempoExtraGeneral[no_horas][]" readonly />
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-4">
                                            <div class="form-group field-reporte-extra-actividad">
                                                <textarea class="form-control" rows="2" name="ActividadReporteTiempoExtraGeneral[actividad][]"></textarea>
                                            </div>
                                        </div>
                                       
                                        <div class="col-12 col-sm-2 text-center">
                                            <button type="button" class="btn btn-danger btn-remove-activity">Quitar</button>
                                        </div>
                                    </div>
                                </div>`;
                                $("#activities-container").append(newActivity);
                            });

                            $(document).on("click", ".btn-remove-activity", function () {
                                $(this).closest(".activity-item").remove();
                            });

                            $(document).on("change", ".horario-inicio, .horario-fin", function () {
                                var row = $(this).closest(".row");
                                var horarioInicio = row.find(".horario-inicio").val();
                                var horarioFin = row.find(".horario-fin").val();
                                if (horarioInicio && horarioFin) {
                                    var inicio = new Date('1970-01-01T' + horarioInicio + 'Z');
                                    var fin = new Date('1970-01-01T' + horarioFin + 'Z');
                                    var diferencia = (fin - inicio) / 1000 / 60 / 60; // diferencia en horas
                                    if (diferencia < 0) {
                                        diferencia += 24; // ajuste si la hora de fin es después de medianoche
                                    }
                                    row.find(".no-horas").val(Math.floor(diferencia));
                                }
                            });
                            JS;
                            $this->registerJs($script);
                            ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
