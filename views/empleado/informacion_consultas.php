<?php
//IMPORTACIONES
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\jui\DatePicker;

?>
<div class="card">
                                <div class="card-body">
                                    <div class="card-header bg-success text-dark text-center">
                                        <h3>HISTORIAL DE CONSULTAS MEDICAS: </h3>
                                    </div>
                                    <li class="dropdown-divider"></li>
                                    <div class="row">
                                        <?php Pjax::begin(); //SE INICIALIZA EL AJAX O PJAX ?>
                                        <?= GridView::widget([
                                            'dataProvider' => $dataProviderConsultas, // SE EXTRAN LOS DATOS DE LOS REGISTROS CORRESPONDIENTES AL MODELO
                                            'filterModel' => $searchModelConsultas, //SE UTILIZA EL MODELO QUE SE ENCARGA DE BUSCAR Y FILTRAR
                                            'columns' => [
                                                ['class' => 'yii\grid\SerialColumn'],

                                                [
                                                    'label' => 'Fecha de la consula',
                                                    'attribute' => 'created_at',
                                                    'format' => 'raw',
                                                    'value' => function ($model) {
                                                        setlocale(LC_TIME, "es_419.UTF-8");
                                                        return strftime('%A, %d de %B de %Y', strtotime($model->created_at));
                                                    },
                                                    'filter' => DatePicker::widget([
                                                        'model' => $searchModelConsultas,
                                                        'attribute' => 'created_at',
                                                        'language' => 'es',
                                                        'dateFormat' => 'php:Y-m-d',
                                                        'options' => [
                                                            'class' => 'form-control',
                                                            'autocomplete' => 'off',
                                                        ],
                                                        'clientOptions' => [
                                                            'changeYear' => true,
                                                            'changeMonth' => true,
                                                            'yearRange' => '-100:+0',
                                                        ],

                                                    ]),
                                                ],

                                                [
                                                    'label' => 'Motivo de la consulta',
                                                    'attribute' => 'motivo',
                                                    'format' => 'html',
                                                    'value' => function ($model) {
                                                        return \yii\helpers\Html::decode($model->motivo);
                                                    },
                                                    'filter' => false,
                                                    'options' => ['style' => 'width: 65%;'],
                                                ],
                                             //ACCIONES QUE SE PUEDE REALIZAR EN LAS FILAS DEL GRIDVIEW
                                                [
                                                    'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                    'template' => '{view} ',
                                                    'buttons' => [
                                                        'view' => function ($url, $model, $key) {
                                                            return Html::a('<i class="fa fa-eye"></i>', ['consulta-medica/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                                                        },
                                              //          'delete' => function ($url, $model, $key) {
                                                //            return Html::a('<i class="fa fa-trash"></i>', ['consulta-medica/delete', 'id' => $model->id], [
                                                  //              'title' => 'Eliminar',
                                                    //            'class' => 'btn btn-danger btn-xs',
                                                      //          'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                                                        //        'data-method' => 'post',
                                                          //  ]);
                                 //                       },
                                                    ],
                                                ],
                                            ],
                                            'summaryOptions' => ['class' => 'summary mb-2'],
                                            'pager' => [
                                                'class' => 'yii\bootstrap4\LinkPager',
                                            ],
                                        ]); ?>
                                        <?php Pjax::end(); ?>
                                    </div>
                                </div>
                            </div>
                          