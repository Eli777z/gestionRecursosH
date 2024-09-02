<?php
// IMPORTACIONES
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
use yii\helpers\Url;
use app\models\JuntaGobiernoSearch;
use yii\bootstrap5\Alert;
/* @var $this yii\web\View */
/* @var $searchModel app\models\EmpleadoSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

//CARGAR ESTILOS
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = 'Empleados';
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
//PERMISO
$visible = Yii::$app->user->can('medico');
?>
<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-md-12">
            
                <div class="row justify-content-center">
                <div class="col-md-10">
                            <div class="d-flex align-items-center mb-3 float-right">




                                <?php
    //ALERTA


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
      
        <?php 
        //PERMISO PARA IR A CREATE EMPLEADO
        if (Yii::$app->user->can('manejo-empleados')): ?>
            <?= Html::a('AÑADIR NUEVO EMPLEADO  <i class="fa fa-user-plus fa-lg"></i>', ['create'], ['class' => 'btn btn-warning float-right fa-lg btn-dark']) ?>
            <?= Html::a('Lista General de Horas extras <i class="fas fa-file-alt"></i>', ['reporte-tiempo-extra/reporte-general'], [
    'class' => 'btn btn-success mb-3',
    'target' => '_blank', // Para abrir en una nueva pestaña
    'data-pjax' => "0",
]) ?>


            <?php endif; ?>

      
    </div>

    <div class="card-body">
        <?php Pjax::begin(); ?>
        <?php 
        //PERMISO PARA MOSTRAR LA LISTA COMPLETA DE TODOS LOS REGISTROS DE EMPLEADO
        if (Yii::$app->user->can('ver-todos-empleados')) { ?>
            <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                [
                    'class' => 'yii\grid\DataColumn',
                    'header' => '1ra. Revisión Medica',
                    'format' => 'raw',
                    'visible' => $visible,
                    'value' => function ($model) {
                        // Verificar si expedienteMedico no es null
                        if ($model->expedienteMedico !== null) {
                            if ($model->expedienteMedico->primera_revision === 0) {
                                return '<i class="fas fa-exclamation-triangle"  style="color: #ea4242 "></i> <br> PENDIENTE' ;
                                
                            } elseif ($model->expedienteMedico->primera_revision === 1) {
                                return '<i class="fas fa-check-circle" aria-hidden="true" style="color: #4678fc"></i>';
                                
                            } else {
                                return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>';
                            }
                        } else {
                            return '<i class="fa fa-question" aria-hidden="true" style="color: #6c757d"></i>'; // Valor predeterminado si expedienteMedico es null
                        }
                    },
                    'contentOptions' => ['class' => 'text-center'],
                    'headerOptions' => ['class' => 'text-center'],
                ],
                
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
                        'data' => \yii\helpers\ArrayHelper::map(\app\models\Empleado::find()->select(['numero_empleado'])->distinct()->all(), 'numero_empleado', 'numero_empleado'),
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
                   // if (Yii::$app->user->can('btn-vista-crear-empleado')): 

                    'template' => Yii::$app->user->can('manejo-empleados') ? '{view} {toggle-activation}' : '{view}',
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
                                'title' => 'Ver información de empleado',
                                'class' => 'btn btn-info btn-xs',
                                'data-pjax' => "0"
                            ]);
                        },
                       // 'delete' => function ($url, $model) {
                           // return Html::a('<i class="fas fa-trash"></i>', $url, [
                            //    'title' => Yii::t('yii', 'Eliminar'),
                           //     'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                         //       'data-method' => 'post',
                          //      'class' => 'btn btn-danger btn-xs',
                         //  ]);
                        //},
                    ],
                ],
            ],
            'summaryOptions' => ['class' => 'summary mb-2'],
            'pager' => [
                'class' => 'yii\bootstrap4\LinkPager',
            ],
        ]); ?>
        

        <?php 
    //MOSTRAR LISTA DE EMPLEADOS POR DIRECCION O DEPARTAMENTO
    } elseif (Yii::$app->user->can('ver-empleados-direccion') || Yii::$app->user->can('ver-empleados-departamento')) { ?>
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
                    'data' => $nombresEmpleadosData,
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
                    'data' => $numeroEmpleadosData,
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
                    'data' => $departamentosData, // Usa los datos filtrados
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
                'class' => 'yii\grid\ActionColumn',
                
                'template' => Yii::$app->user->can('manejo-empleados') ? '{view}  {toggle-activation}' : '{view}',
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
                            'title' => 'Ver información de empleado',
                           'class' => 'btn btn-outline-info btn-sm',
                            'data-pjax' => "0"
                        ]);
                    },
                   // 'delete' => function ($url, $model) {
                    //    return Html::a('<i class="fas fa-trash"></i>', $url, [
                     //      'title' => Yii::t('yii', 'Eliminar'),
                      //     'data-confirm' => Yii::t('yii', '¿Estás seguro de que deseas eliminar este elemento?'),
                       //     'data-method' => 'post',
                       //    'class' => 'btn btn-danger btn-xs',
                        //]);
                    //},
                ],
            ],

            [
                'label' => 'Formatos Incidencias',
                'format' => 'raw',
                'value' => function ($model) {//DROPDWON PARA MOSTRAR LAS ACCIONES QUE SE PUEDEN REALIZAR CON LOS EMPLEADOS
                    $solicitudContratoMenu = '';

                    // Verificamos si el empleado tiene el tipo de contrato "Eventual"
                    if ($model->informacionLaboral && $model->informacionLaboral->catTipoContrato && $model->informacionLaboral->catTipoContrato->nombre_tipo === 'Eventual') {
                        $solicitudContratoMenu = '
                        <div class="dropdown-submenu">
                            <a class="dropdown-item dropdown-toggle" href="#">SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL</a>
                            <ul class="dropdown-menu">
                                <li>' . Html::a('Ver', Url::to(['contrato-para-personal-eventual/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                <div class="dropdown-divider"></div>
                                <li>' . Html::a('Crear', Url::to(['contrato-para-personal-eventual/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                            </ul>
                        </div>';
                    }
            
                    // El resto de tu dropdown
                    return '
            
                    <div class="btn-group dropleft">
                        <button class="btn btn-success dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Formatos
                        </button>
                        <div class="dropdown-menu " aria-labelledby="dropdownMenuButton ">
                        <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">CITA MEDICA</a>
                                <ul class="dropdown-menu ">
                                    <li>' . Html::a('Ver', Url::to(['cita-medica/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div> 
                                    
                                    <li>' .  Html::a('Crear', Url::to(['cita-medica/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                              
                              
                                    </ul>
                            </div>
                                            ' . $solicitudContratoMenu . '  <!-- Aquí se agrega la opción solo si es Eventual -->

                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">PERMISO FUERA DEL TRABAJO</a>
                                <ul class="dropdown-menu ">
                                    <li>' . Html::a('Ver', Url::to(['permiso-fuera-trabajo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['permiso-fuera-trabajo/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">COMISIÓN ESPECIAL</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['comision-especial/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['comision-especial/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">CAMBIO DE DÍA LABORAL</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['cambio-dia-laboral/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['cambio-dia-laboral/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">CAMBIO DE HORARIO DE TRABAJO</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['cambio-horario-trabajo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['cambio-horario-trabajo/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">PERMISO ECONÓMICO</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['permiso-economico/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['permiso-economico/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">PERMISO SIN GOCE DE SUELDO</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['permiso-sin-sueldo/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                     <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['permiso-sin-sueldo/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                             

                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">CAMBIO DE PERIODO VACACIONAL</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['cambio-periodo-vacacional/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                      <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['cambio-periodo-vacacional/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>

                            <div class="dropdown-submenu">
                                <a class="dropdown-item dropdown-toggle" href="#">REPORTE DE TIEMPO EXTRA</a>
                                <ul class="dropdown-menu">
                                    <li>' . Html::a('Ver', Url::to(['reporte-tiempo-extra/historial', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                     <div class="dropdown-divider"></div>

                                    <li>' . Html::a('Crear', Url::to(['reporte-tiempo-extra/create', 'empleado_id' => $model->id]), ['class' => 'dropdown-item text-primary', 'data-pjax' => '0']) . '</li>
                                </ul>
                            </div>
                        </div>
                    </div>';
                },
                'contentOptions' => ['class' => 'small-font'],
            ],
            
           
              
                    ],
        'summaryOptions' => ['class' => 'summary mb-2'],
        'pager' => [
            'class' => 'yii\bootstrap4\LinkPager',
        ],
    ]); ?>

<?php } ?>



    </div>
    <script> 
            $(document).ready(function(){
                $('.dropdown-submenu').on('mouseenter', function() {
                    $(this).children('.dropdown-menu').show();
                }).on('mouseleave', function() {
                    $(this).children('.dropdown-menu').hide();
                });
            });
        //SCRIPT QUE RESPONDE A LAS ACCIONES DEL DROPDOWN
</script>
    <?php Pjax::end(); ?>
</div>
<br>
<br><br><br> <br> <br><br><br> <br> <br><br><br> <br> 
<?php $this->endBlock(); ?>

                            <?php $this->beginBlock('block-junta-gobierno'); ?>
                           
                            <?php 
                            //BLOQUE PARA CARGAR UNA LISTA QUE MUESTRE LOS EMPLEADOS
                            //QUE SE ENCUENTRAN COMO DIRECTORES, JEFES DE UNIDA O DEPRTAMENTO
                            $this->beginBlock('block-view-junta-gobierno'); ?>

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
                                            'attribute' => 'foto',
                                            'format' => 'html',
                                            'filter' => false,
                                            'value' => function ($juntaGobiernoModel) {
                                                if ($juntaGobiernoModel->empleado->foto) {
                                                    $urlImagen = Yii::$app->urlManager->createUrl(['empleado/foto-empleado', 'id' => $juntaGobiernoModel->empleado->id]);
                                                    return Html::img($urlImagen, ['width' => '80px', 'height' => '80px']);
                                                }
                                                return null;
                                            },
                                        ],
                                        [
                                            'label' => 'Empleado',
                                            'attribute' => 'empleado_id',
                                            'filter' => false,

                                            'value' => function ($juntaGobiernoModel) {
                                                return $juntaGobiernoModel->empleado->nombre . ' ' . $juntaGobiernoModel->empleado->apellido; 

                                            },
                                        ],
                                            [
                                                'label' => 'Dirección',
                                                'attribute' => 'cat_direccion_id',
                                                'filter' => false,

                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->catDireccion->nombre_direccion; 
                                                },
                                            ],
                                          
                                            [
                                                'label' => 'Nombramiento',
                                                'attribute' => 'empleado_id',
                                                'filter' => false,

                                                'value' => function ($juntaGobiernoModel) {
                                                    return $juntaGobiernoModel->empleado->informacionLaboral->catPuesto->nombre_puesto; 
                                                },
                                            ],
                                           
                                          
                                           

                                           
                                            [
                                                'class' => 'hail812\adminlte3\yii\grid\ActionColumn',
                                                'template' => '{view} ',
                                                'buttons' => [
                                                    'view' => function ($url, $juntaGobiernoModel) {
                                                        return Html::a('<i class="far fa-eye"></i>', ['empleado/view', 'id' => $juntaGobiernoModel->empleado_id], [
                                                            //'target' => '_blank',
                                                            'title' => 'Ver empleado',
                                                            'class' => 'btn btn-info btn-xs',
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
                                    ]); ?>



                                    <?php Pjax::end(); ?>

                                </div>


                            </div>
                            <?php $this->endBlock(); ?>
                            <?php echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'custom-tabs-2'],
                                'items' => [

                                  


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


                        <?php
$tabs = [
    [
        'label' => 'Empleados',
        'content' => $this->blocks['block-empleados'],
        'active' => true,
        'options' => [
            'id' => 'empleados',
        ],
    ],
];
//PERMISO
if (Yii::$app->user->can('ver-lista-directores-jefes')) {
    $tabs[] = [
        'label' => 'Directores y Jefes',
        'content' => $this->blocks['block-view-junta-gobierno'],
        'options' => [
            'id' => 'junta_gobierno',
        ],
    ];
}
//CARGAR TABS
echo TabsX::widget([
    'enableStickyTabs' => true,
    'options' => ['class' => 'custom-tabs-2'],
    'items' => $tabs,
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_CENTER,
    'encodeLabels' => false,
]);
?>

                    </div>

        </div>
      

    </div>
   
</div>
