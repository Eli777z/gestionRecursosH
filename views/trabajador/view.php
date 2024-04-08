<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\helpers\Url;
/* @var $this yii\web\View */
/* @var $model app\models\Trabajador */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Trabajadors', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-md-12">
                    <p>
                        <?= Html::a('Update', ['update', 'id' => $model->id, 'idusuario' => $model->idusuario], ['class' => 'btn btn-primary']) ?>
                        <?= Html::a('Delete', ['delete', 'id' => $model->id, 'idusuario' => $model->idusuario], [
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
                            'nombre',
                            'apellido',
                            'email:email',
                            'fecha_nacimiento',
                            'codigo_postal',
                            'calle',
                            'numero_casa',
                            'telefono',
                            'colonia',
                            [
                                'attribute' => 'foto',
                                'value' => Url::to(['trabajador/foto-trabajador', 'id' => $model->id]),
                                'format' => ['image',['width'=>'100','height'=>'100']], // Ajusta el tamaño según necesites
                            ],
                            
                            
                            'idusuario',
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