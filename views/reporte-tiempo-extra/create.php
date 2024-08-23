<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReporteTiempoExtra */

$this->title = Yii::t('app', 'Create Reporte Tiempo Extra');

?>

                    <?=$this->render('_form', [
                        'model' => $model,
                        'empleado' => $empleado,
                        'actividadModel' => $actividadModel, // Añadir esta línea

                    ]) ?>
           