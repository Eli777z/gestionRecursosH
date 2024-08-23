<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this yii\web\View */
/* @var $searchModel app\models\ConsultaMedicaSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Consulta Medicas';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Consultas Medicas</h3>
                  



                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                        <?php
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>

                        </div>
                    </div>
<?php Pjax::begin();?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                           // 'id',
                           [
                            'label' => 'Nombre',
                            'value' => function ($model) {
                                return $model->expedienteMedico->empleado ? $model->expedienteMedico->empleado->nombre . ' ' . $model->expedienteMedico->empleado->apellido : 'N/A';
                            },
                            
                        ],
                           [
                            'attribute' => 'created_at',
                            'label' => 'Fecha de la consulta',
                            'format' => 'raw',
                            'value' => function ($model) {
                                setlocale(LC_TIME, "es_419.UTF-8");
                                return strftime('%A, %d de %B de %Y', strtotime($model->created_at));
                            },
                            'filter' => false,
                        ],
                        [
                            'label' => 'Motivo',
                            'attribute' => 'motivo',
                            'format' => 'html',
                            'value' => function ($model) {
                                return \yii\helpers\Html::decode($model->motivo);
                            },
                            'filter' => false,
                            //'options' => ['style' => 'width: 65%;'],
                        ],
                        [
                            'label' => 'Sintomas',
                            'attribute' => 'sintomas',
                            'format' => 'html',
                            'value' => function ($model) {
                                return \yii\helpers\Html::decode($model->sintomas);
                            },
                            'filter' => false,
                            //'options' => ['style' => 'width: 65%;'],
                        ],
                        [
                            'label' => 'Diagnostico',
                            'attribute' => 'diagnostico',
                            'format' => 'html',
                            'value' => function ($model) {
                                return \yii\helpers\Html::decode($model->diagnostico);
                            },
                            'filter' => false,
                            //'options' => ['style' => 'width: 65%;'],
                        ],
                          
                           

                        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',

    
                        'template' => '{view}',
                
                     'buttons' => [
                                    
                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="far fa-eye"></i>', $url, [
                                            'title' => 'Ver consulta',
                                            'class' => 'btn btn-outline-info btn-sm',
                                            'data-pjax' => "0"
                                        ]);
                                    },
                                   
                                ],
                    
                    
                    
                    
                    ],
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
