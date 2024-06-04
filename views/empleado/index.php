<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use hail812\adminlte3\yii\grid\ActionColumn;
use yii\web\View;
use kartik\tabs\TabsX;
use yii\bootstrap4\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\CatDireccion;
use kartik\select2\Select2;
use app\models\CatDepartamento;
use app\models\Empleado;
use app\models\JuntaGobiernoSearch;
use hail812\adminlte\widgets\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EmpleadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'Empleados';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
            <div class="card-header gradient-info text-white">
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">




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


                            <?php $this->beginBlock('block-empleados'); ?>
                            <div class="card">
                                <div class="card-header bg-info text-white">
                                    <h3>LISTA DE EMPLEADOS</h3>
                                    <?= Html::a('AÑADIR NUEVO EMPLEADO  <i class="fa fa-user-plus fa-lg"></i>', ['create'], ['class' => 'btn btn-warning float-right fa-lg btn-dark']) ?>

                                </div>


                                <div class="card-body">
                                    <?php Pjax::begin(); ?>
                                    <?php // echo $this->render('_search', ['model' => $searchModel]); 
                                    ?>

                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,


                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                            [
                                                'attribute' => 'foto',
                                                'format' => 'html',
                                                'filter' => false,
                                                'value' => function ($model) {
                                                    if ($model->foto) {
                                                        $urlImagen = Yii::$app->urlManager->createUrl(['empleado/foto-empleado', 'id' => $model->id]);
                                                        return Html::img($urlImagen, ['width' => '80px', 'height' => '80px']);
                                                    }
                                                    return null;
                                                },
                                            ],
                                            [
                                                'attribute' => 'id',
                                                'label' => 'Empleado',
                                                'value' => function ($model) {
                                                    return $model ? $model->apellido . ' ' . $model->nombre : 'N/A';
                                                },
                                                'filter' => Select2::widget([
                                                    'model' => $searchModel,
                                                    'attribute' => 'id',
                                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->all(), 'id', function ($model) {
                                                        return $model->apellido . ' ' . $model->nombre;
                                                    }),
                                                    'options' => ['placeholder' => 'Empleado'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true
                                                    ],
                                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                                    
                                                ]),
                                                'contentOptions' => ['class' => 'small-font'],

                                            ],
                                            [
                                                'attribute' => 'numero_empleado',
                                                'label' => 'Número de empleado',
                                                'value' => function ($model) {
                                                    return $model->numero_empleado;
                                                },
                                                'filter' => Select2::widget([
                                                    'model' => $searchModel,
                                                    'attribute' => 'numero_empleado',
                                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->select(['numero_empleado'])->distinct()->all(), 'numero_empleado', 'numero_empleado'), // Asegúrate de que 'nombre_formato' sea el nombre correcto del campo en tu tabla Solicitud
                                                    'options' => ['placeholder' => 'Número Empleado'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true


                                                    ],
                                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                                ]),
                                                'contentOptions' => ['class' => 'small-font'], 

                                            ],

                                            [
                                                'attribute' => 'cat_departamento_id',
                                                'label' => 'Departamento',
                                                'value' => function ($model) {
                                                    return $model->informacionLaboral && $model->informacionLaboral->catDepartamento
                                                        ? $model->informacionLaboral->catDepartamento->nombre_departamento
                                                        : 'N/A';
                                                },
                                                'filter' => Select2::widget([
                                                    'model' => $searchModel,
                                                    'attribute' => 'cat_departamento_id',
                                                    'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'),
                                                    'options' => ['placeholder' => 'Departamento', 'class' => 'small-select2'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                                ]),
                                                'contentOptions' => ['class' => 'small-font'], 
                                                'headerOptions' => ['class' => 'small-font'], 
                                            ],
                                            
                                            [
                                                'attribute' => 'cat_direccion_id',
                                                'label' => 'Dirección',
                                                'value' => function ($model) {
                                                    return $model->informacionLaboral && $model->informacionLaboral->catDireccion
                                                        ? $model->informacionLaboral->catDireccion->nombre_direccion
                                                        : 'N/A';
                                                },
                                                'filter' => Select2::widget([
                                                    'model' => $searchModel,
                                                    'attribute' => 'cat_direccion_id',
                                                    'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'),
                                                    'options' => ['placeholder' => 'Dirección'],
                                                    'pluginOptions' => [
                                                        'allowClear' => true,
                                                    ],
                                                    'theme' => Select2::THEME_KRAJEE_BS3, 
                                                ]),
                                                'contentOptions' => ['class' => 'small-font'], 

                                            ],

                                            

                                            [
                                                'class' => ActionColumn::class,
                                                'template' => '{view} {delete}  {toggle-activation}',
                                                'buttons' => [
                                                    'toggle-activation' => function ($url, $model) {

                                                        $isActive = $model->usuario->status == 10;

                                                        $icon = $isActive ? 'fas fa-ban' : 'far fa-check-circle';
                                                        $title = $isActive ? 'Desactivar Usuario' : 'Activar Usuario';

                                                        return Html::a('<i class="' . $icon . '"></i>', ['empleado/toggle-activation', 'id' => $model->id], [
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


                                                ],

                                            ],

                                        ],
                                        'summaryOptions' => ['class' => 'summary mb-2'],
                                        'pager' => [
                                            'class' => 'yii\bootstrap4\LinkPager',
                                        ],
                                    ]); ?>
                                </div>
                                <?php Pjax::end(); ?>

                            </div>

                            <?php $this->endBlock(); ?>
                            <?php $this->beginBlock('block-junta-gobierno'); ?>
                            <?php $this->beginBlock('block-crear-junta-gobierno'); ?>

                            <div class="card">

                                <?php $form = ActiveForm::begin(['action' => ['junta-gobierno/create'], 'options' => ['enctype' => 'multipart/form-data']]); ?>

                                <div class="card-header bg-info text-white">
                                    <h3>Añadir empleado a la junta de gobierno</h3>

                                    <?= Html::submitButton('AÑADIR <i class="fa fa-save"></i>', ['class' => 'btn btn-success float-right mr-3 btn-dark', 'id' => 'save-button-personal']) ?>
                                </div>
                                <div class="card-body">
                                    <?= $form->field($juntaGobiernoModel, 'cat_direccion_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), 
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('Dirección:'); ?>

                                    <?= $form->field($juntaGobiernoModel, 'cat_departamento_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'), 
                                        'options' => ['placeholder' => 'Selecciona un departamento...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3,
                                    ])->label('Departamento:'); ?>

                                    <?= $form->field($juntaGobiernoModel, 'empleado_id')->widget(Select2::classname(), [
                                        'data' => ArrayHelper::map(Empleado::find()->all(), 'id', function ($model) {
                                            return $model->nombre . ' ' . $model->apellido;
                                        }),
                                        'options' => ['placeholder' => 'Selecciona un empleado...'],
                                        'pluginOptions' => [
                                            'allowClear' => true
                                        ],
                                        'theme' => Select2::THEME_KRAJEE_BS3, 
                                    ])->label('Empleado:'); ?>


                                    <?= $form->field($juntaGobiernoModel, 'nivel_jerarquico')->dropDownList([
                                        'Director' => 'Director',
                                        'Jefe de unidad' => 'Jefe de unidad',
                                        'Jefe de departamento' => 'Jefe de departamento',
                                    ], ['prompt' => 'Selecciona el nivel jerárquico...'])->label('Tipo de empleado:') ?>

                                   

                                </div>
                                <?php ActiveForm::end(); ?>

                            </div>


                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('block-view-junta-gobierno'); ?>

                            <div class="card">


                                <div class="card-header bg-info text-white">
                                    <h3>Lista de directores, jefes de unidad y jefes de departamento</h3>

                                </div>
                                <div class="card-body">
                                    <?php
                                    $searchModel = new JuntaGobiernoSearch();
                                    $params = Yii::$app->request->queryParams;
                                    $params[$searchModel->formName()]['id'] = $juntaGobiernoModel->id;
                                    $dataProvider = $searchModel->search($params);
                                    ?>

                                    <?php Pjax::begin(); ?>

                                    <?= GridView::widget([
                                        'dataProvider' => $dataProvider,
                                        'filterModel' => $searchModel,
                                        'columns' => [
                                            ['class' => 'yii\grid\SerialColumn'],
                                           // 'id',
                                            [
                                                'attribute' => 'cat_direccion_id',
                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->catDireccion->nombre_direccion; 
                                                },
                                            ],
                                            [
                                                'attribute' => 'cat_departamento_id',
                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->catDepartamento->nombre_departamento; 
                                                },
                                            ],
                                            [
                                                'attribute' => 'empleado_id',
                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->empleado->nombre . ' ' . $juntaGobiernoModel->empleado->apellido; 

                                                },
                                            ],
                                            'nivel_jerarquico',
                                            [
                                                'attribute' => 'profesion',
                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->empleado->profesion; 

                                                },
                                            ],
                                            [
                                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                'template' => '{view} {delete}',
                                                'buttons' => [
                                                    'view' => function ($url, $juntaGobiernoModel) {
                                                        return Html::a('<i class="far fa-eye"></i>', ['junta-gobierno/view', 'id' => $juntaGobiernoModel->id, 'cat_direccion_id' => $juntaGobiernoModel->cat_direccion_id, 'cat_departamento_id' => $juntaGobiernoModel->cat_departamento_id, 'empleado_id' => $juntaGobiernoModel->empleado_id], [
                                                            'target' => '_blank',
                                                            'title' => 'Ver archivo',
                                                            'class' => 'btn btn-info btn-xs',
                                                            'data-pjax' => "0"
                                                        ]);
                                                    },
                                                    'delete' => function ($url, $juntaGobiernoModel) {
                                                        return Html::a('<i class="fas fa-trash"></i>', ['junta-gobierno/delete', 'id' => $juntaGobiernoModel->id, 'cat_direccion_id' => $juntaGobiernoModel->cat_direccion_id, 'cat_departamento_id' => $juntaGobiernoModel->cat_departamento_id, 'empleado_id' => $juntaGobiernoModel->empleado_id], [
                                                            'title' => Yii::t('yii', 'Eliminar'),
                                                            'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                                                            'data-method' => 'post',
                                                            'class' => 'btn btn-danger btn-xs',
                                                        ]);
                                                    },

                                                ],
                                            ],
                                        ],
                                        'summaryOptions' => ['class' => 'summary mb-2'],
                                        'pager' => [
                                            'class' => 'yii\bootstrap4\LinkPager',
                                        ],
                                    ]); ?>



                                    <?php Pjax::end(); ?>

                                </div>


                            </div>
                            <?php $this->endBlock(); ?>
                            <?php echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'custom-tabs-2'],
                                'items' => [

                                  ///  [
                                     //   'label' => 'Junta De Gobierno',
                                       // 'content' => $this->blocks['block-view-junta-gobierno'],
                                       // 'active' => true,


                                    //],
                                   // [
                                     //   'label' => 'Añadir',
                                       // 'content' => $this->blocks['block-crear-junta-gobierno'],




                                    //],


                                ],
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_CENTER,
                                // 'bordered'=>true,
                                'encodeLabels' => false

                            ]);

                            ?>



                            <?php $this->endBlock(); ?>


                        </div>
                        <!--.col-md-12-->


                        <?php echo TabsX::widget([
                            'enableStickyTabs' => true,
                            'options' => ['class' => 'custom-tabs-2'],
                            'items' => [
                                [
                                    'label' => 'Empleados',
                                    'content' => $this->blocks['block-empleados'],
                                    'active' => true,
                                    'options' => [
                                        'id' => 'empleados',
                                    ],



                                ],
                                [
                                    'label' => 'Directores y Jefes',
                                    'content' => $this->blocks['block-view-junta-gobierno'],
                                    'options' => [
                                        'id' => 'junta_gobierno',
                                    ],


                                ],


                            ],
                            'position' => TabsX::POS_ABOVE,
                            'align' => TabsX::ALIGN_CENTER,
                            // 'bordered'=>true,
                            'encodeLabels' => false

                        ]);

                        ?>

                    </div>
                    <!--.row-->



                </div>
                <!--.card-body-->


            </div>
            <!--.card-->


        </div>
        <!--.col-md-10-->

    </div>
    <!--.row-->
</div>
<!--.container-fluid-->