<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ContratoParaPersonalEventual */

$this->title = Yii::t('app', 'Create Contrato Para Personal Eventual');

?>


                    <?=$this->render('_form', [
                        'model' => $model,
                        'empleado' => $empleado, // Pasar empleado a la vista
                        'numeroContrato' => $numeroContrato,

                    ]) ?>
               