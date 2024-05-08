<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\JuntaGobierno */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Junta Gobiernos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id, 'cat_direccion_id' => $model->cat_direccion_id, 'cat_departamento_id' => $model->cat_departamento_id, 'empleado_id' => $model->empleado_id], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'cat_direccion_id' => $model->cat_direccion_id, 'cat_departamento_id' => $model->cat_departamento_id, 'empleado_id' => $model->empleado_id], [
                            'class' => 'btn btn-danger',
                            'data' => [
                                'confirm' => 'Are you sure you want to delete this item?',
                                'method' => 'post',
                            ],
                        ]) ?>
                    </p>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'cat_direccion_id',
                            'cat_departamento_id',
                            'empleado_id',
                            'nivel_jerarquico',
                        ],
                    ]) ?>
                </div>
                <!--.col-md-12-->
            </div>
            <!--.row-->
        </div>
        <!--.card-body-->
    </div>
    <!--.card-->
</div>