<?php
//IMPORTACIONES

use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;
use kartik\form\ActiveForm;

//SE ASIGNA EL PERMISO DE EDITAR SOBRE LOS REGISTROS A UNA VARIABLE
$editable = Yii::$app->user->can('editar-expediente-medico');

?>
<?php
//FORMULARIO QUE SE ENCARGA DE MOSTRAR LA INFORMACIÓN EN LOS REGITROS O QUE PERMITE EDITAR EL REGISTRO
$form = ActiveForm::begin(['action' => ['empleado/no-patologicos', 'id' => $model->id]]); ?>
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
                                                    'align',
                                                    'charCounter',
                                                    'codeBeautifier',
                                                    'codeView',
                                                    'colors',
                                                    'draggable',
                                                    'emoticons',
                                                    'entities',
                                                    'fontFamily',
                                                    'fontSize',
                                                    'fullscreen',
                                                    'inlineStyle',
                                                    'lineBreaker',
                                                    'link',
                                                    'lists',
                                                    'paragraphFormat',
                                                    'paragraphStyle',
                                                    'quickInsert',
                                                    'quote',
                                                    'save',
                                                    'table',
                                                    'url',
                                                    'wordPaste'
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
///SCRIPT QUE SE ENCARGA DE MOSTRAR CAMPOS OCULTOS
//PERMITE QUE SE MUESTREN ESOS CAMPOS EN CASO DE QUE SEAN VERDAEROS
//EN RELACIÓN A OTRO CAMPO

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

    toggleEjercicioFields();
    toggleDeporteFields();

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


<?php //SE SEPARAN LOS CAMPOS EN DIFERENTES TARJETAS, SIN EMBARGO, SIGUEN SIENDO PARTE DEL MISMO FORMULARIO?>
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
                                                'class' => 'form-control',
                                                'disabled' => !$editable // Deshabilitar si no tiene permiso
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
                    ///SCRIPT QUE SE ENCARGA DE MOSTRAR CAMPOS OCULTOS
//PERMITE QUE SE MUESTREN ESOS CAMPOS EN CASO DE QUE SEAN VERDAEROS
//EN RELACIÓN A OTRO CAMPO
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

    toggleCafeFields();
    toggleDietaFields();
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
                    ///SCRIPT QUE SE ENCARGA DE MOSTRAR CAMPOS OCULTOS
//PERMITE QUE SE MUESTREN ESOS CAMPOS EN CASO DE QUE SEAN VERDAEROS
//EN RELACIÓN A OTRO CAMPO
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

    toggleAlcoholFields();
    toggleExAlcoholicoFields();

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
                    ///SCRIPT QUE SE ENCARGA DE MOSTRAR CAMPOS OCULTOS
//PERMITE QUE SE MUESTREN ESOS CAMPOS EN CASO DE QUE SEAN VERDAEROS
//EN RELACIÓN A OTRO CAMPO
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

  
    toggleTabaquismoFields();
    toggleExFumadorFields();

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
                                                'class' => 'form-control',
                                                'prompt' => 'Seleccione la religión',
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
                                                'class' => 'form-control',
                                                'prompt' => 'Seleccione una opción',
                                                'disabled' => !$editable
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
                                                'class' => 'form-control',
                                                'prompt' => 'Seleccione el tipo de sangre',
                                                'disabled' => !$editable
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
                                         
                                            <div class="form-group">
                                                <?= Html::label('Frecuencia de su consumo', 'p_frecuencia_droga') ?>
                                                <?= Html::dropDownList('AntecedenteNoPatologico[p_frecuencia_droga]', $antecedenteNoPatologico->p_frecuencia_droga, [

                                                    'Casual' => 'Casual',
                                                    'Moderado' => 'Moderado',
                                                    'Intenso' => 'Intenso',
                                                ], [
                                                    'class' => 'form-control',
                                                    'prompt' => 'Seleccione la frecuencia',
                                                    'disabled' => !$editable
                                                ]) ?>
                                            </div>
                                            <?= Html::label('¿A qué edad se inicio el consumo?', 'p_edad_droga') ?>
                                            <?= Html::input('number', 'AntecedenteNoPatologico[p_edad_droga]', $antecedenteNoPatologico->p_edad_droga, ['class' => 'form-control', 'disabled' => !$editable]) ?>


                                            <div class="custom-control custom-checkbox">
                                                <?= Html::checkbox('AntecedenteNoPatologico[p_droga_intravenosa]', $antecedenteNoPatologico->p_droga_intravenosa, [
                                                    'class' => 'custom-control-input',
                                                    'id' => 'p_droga_intravenosa',
                                                    'disabled' => !$editable
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
                    ///SCRIPT QUE SE ENCARGA DE MOSTRAR CAMPOS OCULTOS
//PERMITE QUE SE MUESTREN ESOS CAMPOS EN CASO DE QUE SEAN VERDAEROS
//EN RELACIÓN A OTRO CAMPO
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

    toggleDrogasFields();
    toggleExAdictoFields();
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
                            <h5>OBSERVACION GENERAL / OTRAS OBSERVACIONES</h5>

                        </div>
                        <div class="card-body bg-light">
                            <div class="row">

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
                                                    'align',
                                                    'charCounter',
                                                    'codeBeautifier',
                                                    'codeView',
                                                    'colors',
                                                    'draggable',
                                                    'emoticons',
                                                    'entities',
                                                    'fontFamily',
                                                    'fontSize',
                                                    'fullscreen',
                                                    'inlineStyle',
                                                    'lineBreaker',
                                                    'link',
                                                    'lists',
                                                    'paragraphFormat',
                                                    'paragraphStyle',
                                                    'quickInsert',
                                                    'quote',
                                                    'save',
                                                    'table',
                                                    'url',
                                                    'wordPaste'
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
            </div>

        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>