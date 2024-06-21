<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */
/* @var $expedienteMedico app\models\ExpedienteMedico */
/* @var $descripcionAntecedentes string */

$this->title = 'Antecedentes Patológicos';
$this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
// Si ya existe un antecedente patológico, obtenemos su descripción
$descripcionAntecedentes = ''; // Asegúrate de definir esta variable
if ($expedienteMedico) {
    $antecedentePatologico = \app\models\AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
    if ($antecedentePatologico) {
        $descripcionAntecedentes = $antecedentePatologico->descripcion_antecedentes;
    }
}

?>
<div class="empleado-patologicos">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header gradient-blue text-white text-center">
                    <h2>Antecedentes Patológicos</h2>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <?= Html::label('Descripción de Antecedentes Patológicos', 'descripcion_antecedentes') ?>
                        <?= Html::textarea('descripcion_antecedentes', $descripcionAntecedentes, [
                            'class' => 'form-control',
                            'rows' => 10,
                            'style' => 'width: 100%;',
                        ]) ?>
                    </div>
                    <div class="form-group text-right">
                        <?= Html::submitButton('Guardar &nbsp; &nbsp; <i class="fa fa-save"></i>', ['class' => 'btn btn-success']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php ActiveForm::end(); ?>

</div>
