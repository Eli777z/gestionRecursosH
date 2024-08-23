<?php
//IMPORTACIONES
use app\models\Solicitud;
use yii\bootstrap5\Alert;
use yii\helpers\Html;
use kartik\file\FileInput;
use yii\web\View;
use kartik\form\ActiveForm;
use kartik\tabs\TabsX;

/* @var $this yii\web\View */
/* @var $model app\models\Empleado */

//$activeTab = Yii::$app->request->get('tab', 'info_p');
//CARGAR ESTILOS
$this->registerCssFile('@web/css/site.css', ['position' => View::POS_HEAD]);
$this->registerCssFile('@web/css/grid-view.css', ['position' => View::POS_HEAD]);

$this->registerJsFile('@web/js/municipios.js', ['depends' => [\yii\web\JqueryAsset::class]]);
$this->registerJsFile('@web/js/select_estados.js', ['depends' => [\yii\web\JqueryAsset::class]]);
if (Yii::$app->user->can('medico') || Yii::$app->user->can('gestor-rh')) :


    $this->title = 'Empleado: ' . $model->nombre . ' ' . $model->apellido;
    $this->params['breadcrumbs'][] = ['label' => 'Empleados', 'url' => ['index']];
endif;

\yii\web\YiiAsset::register($this);
$currentDate = date('Y-m-d');
$antecedentesExistentes = [];
$observacionGeneral = '';
$descripcionAntecedentes = '';
$modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
$modelExploracionFisica = new \app\models\ExploracionFisica();
$editable = Yii::$app->user->can('editar-expediente-medico');

//CARGAR ANTECEDENTES
if ($antecedentes) {
    foreach ($antecedentes as $antecedente) {
        $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
        if (empty($observacionGeneral)) {
            $observacionGeneral = $antecedente->observacion;
        }
    }
}

// Si ya existe un antecedente patológico se obtiene el registro
$modelAntecedentePatologico = \app\models\AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedentePatologico) {
    $modelAntecedentePatologico = new \app\models\AntecedentePatologico();
    $modelAntecedentePatologico->expediente_medico_id = $expedienteMedico->id;
}

// Obtener antecedentes no patológicos
$modelAntecedenteNoPatologico = \app\models\AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteNoPatologico) {
    $modelAntecedenteNoPatologico = new \app\models\AntecedenteNoPatologico();
    $modelAntecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
}


$modelExploracionFisica = \app\models\ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelExploracionFisica) {
    $modelExploracionFisica = new \app\models\ExploracionFisica();
    $modelExploracionFisica->expediente_medico_id = $expedienteMedico->id;
}

$modelInterrogatorioMedico = \app\models\InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelInterrogatorioMedico) {
    $modelInterrogatorioMedico = new \app\models\InterrogatorioMedico();
    $modelInterrogatorioMedico->expediente_medico_id = $expedienteMedico->id;
}




$modelAntecedenteObstrectico = \app\models\AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAntecedenteObstrectico) {
    $modelAntecedenteObstrectico = new \app\models\AntecedenteObstrectico();
    $modelAntecedenteObstrectico->expediente_medico_id = $expedienteMedico->id;
}

$modelAlergia = \app\models\Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
if (!$modelAlergia) {
    $modelAlergia = new \app\models\Alergia();
    $modelAlergia->expediente_medico_id = $expedienteMedico->id;
}

?>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    function showAlert(title, text) {
        Swal.fire({
            icon: 'warning',
            title: title,
            text: text,
        });
    }
</script>


<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card bg-light">
                <div class="card-header gradient-info text-white">
                    <div class="d-flex align-items-center position-relative ml-4">
                        <div class="bg-light p-1 rounded-circle custom-shadow mt-4" style="width: 140px; height: 140px; position: relative;">
                            <?= Html::img(['empleado/foto-empleado', 'id' => $model->id], [
                                'class' => 'rounded-circle',
                                'style' => 'width: 130px; height: 130px;'
                            ]) ?>
                            <?php
                            //PERMISO PARA PODER CAMBIAR LA FOTO DEL EMPLEADO
                            if (Yii::$app->user->can('modificar-informacion-empleados')) : ?>
                                <?= Html::button('<i class="fas fa-edit"></i>', [
                                    'class' => 'btn btn-dark position-absolute',
                                    'style' => 'top: 5px; right: 5px; padding: 5px 10px;',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#changePhotoModal'
                                ]) ?>
                            <?php endif; ?>
                        </div>
                        <div class="ml-4">
    <div class="alert alert-light mb-0" role="alert">
        <h3 class="mb-0"><?= $model->nombre ?> <?= $model->apellido ?></h3>
        <h5 class="mb-0">Número de empleado: <?= $model->numero_empleado ?></h5>
        
    </div>
</div>


                    </div>
                    <?php 
                    //PERMISO PARA MOSTRAR LOS BOTONES DE CREAR CONSULTA Y CITA MEDICA
                    if (Yii::$app->user->can('crear-consulta-medica')) : ?>

                        <?php if ($model->expedienteMedico) : ?>
                            <?= Html::a('Nueva consulta <i class="fa fa-user-md" ></i>', ['consulta-medica/create', 'expediente_medico_id' => $model->expedienteMedico->id], [
                                'class' => 'btn btn-dark  float-right fa-lg'
                            ]) ?>


                        <?php endif; ?>



                        <?php if ($model->id) : ?>

                            <?= Html::a('Nueva cita medica <i class="fa fa-plus-square" ></i>', ['cita-medica/create', 'empleado_id' => $model->id], [
                                'class' => 'btn btn-light mr-3 float-right fa-lg'
                            ]) ?>



                        <?php endif; ?>


                        <?php if ($model->id) : ?>
        <?= Html::a('Informe Médico General <i class="fa fa-file-medical"></i>', ['informe-medico-general', 'id' => $model->id], [
            'class' => 'btn btn-success mr-3 float-right fa-lg',
            'title' => 'Ver informe médico general del empleado',
            'target' => '_blank',
        ]) ?>
    <?php endif; ?>



                    <?php endif; ?>
                    <?php 
                    //PERMISO PARA MOSTRAR LOS BOTONES DE CREAR CONSULTA Y CITA MEDICA
                    if (Yii::$app->user->can('aprobar-solicitudes')) { ?>
                    <?= Html::a('Ver Horas Extras', ['//reporte-tiempo-extra/reporte', 'empleado_id' => $model->id], [   'class' => 'btn btn-success mr-3 float-right fa-lg']) ?>
                    <?php }?>

                      
                </div>

            
                <div class="modal fade" id="changePhotoModal" tabindex="-1" role="dialog" aria-labelledby="changePhotoModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-md" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="changePhotoModalLabel">Cambiar Foto de Perfil</h5>

                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <?php 
                                //FORMULARIO PARA SUBIR Y HACER EL CAMBIO DE FOTO DEL PERFIL
                                $form = ActiveForm::begin([
                                    'action' => ['empleado/change-photo', 'id' => $model->id],
                                    'options' => ['enctype' => 'multipart/form-data']
                                ]); ?>

                                <?= $form->field($model, 'foto')->widget(FileInput::classname(), [
                                    'options' => ['accept' => 'image/*'],
                                    'pluginOptions' => [
                                        'showPreview' => true,
                                        'showCaption' => true,
                                        'showRemove' => true,
                                        'showUpload' => false,
                                        'showCancel' => false,
                                        'browseClass' => 'btn btn-primary btn-block',
                                        'browseLabel' => 'Seleccionar Foto'
                                    ]
                                ]); ?>

                                <div class="form-group">
                                    <?= Html::submitButton('Subir', ['class' => 'btn btn-primary']) ?>
                                </div>

                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
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
                                <?php 
                                //VERIFICA EL ESTATUS DE PRIMERA_REVISION
        if ($model->expedienteMedico !== null) {
            if ($model->expedienteMedico->primera_revision === 0) {
                echo '
                
                
                 <div class="alert alert-warning mb-0" role="alert">
              <h6>  <i class="fas fa-exclamation-triangle" style="color: #dd0000 "></i> PENDIENTE DE PRIMERA REVISION MEDICA</h6> </div>';
            }
        }
        ?>
                            </div>
                            <?php $this->beginBlock('datos'); ?>
                            <?php $this->beginBlock('info_p'); ?>
                            <?php
                            // Otros contenidos de la vista principal

                            // Incluir el contenido del nuevo archivo de vista
                            echo $this->render('informacion_personal', [
                                'model' => $model,

                            ]);
                            ?>
                            <?php $this->endBlock(); ?>



                            <?php $this->beginBlock('info_c'); ?>

                            <?php $this->endBlock(); ?>
                            <!-- INFOLABORAL-->
                            <?php $this->beginBlock('info_l'); ?>
                            <?php
                            // Otros contenidos de la vista principal

                            // Incluir el contenido del nuevo archivo de vista
                            echo $this->render('informacion_laboral', [
                                'model' => $model,

                            ]);
                            ?>


                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('info_vacacional'); ?>
                            <?php
                            // Otros contenidos de la vista principal

                            // Incluir el contenido del nuevo archivo de vista
                            echo $this->render('informacion_vacacional', [
                                'model' => $model,
                                'currentDate' => $currentDate,
                                'historial' => $historial,
                            ]);
                            ?>

                            <?php $this->endBlock(); ?>

                            <?php $this->beginBlock('info_solicitudes'); ?>
                            <?php
                            // Otros contenidos de la vista principal

                            // Incluir el contenido del nuevo archivo de vista
                            echo $this->render('informacion_solicitudes', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'searchModelConsultas' => $searchModelConsultas,
                                'dataProviderConsultas' => $dataProviderConsultas,

                            ]);
                            ?>

                            <?php $this->endBlock(); ?>

                            <?php
                            // Función para contar las nuevas solicitudes
                         // Función para contar las nuevas solicitudes de un empleado específico
function countNewSolicitudes($empleado_id)
{
    return Solicitud::find()->where(['status' => 'Nueva', 'empleado_id' => $empleado_id])->count();
}

function countNewSolicitudesMedicas($empleado_id)
{
    return Solicitud::find()->where(['status' => 'Nueva', 'nombre_formato' => 'CITA MEDICA', 'empleado_id' => $empleado_id])->count();
}


                            // Contar las nuevas solicitudes
                        // Obtener el empleado_id del modelo
$empleado_id = $model->id;

// Contar las nuevas solicitudes para el empleado específico
$newSolicitudesCount = countNewSolicitudes($empleado_id);
$newSolicitudesMedicasCount = countNewSolicitudesMedicas($empleado_id);

                           // Configurar las pestañas
$tabs = [
    [
        'label' => 'Información personal',
        'content' => $this->blocks['info_p'],
        'options' => [
            'id' => 'informacion_personal',
        ],
    ],
    [
        'label' => 'Información laboral',
        'content' => $this->blocks['info_l'],
        'options' => [
            'id' => 'informacion_laboral',
        ],
    ],
];

if (Yii::$app->user->can('ver-informacion-vacacional')) {
    $tabs[] = [
        'label' => 'Vacaciones',
        'content' => $this->blocks['info_vacacional'],
        'options' => [
            'id' => 'informacion_vacaciones',
        ],
    ];
}

if ( Yii::$app->user->can('gestor-rh')) {
    $tabs[] = [
        'label' => 'Solicitudes' . ($newSolicitudesCount > 0 ? " <span class='badge badge-danger'>$newSolicitudesCount</span>" : ''),
        'content' => $this->blocks['info_solicitudes'],
        'options' => [
            'id' => 'informacion_solicitudes',
        ],
    ];
} 


else if (Yii::$app->user->can('medico') ) {
    $tabs[] = [
        'label' => 'Solicitudes' . ($newSolicitudesMedicasCount > 0 ? " <span class='badge badge-danger'>$newSolicitudesMedicasCount</span>" : ''),
        'content' => $this->blocks['info_solicitudes'],
        'options' => [
            'id' => 'informacion_solicitudes',
        ],
    ];
} 



else {
    $tabs[] = [
        'label' => 'Solicitudes',
        'content' => $this->blocks['info_solicitudes'],
        'options' => [
            'id' => 'informacion_solicitudes',
        ],
    ];
}
// CARGAR TABS CON LOS BLOQUES QUE RENDERIZAN LAS VISTAS DE INFORMACION
echo TabsX::widget([
    'enableStickyTabs' => true,
    'options' => ['class' => 'nav-tabs-custom'],
    'items' => $tabs,
    'position' => TabsX::POS_ABOVE,
    'align' => TabsX::ALIGN_CENTER,
    'encodeLabels' => false,
]);
  ?>


                            <?php $this->beginBlock('info_consultas'); ?>
                            <?php
                            //RENDERIZAR HISTORIAL DE CONSULTAS MEDICAS
                            echo $this->render('informacion_consultas', [
                                'model' => $model,

                                'searchModelConsultas' => $searchModelConsultas,
                                'dataProviderConsultas' => $dataProviderConsultas,

                            ]);
                            ?>
                            <?php $this->endBlock(); ?>




                            <?php $this->beginBlock('expediente'); ?>
                            <?php
                           //RENDERIZAR DOCUMENTOS DEL EMPLEADO
                            echo $this->render('documentos_empleado', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'documentos' => $documentos,
                                'documentoModel' => $documentoModel,
                               
                            ]);
                            ?>

                            <?php $this->endBlock(); ?>
                            <?php 
                            //BLOQUE DONDE SE RENDERIZA LA INFORMACION MEDICA DEL
                            //EMPLEADO Y SUS RESPECTIVOS FORMULARIOS
                                                       $this->beginBlock('expediente_medico'); ?>

                            <?php $this->beginBlock('documento-medico'); ?>
                            <?php
                            // RENDERIZA DOCUMENTO MEDICOS

                       
                            echo $this->render('documento_medico_empleado', [
                                'model' => $model,
                                'searchModel' => $searchModel,
                                'dataProvider' => $dataProvider,
                                'documentoMedicoModel' => $documentoMedicoModel,
                              
                            ]);
                            ?>
                            <?php $this->endBlock(); ?>




                            <?php $this->beginBlock('antecedentes'); ?>

                            <!-- Bloque de antecedentes hereditarios -->
                            <?php $this->beginBlock('hereditarios'); ?>
                            <?php

                            echo $this->render('antecedente_hereditario', [
                                'model' => $model,
                                'catAntecedentes' => $catAntecedentes,
                                'antecedentes' => $antecedentes,



                            ]);
                            ?>
                            <?php $this->endBlock(); ?>


                            <!-- Bloque de antecedentes patológicos -->
                            <?php $this->beginBlock('patologicos'); ?>
                            <?php

                            echo $this->render('antecedente_patologico', [
                                'model' => $model,
         
                                'antecedentePatologico' => $antecedentePatologico,




                            ]);
                            ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('no_patologicos'); ?>
                            <?php
                        
                            echo $this->render('antecedente_no_patologico', [
                                'model' => $model,

                                'antecedenteNoPatologico' => $antecedenteNoPatologico,

                            ]);
                            ?>
                            <?php $this->endBlock(); ?>

                            <?php $this->beginBlock('perinatales'); ?>
                            <?php

                            echo $this->render('antecedente_perinatal', [
                                'model' => $model,

                                'AntecedentePerinatal' => $AntecedentePerinatal,
                                'expedienteMedico' => $expedienteMedico,





                            ]);
                            ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('ginecologicos'); ?>
                            <?php

                            echo $this->render('antecedente_ginecologico', [
                                'model' => $model,

                                'AntecedenteGinecologico' => $AntecedenteGinecologico,

                                'expedienteMedico' => $expedienteMedico,





                            ]);
                            ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('obstrecticos'); ?>
                            <?php

                            echo $this->render('antecedente_obstrectico', [
                                'model' => $model,

                                'AntecedenteObstrectico' => $AntecedenteObstrectico,






                            ]);
                            ?>
                            <?php $this->endBlock(); ?>

                            <!-- TABS EXPEDIENTE MEDICO  -->

                            <?php if($model->sexo === "Femenino" ){ ?>
    <?php echo TabsX::widget([
        'enableStickyTabs' => true,
        'options' => ['class' => 'nav-tabs-custom'],
        'items' => [
            [
                'label' => 'Hereditarios',
                'content' => $this->blocks['hereditarios'],
                'options' => [
                    'id' => 'hereditarios',
                ],
            ],
            [
                'label' => 'Patologicos',
                'content' => $this->blocks['patologicos'],
                'options' => [
                    'id' => 'patologicos',
                ],
            ],
            [
                'label' => 'No Patologicos',
                'content' => $this->blocks['no_patologicos'],
                'options' => [
                    'id' => 'nopatologicos',
                ],
            ],
            [
                'label' => 'Perinatales',
                'content' => $this->blocks['perinatales'],
                'options' => [
                    'id' => 'perinatales',
                ],
            ],
            [
                'label' => 'Ginecologicos',
                'content' => $this->blocks['ginecologicos'],
                'options' => [
                    'id' => 'ginecologicos',
                ],
            ],
            [
                'label' => 'Obstrecticos',
                'content' => $this->blocks['obstrecticos'],
                'options' => [
                    'id' => 'obstrecticos',
                ],
            ],
        ],
        'position' => TabsX::POS_ABOVE,
        'align' => TabsX::ALIGN_CENTER,
        'encodeLabels' => false
    ]); ?>
<?php } else { ?>
    <?php echo TabsX::widget([
        'enableStickyTabs' => true,
        'options' => ['class' => 'nav-tabs-custom'],
        'items' => [
            [
                'label' => 'Hereditarios',
                'content' => $this->blocks['hereditarios'],
                'options' => [
                    'id' => 'hereditarios',
                ],
            ],
            [
                'label' => 'Patologicos',
                'content' => $this->blocks['patologicos'],
                'options' => [
                    'id' => 'patologicos',
                ],
            ],
            [
                'label' => 'No Patologicos',
                'content' => $this->blocks['no_patologicos'],
                'options' => [
                    'id' => 'nopatologicos',
                ],
            ],
            [
                'label' => 'Perinatales',
                'content' => $this->blocks['perinatales'],
                'options' => [
                    'id' => 'perinatales',
                ],
            ],
        ],
        'position' => TabsX::POS_ABOVE,
        'align' => TabsX::ALIGN_CENTER,
        'encodeLabels' => false
    ]); ?>
<?php } ?>


                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('alergias'); ?>
                            <?php

                            echo $this->render('alergias', [
                                'model' => $model,
                               
                                'Alergia' => $Alergia,

                            ]);
                            ?>
                            <?php $this->endBlock(); ?>



                            <?php $this->beginBlock('exploracion_fisica'); ?>
                            <?php

                            echo $this->render('exploracion_fisica', [
                                'model' => $model,
                               
                                'ExploracionFisica' => $ExploracionFisica,


                            ]);
                            ?>
                            <?php $this->endBlock(); ?>


                            <?php $this->beginBlock('interrogatorio_medico'); ?>
                            <?php

                            echo $this->render('interrogatorio_medico', [
                                'model' => $model,
                               
                                'InterrogatorioMedico' => $InterrogatorioMedico,







                            ]);
                            ?>
                            <?php $this->endBlock(); ?>



                            <!-- TABS PRINCIPAL EXPEDIENTE MEDICO-->
                            <?php

                            // Construir las pestañas
                            $items = [
                                [
                                    'label' => 'Antecedentes',
                                    'content' => $this->blocks['antecedentes'],
                                    'options' => ['id' => 'antecedentes'],
                                ],
                                [
                                    'label' => 'Exploración Física',
                                    'content' => $this->blocks['exploracion_fisica'],
                                    'options' => ['id' => 'exploracion_fisica'],
                                ],
                                [
                                    'label' => 'Interrogatorio Médico',
                                    'content' => $this->blocks['interrogatorio_medico'],
                                    'options' => ['id' => 'interrogatorio_medico'],
                                ],
                                [
                                    'label' => 'Alergias',
                                    'content' => $this->blocks['alergias'],
                                    'options' => ['id' => 'alergias'],
                                ],
                            ];

                            if (Yii::$app->user->can('ver-documentos-medicos')) {
                                $items[] = [
                                    'label' => 'Documentos Médicos',
                                    'content' => $this->blocks['documento-medico'],
                                    'options' => ['id' => 'documentos-medicos'],
                                ];
                            }

                            // Mostrar el widget de TabsX
                            echo TabsX::widget([
                                'enableStickyTabs' => true,
                                'options' => ['class' => 'nav-tabs-custom'],
                                'items' => $items,
                                'position' => TabsX::POS_ABOVE,
                                'align' => TabsX::ALIGN_LEFT,
                                'encodeLabels' => false,
                            ]);
                            ?>

                            <?php $this->endBlock(); ?>

                            <!-- antecedentes-->


                            <?php $this->endBlock(); ?>






                        </div>
                        <!--.col-md-12-->


                        <!-- TABS PRINCIPAL -->
                        <?php
                        $tabs = [
                            [
                                'label' => 'Información',
                                'content' => $this->blocks['datos'],
                                'active' => true,
                                'options' => [
                                    'id' => 'datos',
                                ],
                            ],

                        ];

                        if (Yii::$app->user->can('ver-expediente-medico')) {
                            $tabs[] = [
                                'label' => 'Expediente Medico',
                                'content' => $this->blocks['expediente_medico'],
                                'options' => [
                                    'id' => 'expediente_medico',
                                ],
                            ];
                        }
                        if (Yii::$app->user->can('ver-documentos')) {
                            $tabs[] = [
                                'label' => 'Documentos',
                                'content' => $this->blocks['expediente'],
                                'options' => [
                                    'id' => 'documentos',
                                ],
                            ];
                        }
                        if (Yii::$app->user->can('ver-consultas-medicas')) {
                            $tabs[] = [
                                'label' => 'Consultas medicas',
                                'content' => $this->blocks['info_consultas'],
                                'options' => [
                                    'id' => 'informacion_consultas',
                                ],

                            ];
                        }

                        echo TabsX::widget([
                            'enableStickyTabs' => true,
                            'options' => ['class' => 'nav-tabs-custom'],
                            'items' => $tabs,
                            'position' => TabsX::POS_ABOVE,
                            'align' => TabsX::ALIGN_LEFT,
                            'encodeLabels' => false,
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
    <script>
        //SCRIPT PARA SEGURARSE DE QUE SE RECARGUEN BIEN 
        //LAS PESTAÑAS EN BASE A SU ID
        $(document).ready(function() {
            function activateTabFromHash() {
                var hash = window.location.hash;

                if (hash) {
                    var tabId = hash.replace('#', '');

                    var tabLink = $('a[href="' + hash + '"]');

                    if (tabLink.length) {
                        tabLink.tab('show');

                       
                        tabLink.parents('.tab-pane').each(function() {
                            var parentTabId = $(this).attr('id');
                            $('a[href="#' + parentTabId + '"]').tab('show');
                        });
                    }
                }
            }

            activateTabFromHash();

            $(window).on('hashchange', function() {
                activateTabFromHash();
            });

            $('a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
                window.location.hash = $(e.target).attr('href');
            });
        });
    </script>
</div>
