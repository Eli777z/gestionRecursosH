<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use froala\froalaeditor\FroalaEditorWidget;
use yii\bootstrap5\Alert;

/* @var $this yii\web\View */
/* @var $model app\models\ConsultaMedica */
/* @var $form yii\bootstrap4\ActiveForm */
?>

<div class="consulta-medica-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="d-flex align-items-center mb-3">
                                <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>
                            </div>

    <?= $form->field($model, 'expediente_medico_id')->textInput(['readonly' => true]) ?>

    <div class="col-6 col-sm-12">
    
    <div class= "card">

                                               
                                               <div class="card-header custom-nopato text-white text-left">
                                                   <h5>Interrogatorio</h5>
                                               </div>
                                               <div class="card-body">
                                                   <div class="row">

                                                       <!-- Columna derecha con el textarea -->






<?= $form->field($model, 'motivo')->widget(FroalaEditorWidget::className(), [
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
                                                   ])->label('MOTIVOS') ?>

<?= $form->field($model, 'sintomas')->widget(FroalaEditorWidget::className(), [
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
                                                   ])->label('SINTOMAS') ?>




                                                     

                                                   </div>
                                                   
                                               </div>



                                           </div>
                                           </div>
                                           <div class="col-6 col-sm-12">
    
    <div class="card">
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>Exploración</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->


                                                           
                                                            <div class="col-6 col-sm-2">
    <?= $form->field($model, 'presion_arterial_minimo')->input('number', ['step' => '0.01']) ?>
    <?= $form->field($model, 'nivel_glucosa')->input('number', ['step' => '0.01']) ?>
    <?= $form->field($model, 'temperatura_corporal')->input('number', ['step' => '0.01']) ?>
    <?= $form->field($model, 'oxigeno_sangre')->input('number', ['step' => '0.01']) ?>


</div>
<div class="col-6 col-sm-1">
</div>
<div class="col-6 col-sm-2">
    <?= $form->field($model, 'presion_arterial_maximo')->input('number', ['step' => '0.01']) ?>
    <?= $form->field($model, 'frecuencia_cardiaca')->input('number', ['step' => '0.01']) ?>
    <?= $form->field($model, 'frecuencia_respiratoria')->input('number', ['step' => '0.01']) ?>


</div>
<div class="col-6 col-sm-1">
</div>

<div class="col-6 col-sm-2">
<?= $form->field($model, 'estatura')->input('number', ['step' => '0.01']) ?>

<?= $form->field($model, 'peso')->input('number', ['step' => '0.01']) ?>

<?= $form->field($model, 'imc')->input('number', ['step' => '0.01']) ?>
</div>







<?= $form->field($model, 'aspecto_fisico')->widget(FroalaEditorWidget::className(), [
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
                                                        ])->label('Exploración fisica') ?>




                                                          

                                                        </div>
                                                        
                                                    </div>




                                                </div>

                                           </div>
                                           <div class="col-6 col-sm-12">
                                                <div class= "card">
                                               
                                                    <div class="card-header custom-nopato text-white text-left">
                                                        <h5>Evaluación</h5>
                                                    </div>
                                                    <div class="card-body">
                                                        <div class="row">

                                                            <!-- Columna derecha con el textarea -->






<?= $form->field($model, 'diagnostico')->widget(FroalaEditorWidget::className(), [
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
                                                        ])->label('DIAGNOSTICO') ?>

<?= $form->field($model, 'tratamiento')->widget(FroalaEditorWidget::className(), [
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
                                                        ])->label('TRATAMIENTO') ?>




                                                          

                                                        </div>
                                                        
                                                    </div>



                                                </div>
                                           </div>


    <div class="form-group">
        <?= Html::submitButton('Guardar consulta medica', ['class' => 'btn btn-success  float-right']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
