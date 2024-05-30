<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CambioPeriodoVacacional */

$this->title = 'Create Cambio Periodo Vacacional';
$this->params['breadcrumbs'][] = ['label' => 'Cambio Periodo Vacacionals', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
       