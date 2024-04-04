<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */

$this->title = 'Create Trabajador';
$this->params['breadcrumbs'][] = ['label' => 'Trabajadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                          'model' => $model,
                          'user' => $user, // Pasar el modelo Usuario a la vista
                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>