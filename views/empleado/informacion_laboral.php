<?php

use app\models\CatDepartamento;

use app\models\CatPuesto;
use app\models\CatTipoContrato;

use yii\helpers\Html;

use kartik\form\ActiveForm;

use yii\helpers\ArrayHelper;

use app\models\JuntaGobierno;

use kartik\select2\Select2;

?>


<br>

<?php
$juntaDirectorDireccion = JuntaGobierno::find()
    ->where(['nivel_jerarquico' => 'Director'])
    ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id])
    ->one();

$jefesDirectores = ArrayHelper::map(
    JuntaGobierno::find()
        ->where(['nivel_jerarquico' => 'Jefe de unidad'])
        ->orWhere(['nivel_jerarquico' => 'Jefe de departamento'])
        ->andWhere(['cat_direccion_id' => $model->informacionLaboral->cat_direccion_id])
        ->all(),
    'id',
    function ($model) {
        return $model->empleado->nombre . ' ' . $model->empleado->apellido;
    }
);





?>
<div class="card">
    <?php $form = ActiveForm::begin([
        'action' => ['actualizar-informacion-laboral', 'id' => $model->id],
        'options' => ['id' => 'laboral-info-form']
    ]); ?>
    <div id="loading-spinner-laboral" style="display: none;">
        <i class="fa fa-spinner fa-spin fa-2x" style="color: #000000;"></i> Procesando...
    </div>

    <div class="card-header bg-info text-white">
        <h3>Información Laboral</h3>
        <?php if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>

            <button type="button" id="edit-button-laboral" class="btn btn-light float-right"><i class="fa fa-edit"></i></button>
            <button type="button" id="cancel-button-laboral" class="btn btn-danger float-right" style="display:none;"><i class="fa fa-times"></i></button>
            <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right  mr-3', 'id' => 'save-button-laboral', 'style' => 'display:none;']) ?>
        <?php endif; ?>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-6 col-sm-6">

                <div class="form-group">

                    <label class="font-weight-bold">Días Laborales</label><br>
                    <div id="dias-laborales-display" class="alert alert-warning">
                        <p><strong>Días laborales actuales:</strong> <?= Html::encode(implode(', ', explode(', ', $model->informacionLaboral->dias_laborales))) ?></p>
                    </div>
                    <div id="dias-laborales-checkboxes" style="display: none;">
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Lunes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Lunes', 'class' => 'form-check-input', 'id' => 'lunes']) ?>
                            <?= Html::label('Lunes', 'lunes', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Martes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Martes', 'class' => 'form-check-input', 'id' => 'martes']) ?>
                            <?= Html::label('Martes', 'martes', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Miércoles', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Miércoles', 'class' => 'form-check-input', 'id' => 'miercoles']) ?>
                            <?= Html::label('Miércoles', 'miercoles', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Jueves', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Jueves', 'class' => 'form-check-input', 'id' => 'jueves']) ?>
                            <?= Html::label('Jueves', 'jueves', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Viernes', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Viernes', 'class' => 'form-check-input', 'id' => 'viernes']) ?>
                            <?= Html::label('Viernes', 'viernes', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Sábado', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Sábado', 'class' => 'form-check-input', 'id' => 'sabado']) ?>
                            <?= Html::label('Sábado', 'sabado', ['class' => 'form-check-label']) ?>
                        </div>
                        <div class="form-check form-check-inline">
                            <?= Html::checkbox('InformacionLaboral[dias_laborales][]', in_array('Domingo', explode(', ', $model->informacionLaboral->dias_laborales)), ['value' => 'Domingo', 'class' => 'form-check-input', 'id' => 'domingo']) ?>
                            <?= Html::label('Domingo', 'domingo', ['class' => 'form-check-label']) ?>
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-6 col-sm-2">
                <?= $form->field($model->informacionLaboral, 'horario_laboral_inicio')->input('time', [
                    'disabled' => true,
                    'title' => $model->informacionLaboral->horario_laboral_inicio,
                ])->label('Hora de entrada', [
                    'title' => $model->informacionLaboral->horario_laboral_inicio,
                ]) ?>
            </div>

            <div class="col-6 col-sm-2">
                <?= $form->field($model->informacionLaboral, 'horario_laboral_fin')->input('time', [
                    'disabled' => true,
                    'title' => $model->informacionLaboral->horario_laboral_fin,
                ])->label('Hora de salida', [
                    'title' => $model->informacionLaboral->horario_laboral_fin,
                ]) ?>
            </div>

            <div class="w-100"></div>

            <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>
                <div class="col-6 col-sm-2">
                    <?= $form->field($model->informacionLaboral, 'cat_tipo_contrato_id')->widget(Select2::classname(), [
                        'data' => ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
                        'options' => [
                            'placeholder' => 'Seleccionar Tipo de Contrato',
                            'disabled' => true,
                            'title' => $model->informacionLaboral->cat_tipo_contrato_id ?
                                CatTipoContrato::findOne($model->informacionLaboral->cat_tipo_contrato_id)->nombre_tipo : '',
                        ],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                        'theme' => Select2::THEME_KRAJEE_BS3,

                    ])->label('Tipo de contrato', [
                        'title' => $model->informacionLaboral->cat_tipo_contrato_id ?
                            CatTipoContrato::findOne($model->informacionLaboral->cat_tipo_contrato_id)->nombre_tipo : ''
                    ]) ?>
                </div>
            <?php endif; ?>
            <div class="col-6 col-sm-2">
                <?= $form->field($model->informacionLaboral, 'fecha_ingreso')->input('date', [
                    'disabled' => true,
                    'title' => $model->informacionLaboral->fecha_ingreso,
                ])->label('Fecha de ingreso', [
                    'title' => $model->informacionLaboral->fecha_ingreso,
                ]) ?>
            </div>



            <div class="col-6 col-sm-4">
                <?= $form->field($model->informacionLaboral, 'cat_departamento_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
                    'options' => [
                        'placeholder' => 'Seleccionar Departamento',
                        'disabled' => true,
                        'title' => $model->informacionLaboral->cat_departamento_id ?
                            CatDepartamento::findOne($model->informacionLaboral->cat_departamento_id)->nombre_departamento : '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_KRAJEE_BS3,


                ])->label('Departamento', ['title' => $model->informacionLaboral->cat_departamento_id ?
                    CatDepartamento::findOne($model->informacionLaboral->cat_departamento_id)->nombre_departamento : '']) ?>
            </div>



            <div class="col-6 col-sm-2">
                <div class="form-group">
                    <label class="control-label" title="<?= Html::encode($model->informacionLaboral->catDireccion->nombre_direccion); ?>">Dirección:</label>
                    <p id="edad-label" title="<?= Html::encode($model->informacionLaboral->catDireccion->nombre_direccion); ?>"><?= Html::encode($model->informacionLaboral->catDireccion->nombre_direccion); ?></p>
                </div>
            </div>

            <div class="col-6 col-sm-2">
                <div class="form-group">
                    <label class="control-label" title="<?= Html::encode($model->informacionLaboral->catDptoCargo->nombre_dpto); ?>">DPTO:</label>
                    <p id="edad-label" title="<?= Html::encode($model->informacionLaboral->catDptoCargo->nombre_dpto); ?>"><?= Html::encode($model->informacionLaboral->catDptoCargo->nombre_dpto); ?></p>
                </div>
            </div>

            <div class="w-100"></div>

            <div class="w-100"></div>

            <div class="col-6 col-sm-3">
                <?= $form->field($model->informacionLaboral, 'junta_gobierno_id')->widget(Select2::classname(), [
                    'data' => $jefesDirectores,
                    'options' => [
                        'placeholder' => 'Seleccionar Jefe o director a cargo',
                        'disabled' => true,
                        'title' => isset($jefesDirectores[$model->informacionLaboral->junta_gobierno_id]) ?
                            $jefesDirectores[$model->informacionLaboral->junta_gobierno_id] : 'No asignado',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_KRAJEE_BS3, 

                    
                ])->label('Jefe inmediato', ['title' => isset($jefesDirectores[$model->informacionLaboral->junta_gobierno_id]) ?
                    $jefesDirectores[$model->informacionLaboral->junta_gobierno_id] : 'No asignado']) ?>
            </div>


            <div class="col-6 col-sm-3">


                <div class="form-group">
                    <label class="control-label" title="<?= $juntaDirectorDireccion ? Html::encode($juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido) : 'No Asignado' ?>">Director de dirección</label>
                    <p id="director-label" title="<?= $juntaDirectorDireccion ? Html::encode($juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido) : 'No asignado' ?>"><?= $juntaDirectorDireccion ? Html::encode($juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido) : 'No asignado' ?></p>
                </div>
            </div>

            <div class="col-6 col-sm-4">
                <?= $form->field($model->informacionLaboral, 'cat_puesto_id')->widget(Select2::classname(), [
                    'data' => ArrayHelper::map(CatPuesto::find()->all(), 'id', 'nombre_puesto'),
                    'options' => [
                        'placeholder' => 'Seleccionar Puesto',
                        'disabled' => true,
                        'title' => $model->informacionLaboral->cat_puesto_id ?
                            CatPuesto::findOne($model->informacionLaboral->cat_puesto_id)->nombre_puesto : '',
                    ],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                    'theme' => Select2::THEME_KRAJEE_BS3, 

                   
                ])->label('Nombramiento', [
                    'title' => $model->informacionLaboral->cat_puesto_id ?
                        CatPuesto::findOne($model->informacionLaboral->cat_puesto_id)->nombre_puesto : ''
                ]) ?>
            </div>


            <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>
                <div class="col-6 col-sm-4">
                    <?= $form->field($model->informacionLaboral, 'numero_cuenta')->input('number', [
                        'maxlength' => 18,
                        'readonly' => true,
                        'class' => 'form-control no-spinner',
                        'title' => $model->informacionLaboral->numero_cuenta,
                    ])->label('Número de cuenta', [
                        'title' => $model->informacionLaboral->numero_cuenta
                    ]) ?>
                </div>


            <?php endif; ?>
            <?php if (Yii::$app->user->can('ver-informacion-completa-empleados')) : ?>

                <div class="col-6 col-sm-3">
                    <?= $form->field($model->informacionLaboral, 'salario')->textInput([
                        'type' => 'number',
                        'step' => '0.01',
                        'readonly' => true,
                        'class' => 'form-control',
                        'title' => $model->informacionLaboral->salario
                    ])->label('Salario', [
                        'title' => $model->informacionLaboral->salario
                    ]) ?>
                </div>


            <?php endif; ?>












            <?php ActiveForm::end(); ?>
            <?php
            $script = <<< JS
$(document).ready(function() {
$('#laboral-info-form').on('beforeSubmit', function() {
var button = $('#save-button-laboral');
var spinner = $('#loading-spinner-laboral');

button.prop('disabled', true); // Deshabilita el botón
spinner.show(); // Muestra el spinner

return true; // Permite que el formulario se envíe
});
});
JS;
            $this->registerJs($script);
            ?>


        </div>
    </div>
</div>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Al cargar la página, guardar los valores originales de los campos
        var originalValues = {};
        var fields = document.querySelectorAll('#laboral-info-form input, #laboral-info-form select');
        fields.forEach(function(field) {
            originalValues[field.id] = field.value;
        });

        // Botón Editar
        document.getElementById('edit-button-laboral').addEventListener('click', function() {
            fields.forEach(function(field) {
                field.readOnly = false;
                field.disabled = false;
            });
            document.getElementById('dias-laborales-display').style.display = 'none';
            document.getElementById('dias-laborales-checkboxes').style.display = 'block';
            document.getElementById('edit-button-laboral').style.display = 'none';
            document.getElementById('save-button-laboral').style.display = 'block';
            document.getElementById('cancel-button-laboral').style.display = 'block';
        });

        // Botón Cancelar
        document.getElementById('cancel-button-laboral').addEventListener('click', function() {
            fields.forEach(function(field) {
                field.readOnly = true;
                field.disabled = true;
                // Restaurar al valor original
                field.value = originalValues[field.id];
            });
            document.getElementById('dias-laborales-display').style.display = 'block';
            document.getElementById('dias-laborales-checkboxes').style.display = 'none';
            document.getElementById('edit-button-laboral').style.display = 'block';
            document.getElementById('save-button-laboral').style.display = 'none';
            document.getElementById('cancel-button-laboral').style.display = 'none';

            location.reload();
        });
    });
</script>