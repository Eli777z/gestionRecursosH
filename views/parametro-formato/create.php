<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ParametroFormato */

$this->title = Yii::t('app', 'Create Parametro Formato');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parametro Formatos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>


                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
              