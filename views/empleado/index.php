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
                <div class="card-body">
                    <?php $this->beginBlock('block-empleados');?>
                    <div class="row mb-2">
                        <div class="col-md-12">
                            <?= Html::a('Create Empleado', ['create'], ['class' => 'btn btn-success']) ?>
                        </div>
                    </div>


                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['class' => 'grid-view', 'style' => 'width: 80%; margin: auto;'],
                        'tableOptions' => ['class' => 'table table-striped table-bordered table-condensed'],
                        
                        'columns' => [
                            
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
                            'id',
                            'numero_empleado',
                            //'usuario_id',
                            //'informacion_laboral_id',
                           // 'cat_nivel_estudio_id',
                            //'parametro_formato_id',
                            'nombre',
                            'apellido',
                            //'fecha_nacimiento',
                            //'edad',
                            //'sexo',
                            //'foto',
                            //'telefono',
                            //'email:email',
                            //'estado_civil',
                            //'colonia',
                            //'calle',
                            //'numero_casa',
                            //'codigo_postal',
                            //'nombre_contacto_emergencia',
                            //'relacion_contacto_emergencia',
                            //'telefono_contacto_emergencia',
                            //'institucion_educativa',
                            //'titulo_grado',

                            [
                                'class' => ActionColumn::class,
            'template' => '{view} {delete}  {toggle-activation}', //botones deseados
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
            'options' => ['style' => 'width: 15%;'], 
        ],
    ],
    'summaryOptions' => ['class' => 'summary mb-2'],
    'pager' => [
        'class' => 'yii\bootstrap4\LinkPager',
    ]
]); ?>

                    <?php Pjax::end(); ?>
                    <?php $this->endBlock();?>
                    <?php $this->beginBlock('block-junta-gobierno');?>
                    <?php $this->beginBlock('block-crear-junta-gobierno');?>
                    <div class="junta-gobierno-form">
    <?php $form = ActiveForm::begin(['action' => ['junta-gobierno/create'], 'options' => ['enctype' => 'multipart/form-data', 'class' => 'narrow-form']]); ?>

    <?= $form->field($juntaGobiernoModel, 'cat_direccion_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona una dirección...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

    <?= $form->field($juntaGobiernoModel, 'cat_departamento_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona un departamento...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>

<?= $form->field($juntaGobiernoModel, 'empleado_id')->widget(Select2::classname(), [
    'data' => ArrayHelper::map(Empleado::find()->all(), 'id', function($model) {
        return $model->nombre . ' ' . $model->apellido;
    }),
    'options' => ['placeholder' => 'Selecciona un empleado...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]); ?>


<?= $form->field($juntaGobiernoModel, 'nivel_jerarquico')->dropDownList([
    'Director' => 'Director',
    'Jefe de unidad' => 'Jefe de unidad',
], ['prompt' => 'Selecciona el nivel jerárquico...']) ?>


    <div class="form-group">
        <?= Html::submitButton('Guardar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>


<?php $this->endBlock();?>


<?php $this->beginBlock('block-view-junta-gobierno');?>


<?php
                            $searchModel = new JuntaGobiernoSearch();
                            $params = Yii::$app->request->queryParams;
                            $params[$searchModel->formName()]['id'] = $juntaGobiernoModel->id;
                            $dataProvider = $searchModel->search($params);
                            ?>

                            <?php Pjax::begin(); ?>
                            <div class="card-container">
                                <?= GridView::widget([
                                    'dataProvider' => $dataProvider,
                                    'filterModel' => $searchModel,
                                    'options' => ['class' => 'grid-view'],
                                    'tableOptions' => ['class' => 'table table-simple table-striped table-bordered table-condensed borderless'], // Aquí agregamos la clase "table-simple"
                                    'columns' => [
                                        ['class' => 'yii\grid\SerialColumn'],
                                        'id',
        [
            'attribute' => 'cat_direccion_id',
            'value' => function($juntaGobiernoModel) {
                return $juntaGobiernoModel->catDireccion->nombre_direccion; // Reemplaza 'nombre' con el nombre del atributo que deseas mostrar
            },
        ],
        [
            'attribute' => 'cat_departamento_id',
            'value' => function($juntaGobiernoModel) {
                return $juntaGobiernoModel->catDepartamento->nombre_departamento; // Reemplaza 'nombre' con el nombre del atributo que deseas mostrar
            },
        ],
        [
            'attribute' => 'empleado_id',
            'value' => function($juntaGobiernoModel) {
                return $juntaGobiernoModel->empleado->nombre. ' '.$juntaGobiernoModel->empleado->apellido ; // Reemplaza 'nombreCompleto' con el nombre del método que devuelve el nombre completo

            },
        ],
        'nivel_jerarquico',
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
                                            'options' => ['style' => 'width: 15%;'], //ancho de la columna
                                        ],
                                    ],
                                    'summaryOptions' => ['class' => 'summary mb-2'],
                                    'pager' => [
                                        'class' => 'yii\bootstrap4\LinkPager',
                                    ],
                                ]); ?>
                            </div>


                            <?php Pjax::end(); ?>
                          

                            <?php $this->endBlock();?>
<?php echo TabsX::widget([
                    'options' => ['class' => 'custom-tabs-2'],
                    'items' => [
                       
                        [
                            'label' => 'Junta De Gobierno',
                           'content' => $this->blocks['block-view-junta-gobierno'],
                           'active' => true,

                        ],
                        [
                            'label' => 'Añadir',
                            'content' => $this->blocks['block-crear-junta-gobierno'],
                           


                        ],


                    ],
                    'position' => TabsX::POS_ABOVE,
                    'align' => TabsX::ALIGN_CENTER,
                    // 'bordered'=>true,
                    'encodeLabels' => false

                ]);

                ?>



                    <?php $this->endBlock();?>


                </div>
                <!--.card-body-->



                <?php echo TabsX::widget([
                    'options' => ['class' => 'custom-tabs-2'],
                    'items' => [
                        [
                            'label' => 'Empleados',
                            'content' => $this->blocks['block-empleados'],
                            'active' => true,


                        ],
                        [
                            'label' => 'Junta De Gobierno',
                           'content' => $this->blocks['block-junta-gobierno'],


                        ],


                    ],
                    'position' => TabsX::POS_ABOVE,
                    'align' => TabsX::ALIGN_CENTER,
                    // 'bordered'=>true,
                    'encodeLabels' => false

                ]);

                ?>

            </div>
            <!--.card-->
        </div>
        <!--.col-md-12-->
    </div>
    <!--.row-->
</div>
