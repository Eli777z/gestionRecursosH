<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoEconomico */

$this->title = 'Create Permiso Economico';
$this->params['breadcrumbs'][] = ['label' => 'Permiso Economicos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                       'model' => $model,
                       'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                       'solicitudModel' => $solicitudModel,
                       'noPermisoAnterior' => $noPermisoAnterior, // Asegúrate de pasar $noPermisoAnterior a la vista
                       'fechaPermisoAnterior' => $fechaPermisoAnterior, // Asegúrate de pasar $fechaPermisoAnterior a la vista
                    ]) ?>
         