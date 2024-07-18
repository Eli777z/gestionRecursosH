<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = 'Create Cambio Horario Trabajo';
?>


                    <?=$this->render('_form', [
                      'model' => $model,
                      'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                      'solicitudModel' => $solicitudModel,
                      'empleado' => $empleado, // Pasar empleado a la vista

                    ]) ?>
          