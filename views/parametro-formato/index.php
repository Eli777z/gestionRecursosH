<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use kartik\select2\Select2;
/* @var $this yii\web\View */
/* @var $searchModel app\models\ParametroFormatoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Parametro Formatos');
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-8">
            <div class="card">
            <div class="card-header bg-info text-white">
                    <h3>Limites de formatos:</h3>
                    <?= Html::a('Agregar nuevo <i class="fa fa-plus-circle"></i> ', ['//parametro-formato/create'], ['class' => 'btn btn-dark fa-lg mt-3 ml-3'])?>
                   


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
                        'columns' => [
                            ['class' => 'yii\grid\SerialColumn'],

                            //'id',
                            //'tipo_permiso',

                            [
                                'attribute' => 'tipo_permiso',
                                'label' => 'Tipo de formato:',
                                'value' => function ($model) {
                                    return $model->tipo_permiso;
                                },
                                'filter' => Select2::widget([
                                    'model' => $searchModel,
                                    'attribute' => 'tipo_permiso',
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\ParametroFormato::find()->select(['tipo_permiso'])->distinct()->all(), 'tipo_permiso', 'tipo_permiso'),
                                    'options' => ['placeholder' => 'Buscar Formato'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                ]),
                                'contentOptions' => ['class' => 'small-font'], 
                            ],
                          ///  'limite_anual',
                          [
                            'attribute' => 'cat_tipo_contrato_id',
                            'label' => 'Tipo de Contrato:',
                            'value' => function ($model) {
                                return $model->catTipoContrato->nombre_tipo;
                            },
                            'filter' => Select2::widget([
                                'model' => $searchModel,
                                'attribute' => 'cat_tipo_contrato_id',
                                'data' => \yii\helpers\ArrayHelper::map(
                                    \app\models\CatTipoContrato::find()->all(),
                                    'id', 
                                    'nombre_tipo'
                                ),
                                'options' => ['placeholder' => 'Buscar Contrato'],
                                'pluginOptions' => [
                                    'allowClear' => true,
                                ],
                                'theme' => Select2::THEME_KRAJEE_BS3,
                            ]),
                            'contentOptions' => ['class' => 'small-font'],
                        ],
                        
                            [
                                'attribute' => 'limite_anual',
                                'label' => 'Limite Anual',
                              
                                'filter' => false,
                                'value' => function ($model) {
                                    
                                    return $model->limite_anual;
                                },
                            ],

                             // Otras columnas que desees mostrar...
                             [
                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                'template' => ' {update} {delete}',
                                'buttons' => [
                                    
                                 //   'view' => function ($url, $model, $key) {
                                   //     return Html::a('<i class="fa fa-eye"></i>', ['//parametro-formato/view', 'id' => $model->id], ['title' => 'Ver', 'class' => 'btn btn-primary btn-xs']);
                                   // },
                                    'update' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-pen"></i>', ['//parametro-formato/update', 'id' => $model->id], ['title' => 'Editar', 'class' => 'btn btn-warning btn-xs']);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::a('<i class="fa fa-trash"></i>', ['//parametro-formato/delete', 'id' => $model->id], [
                                            'title' => 'Eliminar',
                                            'class' => 'btn btn-danger btn-xs',
                                            'data-confirm' => '¿Estás seguro de eliminar este elemento?',
                                            'data-method' => 'post',
                                        ]);
                                    },
                                ],
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
</div>
</div>