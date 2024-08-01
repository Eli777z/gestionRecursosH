<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
use yii\web\View;
/* @var $this yii\web\View */
/* @var $model app\models\Solicitud */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = $model->id;


//$this->params['breadcrumbs'][] = ['label' => 'Solicitudes', 'url' => ['solicitud/index']];

if ($empleadoId !== null && Yii::$app->user->can('medico') || Yii::$app->user->can('gestor-rh')) {
   
    $this->params['breadcrumbs'][] = ['label' => 'Empleado: ' .$model->empleado->nombre . ' ' . $model->empleado->apellido, 'url' => ['empleado/view', 'id' => $empleadoId]];
}
else  if (Yii::$app->user->can('empleado')) {
    $this->params['breadcrumbs'][] = ['label' => 'Inicio', 'url' => ['site/portalempleado']];
    
     }
 if (Yii::$app->user->can('gestor-rh')) {
$this->params['breadcrumbs'][] = ['label' => 'Solicitudes', 'url' => ['solicitud/index']];

 }
$this->params['breadcrumbs'][] = ['label' => 'Solicitud ' . $model->id];

\yii\web\YiiAsset::register($this);
?>
<div class="container-fluid">
    <div class="row justify-content-center"> 
        <div class="col-md-8">
            <div class="card">
            <div class="card-header gradient-info text-white">
                    <div class="d-flex justify-content-between"> 
                        <h1> CITA MEDICA </h1>
                        <?php if (Yii::$app->user->can('crear-consulta-medica')) : ?>

<?php if ($model->empleado->expedienteMedico): ?>
                <?= Html::a('Nueva consulta <i class="fa fa-user-md" ></i>', ['consulta-medica/create', 'expediente_medico_id' => $model->empleado->expedienteMedico->id], [
                    'class' => 'btn btn-dark mt-3 float-right fa-lg'
                ]) ?>
                

            <?php endif; ?>
            
            <?php endif; ?>
                    <?php /* ?>
<div class="form-group mr-2">
    <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::label('Añadir Comentarios:', null, ['class' => 'control-label']) ?>
        <?= Html::textInput('comentario', $model->comentario, ['class' => 'form-control']) ?>
    <?= Html::endForm() ?>
</div>

<div class="form-group mr-2 mb-2">
    <?php if ($model->nombre_formato == 'CAMBIO PERIODO VACACIONAL'): ?>
        <?= Html::beginForm(['aprobar-cambio-periodo-vacacional', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::submitButton(Html::tag('i', '  Aprobar', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-primary', 'title' => 'Aceptar']) ?>
        <?= Html::endForm() ?>
    <?php else: ?>
        <?= Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']) ?>
        <?= Html::submitButton(Html::tag('i', '  Aprobar', ['class' => 'fas fa-check']), ['name' => 'status', 'value' => 'Aprobado', 'class' => 'btn btn-success', 'title' => 'Aceptar']) ?>
        <?= Html::endForm() ?>
    <?php endif; ?>
</div>
<?php 




                        <div class="form-group mr-2 mb-2">
                            <?= Html::submitButton(Html::tag('i', '  Rechazar', ['class' => 'fas fa-times']), ['name' => 'status', 'value' => 'Rechazado', 'class' => 'btn btn-danger', 'title' => 'Rechazar']) ?>
                        </div>
                        
                        <?= Html::endForm() ?>
                    </div>

                    */ ?>
                </div>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) {
                                echo Alert::widget([
                                    'options' => ['class' => 'alert-' . $type],
                                    'body' => $message,
                                ]);
                            } ?>
                          

<?php

echo DetailView::widget([
    'model' => $model,
    'attributes' => [
        [
            'label' => 'Número de empleado',
            'value' => function ($model) {
                return $model->empleado->numero_empleado;
            },
        ],
        [
            'label' => 'Tipo de formato',
            'value' => function ($model) {
                return $model->nombre_formato;
            },
        ],
        [
            'label' => 'Nombre',
            'value' => function ($model) {
                return $model->empleado ? $model->empleado->nombre . ' ' . $model->empleado->apellido : 'N/A';
            },
        ],
        [
            'attribute' => 'fecha_creacion',
            'format' => 'raw',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
            },
        ],
        // Otros atributos de la solicitud...
    ],
]);

if ($formato) {
    $formatoAttributes = [];
    
    switch ($model->nombre_formato) {
        case 'PERMISO FUERA DEL TRABAJO':
            $formatoAttributes = [
                //[
                  //  'label' => 'Motivo Fecha Permiso',
                    //'value' => $formato->motivo_fecha_permiso ? $formato->motivo_fecha_permiso->descripcion : 'N/A',
                //],
                [
                    'attribute' => 'hora_salida',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->hora_salida));
                        return $hora;
                    },
                ],
                [
                    'attribute' => 'hora_regreso',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->hora_regreso));
                        return $hora;
                    },
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

                [
                    'attribute' => 'horario_fecha_a_reponer',
                    'value' => function ($formato) {
                        $hora = date("g:i A", strtotime($formato->horario_fecha_a_reponer));
                        return $hora;
                    },
                ],
               // 'nota:ntext',
                [
                    'label' => 'Fecha de Permiso',
                    'value' => function ($formato) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],
                // Otros atributos específicos de PermisoFueraTrabajo
            ];
            break;

        case 'COMISION ESPECIAL':
            $formatoAttributes = [
                
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
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($formato) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                // Otros atributos específicos de ComisionEspecial
            ];
            break;

        case 'PERMISO ECONOMICO':
            $formatoAttributes = [
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
                [
                    'label' => 'Fecha de Permiso',
                    'value' => function ($formato) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],
                // Otros atributos específicos de PermisoEconomico
            ];
            break;


            case 'CAMBIO DE DÍA LABORAL':
                $formatoAttributes = [
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
                    [
                        'label' => 'Fecha de Permiso',
                        'value' => function ($formato) {
                           
                            setlocale(LC_TIME, "es_419.UTF-8");
                            
                            $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                            
                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                            
                            setlocale(LC_TIME, null);
                            
                            return $fechaFormateada;
                        },
                    ],
                    [
                        'label' => 'Fecha a Laborar',
                        'value' => function ($formato) {
                           
                            setlocale(LC_TIME, "es_419.UTF-8");
                            
                            $fechaPermiso = strtotime($formato->fecha_permiso);
                            
                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                            
                            setlocale(LC_TIME, null);
                            
                            return $fechaFormateada;
                        },
                    ],
                    // Otros atributos específicos de PermisoEconomico
                ];
                break;


                case 'CAMBIO DE DÍA LABORAL':
                    $formatoAttributes = [
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
                        [
                            'label' => 'Fecha de Permiso',
                            'value' => function ($formato) {
                               
                                setlocale(LC_TIME, "es_419.UTF-8");
                                
                                $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                                
                                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                
                                setlocale(LC_TIME, null);
                                
                                return $fechaFormateada;
                            },
                        ],
                        [
                            'label' => 'Fecha a Laborar',
                            'value' => function ($formato) {
                               
                                setlocale(LC_TIME, "es_419.UTF-8");
                                
                                $fechaPermiso = strtotime($formato->fecha_permiso);
                                
                                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                
                                setlocale(LC_TIME, null);
                                
                                return $fechaFormateada;
                            },
                        ],
                        // Otros atributos específicos de PermisoEconomico
                    ];
                    break;
                    case 'CAMBIO DE HORARIO DE TRABAJO':
                        $formatoAttributes = [
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
                            [
                                'label' => 'Fecha de Permiso',
                                'value' => function ($formato) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'attribute' => 'horario_inicio',
                                'value' => function ($formato) {
                                    $hora = date("g:i A", strtotime($formato->horario_inicio));
                                    return $hora;
                                },
                            ],
                            [
                                'attribute' => 'horario_fin',
                                'value' => function ($formato) {
                                    $hora = date("g:i A", strtotime($formato->horario_fin));
                                    return $hora;
                                },
                            ],
                            [
                                'label' => 'Fecha de Inicio',
                                'value' => function ($formato) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaInicio= strtotime($formato->fecha_inicio);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                            [
                                'label' => 'Fecha de Termino',
                                'value' => function ($formato) {
                                   
                                    setlocale(LC_TIME, "es_419.UTF-8");
                                    
                                    $fechaTermino= strtotime($formato->fecha_termino);
                                    
                                    $fechaFormateada = strftime('%A, %B %d, %Y', $fechaTermino);
                                    
                                    setlocale(LC_TIME, null);
                                    
                                    return $fechaFormateada;
                                },
                            ],
                           
                            // Otros atributos específicos de PermisoEconomico
                        ];
                        break;

                        case 'PERMISO SIN GOCE DE SUELDO':
                            $formatoAttributes = [
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
                                [
                                    'label' => 'Fecha de Permiso',
                                    'value' => function ($formato) {
                                       
                                        setlocale(LC_TIME, "es_419.UTF-8");
                                        
                                        $fechaPermiso = strtotime($formato->motivoFechaPermiso->fecha_permiso);
                                        
                                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                        
                                        setlocale(LC_TIME, null);
                                        
                                        return $fechaFormateada;
                                    },
                                ],
                                // Otros atributos específicos de PermisoEconomico
                            ];
                            break;
                            case 'CITA MEDICA':
                                $formatoAttributes = [
                                    //[
                                      //  'label' => 'Motivo de cita medica',
                                        //'value' => function ($formato) {
                                          //  return $formato->comentario; 
                                   //     },
                                    ///],

                                    [
                                        'label' => 'Motivo de cita medica',
                                        'attribute' => 'comentario',
                                        'format' => 'html',
                                        'value' => function ($formato) {
                                            return \yii\helpers\Html::decode($formato->comentario);
                                        },
                                        'filter' => false,
                                        'options' => ['style' => 'width: 65%;'],
                                    ],
                                   [
                                   'label' => 'Fecha de Cita medica',
                                        'value' => function ($formato) {
                                           
                                            setlocale(LC_TIME, "es_419.UTF-8");
                                            
                                            $fechaPermiso = strtotime($formato->fecha_para_cita);
                                            
                                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                            
                                            setlocale(LC_TIME, null);
                                            
                                           return $fechaFormateada;
                                        },
                                    ],

                                    [
                                        'attribute' => 'horario_inicio',
                                        'value' => function ($formato) {
                                            $hora = date("g:i A", strtotime($formato->horario_inicio));
                                            return $hora;
                                        },
                                    ],
                                    [
                                        'attribute' => 'horario_finalizacion',
                                        'value' => function ($formato) {
                                            $hora = date("g:i A", strtotime($formato->horario_finalizacion));
                                            return $hora;
                                        },
                                    ],
                                    // Otros atributos específicos de PermisoEconomico
                                ];
                                break;
                    

                            case 'CAMBIO DE PERIODO VACACIONAL':
                                $formatoAttributes = [
                                    [
                                        'label' => 'Motivo',
                                        'value' => function ($formato) {
                                            return $formato->motivo; 
                                        },
                                    ],
                                    [
                                        'label' => 'Año',
                                        'value' => function ($formato) {
                                            return $formato->año; 
                                        },
                                    ],

                                    [
                                        'label' => 'Primera vez',
                                        'value' => function ($formato) {
                                            return $formato->primera_vez; 
                                        },
                                    ],

                                    [
                                        'label' => 'Numero de periodo',
                                        'value' => function ($formato) {
                                            return $formato->numero_periodo; 
                                        },
                                    ],
                                    [
                                        'label' => 'Fecha de Inicio',
                                        'value' => function ($formato) {
                                           
                                            setlocale(LC_TIME, "es_419.UTF-8");
                                            
                                            $fechaInicio= strtotime($formato->fecha_inicio_periodo);
                                            
                                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                            
                                            setlocale(LC_TIME, null);
                                            
                                            return $fechaFormateada;
                                        },
                                    ],
                                    [
                                        'label' => 'Fecha de Fin',
                                        'value' => function ($formato) {
                                           
                                            setlocale(LC_TIME, "es_419.UTF-8");
                                            
                                            $fechaInicio= strtotime($formato->fecha_fin_periodo);
                                            
                                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaInicio);
                                            
                                            setlocale(LC_TIME, null);
                                            
                                            return $fechaFormateada;
                                        },
                                    ],
                                    // Otros atributos específicos de PermisoEconomico
                                ];
                                break;
                     
            

        // Agrega aquí más casos para otros formatos si es necesario...
    }

    echo DetailView::widget([
        'model' => $formato,
        'attributes' => $formatoAttributes,
    ]);
} else {
    echo '<p>No se encontró información del formato asociado.</p>';
}

?>
                        </div>
                        <!--.col-md-12-->
                    </div>
                    <!--.row-->
                    
                </div>
                <!--.card-body-->
            </div>
            <!--.card-->
        </div>
        <!--.col-md-6-->
    </div>
    <!--.row justify-content-center-->
</div>
<!--.container-fluid-->
