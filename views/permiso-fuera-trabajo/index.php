<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PermisoFueraTrabajoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Permiso Fuera Trabajos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Create Permiso Fuera Trabajo', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>
<?php Pjax::begin();?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            [
                                'attribute' => 'fecha_creacion',
                                'label' => 'Fecha de creación',
                                'value' => function ($model) {
                                    return $model->solicitud->fecha_creacion;
                                },
                            ],
                        
            ///                [
               ///                 'attribute' => 'status',
                  //              'label' => 'Status',
                    //            'value' => function ($model) {
                      //              return $model->solicitud->status;
                        //        },
                          //  ],
                            [
                                'attribute' => 'comentario',
                                'label' => 'Comentarios',
                                'value' => function ($model) {
                                    return $model->solicitud->comentario;
                                },
                            ],
             //               [
               //                 'attribute' => 'nombre_aprobante',
                 //               'label' => 'Aprobó',
                   //             'value' => function ($model) {
                     //               return $model->solicitud->nombre_aprobante;
                       //         },
                         //   ],


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
