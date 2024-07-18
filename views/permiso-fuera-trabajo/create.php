<?php

use yii\helpers\Html;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->title = 'Create Permiso Fuera Trabajo';

?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                        'empleado' => $empleado, // Pasar empleado a la vista

                        'solicitudModel' => $solicitudModel,
                    ]) ?>
              