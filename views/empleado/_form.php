<?php
//IMPORTACIONES
use app\models\CatDepartamento;
use app\models\CatDireccion;
use app\models\CatPuesto;
use app\models\CatTipoContrato;
use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use kartik\file\FileInput;
use app\models\Departamento;
use kartik\select2\Select2;
use yii\bootstrap5\Alert;
use yii\helpers\ArrayHelper;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $form yii\bootstrap4\ActiveForm */
//CARGAR ESTILOS
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);
?>

<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <?php
                //FORMULARIO DE CREACION DE EMPLEADO
                $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data', 'id' => 'form-empleado']]); ?>
                <div id="loading-spinner-laboral" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x" style="color: #000000;"></i> Procesando...
    </div>
                <div class="card-header bg-primary text-white">
                    <h2>AÑADIR NUEVO EMPLEADO</h2>
                    <?= Html::submitButton('Guardar y enviar datos de usuario <i class="fa fa-save fa-md"></i>', [
                        'class' => 'btn btn-light btn-lg float-right', 
                        'id' => 'save-button-personal'
                    ]) ?>


                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">
                                <?php
                                //ALERTA
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

                            <div class="empleado-form">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="card">
                                            <div class="card-header bg-info text-white">Información del Empleado</div>
                                            <div class="card-body">
                                            <div class="col-6 col-sm-8">

                                                <?= $form->field($usuario, 'rol')->dropDownList([
                                                    1 => 'Trabajador',
                                                    2 => 'Gestor de recursos humanos',
                                                    3 => 'Medico'
                                                ], ['prompt' => 'Selecciona el rol'])->label('Seleccione el rol del empleado:') ?>
                                            </div>
                                            <div class="col-6 col-sm-6">

                                            <?= $form->field($model, 'numero_empleado')->input('number', ['maxlength' => 4, 'class' => "form-control"]) ?>

                                            </div>
                                            <div class="col-6 col-sm-10">

                                                <?= $form->field($model, 'nombre')->textInput(['maxlength' => true])->label('Nombre del empleado:') ?>
                                            </div>
                                            <div class="col-6 col-sm-10">
                                                <?= $form->field($model, 'apellido')->textInput(['maxlength' => true])->label('Apellido del empleado:') ?>
                                            </div>
                                            <div class="col-6 col-sm-10">
                                                <?= $form->field($model, 'email')->textInput(['maxlength' => true])->label('Email del empleado:') ?>
                                            </div>
                                                <?= 
                                                //SE CARGA LA EXTENSIÓN DE KARTIK / FILEINPUT PARA SUBIR ARCHIVOS
                                                $form->field($model, 'foto')->widget(FileInput::classname(), [
                                                   // 'options' => ['accept' => 'file/*'],
                                                    'pluginOptions' => [
                                                        'showUpload' => false,
                                                        'showCancel' => false,
                                                        'allowedFileExtensions' => ['jpeg', 'jpg', 'png'],
 

                                                    ],
                                                ])->label('Foto del empleado:') ?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card">
                                        <div class="card-header gradient-verde text-white">Información Laboral</div>
<div class="card-body">
    <div class="col-6 col-sm-12">
        <?=
        //SE CARGA EL WIDGET (EXTENSION) SELECT2 CON LOS DATOS DEL MODELO DE CAT_DEPARTAMENTO
        $form->field($informacion_laboral, 'cat_departamento_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
            'language' => 'es',
            'options' => ['placeholder' => 'Seleccione departamento'],
            'pluginOptions' => ['allowClear' => true],
            'theme' => Select2::THEME_BOOTSTRAP,
        ])->label('Departamento al que pertenece:') ?>
    </div>

    <div class="col-6 col-sm-12">
        <?=
        //SE CARGA EL WIDGET (EXTENSION) SELECT2 CON LOS DATOS DEL MODELO DE CAT_PUESTO
        $form->field($informacion_laboral, 'cat_puesto_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(CatPuesto::find()->all(), 'id', 'nombre_puesto'),
            'language' => 'es',
            'options' => ['placeholder' => 'Seleccione puesto del empleado'],
            'pluginOptions' => ['allowClear' => true],
            'theme' => Select2::THEME_BOOTSTRAP,
        ])->label('Puesto del empleado:') ?>
    </div>

    <div class="col-6 col-sm-8">
        <?=
        //SE CARGA EL WIDGET (EXTENSION) SELECT2 CON LOS DATOS DEL MODELO DE CAT_TIPO_CONTRATO
        $form->field($informacion_laboral, 'cat_tipo_contrato_id')->widget(Select2::classname(), [
            'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
            'language' => 'es',
            'options' => ['placeholder' => 'Seleccione el tipo de contrato'],
            'pluginOptions' => ['allowClear' => true],
            'theme' => Select2::THEME_BOOTSTRAP,
        ])->label('Tipo de contrato del empleado:') ?>
    </div>

    <div class="col-6 col-sm-8">
        <?= $form->field($model, 'profesion')->dropDownList([
            'No tiene' => 'No tiene',
            'ING.' => 'ING.',
            'LIC.' => 'LIC.',
            'PROF.' => 'PROF.',
            'ARQ.' => 'ARQ.',
            'C.' => 'C.',
            'DR.' => 'DR.',
            'DRA.' => 'DRA.',
            'TEC.' => 'TEC.',
        ], ['prompt' => 'Selecciona el nivel académico...'])->label('Profesión:') ?>
    </div>

    <div class="col-6 col-sm-6">
        <?= $form->field($informacion_laboral, 'fecha_ingreso')->input('date')->label('Fecha de ingreso del empleado:') ?>
    </div>
</div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <?php ActiveForm::end(); ?>
                <?php
                //SECRIPT PARA ALERTAR AL USUARIO QUE SU SOLICITUD SE ESTRA PROCESANDO
                                        $script = <<< JS
    $('#form-empleado').on('beforeSubmit', function() {
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
