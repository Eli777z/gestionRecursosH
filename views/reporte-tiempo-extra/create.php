<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReporteTiempoExtra */

$this->title = Yii::t('app', 'Create Reporte Tiempo Extra');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reporte Tiempo Extras'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

                    <?=$this->render('_form', [
                        'model' => $model,
                        'empleado' => $empleado,
                        'actividadModel' => $actividadModel, // Añadir esta línea

                    ]) ?>
           