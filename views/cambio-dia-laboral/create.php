<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioDiaLaboral */

$this->title = 'Create Cambio Dia Laboral';

?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                        'empleado' => $empleado, // Pasar empleado a la vista
                        'permisosUsados' => $permisosUsados,
                        'permisosDisponibles' => $permisosDisponibles,
                        'solicitudModel' => $solicitudModel,
                    ]) ?>
         