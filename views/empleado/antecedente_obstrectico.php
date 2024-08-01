
<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');

?>
<?php $form = ActiveForm::begin(['action' => ['antecedente-obstrectico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedente Obstrectico</h2>
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
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Fecha probable de parto.', 'p_f_p_p') ?>
                                                        <?= Html::input('date', 'AntecedenteObstrectico[p_f_p_p]', $AntecedenteObstrectico->p_f_p_p, ['class' => 'form-control',  'disabled' => !$editable]) ?>



                                                    </div>
                                                    <!-- Columna derecha con el textarea -->
                                                    <div class="w-100"></div>

                                                    <br>
                                                    <hr class="solid">

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Gestación', 'p_gestacion') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_gestacion]', $AntecedenteObstrectico->p_gestacion, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Aborto', 'p_aborto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_aborto]', $AntecedenteObstrectico->p_aborto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Parto', 'p_parto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_parto]', $AntecedenteObstrectico->p_parto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="col-6 col-sm-1 ">
                                                        <?= Html::label('Cesarea', 'p_cesarea') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_cesarea]', $AntecedenteObstrectico->p_cesarea, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Nacidos vivos', 'p_nacidos_vivo') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_nacidos_vivo]', $AntecedenteObstrectico->p_nacidos_vivo, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Viven', 'p_viven') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_viven]', $AntecedenteObstrectico->p_viven, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Nacidos Muertos', 'p_nacidos_muerto') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_nacidos_muerto]', $AntecedenteObstrectico->p_nacidos_muerto, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Muerto - 1ra semana', 'p_muerto_primera_semana') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_mmuerto_primera_semana]', $AntecedenteObstrectico->p_muerto_primera_semana, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>
                                                    <div class="w-100"></div>

                                                    <div class="col-6 col-sm-3 ">

                                                    </div>

                                                    <div class="col-6 col-sm-3 ">
                                                        <?= Html::label('Muerto despues - 1ra semana', 'p_muerto_despues_semana') ?>
                                                        <?= Html::input('number', 'AntecedenteObstrectico[p_mmuerto_despues_semana]', $AntecedenteObstrectico->p_muerto_despues_semana, ['class' => 'form-control',  'disabled' => !$editable]) ?>

                                                    </div>

                                                    <div class="w-100"></div>

                                                    <br>




                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_intergenesia]', $AntecedenteObstrectico->p_intergenesia, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_intergenesia',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Intergenesia', 'p_intergenesia', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_malformaciones]', $AntecedenteObstrectico->p_malformaciones, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_malformaciones',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Malformaciones', 'p_malformaciones', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_atencion_prenatal]', $AntecedenteObstrectico->p_atencion_prenatal, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_atencion_prenatal',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Atención prenatal', 'p_atencion_prenatal', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_parto_prematuro]', $AntecedenteObstrectico->p_parto_prematuro, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_parto_prematuro',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Parto prematuro', 'p_parto_prematuro', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 col-sm-3 ">

                                                        <div class="custom-control custom-checkbox">
                                                            <?= Html::checkbox('AntecedenteObstrectico[p_isoinmunizacion]', $AntecedenteObstrectico->p_isoinmunizacion, [
                                                                'class' => 'custom-control-input',
                                                                'id' => 'p_isoinmunizacion',
                                                                'disabled' => !$editable
                                                            ]) ?>
                                                            <?= Html::label('Isoinmunización', 'p_isoinmunizacion', ['class' => 'custom-control-label']) ?>
                                                        </div>
                                                    </div>


                                                    <div class="w-100"></div>
                                                    <br>
                                                    <div class="col-6 col-sm-8 ">

                                                        <div class="form-group">
                                                            <?= Html::label('Medicación Gestacional', 'p_medicacion_gestacional') ?>
                                                            <?= Html::textarea('AntecedenteObstrectico[p_medicacion_gestacional]', $AntecedenteObstrectico->p_medicacion_gestacional, ['class' => 'form-control', 'rows' => 2, 'id' => 'p_medicacion_gestacional',  'disabled' => !$editable]) ?>
                                                        </div>
                                                    </div>
                                                </div>


                                                <div class="form-group">
                                                    <?= Html::label('Observación General') ?>


                                                    <?php

                                                    if ($editable) {
                                                        echo $form->field($AntecedenteObstrectico, 'observacion')->widget(FroalaEditorWidget::className(), [
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
                                                        $htmlcont = Html::decode($AntecedenteObstrectico->observacion);
                                                        echo HtmlPurifier::process($htmlcont);
                                                    }
                                                    ?>
                                                </div>


                                                <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                    <div class="form-group">
                                                        <?= Html::submitButton('Guardar Todos los Cambios &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success fa-lg']) ?>
                                                    </div>

                                                <?php } ?>

                                            </div>


                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php ActiveForm::end(); ?>
                           