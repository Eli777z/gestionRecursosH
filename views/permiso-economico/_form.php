<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno;
use kartik\select2\Select2;
use app\models\Empleado;
use hail812\adminlte\widgets\Alert;
use floor12\summernote\Summernote;
use froala\froalaeditor\FroalaEditorWidget;
/* @var $this yii\web\View */
/* @var $model app\models\PermisoEconomico */
/* @var $form yii\bootstrap4\ActiveForm */
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <?php $form = ActiveForm::begin(['options' => [ 'id' => 'employee-form']]); ?>
            <div class="card-header bg-info text-white">
                    <h2>PERMISO ECONOMICO</h2>
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
<div class="permiso-economico-form">
<div class="card bg-light">
                                            <div class="card-body">
                                            <div class="row">


<div class="form-group">
    <label class="control-label">No Permiso Anterior</label>
    <?php if ($noPermisoAnterior === null): ?>
        <p class="form-control-static">Aún no tienes permisos hechos!</p>
    <?php else: ?>
        <p class="form-control-static"><?= $noPermisoAnterior ?></p>
    <?php endif; ?>
</div>

<div class="form-group">
    <label class="control-label">Fecha Permiso Anterior</label>
    <?php if ($fechaPermisoAnterior === null): ?>
        <p class="form-control-static">Aún no tienes permisos hechos!</p>
    <?php else: ?>
        <p class="form-control-static"><?= $fechaPermisoAnterior ?></p>
    <?php endif; ?>
</div>





<div class="col-6 col-sm-2">

<?= $form->field($motivoFechaPermisoModel, 'fecha_permiso')->input('date')->label('Fecha de permiso') ?>
                                            </div>












<?= $form->field($motivoFechaPermisoModel, 'motivo')->widget(FroalaEditorWidget::className(), [
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

<div class="col-6 col-sm-4">

    <?php
    $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    $mostrarCampo = true;

    if ($empleado) {
        $juntaGobierno = JuntaGobierno::find()
            ->where(['empleado_id' => $empleado->id])
            ->andWhere(['nivel_jerarquico' => ['Director', 'Jefe de unidad']])
            ->one();

        if ($juntaGobierno) {
            $mostrarCampo = false;
        }
    }

    $direccion = $model->empleado->informacionLaboral->catDireccion;

    if ($mostrarCampo && $direccion && in_array($direccion->nombre_direccion, ['2.- ADMINISTRACIÓN', '3.- COMERCIAL', '4.- OPERACIONES', '5.- PLANEACION'])) :
    ?>
        <?= $form->field($model, 'jefe_departamento_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(
                JuntaGobierno::find()
                    ->where(['nivel_jerarquico' => 'Jefe de departamento'])
                    ->andWhere(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
                    ->all(),
                'id',
                function ($model) {
                    return $model->empleado->profesion . ' ' . $model->empleado->nombre . ' ' . $model->empleado->apellido;
                }
            ),
            'options' => ['placeholder' => 'Seleccionar Jefe de Departamento'],
            'pluginOptions' => [
                'allowClear' => true,
            ],
            'theme' => Select2::THEME_KRAJEE_BS3, 

        ])->label('Jefe de Departamento') ?>

        <?= $form->field($model, 'nombre_jefe_departamento')->hiddenInput()->label(false) ?>
    <?php endif; ?>
    </div>

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
