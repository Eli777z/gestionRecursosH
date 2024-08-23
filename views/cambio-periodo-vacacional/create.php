<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioPeriodoVacacional */

$this->title = 'Create Cambio Periodo Vacacional';

?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'empleado' => $empleado, // Pasar empleado a la vista
'permisosUsados' => $permisosUsados,
            'permisosDisponibles' => $permisosDisponibles,
                    ]) ?>
       