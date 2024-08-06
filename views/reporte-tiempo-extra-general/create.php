<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ReporteTiempoExtraGeneral */

$this->title = Yii::t('app', 'Create Reporte Tiempo Extra General');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Reporte Tiempo Extra Generals'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

                    <?=$this->render('_form', [ 'model' => $model,
            'solicitudModel' => $solicitudModel,
            'actividadModel' => $actividadModel, // Añadir esta línea
            'empleado' => $empleado, 'model' => $model
                    ]) ?>
               