<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoSinSueldo */

$this->title = 'Create Permiso Sin Sueldo';
$this->params['breadcrumbs'][] = ['label' => 'Permiso Sin Sueldos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                         'model' => $model,
                         'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
                         'solicitudModel' => $solicitudModel,
                         'noPermisoAnterior' => $noPermisoAnterior, // Asegúrate de pasar $noPermisoAnterior a la vista
                         'fechaPermisoAnterior' => $fechaPermisoAnterior, // Asegúrate de pasar $fechaPermisoAnterior a la vista
                    ]) ?>
            