<?php

use yii\helpers\Html;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatTipoContrato;
/* @var $this yii\web\View */
/* @var $model app\models\ParametroFormato */
/* @var $form yii\bootstrap4\ActiveForm */
?>


<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-6">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Limites de formatos</h3>
                   
                  <?php  echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['//site/configuracion'], [
                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                'encode' => false,
            ]);

            ?>
                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>

<div class="parametro-formato-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="col-6 col-sm-6">

<?= $form->field($model, 'tipo_permiso')->dropDownList([
    'PERMISO FUERA DEL TRABAJO' => 'PERMISO FUERA DEL TRABAJO',
    'COMISION ESPECIAL' => 'COMISION ESPECIAL',
    'CAMBIO DE DIA LABORAL' => 'CAMBIO DE DIA LABORAL',
    'CAMBIO DE HORARIO DE TRABAJO' => 'CAMBIO DE HORARIO DE TRABAJO',
    'PERMISO ECONOMICO' => 'PERMISO ECONOMICO',
    'PERMISO SIN GOCE DE SUELDO' => 'PERMISO SIN GOCE DE SUELDO',
    'CAMBIO DE PERIODO VACACIONAL' => 'CAMBIO DE PERIODO VACACIONAL',
    //'REPORTE DE TIEMPO EXTRA' => 'REPORTE DE TIEMPO EXTRA',
    //'REPORTE DE TIEMPO EXTRA GENERAL' => 'REPORTE DE TIEMPO EXTRA GENERAL'
    

   
], ['prompt' => 'Selecciona el nombre del formato'])->label('Formatos:') ?>
</div>


<div class="col-6 col-sm-6">

<?= $form->field($model, 'cat_tipo_contrato_id')->dropDownList(
    ArrayHelper::map(CatTipoContrato::find()->all(), 'id', 'nombre_tipo'),
    ['prompt' => 'Selecciona el tipo de contrato']
)->label('Tipo de Contrato') ?>
</div>

    
<div class="col-6 col-sm-3">
    <?= $form->field($model, 'limite_anual')->input('number') ?>
</div>
<div class="col-6 col-sm-3">
    <div class="form-group">
        <?= Html::submitButton(Yii::t('app', 'Guardar'), ['class' => 'btn btn-success']) ?>
    </div>

</div>

    <?php ActiveForm::end(); ?>

</div>

</div>
<!--.card-body-->
</div>
<!--.card-->
</div>
<!--.col-md-12-->
</div>
<!--.row-->
</div>
