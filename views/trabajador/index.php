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
    'options' => ['class' => 'grid-view', 'style' => 'width: 80%; margin: auto;'], // Ajusta el ancho del GridView y lo centra horizontalmente
    'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'], // Agrega clases de Bootstrap para un diseño más compacto
    'rowOptions' => function ($model, $key, $index, $grid) {
        if ($index % 2 === 0) {
            return ['style' => 'background-color: #D5E5FF;'];
            // Fila blanca
        } else {
            return ['style' => 'background-color: #FFFFFF;']; // Fila azul claro
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
                    // Asegúrate de que la ruta de la acción del controlador sea correcta
                    $urlImagen = Yii::$app->urlManager->createUrl(['trabajador/foto-trabajador', 'id' => $model->id]);
                    return Html::img($urlImagen, ['width' => '80px', 'height' => '80px']);
                }
                return null; // O puedes retornar una imagen por defecto si no hay foto
            },
        ],
        'nombre',
        'apellido',
        'email:email',
        ['class' => 'hail812\adminlte3\yii\grid\ActionColumn',
            'template' => '{view} {delete} {update}', // Incluye solo los botones que deseas
            'buttons' => [
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
            'options' => ['style' => 'width: 15%;'], // Ajusta el ancho de la columna
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
