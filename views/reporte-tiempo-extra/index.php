<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ReporteTiempoExtraSearch */
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
                    <h3>Historial de Reporte de Tiempo Extra</h3>
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
                                    return $model->created_at;
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