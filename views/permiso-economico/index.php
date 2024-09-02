<?php

use yii\bootstrap5\Alert;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;

/* @var $this yii\web\View */
/* @var $searchModel app\models\PermisoFueraTrabajoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->title = 'Permiso Economico';

?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Permisos Economicos</h3>
                    <?= Html::a('Crear una nueva <i class="fa fa-plus-circle"></i> ', ['create'], ['class' => 'btn btn-dark fa-lg mt-3 ml-3'])?>
                    <?= Html::a('<i class="fa fa-home"></i> Inicio', ['site/portalempleado'], [
'class' => 'btn btn-outline-warning mr-3 mt-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>



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

<?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    //'options' => ['class' => 'my-custom-grid-view'], // Clase específica para el GridView
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],

        [
            'attribute' => 'fecha_creacion',
            'label' => 'Fecha de creación',
            'value' => function ($model) {
                return $model->solicitud->fecha_creacion;
            },
          //  'options' => ['style' => 'width: 30%;'],
          'headerOptions' => ['class' => 'text-center'],

        ],

        [
            'label' => 'Motivo',
            'attribute' => 'motivo',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::decode($model->motivoFechaPermiso->motivo);
            },
            'filter' => false,
            'options' => ['style' => 'width: 45%;'],
            'headerOptions' => ['class' => 'text-center'],

        ],

        [
            'class' => 'yii\grid\DataColumn',
            'header' => 'Estatus',
            'format' => 'raw',
            'value' => function ($model) {
                // AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                if ($model->status === 1) {
                    return 'ACTIVO <i class="fa fa-fire-alt" aria-hidden="true" style="color: #2fcf04"></i> ';
                } elseif ($model->status === 0) {
                    return 'CANCELADO <i class="fa fa-times" aria-hidden="true" style="color: #ea4242"></i> ';
                } else {
                    return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                }
            },
            'contentOptions' => ['class' => 'text-center'],
            'headerOptions' => ['class' => 'text-center'],

        ],
        [
            'label' => 'Comentario',
            'attribute' => 'comentario',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::decode($model->comentario);
            },
            'filter' => false,
            //'options' => ['style' => 'width: 45%;'],
            'headerOptions' => ['class' => 'text-center'],

        ],


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
