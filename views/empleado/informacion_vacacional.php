<?php 
//IMPORTACIONES
use kartik\form\ActiveForm;
use yii\helpers\Html;
use yii\web\JsExpression;
use kartik\daterange\DateRangePicker;

?>


                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="card">
                                            <div class="card-header bg-success text-dark text-center">

                                        <h3>Historial de cambios de periodos vacacionales</h3>
                                        <h6>Total de dias vacacionales: <?= $model->informacionLaboral->vacaciones->total_dias_vacaciones ?></h6>

                                    </div>
                                            <div class="card-body">
                                               
                                                <?php
                                                //FORMULARIO PARA REGISTRAR LA INFORMACIÓN VACACIONAL DEL EMPLEADO
                                                $form = ActiveForm::begin([
                                                    'action' => ['actualizar-primer-periodo', 'id' => $model->id],
                                                    'options' => ['id' => 'first-period-form']
                                                ]); ?>
                                        <!--        <div class="card-header bg-info text-white">
                                                    <h3>PRIMER PERIODO</h3>-->
                                                    <?php setlocale(LC_TIME, "es_419.UTF-8"); ?>


                                                  
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

                                                 
                                                    <?php ActiveForm::end(); ?>
                                                    </div>
                                    </div>

                                </div>
                            </div>

                       