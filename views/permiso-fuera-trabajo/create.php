<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\PermisoFueraTrabajo */

$this->title = 'Create Permiso Fuera Trabajo';
$this->params['breadcrumbs'][] = ['label' => 'Permiso Fuera Trabajos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,

                        'solicitudModel' => $solicitudModel,
                    ]) ?>
              