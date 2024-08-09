<?php
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use froala\froalaeditor\FroalaEditorWidget;
use kartik\file\FileInput;

/* @var $this yii\web\View */
/* @var $model app\models\Aviso */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card" id="este-card">
                <div class="card-header bg-info text-white">
                    <h3>Aviso:</h3>
                    <?php echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalgestionrh'], [
                                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                                'encode' => false,
                            ]);?>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <!-- Aquí puedes agregar más contenido si es necesario -->
                        </div>
                    </div>
                    <div class="aviso-form">
                        <?php $form = ActiveForm::begin(); ?>
                        <div class="row">
                            <div class="col-6 col-sm-9">
                                <?= $form->field($model, 'titulo')->textInput(['maxlength' => true]) ?>

                            </div>
                            <div class="col-6 col-sm-3">

                            <?php if ($model->imagen): ?>
                                <div class="col-12 mt-3">
                                    <div class="form-group">
                                        <label>Imagen Actual:</label>
                                        <div>
                                            <img src="<?= Yii::$app->urlManager->createUrl(['aviso/ver-imagen', 'nombre' => $model->imagen]) ?>" class="img-fluid" alt="<?= Html::encode($model->titulo) ?>" style="max-width: 100%; height: auto;">
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            </div>
                            <div class="col-6 col-sm-9">
                                <?= $form->field($model, 'mensaje')->widget(FroalaEditorWidget::className(), [
                                    'options' => ['id' => 'exp-fisca'],
                                    'clientOptions' => [
                                        'toolbarInline' => false,
                                        'theme' => 'royal',
                                        'language' => 'es',
                                        'height' => 200,
                                        'pluginsEnabled' => [
                                            'align', 'charCounter', 'codeBeautifier', 'codeView', 'colors',
                                            'draggable', 'emoticons', 'entities', 'fontFamily',
                                            'fontSize', 'fullscreen', 'inlineStyle',
                                            'lineBreaker', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
                                            'quickInsert', 'quote', 'save', 'table', 'url', 'wordPaste'
                                        ]
                                    ]
                                ])->label('Motivo:') ?>
                            </div>
                            <div class="col-6 col-sm-3">
                                <?= $form->field($model, 'imagen')->widget(FileInput::classname(), [
                                    'pluginOptions' => [
                                        'showUpload' => false,
                                        'showCancel' => false,
                                        'allowedFileExtensions' => ['jpeg', 'jpg', 'png'],
                                    ],
                                ])->label('Cambiar imagen:') ?>
                            </div>
                          
                            <div class="form-group">
                                <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-success']) ?>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
