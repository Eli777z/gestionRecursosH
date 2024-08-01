

<?php


use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;

use kartik\form\ActiveForm;


$editable = Yii::$app->user->can('editar-expediente-medico');

?>
<div class="row">
                                <?php $form = ActiveForm::begin(['action' => ['alergia', 'id' => $model->id]]); ?>

                                <div class="col-md-12">

                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Alergias</h2>
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




                                                    <div class="form-group">
                                                        <?= Html::label('Observación General') ?>


                                                        <?php

                                                        if ($editable) {
                                                            echo $form->field($Alergia, 'p_alergia')->widget(FroalaEditorWidget::className(), [
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
                                                            $htmlcont = Html::decode($Alergia->p_alergia);
                                                            echo HtmlPurifier::process($htmlcont);
                                                        }
                                                        ?>
                                                    </div>




                                                </div>
                                                <div class="alert alert-white custom-alert" role="alert">
                                                    <i class="fa fa-exclamation-circle" style="color: #007bff;" aria-hidden="true"></i> Escriba NEGADAS o deje en blanco si el paciente no tiene nignuna alergia.
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
                           