
<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');
$modelAntecedentePerinatal = \app\models\AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePerinatal) {
    $modelAntecedentePerinatal = new \app\models\AntecedentePerinatal();
    $modelAntecedentePerinatal->expediente_medico_id = $expedienteMedico->id;
}

?>
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
                                                    
                                                   
                                                    <div class="col-6 col-sm-8">
                                                        <div class="form-group">
                                                            <?= Html::label('Complicaciones', 'p_complicacion') ?>
                                                            <?= Html::textarea('AntecedentePerinatal[p_complicacion]', $AntecedentePerinatal->p_complicacion, ['class' => 'form-control', 'rows' => 2, 'id' => 'p_complicacion', 'disabled' => !$editable]) ?>
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

                           