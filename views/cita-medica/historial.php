<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $searchModel app\models\PermisoFueraTrabajoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->title = 'Citas Medicas';

?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Citas Medicas</h3>
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
                    <?php if (Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion') ) {?>
<?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], ['class' => 'btn btn-outline-warning mr-3 float-right fa-lg', 'encode' => false, ]) ?>

<?php } if (Yii::$app->user->can('gestor-rh')) {
    // Redirige al historial del navegador
    echo Html::a('<i class="fa fa-chevron-left"></i> Volver', 'javascript:void(0);', [
        'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',
        'encode' => false,
        'onclick' => 'window.history.back(); return false;',
    ]);
} ?>

                </div>
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                        

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
                return $model->fecha_para_cita;
            },
          //  'options' => ['style' => 'width: 30%;'],

        ],

        [
            'label' => 'Motivo',
            'attribute' => 'comentario',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::decode($model->comentario);
            },
            'filter' => false,
            'options' => ['style' => 'width: 40%;'],
        ],
        [
            'class' => 'yii\grid\DataColumn',
            'header' => 'Aprobación',
            'format' => 'raw',
            
            'value' => function ($model) {//AYUDA A INDENTIFICAR EL ESTATUS DE LA SOLICITUD Y VERIFICAR SI SON NUEVAS O YA HAN SIDO VISUALIZADAS
                if ($model->solicitud->aprobacion === 'PENDIENTE') {
                    return 'PENDIENTE <i class="fas fa-stopwatch" aria-hidden="true" style="color: #ea4242 "></i>';
                } elseif ($model->solicitud->aprobacion  === 'APROBADO') {
                    return 'APROBADO <i class="fas fa-check" aria-hidden="true" style="color: #4678fc"></i>';
                } elseif ($model->solicitud->aprobacion  === 'RECHAZADO') {
                    return 'RECHAZADO <i class="fa fa-times" aria-hidden="true" style="color: #4678fc"></i>';
               
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
               
                setlocale(LC_TIME, "es_419.UTF-8");
                
                $fechaPermiso = strtotime($model->solicitud->fecha_aprobacion);
                
                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                
                setlocale(LC_TIME, null);
                
                return $fechaFormateada;
            },
        ],
       

        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',

    
        'template' => Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion')  ? '{view}' : '{view} {delete}',

     'buttons' => [
                    
                    'view' => function ($url, $model) {
                        return Html::a('<i class="far fa-eye"></i>', $url, [
                            'title' => 'Ver solicitud',
                            'class' => 'btn btn-outline-info btn-sm',
                            'data-pjax' => "0"
                        ]);
                    },

                    'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-trash"></i>', $url, [
                            'title' => Yii::t('yii', 'Eliminar'),
                            'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                            'data-method' => 'post',
                            'class' => 'btn btn-outline-danger btn-sm'
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
