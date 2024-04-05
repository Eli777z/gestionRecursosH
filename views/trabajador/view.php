<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

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
                                'value' => function ($model) {
                                    // Asumiendo que 'web' es un alias que apunta a la carpeta pública
                                    $rutaRelativa = str_replace(Yii::getAlias('@runtime'), Yii::getAlias('@web'), $model->foto);
                                    return $rutaRelativa;
                                },
                                'format' => ['image', ['width' => '100', 'height' => '100']],
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