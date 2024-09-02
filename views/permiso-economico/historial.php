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

$this->title = 'Permiso Economico';

?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-10">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Historial de Permisos Economicos</h3>
                    <p>  Empleado: <?= $empleado->nombre.' '.$empleado->apellido ?></p>
                    <div class="col-12">
                            <p><strong>Permisos usados:</strong> <?= $permisosUsados ?></p>
                            <p><strong>Permisos disponibles:</strong> <?= $permisosDisponibles ?></p>
                        </div> 
                    <?php if (Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion') ) {?>
                            <?= Html::a('<i class="fa fa-chevron-left"></i> Volver', ['empleado/index'], [
'class' => 'btn btn-outline-warning mr-3 float-right fa-lg',

'encode' => false, // Para que el HTML dentro del enlace no se escape
]) ?>

<?php } else if (Yii::$app->user->can('gestor-rh')) {
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
            'options' => ['style' => 'width: 25%;'],
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
            'attribute' => 'comentario',
            'label' => 'Comentario',
            'format' => 'raw',
            'value' => function ($model) {
                if (Yii::$app->user->can('borrar-registros-formatos-incidencias')) {
                    // Permitir añadir comentario
                    return Html::beginForm(['guardar-comentario'], 'post', ['data-pjax' => 1])
                        . Html::textarea("comentario[{$model->id}]", $model->comentario, ['class' => 'form-control'])
                        . Html::hiddenInput('id', $model->id)
                        . Html::submitButton('Añadir', ['class' => 'btn btn-success btn-sm float-right mt-1'])
                        . Html::endForm();
                } else {
                    // Solo mostrar el comentario
                    return Html::encode($model->comentario);
                }
            },
            'headerOptions' => ['class' => 'text-center'],
            'options' => ['style' => 'width: 25%;'],
        ],



        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',

    
      'template' => Yii::$app->user->can('ver-empleados-departamento') || Yii::$app->user->can('ver-empleados-direccion')  ? '{view}' : '{view}  {restore} {delete}',

     'buttons' => [
                    
                    'view' => function ($url, $model) {
                        return Html::a('<i class="far fa-eye"></i>', $url, [
                            'title' => 'Ver solicitud',
                            'class' => 'btn btn-outline-info btn-sm',
                            'data-pjax' => "0"
                        ]);
                    },
                    'restore' => function ($url, $model) {
                        return Html::a('<i class="fas fa-check"></i>', $url, [
                            'title' => Yii::t('yii', 'Activar'),
                            'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas activar esta solicitud?'),
                            'data-method' => 'post',
                           'class' => 'btn btn-outline-success btn-sm'
                        ]);
                    },
                   'delete' => function ($url, $model) {
                        return Html::a('<i class="fas fa-times"></i>', $url, [
                            'title' => Yii::t('yii', 'Cancelar'),
                            'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas cancelar esta solicitud?'),
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
