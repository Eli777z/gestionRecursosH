<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap5\Alert;
use yii\web\View;
use froala\froalaeditor\FroalaEditorWidget;



/* @var $this yii\web\View */
/* @var $model app\models\ContratoParaPersonalEventual */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <?php $form = ActiveForm::begin(['options' => ['id' => 'employee-form']]); ?>
                <div class="card-header bg-info text-white">
                    <h2>SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL</h2>
                    <p>Empleado: <?= $empleado->nombre . ' ' . $empleado->apellido ?></p>

                    <?php
                    $usuarioActual = Yii::$app->user->identity;
                    $empleadoActual = $usuarioActual->empleado;

                    if ($empleadoActual->id === $empleado->id) {
                        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
                            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                            'encode' => false,
                        ]);
                    } else {
                        if (Yii::$app->user->can('crear-formatos-incidencias-empleados')) {
                            echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
                                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                                'encode' => false,
                            ]);
                        } else {
                            echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
                                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                                'encode' => false,
                            ]);
                        }
                    }
                    ?>

                    
                </div>

                <div id="loading-spinner-laboral" style="display: none;">
                    <i class="fa fa-spinner fa-spin fa-2x" style="color: #000000;"></i> Procesando...
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                        </div>
                        <div class="col-md-12">
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
<div class="contrato-para-personal-eventual-form">

<div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">

                                        <div class="form-group">
    <label class="control-label">Número de contrato: </label>
   
        <p class="form-control-static"><?= $numeroContrato ?></p>
    
</div>
  

    <div class="col-6 col-sm-3">

    <?= $form->field($model, 'fecha_inicio')->input('date')->label('Fecha de inicio') ?>

    </div>
    <div class="col-6 col-sm-3">

    <?= $form->field($model, 'fecha_termino')->input('date')->label('Fecha de termino') ?>
    </div>
    <div class="col-6 col-sm-2">
    <?= $form->field($model, 'duracion')->input('number', ['readonly' => true])->label('Días del contrato') ?>
</div>

    <div class="col-6 col-sm-3">

    <?= $form->field($model, 'modalidad')->dropdownList([
        'LISTA DE RAYA' => 'LISTA DE RAYA',
        'PRUEBA' => 'PRUEBA',

    ], ['prompt' => 'Selecciona la modalidad'])->label('Modalidad:') ?>
    </div>
    <div class="w-100"></div>
                                            <div class="col-6 col-sm-9">
                                                <?= $form->field($model, 'actividades_realizar')->widget(FroalaEditorWidget::className(), [
                                                    'options' => [
                                                        'id' => 'exp-fisca'
                                                    ],
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
                                                ])->label('Actividades a realizar'); ?>
                                            </div>


    <div class="w-100"></div>
                                            <div class="col-6 col-sm-9">
                                                <?= $form->field($model, 'origen_contrato')->widget(FroalaEditorWidget::className(), [
                                                    'options' => [
                                                        'id' => 'exp-fisca'
                                                    ],
                                                  
                                                ])->label('Origen de contrato'); ?>
                                            </div>



    </div>
    <div class="form-group">
    <?= Html::submitButton('Generar <i class="fa fa-check"></i>', [
                                            'class' => 'btn btn-success btn-lg float-right',
                                            'id' => 'save-button-personal'
                                        ]) ?>
        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>

                <?php
                $script = <<<JS
                // Script para el botón de mostrar/ocultar y cambiar el texto del botón
               

                $('#employee-form').on('beforeSubmit', function() {
                    var button = $('#save-button-personal');
                    var spinner = $('#loading-spinner-laboral');

                    button.prop('disabled', true); // Deshabilita el botón
                    spinner.show(); // Muestra el spinner

                    return true; // Permite que el formulario se envíe
                });
               
$('#contratoparapersonaleventual-fecha_inicio, #contratoparapersonaleventual-fecha_termino').on('change', function() {
    var fechaInicio = new Date($('#contratoparapersonaleventual-fecha_inicio').val());
    var fechaTermino = new Date($('#contratoparapersonaleventual-fecha_termino').val());
    
    if (!isNaN(fechaInicio) && !isNaN(fechaTermino)) {
        var timeDiff = Math.abs(fechaTermino.getTime() - fechaInicio.getTime());
        var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24)) + 1; // Se suma 1 día como especificaste
        $('#contratoparapersonaleventual-duracion').val(diffDays);
    }
});
JS;
$this->registerJs($script);
                
                ?>



            </div>
        </div>
    </div>
</div>
