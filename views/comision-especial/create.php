<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ComisionEspecial */

$this->title = 'Create Comision Especial';
$this->params['breadcrumbs'][] = ['label' => 'Comision Especials', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'motivoFechaPermisoModel' => $motivoFechaPermisoModel,

                        'solicitudModel' => $solicitudModel,
                    ]) ?>
          