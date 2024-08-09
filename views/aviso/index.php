<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\AvisoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Avisos');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a(Yii::t('app', 'Create Aviso'), ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            //'mensaje:ntext',
                            [
                                'attribute' => 'imagen',
                                'format' => 'html',
                                'label' => 'Imagen',
                                'value' => function ($model) {
                                    if ($model->imagen) {
                                        $imageUrl = Yii::$app->urlManager->createUrl(['aviso/ver-imagen', 'nombre' => $model->imagen]);
                                        return Html::img($imageUrl, ['width' => '10%', 'height' => '10%']);
                                    }
                                    return 'No image';
                                },
                                'filter' => false,
                                'options' => ['style' => 'height: 10%;'],
                            ],
                            [
                                'label' => 'Mensaje',
                                'attribute' => 'mensaje',
                                'format' => 'html',
                                'value' => function ($model) {
                                    return \yii\helpers\Html::decode($model->mensaje);
                                },
                                'filter' => false,
                                'options' => ['style' => 'width: 30%;'],
                            ],
                            'titulo',
                            'created_at',
                           
                            
                            

                            ['class' => 'hail812\adminlte3\yii\grid\ActionColumn'],
                        ],
                        'summaryOptions' => ['class' => 'summary mb-2'],
                        'pager' => [
                            'class' => 'yii\bootstrap4\LinkPager',
                        ]
                    ]); ?>

                    <?php Pjax::end(); ?>

                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
