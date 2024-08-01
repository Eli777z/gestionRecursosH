




<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');

?>
<?php $form = ActiveForm::begin(['action' => ['interrogatorio-medico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Interrogatorio Medico</h2>
                                            <?php if (Yii::$app->user->can('editar-expediente-medico')) { ?>

                                                <div class="float-submit-btn">
                                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                                                </div>
                                            <?php } ?>

                                        </div>
                                        <div class="card-body">
                                            <div class="container">

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CARDIOVASCULAR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_cardiovascular')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_cardiovascular);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Antecedentes de cardiopatías, disnea, tos, hemoptisis, bronquitis frecuente, lipotimias, vértigos, insuficiencia arterial y venosa, sincope, fatiga, palpitaciones, dolor precordial, encuclillamiento, edemas, ascitis, cianosis, estasis venosa, varices.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>DIGESTIVO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_digestivo')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_digestivo);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Apetito, masticación, disfagia, pirosis, regurgitación, distención abdominal, dolor, vomito, hematemesis, evacuaciones diarreicas, melena, pujo y tenesmo, constipación, ictericia, intolerancia a alimentos.
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ENDOCRINO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_endocrino')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_endocrino);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Crecimiento y estatura, perturbaciones somaticas, caracteres sexuales, sensibilidad al calor y al frio y faneras, exoftalmos,diabetes, acne.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>HEMOLINFATICO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_hemolinfatico')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_hemolinfatico);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Anemias, hemolisis, tendencia a hemorragia, adenopatias, menor resistencia a infecciones.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>MAMAS</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_mamas')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_mamas);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Sin descripción.
                                                        </div>
                                                    </div>

                                                </div>
                                                <!-- aqui -->


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>PIEL Y ANEXOS</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_piel_anexos')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_piel_anexos);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Mucosas, piel, pelo, unas, prurito, cambios de coloracion, alopecia, erupciones, infestaciones, micosis.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>REPRODUCTOR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_reproductor')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_reproductor);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>



                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Alteraciones menstruales, dolor pelvico, colporrea patologica, alteraciones de la libido, patologia obstetrica. Alteraciones testiculares, trastornos en la ereccion y/o eyaculacion, alteraciones de la libido.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>RESPIRATORIO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_respiratorio')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_respiratorio);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Obstuccion nasal, disfonia, tos, dolor, expectoracion, disnea, cianosis, hemoptisis.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>SISTEMA NERVIOSO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_sistema_nervioso')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_sistema_nervioso);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Perdida del conocimiento, paralisis, paresias, temblores, coordinacion, convulsiones atrofias, hipo o hiperestesias, cefaleas, algias, vision, audicion, equilibrio, olfato, gusto, sueno, alteraciones de la personalidad, depresion, compulsion, exitacion, atencion, atencion, memoria, cambios en la conducta, afectividad, nerviosismo, angustia.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>SISTEMAS GENERALES</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_sistemas_generales')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_sistemas_generales);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Fiebre, escalofrio, diaforesis, astenia, adinamia, anorexia y variaciones de peso.
                                                        </div>
                                                    </div>

                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>URINARIO</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($InterrogatorioMedico, 'desc_urinario')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($InterrogatorioMedico->desc_urinario);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>Disuria, polaquiuria, tenesmo vesical, hematuria piuria, incontinencia, dolor lumbar, expulsion de calculos, secrecion uretral.
                                                        </div>
                                                    </div>

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
