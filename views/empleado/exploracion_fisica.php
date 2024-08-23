



<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');

?>
<?php $form = ActiveForm::begin(['action' => ['exploracion-fisica', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Exploracion Fisica</h2>
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
                                                        <h5>HABITUS EXTERIOR</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>

                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_habitus_exterior')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],
                                                                        'clientOptions' => [
                                                                            'toolbarInline' => false,
                                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                                            'height' => 200,
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
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_habitus_exterior);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Edad aparente, biotipo, estado de conciencia, orientación en tiempo, espacio y persona, facies, postura, marcha, movimientos anormales, estado y color de tegumentos, actitud.
                                                        </div>
                                                    </div>




                                                </div>
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CABEZA</h5>
                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_cabeza')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_cabeza);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Craneo: tipo, forma, volumen, cabello, exostosis, hundimientos, fontanelas.

                                                            Cara: tinte ojos, reflejos pupilares,fondo de ojos, conjuntivas, cornea.

                                                            Nariz: obstruccion, mucosa. Boca: desviacion de las comisuras, aliento, labios y paladar.

                                                            Oidos: conducto auditivo, y timpano.

                                                            Faringe: uvula, secreciones, amigdalas, adenoides.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>CUELLO</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->




                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_cuello')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_cuello);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>


                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Forma, movilidad, contracturas; arterias: pulsos, soplos venosos, fremitos. Traquea, tiroides, cadenas linfaticas, huecos supraclaviculares.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>TORAX</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_torax')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_torax);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspección, forma, volumen, simetría, tiros, red venosa y puntos dolorosos, campos pulmonares, movimientos de amplexion y amplexacion, vibraciones vocales, ganglios satélites y nodulos.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>ABDOMEN</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_abdomen')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_abdomen);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i>
                                                        </div>
                                                    </div>




                                                </div>
                                                <?php if($model->expedienteMedico->empleado->sexo === "Femenino" ){ ?>
                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN GINECOLOGICA</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_exploración_ginecologica')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_exploración_ginecologica);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Exploracion Manual: utero, forma, tamaño, volumen, posicion, consistencia, masas tumorales, fondos de saco y adeherencias.
                                                        </div>
                                                    </div>




                                                </div>
                                                <?php }?>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN DE GENITALES</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->

                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_genitales')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_genitales);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspeccion, madurez, tacto vaginal, tacto rectal, secreciones, vesiculas, y ulceras, verrugas, condilomas u otras lesiones.
                                                        </div>
                                                    </div>




                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>COLUMNA VERTEBRAL</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_columna_vertebral')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_columna_vertebral);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Inspección, posición, dolor, deformaciones, disfunción, alineación, función, simetría, movimiento, flexión, extensión, rotación y lateralidad, curvaturas, lordosis, cifosis, escoliosis, masas musculares, lesiones cutáneas.
                                                        </div>
                                                    </div>

                                                </div>


                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXTREMIDADES</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_extremidades')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_extremidades);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Forma, volumen, piel, unas, dedos, articulaciones, tono, fuerza, reflejos tendinosos, movimientos, pulsos arteriales, simetría, amplitud, frecuencia, ritmo, arcos de movilidad, varices, ulceras, flebitis, micosis, marcha, edemas, reflejos: rotuliano, aquiliano y plantar.
                                                        </div>
                                                    </div>




                                                </div>

                                                <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>EXPLORACIÓN NEUROLOGICA</h5>

                                                    </div>
                                                    <div class="card-body bg-light">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->



                                                            <div class="form-group bg-white">
                                                                <?= Html::label('Descripción') ?>
                                                                <br>


                                                                <?php

                                                                if ($editable) {
                                                                    echo $form->field($ExploracionFisica, 'desc_exploracion_neurologica')->widget(FroalaEditorWidget::className(), [
                                                                        'options' => [
                                                                            'id' => 'exp-fisca'
                                                                        ],

                                                                    ])->label(false);
                                                                } else {
                                                                    // Si no tiene permiso, mostrar el texto plano
                                                                    $htmlcont = Html::decode($ExploracionFisica->desc_exploracion_neurologica);
                                                                    echo HtmlPurifier::process($htmlcont);
                                                                }
                                                                ?>
                                                            </div>

                                                        </div>
                                                        <div class="alert alert-white custom-alert" role="alert">
                                                            <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Razonamiento, atencion, memoria, ansiedad, depresion, alucinaciones, postura corporal, funciones motoras, movimientos corporales voluntarios e involuntarios, paresias, paralisis, marcha, equilibrio, pares craneales, funcion sensorial.
                                                        </div>
                                                    </div>




                                                </div>












                                                <br>
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
