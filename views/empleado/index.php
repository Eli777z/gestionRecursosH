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
            <div class="card-header bg-primary text-white"> <!-- Agregando las clases bg-primary y text-white -->
                    <h3><?= Html::encode($this->title) ?></h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex align-items-center mb-3">




                                <?php
                                // Mostrar los flash messages



                                // En tu vista donde deseas mostrar los mensajes de flash:
                                foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                    if ($type === 'error') {
                                        // Muestra los mensajes de error en rojo
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-danger'],
                                            'body' => $message,
                                        ]);
                                    } else {
                                        // Muestra los demás mensajes de flash con estilos predeterminados
                                        echo Alert::widget([
                                            'options' => ['class' => 'alert-' . $type],
                                            'body' => $message,
                                        ]);
                                    }
                                }
                                ?>
                            </div>


                    <?php $this->beginBlock('block-empleados');?>
                    <div class="card">
                     <div class="card-header bg-secondary text-white">
                     <h3>LISTA DE EMPLEADOS</h3>       
                     <?= Html::a('Añadir nuevo   <i class="fa fa-plus"></i>', ['create'], ['class' => 'btn btn-secondary float-right mr-3']) ?>

                            </div>
                   

                            <div class="card-body">
                    <?php Pjax::begin(); ?>
                    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

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
                                    'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->all(), 'id', function($model) {
                                        return $model->apellido . ' ' . $model->nombre;
                                    }), 
                                    'options' => ['placeholder' => 'Empleado'],
                                    'pluginOptions' => [
                                        'allowClear' => true
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                    ],
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                                    ],
                                ]),
                                'contentOptions' => ['class' => 'small-font'], // Aplica la clase CSS personalizada

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
                                    'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                    ],
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                                    ],
                                ]),
                                'contentOptions' => ['class' => 'small-font'], // Aplica la clase CSS personalizada

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
                                    'options' => ['placeholder' => 'Departamento', 'class' => 'small-font'],
                                    'pluginOptions' => [
                                        'allowClear' => true,
                                    ],
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }",
                                    ],
                                ]),
                                'contentOptions' => ['class' => 'small-font'], // Aplica la clase CSS personalizada

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
                                    'theme' => Select2::THEME_BOOTSTRAP,
                                    'pluginEvents' => [
                                        'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }",
                                        'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '0x',); }",
                                    ],
                                ]),
                                'contentOptions' => ['class' => 'small-font'], // Aplica la clase CSS personalizada

                            ],

                            //'usuario_id',
                            //'informacion_laboral_id',
                           // 'cat_nivel_estudio_id',
                            //'parametro_formato_id',
                            //'nombre',
                           // 'apellido',
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

                    <?php $this->endBlock();?>
                    <?php $this->beginBlock('block-junta-gobierno');?>
                    <?php $this->beginBlock('block-crear-junta-gobierno');?>

                    <div class="card">
                   
    <?php $form = ActiveForm::begin(['action' => ['junta-gobierno/create'], 'options' => ['enctype' => 'multipart/form-data']]); ?>

    <div class="card-header bg-secondary text-white">
                                    <h3>Añadir empleado a la junta de gobierno</h3>
                                    
                                    <?= Html::submitButton('<i class="fa fa-save"></i>', ['class' => 'btn btn-secondary float-right mr-3', 'id' => 'save-button-personal']) ?>
                                </div>
                                <div class="card-body">
    <?= $form->field($juntaGobiernoModel, 'cat_direccion_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDireccion::find()->all(), 'id', 'nombre_direccion'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona una dirección...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                                        ],
    ]); ?>

    <?= $form->field($juntaGobiernoModel, 'cat_departamento_id')->widget(Select2::classname(), [
        'data' => ArrayHelper::map(CatDepartamento::find()->all(), 'id', 'nombre_departamento'), // Suponiendo que 'nombre' es el atributo que deseas mostrar en la lista desplegable
        'options' => ['placeholder' => 'Selecciona un departamento...'],
        'pluginOptions' => [
            'allowClear' => true
        ],
        'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
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
    'theme' => Select2::THEME_BOOTSTRAP, // Esto aplicará el estilo de Bootstrap al Select2
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').html('<span class=\"fas fa-times\"></span>'); }", // Aquí se personaliza el icono de borrar
                                        ],
                                        'pluginEvents' => [
                                            'select2:opening' => "function() { $('.select2-selection__clear').css('margin-left', '2px'); }", // Agregar un margen izquierdo
                                        ],
]); ?>


<?= $form->field($juntaGobiernoModel, 'nivel_jerarquico')->dropDownList([
    'Director' => 'Director',
    'Jefe de unidad' => 'Jefe de unidad',
    'Jefe de departamento' => 'Jefe de departamento',
], ['prompt' => 'Selecciona el nivel jerárquico...']) ?>

<?= $form->field($juntaGobiernoModel, 'profesion')->dropDownList([
    'ING.' => 'ING.',
    'LIC.' => 'LIC.',
    'PROF.' => 'PROF.',
    'ARQ.' => 'ARQ.',
    'C.' => 'C.',
    'DR.' => 'DR.',
    'DRA.' => 'DRA.',
    'TEC.' => 'TEC.',
], ['prompt' => 'Selecciona el nivel académico...']) ?>

</div>
    <?php ActiveForm::end(); ?>

</div>


<?php $this->endBlock();?>


<?php $this->beginBlock('block-view-junta-gobierno');?>

<div class="card">
                   
               
                   <div class="card-header bg-secondary text-white">
                                                   <h3>Lista de empleados que pertenecen a la junta de gobierno</h3>
                                                   
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
        'profesion',
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
                         


                            <?php Pjax::end(); ?>
                          
                            </div>
 

</div>
                            <?php $this->endBlock();?>
<?php echo TabsX::widget([
    'enableStickyTabs' => true,
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
              <!--.col-md-12-->


                <?php echo TabsX::widget([
                     'enableStickyTabs' => true,
                    'options' => ['class' => 'custom-tabs-2'],
                    'items' => [
                        [
                            'label' => 'Empleados',
                            'content' => $this->blocks['block-empleados'],
                            'active' => true,
                            'options' =>[
                                'id' => 'empleados',
                            ],
                            


                        ],
                        [
                            'label' => 'Junta De Gobierno',
                           'content' => $this->blocks['block-junta-gobierno'],
                           'options' =>[
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
