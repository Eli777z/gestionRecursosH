<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioHorarioTrabajo */

$this->title = 'Create Cambio Horario Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Cambio Horario Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                      'model' => $model,
                      'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                      'solicitudModel' => $solicitudModel,
                    ]) ?>
          