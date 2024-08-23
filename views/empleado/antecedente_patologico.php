<?php
//IMPORTACIONES
use yii\helpers\Html;
use froala\froalaeditor\FroalaEditorWidget;
use yii\helpers\HtmlPurifier;
use kartik\form\ActiveForm;

$editable = Yii::$app->user->can('editar-expediente-medico');

?>

<?php 
//FORMULARIO PARA AGREGAR O EDITAR INFORMACIÓN DE REGISTRO
$form = ActiveForm::begin(['action' => ['empleado/antecedente-patologico', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedentes Patológicos</h2>
                                        </div>
                                        <div class="card-body bg-light">
                                            <?php
                                            // Verifica si el usuario tiene permiso para editar
                                            $editable = Yii::$app->user->can('editar-expediente-medico');
                                            ?>

                                            <?php if ($editable) : ?>
                                                <div class="form-group">
                                                    <?= $form->field($antecedentePatologico, 'descripcion_antecedentes')->widget(FroalaEditorWidget::className(), [
                                                        'clientOptions' => [
                                                            'toolbarInline' => false,
                                                            'theme' => 'royal', // optional: dark, red, gray, royal
                                                            'language' => 'es', // optional: ar, bs, cs, da, de, en_ca, en_gb, en_us ...
                                                            'height' => 400,
                                                            'pluginsEnabled' => [
                                                                'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                                                'draggable', 'emoticons', 'entities', 'fontFamily',
                                                                'fontSize', 'fullscreen', 'inlineStyle',
                                                                'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                                                'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                                            ],
                                                        ]
                                                    ])->label(false) ?>
                                                </div>

                                                <div class="form-group text-right">
                                                    <?= Html::submitButton('Guardar &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success fa-lg']) ?>
                                                </div>
                                            <?php else : //SI EL PERMISO ES DE SOLO VISUALIZAR, SE MUESTRA EL PURO CONTENIDO DEL REGISTRO?>
                                                <div class="form-group">
                                                    <?= Html::label('Descripción de Antecedentes Patológicos', 'descripcion_antecedentes') ?>
                                                    <?php
                                                    $contenidoHTML = Html::decode($antecedentePatologico->descripcion_antecedentes);
                                                    echo HtmlPurifier::process($contenidoHTML);
                                                    ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="alert alert-white custom-alert" role="alert">
                                                <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Peso al nacer, anormalidades perinatales, desarrollo físico y mental, y el esquema básico de vacunación.
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                           