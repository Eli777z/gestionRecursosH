<?php
//IMPORTACIONES
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\select2\Select2;
use yii\grid\GridView;
use yii\jui\DatePicker;
use yii\web\View;
use kartik\daterange\DateRangePicker;
/* @var $this yii\web\View */
/* @var $searchModel app\models\SolicitudSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Historial de solicitudes de incidencias';
$this->params['breadcrumbs'][] = $this->title;
//SE CARGAN LOS ESTILOS
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header gradient-info text-white">
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">

                    <?php
//LISTA QUE MUESTRA LOS REGISTROS DE SOLICITUDES QUE HA REALIZADO LOS EMPLEADOS 
                    Pjax::begin(['id' => 'pjax-container']);
                    echo GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,

                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],
                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => '',
                                'format' => 'raw',
                                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                                    if ($model->status === 'Nueva') {
                                        return '<i class="fa fa-fire" aria-hidden="true" style="color: #ea4242 "></i>';
                                    } elseif ($model->status === 'Visto') {
                                        return '<i class="fa fa-check-double" aria-hidden="true" style="color: #4678fc"></i>';
                                    } else {
                                        return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                                    }
                                },
                                'contentOptions' => ['class' => 'text-center'],
                                'headerOptions' => ['class' => 'text-center'],
                            ],

                           

                            [

                                'attribute' => 'empleado_id',
                                'label' => 'Empleado',
                                'value' => function ($model) {
                                    return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
                                },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'empleado_id',
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->all(), 'id', function ($model) {
                                        return $model->nombre . ' ' . $model->apellido;
                                    }),
                                    'options' => ['placeholder' => 'Seleccione un empleado'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_KRAJEE_BS3,
                                ]),
                            ],
                            [
                                'attribute' => 'fecha_creacion',
                                'format' => 'raw',
                                'value' => function ($model) {
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
                                },
                                'filter' => DatePicker::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'fecha_creacion',
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
                                'attribute' => 'nombre_formato',
                                'label' => 'Tipo de solicitud',
                                'value' => function ($model) {
                                    return $model->nombre_formato;
                                },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'nombre_formato',
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Solicitud::find()->select(['nombre_formato'])->distinct()->all(), 'nombre_formato', 'nombre_formato'),
                                    'options' => ['placeholder' => 'Seleccione un tipo de solicitud'],
                                    'pluginOptions' => [
                                        'allowClear' => true


                                    ],
                                    'theme' => Select2::THEME_KRAJEE_BS3,
                                ]),
                            ],

                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Aprobación',
                                'format' => 'raw',
                                
                                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                                    if ($model->aprobacion === 'PENDIENTE') {
                                        return '<i class="fas fa-stopwatch" aria-hidden="true" style="color: #4678fc"></i> PENDIENTE';
                                    } elseif ($model->aprobacion === 'APROBADO') {
                                        return '<i class="fas fa-check" aria-hidden="true" style="color: #2fcf04"></i> APROBADO';
                                    } elseif ($model->aprobacion === 'RECHAZADO') {
                                        return '<i class="fa fa-times" aria-hidden="true" style="color: #d91e1e"></i> RECHAZADO';
                                   
                                    }else{
                                        return 'No aplica';



                                    }
                                    
                                },
                                'contentOptions' => ['class' => 'text-center'],
                                'headerOptions' => ['class' => 'text-center'],
                            ],

                            [
                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',


                                'template' => '{view}',

                                'buttons' => [

                                    'view' => function ($url, $model) {
                                        return Html::a('<i class="far fa-eye"></i>', $url, [
                                            'title' => 'Ver solicitud',
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
                        ],


                    ]);
                    Pjax::end();

                    ?>


                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>