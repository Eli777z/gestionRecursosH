<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\CatDepartamento */

$this->title = 'Create Cat Departamento';
$this->params['breadcrumbs'][] = ['label' => 'Cat Departamentos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <?=$this->render('_form', [
                        'model' => $model,
                        //'cat_nombre_direccion' => $cat_nombre_direccion,

                    ]) ?>
                </div>
            </div>
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>