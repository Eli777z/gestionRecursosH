<?php

/* @var $this yii\web\View */
/* @var $model app\models\ParametroFormato */

$this->title = Yii::t('app', 'Update Parametro Formato: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Parametro Formatos'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'Update');
?>


                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
               