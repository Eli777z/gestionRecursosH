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

$this->title = 'Cambio de Horario de Trabajo';

?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Cambio de Horario de Trabajo</h3>
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
                    <?php if (Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion') ) {?>
                            <?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php }else if (Yii::$app->user->can('gestor-rh')) {
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
                return $model->solicitud->fecha_creacion;
            },
          //  'options' => ['style' => 'width: 30%;'],

        ],

        [
            'label' => 'Motivo',
            'attribute' => 'motivo',
            'format' => 'html',
            'value' => function ($model) {
                return \yii\helpers\Html::decode($model->motivoFechaPermiso->motivo);
            },
            'filter' => false,
            'options' => ['style' => 'width: 65%;'],
        ],
        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',

    
        'template' => Yii::$app->user->can('ver-empleados-departamento') ? '{view}' : '{view} {delete}',

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
