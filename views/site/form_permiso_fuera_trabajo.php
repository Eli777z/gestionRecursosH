<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\PermisoFueraTrabajo $model */
/** @var ActiveForm $form */
?>
<div class="site-form_permiso_fuera_trabajo">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'empleado_id') ?>
        <?= $form->field($model, 'solicitud_id') ?>
        <?= $form->field($model, 'motivo_fecha_permiso_id') ?>
        <?= $form->field($model, 'fecha_salida') ?>
        <?= $form->field($model, 'fecha_regreso') ?>
        <?= $form->field($model, 'fecha_a_reponer') ?>
        <?= $form->field($model, 'horario_fecha_a_reponer') ?>
        <?= $form->field($model, 'nota') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- site-form_permiso_fuera_trabajo -->
