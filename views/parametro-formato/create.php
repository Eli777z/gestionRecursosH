<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ParametroFormato */

$this->title = Yii::t('app', 'Create Parametro Formato');

?>


                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
              