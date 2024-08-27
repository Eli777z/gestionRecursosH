<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ReporteTiempoExtraGeneralSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);


$this->title = 'Reporte de Tiempo Extra';
//$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Reporte de Tiempo Extra General</h3>
                    <?= Html::a('Crear una nueva <i class="fa fa-plus-circle"></i> ', ['create'], ['class' => 'btn btn-dark fa-lg mt-3 ml-3'])?>
                    <?= Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
'class' => 'btn btn-outline-warning mr-3 mt-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>



                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                       

                        </div>
                    </div>

                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                           // 'id',
                            //'empleado_id',
                            [
                                'label' => 'ID',
                                'attribute' => 'solicitud_id',
                                'value' => function ($model) {
                                    return $model->solicitud_id;
                                },
                            ],
                            [
                                'label' => 'Fecha de creación',
                                'attribute' => 'created_at',
                                'value' => function ($model) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($model->created_at);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                        

                            [
                                'class' => 'yii\grid\DataColumn',
                                'header' => 'Aprobación',
                                'format' => 'raw',
                                
                                'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                                    if ($model->solicitud->aprobacion === 'PENDIENTE') {
                                        return '<i class="fas fa-stopwatch" aria-hidden="true" style="color: #4678fc"></i> PENDIENTE';
                                    } elseif ($model->solicitud->aprobacion === 'APROBADO') {
                                        return '<i class="fas fa-check" aria-hidden="true" style="color: #2fcf04"></i> APROBADO';
                                    } elseif ($model->solicitud->aprobacion === 'RECHAZADO') {
                                        return '<i class="fa fa-times" aria-hidden="true" style="color: #d91e1e"></i> RECHAZADO';
                                   
                                    }else{
                                        return 'No aplica';
                
                }
                

                                    
                                    
                                },
                                'contentOptions' => ['class' => 'text-center'],
                                'headerOptions' => ['class' => 'text-center'],
                            ],

                            [
                                'label' => 'Revisado en',
                                'attribute' => 'fecha_aprobacion',
                                'value' => function ($model) {
                                    if ($model->solicitud->fecha_aprobacion === null) {
                                        return 'No definido';
                                    }
                            
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    $fechaPermiso = strtotime($model->solicitud->fecha_aprobacion);
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    setlocale(LC_TIME, null);
                            
                                    return $fechaFormateada;
                                },
                            ],

                            //'fecha',
                           // 'horario_inicio',
                            //'horario_fin',
                            //'no_horas',
                            //'actividad:ntext',
                            //'total_horas',

                         
        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',

    
        'template' => '{view}',

     'buttons' => [
                    
                    'view' => function ($url, $model) {
                        return Html::a('<i class="far fa-eye"></i>', $url, [
                            'title' => 'Ver solicitud',
                            'class' => 'btn btn-info btn-xs',
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
   // 'tableOptions' => ['class' => 'si-style-gridview'],
     //                                           'rowOptions' => function ($model, $key, $index, $grid) {
       //                                             return ['class' => 'si-style-gridview'];
         //                                       },
]) ?>

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