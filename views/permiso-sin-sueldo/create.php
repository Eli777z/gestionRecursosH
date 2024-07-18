<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoSinSueldo */

$this->title = 'Create Permiso Sin Sueldo';

?>


                    <?=$this->render('_form', [
                         'model' => $model,
                         'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                         'solicitudModel' => $solicitudModel,
                         'noPermisoAnterior' => $noPermisoAnterior, 
                         'fechaPermisoAnterior' => $fechaPermisoAnterior,
                         'empleado' => $empleado, // Pasar empleado a la vista

                    ]) ?>
            