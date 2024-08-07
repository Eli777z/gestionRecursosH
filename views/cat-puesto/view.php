<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\CatPuesto */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Cat Puestos', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>

<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-8">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Nombramiento:</h3>
                    <?php  echo Html::a('<i class="fa fa-chevron-left"></i> Volver', ['//site/configuracion'], [
                'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
                'encode' => false,
            ]);

            ?>


                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>
                    <?= DetailView::widget([
                        'model' => $model,
                        'attributes' => [
                            'id',
                            'nombre_puesto',
                        ],
                    ]) ?>
          </div>
<!--.card-body-->
</div>
<!--.card-->
</div>
<!--.col-md-12-->
</div>
<!--.row-->
</div>