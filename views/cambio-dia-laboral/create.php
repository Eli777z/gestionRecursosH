<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioDiaLaboral */

$this->title = 'Create Cambio Dia Laboral';
$this->params['breadcrumbs'][] = ['label' => 'Cambio Dia Laborals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,

                        'solicitudModel' => $solicitudModel,
                    ]) ?>
         