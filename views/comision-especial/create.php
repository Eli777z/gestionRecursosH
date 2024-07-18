<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComisionEspecial */

$this->title = 'Create Comision Especial';

?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                        'empleado' => $empleado, // Pasar empleado a la vista

                        'solicitudModel' => $solicitudModel,
                    ]) ?>
          