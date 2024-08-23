<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\bootstrap5\Alert;

use yii\web\View;
use froala\froalaeditor\FroalaEditorWidget;
/* @var $this yii\web\View */
/* @var $model app\models\CitaMedica */
/* @var $form yii\bootstrap4\ActiveForm */
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(['options' => [ 'id' => 'employee-form']]); ?>
                <div class="card-header bg-info">
                    <h2>CITA MEDICA</h2>
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>

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
    } else if (Yii::$app->user->can('medico')) {
        echo Html::a('<i class="fa fa-chevron-left"></i> Volver', 'javascript:void(0);', [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
            'onclick' => 'window.history.back(); return false;',
        ]);
    } 
    
    
    else {
        echo Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
            'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
            'encode' => false,
        ]);
    }
}
?>
<div id="loading-spinner" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x"></i> Procesando...
    </div>

                </div>

                <div class="card-body">
                    <div class="row">
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

    <?= $form->field($model, 'fecha_para_cita')->input('date') ?>
    </div>
    <div class="col-6 col-sm-2">


    <?= $form->field($model, 'horario_inicio')->input('time') ?>
    </div>
    

    
<?= $form->field($model, 'comentario')->widget(FroalaEditorWidget::className(), [
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
                                                                    ])->label('Motivo:');?>
    
    </div>

    <?= Html::submitButton('Generar <i class="fa fa-check"></i>', [
                        'class' => 'btn btn-success btn-lg float-right', 
                        'id' => 'save-button-personal'
                    ]) ?>

    </div>
                                        </div>
                                   


<?php ActiveForm::end(); ?>

<?php
$script = <<< JS
    $('#employee-form').on('beforeSubmit', function() {
        var button = $('#save-button-personal');
        var spinner = $('#loading-spinner');

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

             
            </div>
        </div>
    </div>
</div>

