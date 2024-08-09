<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Aviso */

$this->title = Yii::t('app', 'Crear Aviso');

?>

                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
           