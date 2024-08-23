<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno;
use kartik\select2\Select2;
use app\models\Empleado;
use yii\bootstrap5\Alert;
use yii\web\View;
use froala\froalaeditor\FroalaEditorWidget;

/* @var $this yii\web\View */
/* @var $model app\models\ComisionEspecial */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <?php $form = ActiveForm::begin(['options' => ['id' => 'employee-form']]); ?>
                <div class="card-header bg-info text-white">
                    <h2>COMISIÓN ESPECIAL</h2>
                    <p>Empleado: <?= $empleado->nombre . ' ' . $empleado->apellido ?></p>

                    <?php
                    // Obtener el ID del usuario actual
                    $usuarioActual = Yii::$app->user->identity;
                    $empleadoActual = $usuarioActual->empleado;

                    // Comparar el ID del empleado actual con el ID del empleado para el cual se está creando el registro
                    if ($empleadoActual->id === $empleado->id) {
                        // El empleado está creando un registro para sí mismo
                        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
                            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                            'encode' => false,
                        ]);
                    } else {
                        // El empleado está creando un registro para otro empleado
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
                            <p><strong>Permisos usados:</strong> <?= $permisosUsados ?></p>
                            <p><strong>Permisos disponibles:</strong> <?= $permisosDisponibles ?></p>
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

                            <div class="comision-especial-form">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-6 col-sm-3">
                                                <?= $form->field($motivoFechaPermisoModel, 'fecha_permiso')->input('date')->label('Fecha de permiso') ?>
                                            </div>
                                            <div class="w-100"></div>
                                            <div class="col-6 col-sm-9">
                                                <?= $form->field($motivoFechaPermisoModel, 'motivo')->widget(FroalaEditorWidget::className(), [
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
                                                ])->label('Motivo:'); ?>
                                            </div>

                                            <div class="col-6 col-sm-4">
                                            </div>
                                        </div>

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
                JS;
                $this->registerJs($script);
                ?>
            </div>
        </div>
    </div>
</div>
