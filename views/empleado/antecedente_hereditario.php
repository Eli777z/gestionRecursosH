<?php

use yii\helpers\Html;

use kartik\form\ActiveForm;




$antecedentesExistentes = [];
$observacionGeneral = '';
$descripcionAntecedentes = '';

$editable = Yii::$app->user->can('editar-expediente-medico');


if ($antecedentes) {
    foreach ($antecedentes as $antecedente) {
        $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
        if (empty($observacionGeneral)) {
            $observacionGeneral = $antecedente->observacion;
        }
    }
}

?>
<?php $form = ActiveForm::begin(['action' => ['empleado/view', 'id' => $model->id]]); ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header gradient-blue text-white text-center">
                                            <h2>Antecedentes Hereditarios</h2>
                                        </div>
                                        <div class="card-body bg-light">
                                            <div class="row">
                                                <div class="col-md-8">
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr>
                                                                    <th>Enfermedad</th>
                                                                    <th>Abuelos</th>
                                                                    <th>Hermanos</th>
                                                                    <th>Madre</th>
                                                                    <th>Padre</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($catAntecedentes as $catAntecedente) : ?>
                                                                    <tr>
                                                                        <td><?= Html::encode($catAntecedente->nombre) ?></td>
                                                                        <?php foreach (['Abuelos', 'Hermanos', 'Madre', 'Padre'] as $parentezco) : ?>
                                                                            <td>
                                                                                <?php
                                                                                // Verifica si el usuario tiene permiso para editar

                                                                                // Si es editable, muestra el checkbox para editar
                                                                                // Si no es editable, muestra solo el estado actual sin checkbox
                                                                                if ($editable) {
                                                                                    echo Html::checkbox("AntecedenteHereditario[{$catAntecedente->id}][$parentezco]", isset($antecedentesExistentes[$catAntecedente->id][$parentezco]), [
                                                                                        'value' => 1,
                                                                                        'label' => '',
                                                                                    ]);
                                                                                } else {
                                                                                    // Muestra solo el estado actual sin checkbox
                                                                                    $checked = isset($antecedentesExistentes[$catAntecedente->id][$parentezco]) && $antecedentesExistentes[$catAntecedente->id][$parentezco] == 1;
                                                                                    echo $checked ? 'X' : '';
                                                                                }
                                                                                ?>
                                                                            </td>
                                                                        <?php endforeach; ?>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 d-flex flex-column justify-content-between" style="height: 100%;">
                                                    <div class="form-group text-center">
                                                        <?= Html::label('Observaciones', 'observacion_general') ?>
                                                        <?= Html::textarea('observacion_general', $observacionGeneral, [
                                                            'class' => 'form-control',
                                                            'rows' => 5,
                                                            'style' => 'width: 100%;',
                                                            'readonly' => !$editable, // Hace que el campo sea de solo lectura si no tiene permiso
                                                        ]) ?>
                                                    </div>
                                                    <br>
                                                    <div class="form-group mt-auto d-flex justify-content-end">
                                                        <?php
                                                        // Si tiene permiso, muestra el botón de guardar
                                                        if ($editable) {
                                                            echo Html::submitButton('Guardar &nbsp; &nbsp;  <i class="fa fa-save"></i>', ['class' => 'btn btn-success']);
                                                        }
                                                        ?>
                                                    </div>
                                                </div>
                                                <div class="alert alert-white custom-alert" role="alert">
                                                    <i class="fa fa-exclamation-circle" aria-hidden="true"></i> Causas de muerte, malformaciones congénitas, diabetes, cardiopatías, hipertensión arterial, infartos, ateroesclerosis, accidentes vasculares, neuropatías, tuberculosis, artropatías, hemopatías, sida, sífilis, hemopatías, neoplasias, consanguinidad, alcoholismo, toxicomanías.
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php ActiveForm::end(); ?>
                           