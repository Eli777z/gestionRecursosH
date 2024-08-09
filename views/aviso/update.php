<?php

/* @var $this yii\web\View */
/* @var $model app\models\Aviso */

$this->title = Yii::t('app', 'Actualizar Aviso: {name}', [
    'name' => $model->id,
]);

?>


                    <?=$this->render('_form', [
                        'model' => $model
                    ]) ?>
            