<?php
//IMPORTACIONES
use yii\helpers\Html;
use yii\widgets\DetailView;
use yii\bootstrap5\Alert;
use yii\web\View;
use yii\grid\GridView;
/* @var $this yii\web\View */
/* @var $model app\models\Solicitud */
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);

$this->title = $model->id;



if ($empleadoId !== null && Yii::$app->user->can('medico') || Yii::$app->user->can('gestor-rh')) {
//PERMISO   
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
            <div class="card-header bg-info text-white">
                    <div class="d-flex justify-content-between"> 

                    <?php if (Yii::$app->user->can('medico')) { ?>
                        <h3> CITA MEDICA </h3>
                        <p>  Empleado: <?= $model->empleado->nombre.' '.$model->empleado->apellido ?></p>

                        <?php }else { ?>
                            <h3> Solicitud  </h3>
                            <p>  Empleado: <?= $model->empleado->nombre.' '.$model->empleado->apellido ?></p>
                            
                 
                            
                        <?php } ?>

                        <?php if (Yii::$app->user->can('crear-consulta-medica')) : ?>

<?php if ($model->empleado->expedienteMedico): ?>
                <?= Html::a('Nueva consulta <i class="fa fa-user-md" ></i>', ['consulta-medica/create', 'expediente_medico_id' => $model->empleado->expedienteMedico->id], [
                    'class' => 'btn btn-dark mt-3 float-right fa-lg'
                ]) ?>
                

            <?php endif; ?>
            
            <?php endif; ?>
                   
                </div>
            </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message) {//ALERTA
                                echo Alert::widget([
                                    'options' => ['class' => 'alert-' . $type],
                                    'body' => $message,
                                ]);
                            } ?>
                          

<?php

echo DetailView::widget([
    ///MUESTRA LA INFORMACION DEL REGISTRO DE SOLICITUD
    'model' => $model,
    'attributes' => [
        [
            'label' => 'ID de solicitud',
            'attribute' => 'id',
            'value' => function ($model) {
                return $model->id;
            },
        ],
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
            'label' => 'Fecha de creación',
            'attribute' => 'fecha_creacion',
            'format' => 'raw',
            'value' => function ($model) {
                setlocale(LC_TIME, "es_419.UTF-8");
                return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));
            },
        ],
        
        
      
    ],
]);

if ($formato) {
    $formatoAttributes = [];
    //ESTE SWITCH SE ENCARGA DE IDENTIFICAR CUALES ATRIBUTOS SE VAN A MOSTRAR EN EL DETAILVIEW
    //YA QUE DEPENDE DEL TIPO DE SOLICITUD QUE SE HAYA REALIZADO SE VAN A MOSTRAR DIFERENTES DATOS
    //SE MOSTRARA LA INFORMACION DEL FORMATO ASOCIADO A LA SOLICTUD
    switch ($model->nombre_formato) {
        case 'REPORTE DE TIEMPO EXTRA':

            $formatoAttributes = [
                [
                    'label' => 'ID de solicitud',
                    'attribute' => 'solicitud_id',
                    'value' => function ($model) {
                        return $model->solicitud_id;
                    },
                ],
              
                [
                    'label' => 'Fecha de creación de solicitud',
                    'attribute' => 'created_at',
                    'value' => function ($model) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($model->created_at);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],
                [
                    'label' => 'Aprobado en',
                    'attribute' => 'fecha_aprobacion',
                    'value' => function ($model) {
                       
                        setlocale(LC_TIME, "es_419.UTF-8");
                        
                        $fechaPermiso = strtotime($model->solicitud->fecha_aprobacion);
                        
                        $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                        
                        setlocale(LC_TIME, null);
                        
                        return $fechaFormateada;
                    },
                ],

                [
                    'label' => 'Aprobante',
                    'attribute' => 'nombre_aprobante',
                    'value' => function ($model) {
                        return $model->solicitud->nombre_aprobante;
                    },
                ],
            ];
        
            // Calcular el total de horas
            $totalHoras = array_reduce($actividades, function ($carry, $actividad) {
                return $carry + $actividad['no_horas'];
            }, 0);
        
            echo '<div class="row">';
            echo '<div class="col-md-12">';
            echo '<h3>Actividades</h3>';
            echo GridView::widget([
                'dataProvider' => new \yii\data\ArrayDataProvider([
                    'allModels' => $actividades,
                    'pagination' => false,
                ]),
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                //    'fecha',


                    [
                        'label' => 'Fecha',
                        'value' => function ($formato) {
                           
                            setlocale(LC_TIME, "es_419.UTF-8");
                            
                            $fechaPermiso = strtotime($formato->fecha);
                            
                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                            
                            setlocale(LC_TIME, null);
                            
                            return $fechaFormateada;
                        },
                    ],

                    'hora_inicio',
                    'hora_fin',
                    'actividad',
                    'no_horas',
                ],
            ]);
        
            // Mostrar el total de horas fuera del GridView
            echo '<div class="total-horas">';
            echo '<h4>Total de horas: ' . $totalHoras . '</h4>';
            echo '</div>';
        if(Yii::$app->user->can('aprobar-solicitudes')){
            echo Html::beginForm(['aprobar-solicitud', 'id' => $model->id], 'post', ['class' => 'form-inline']);
            echo '<div class="form-group mr-2 mb-2">';
            echo Html::submitButton(Html::tag('i', ' Aprobar', ['class' => 'fas fa-check']), ['name' => 'aprobacion', 'value' => 'APROBADO', 'class' => 'btn btn-success', 'title' => 'Aprobar']);
            echo '</div>';
            echo '<div class="form-group mr-2 mb-2">';
            echo Html::submitButton(Html::tag('i', ' Rechazar', ['class' => 'fas fa-times']), ['name' => 'aprobacion', 'value' => 'RECHAZADO', 'class' => 'btn btn-danger', 'title' => 'Rechazar']);
            echo '</div>';
            echo '<div class="form-group mr-2">';
            echo Html::label('Comentario', null, ['class' => 'control-label']);
            echo Html::textInput('comentario', $model->comentario, ['class' => 'form-control']);
            echo '</div>';
            echo Html::endForm();
        }
        
            echo '</div>';
            echo '</div>';
            break;
        

        case 'PERMISO FUERA DEL TRABAJO':
            $formatoAttributes = [
               
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
            ];
            break;

            case 'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL':
                $formatoAttributes = [
                  //  'numero_contrato',
        
                    [
                        'label' => 'Numero de contrato',
                        'value' => function ($formato) {
                            return $formato->numero_contrato; 
                        },
                    ],
                    [
                        'label' => 'Fecha de Inicio',
                        'value' => function ($formato) {
                           
                            setlocale(LC_TIME, "es_419.UTF-8");
                            
                            $fechaPermiso = strtotime($formato->fecha_inicio);
                            
                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                            
                            setlocale(LC_TIME, null);
                            
                            return $fechaFormateada;
                        },
                    ],

                    [
                        'label' => 'Fecha de Finalización',
                        'value' => function ($formato) {
                           
                            setlocale(LC_TIME, "es_419.UTF-8");
                            
                            $fechaPermiso = strtotime($formato->fecha_termino);
                            
                            $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                            
                            setlocale(LC_TIME, null);
                            
                            return $fechaFormateada;
                        },
                    ],


                   // 'duracion',
                   // 'modalidad',
                    [
                        'label' => 'Duración en días',
                        'value' => function ($formato) {
                            return $formato->duracion; 
                        },
                    ],
                    
                    [
                        'label' => 'Modalidad',
                        'value' => function ($formato) {
                            return $formato->modalidad; 
                        },
                    ],
                    [
                        'label' => 'Actividad a realizar',
                        'attribute' => 'actividades_realizar',
                        'format' => 'html',
                        'value' => function ($formato) {
                            return \yii\helpers\Html::decode($formato->actividades_realizar);
                        },
                        'filter' => false,
                        //'options' => ['style' => 'width: 65%;'],
                    ],

                    [
                        'label' => 'Origen de contrato',
                        'attribute' => 'origen_contrato',
                        'format' => 'html',
                        'value' => function ($formato) {
                            return \yii\helpers\Html::decode($formato->origen_contrato);
                        },
                        'filter' => false,
                       // 'options' => ['style' => 'width: 65%;'],
                    ],
                   
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
                                
                                $fechaPermiso = strtotime($formato->fecha_a_laborar);
                                
                                $fechaFormateada = strftime('%A, %B %d, %Y', $fechaPermiso);
                                
                                setlocale(LC_TIME, null);
                                
                                return $fechaFormateada;
                            },
                        ],
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
                            ];
                            break;
                           
                            case 'CITA MEDICA':
                                
                                $formatoAttributes = [
                                   

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
                                ];
                                if(Yii::$app->user->can('aprobar-solicitudes-medicas')){
                                    echo Html::beginForm(['aprobar-solicitud-medica', 'id' => $model->id], 'post', ['class' => 'form-inline']);
                                    echo '<div class="form-group mr-2 mb-2">';
                                    echo Html::submitButton(Html::tag('i', ' Aprobar', ['class' => 'fas fa-check']), ['name' => 'aprobacion', 'value' => 'APROBADO', 'class' => 'btn btn-success', 'title' => 'Aprobar']);
                                    echo '</div>';
                                    echo '<div class="form-group mr-2 mb-2">';
                                    echo Html::submitButton(Html::tag('i', ' Rechazar', ['class' => 'fas fa-times']), ['name' => 'aprobacion', 'value' => 'RECHAZADO', 'class' => 'btn btn-danger', 'title' => 'Rechazar']);
                                    echo '</div>';
                                    echo '<div class="form-group mr-2">';
                                    echo Html::label('Comentario', null, ['class' => 'control-label']);
                                    echo Html::textInput('comentario', $model->comentario, ['class' => 'form-control']);
                                    echo '</div>';
                                    echo Html::endForm();
                                }
                                break;
                    

                            case 'CAMBIO DE PERIODO VACACIONAL':
                                $formatoAttributes = [
                                    [
                                        'label' => 'Motivo',
                                        'attribute' => 'motivo',
                                        'format' => 'html',
                                        'value' => function ($model) {
                                            return \yii\helpers\Html::decode($model->motivo);
                                        },
                                        'filter' => false,
                                        'options' => ['style' => 'width: 65%;'],
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
                                ];
                                break;
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
