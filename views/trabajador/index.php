<?php
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
/* @var $this yii\web\View */
/* @var $searchModel app\models\TrabajadorSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Trabajadores';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Agregar Trabajador', ['create'], ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
    'dataProvider' => $dataProvider,
    'filterModel' => $searchModel,
    'options' => ['class' => 'grid-view', 'style' => 'width: 80%; margin: auto;'], 
    'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'], 
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($index % 2 === 0) {
            return ['style' => 'background-color: #D1F2EB;'];
            // Fila blanca
        } else {
            return ['style' => 'background-color: #FFFFFF;']; 
        }
    },
    'columns' => [
        ['class' => 'yii\grid\SerialColumn'],
        [
            'attribute' => 'foto',
            'format' => 'html',
            'filter' => false,
            'value' => function ($model) {
                if ($model->foto) {
                    $urlImagen = Yii::$app->urlManager->createUrl(['trabajador/foto-trabajador', 'id' => $model->id]);
                    return Html::img($urlImagen, ['width' => '80px', 'height' => '80px']);
                }
                return null; 
            },
        ],
        'nombre',
        'apellido',
        'email:email',
        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',
            'template' => '{view} {delete} {update} {toggle-activation}', //botones deseados
            'buttons' => [
                'toggle-activation' => function ($url, $model) {
                    
                    $isActive = $model->idusuario0->status == 10;
                 
                    $icon = $isActive ? 'fas fa-ban' : 'far fa-check-circle';
                    $title = $isActive ? 'Desactivar Usuario' : 'Activar Usuario';
    
                    return Html::a('<i class="' . $icon . '"></i>', ['trabajador/toggle-activation', 'id' => $model->id], [
                        'title' => Yii::t('yii', $title),
                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas cambiar el estado de este usuario?'),
                        'data-method' => 'post',
                        'class' => 'btn btn-xs ' . ($isActive ? 'btn-warning' : 'btn-success'),
                    ]);
                },
                'view' => function ($url, $model) {
                    return Html::a('<i class="far fa-eye"></i>', $url, [
                       // 'target' => '_blank',
                        'title' => 'Ver archivo',
                        'class' => 'btn btn-info btn-xs',
                        'data-pjax' => "0"
                    ]);
                },
                'delete' => function ($url, $model) {
                    return Html::a('<i class="fas fa-trash"></i>', $url, [
                        'title' => Yii::t('yii', 'Eliminar'),
                        'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                        'data-method' => 'post',
                        'class' => 'btn btn-danger btn-xs',
                    ]);
                },
                'update' => function ($url, $model) {
                    return Html::a('<i class="fas fa-pencil-alt"></i>', $url, [
                        'title' => Yii::t('yii', 'Actualizar'),
                        'class' => 'btn btn-primary btn-xs',
                    ]);
                },
                
            ],
            'options' => ['style' => 'width: 15%;'], 
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
