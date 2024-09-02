<?php

namespace app\controllers;
use app\models\Usuario;
use app\models\CambioDiaLaboral;
use app\models\CambioHorarioTrabajo;
use Yii;
use app\models\Solicitud;
use app\models\SolicitudSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use app\models\ComisionEspecial;
use app\models\Notificacion;
use PhpOffice\PhpSpreadsheet\IOFactory;
use app\models\CambioPeriodoVacacional;
use app\models\CitaMedica;
use app\models\ComisionEspecialSearch;
use app\models\PeriodoVacacionalHistorial;
use app\models\PermisoEconomico;
use app\models\ActividadReporteTiempoExtra;
use app\models\ActividadReporteTiempoExtraGeneral;
use app\models\ContratoParaPersonalEventual;
use app\models\PermisoFueraTrabajo;
use app\models\PermisoSinSueldo;
use app\models\ReporteTiempoExtra;
use app\models\ReporteTiempoExtraGeneral;

/**
 * SolicitudController implements the CRUD actions for Solicitud model.
 */
class SolicitudController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all Solicitud models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->pagination->pageSize=80;
        $dataProvider->sort = ['defaultOrder' => ['id' => 'DESC']];
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionCambiarEstadoActividad($id, $estado)
    {
        // Encuentra el modelo de la actividad por su ID
        $actividad = ActividadReporteTiempoExtra::findOne($id);
    
        if ($actividad !== null) {
            $actividad->status = $estado;
            if ($actividad->save(false)) {
                Yii::$app->session->setFlash('success', 'El estado de la actividad se ha actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo actualizar el estado de la actividad.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'La actividad no se encontró.');
        }
    
        return $this->redirect(Yii::$app->request->referrer);
    }
    

    
    public function actionCambiarEstadoActividadGeneral($id, $estado)
    {
        // Encuentra el modelo de la actividad por su ID
        $actividad = ActividadReporteTiempoExtraGeneral::findOne($id);
    
        if ($actividad !== null) {
            $actividad->status = $estado;
            if ($actividad->save(false)) {
                Yii::$app->session->setFlash('success', 'El estado de la actividad se ha actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo actualizar el estado de la actividad.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'La actividad no se encontró.');
        }
    
        return $this->redirect(Yii::$app->request->referrer);
    }
    

    /**
     * Displays a single Solicitud model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

     //MOSTRAR LA SOLICITUD CON EL REGISTRO DEL FORMATO ASOCIADO
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        if (Yii::$app->user->can('medico') || Yii::$app->user->can('gestor-rh')) {
            if ($model->status === 'Nueva') {
                $model->status = 'Visto';
                $model->save(false); // Guarda el cambio sin validar el modelo
            }
        }
    
        // Obtener el formato asociado
        $formato = $this->getFormatoAsociado($model);
        $empleadoId = $model->empleado_id;
    
        // Encontrar el ReporteTiempoExtra asociado usando el solicitud_id del modelo
        $reporteTiempoExtra = ReporteTiempoExtra::find()->where(['solicitud_id' => $model->id])->one();
    
        // Si se encuentra el ReporteTiempoExtra, obtener las actividades asociadas
        $actividades = [];
        if ($reporteTiempoExtra) {
            $actividades = ActividadReporteTiempoExtra::find()->where(['reporte_tiempo_extra_id' => $reporteTiempoExtra->id])->all();
        }


         // Encontrar el ReporteTiempoExtra asociado usando el solicitud_id del modelo
         $reporteTiempoExtraGeneral = ReporteTiempoExtraGeneral::find()->where(['solicitud_id' => $model->id])->one();
    
         // Si se encuentra el ReporteTiempoExtra, obtener las actividades asociadas
         $actividadesGeneral = [];
         if ($reporteTiempoExtraGeneral) {
             $actividadesGeneral = ActividadReporteTiempoExtraGeneral::find()->where(['reporte_tiempo_extra_general_id' => $reporteTiempoExtraGeneral->id])->all();
         }
    
        return $this->render('view', [
            'model' => $model,
            'formato' => $formato,
            'empleadoId' => $empleadoId,
            'actividades' => $actividades, // Pasar actividades a la vista
          'actividadesGeneral' =>  $actividadesGeneral,
        ]);
    }
    

    
    //OBTENER EL FORMATO ASOCIADO A LA SOLICITUD
    protected function getFormatoAsociado($solicitud)
    {
        //EN BASE A LOS DIFERENTES MODELOS DE FORMATOS QUE EXISTEN
        //SE IDENTIFICA LA SOLICITUD ASOCIADA A LOS REGISTROS 
        //Y SE SABE DE QUE TIPO DE SOLICITUD ES LA QUE SE ESTA SOLICITANDO
        switch ($solicitud->nombre_formato) {
            case 'PERMISO FUERA DEL TRABAJO':
                return PermisoFueraTrabajo::findOne(['solicitud_id' => $solicitud->id]);
                case 'CITA MEDICA':
                    return CitaMedica::findOne(['solicitud_id' => $solicitud->id]);
            case 'COMISION ESPECIAL':
                return ComisionEspecial::findOne(['solicitud_id' => $solicitud->id]);
            case 'PERMISO ECONOMICO':
                return PermisoEconomico::findOne(['solicitud_id' => $solicitud->id]);
            case 'CAMBIO DE DÍA LABORAL':
                return CambioDiaLaboral::findOne(['solicitud_id' => $solicitud->id]);
            case 'CAMBIO DE HORARIO DE TRABAJO':
                return CambioHorarioTrabajo::findOne(['solicitud_id' => $solicitud->id]);
           
            case 'PERMISO SIN GOCE DE SUELDO':
                return PermisoSinSueldo::findOne(['solicitud_id' => $solicitud->id]);
            case 'CAMBIO DE PERIODO VACACIONAL':
                return CambioPeriodoVacacional::findOne(['solicitud_id' => $solicitud->id]);
                case 'REPORTE DE TIEMPO EXTRA':
                    return ReporteTiempoExtra::findOne(['solicitud_id' => $solicitud->id]);
                    case 'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL':
                        return ContratoParaPersonalEventual::findOne(['solicitud_id' => $solicitud->id]);
                        case 'REPORTE DE TIEMPO EXTRA GENERAL':
                            return ReporteTiempoExtraGeneral::findOne(['solicitud_id' => $solicitud->id]);
          
                        
          
                        default:
                return null;
        }
    }
    
    
    /**
     * Creates a new Solicitud model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Solicitud();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    public function actionAprobarSolicitud($id)
    {
        $model = $this->findModel($id);
    
        if (Yii::$app->request->isPost) {
            //se recibien los valores del active form
            $aprobacion = Yii::$app->request->post('aprobacion');
            $comentario = Yii::$app->request->post('comentario');
    
            $model->aprobacion = $aprobacion;
            $model->comentario = $comentario;
    //se identifica el usuario que tiene la sesion iniciada
            $usuarioId = Yii::$app->user->identity->id;
            $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    
            if ($empleado) {
                //se añade la fecha en que se aprobo y el nombre del aprobante
                $model->fecha_aprobacion = date('Y-m-d H:i:s');
                $model->nombre_aprobante = $empleado->nombre . ' ' . $empleado->apellido;
    
                if ($model->save()) {
                    $notificacion = new Notificacion();
                    $notificacion->usuario_id = $model->empleado->usuario_id;
                  //  $notificacion->mensaje = 'Su solicitud ha sido ' . ($status == 'Aprobado' ? 'aprobada' : 'rechazada') . '.';
                    $notificacion->created_at = date('Y-m-d H:i:s');
                    $notificacion->leido = 0;
    
                    if ($notificacion->save()) {
                        Yii::$app->session->setFlash('success', 'Solicitud modificada correctamente.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la notificación.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al modificar la solicitud.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            }
    
            return $this->redirect(['view', 'id' => $model->id]);
        }
    
        return $this->redirect(['index']);
    }

    public function actionAprobarCambioPeriodoVacacional($id)
    {
        $model = $this->findModel($id);
        $cambioPeriodo = CambioPeriodoVacacional::findOne(['solicitud_id' => $model->id]);
    
        if (Yii::$app->request->isPost) {
            $status = Yii::$app->request->post('status');
            $comentario = Yii::$app->request->post('comentario');
    
            $model->status = $status;
            $model->comentario = $comentario;
    
            $usuarioId = Yii::$app->user->identity->id;
            $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    
            if ($empleado) {
                $model->fecha_aprobacion = date('Y-m-d H:i:s');
                $model->nombre_aprobante = $empleado->nombre . ' ' . $empleado->apellido;
    
                if ($model->save()) {
                    if ($status == 'Aprobado' && $cambioPeriodo) {
                        $vacaciones = $model->empleado->informacionLaboral->vacaciones;
                        if ($cambioPeriodo->numero_periodo == '1ero') {
                            $periodoVacacional = $vacaciones->periodoVacacional;
                            $periodo = 'primer periodo';
                        } else {
                            $periodoVacacional = $vacaciones->segundoPeriodoVacacional;
                            $periodo = 'segundo periodo';
                        }
    
                        if ($periodoVacacional) {
                            $historial = new PeriodoVacacionalHistorial();
                            $historial->empleado_id = $model->empleado_id;
                            $historial->periodo = $periodo;
                            $historial->fecha_inicio = $periodoVacacional->fecha_inicio;
                            $historial->fecha_final = $periodoVacacional->fecha_final;
                            $historial->año = $periodoVacacional->año;
                            $historial->dias_disponibles = $periodoVacacional->dias_disponibles;
                            $historial->original = $periodoVacacional->original;
                            $historial->save();
    
                            $fechaInicio = new \DateTime($cambioPeriodo->fecha_inicio_periodo);
                            $fechaFin = new \DateTime($cambioPeriodo->fecha_fin_periodo);
                            $diasSeleccionados = $fechaInicio->diff($fechaFin)->days + 1;
    
                            $diasDisponibles = $periodoVacacional->dias_vacaciones_periodo - $diasSeleccionados;
    
                            $periodoVacacional->dias_disponibles = $diasDisponibles;
    
                            $periodoVacacional->fecha_inicio = $cambioPeriodo->fecha_inicio_periodo;
                            $periodoVacacional->fecha_final = $cambioPeriodo->fecha_fin_periodo;
                            $periodoVacacional->año = $cambioPeriodo->año;
                            $periodoVacacional->original = 'No';
    
                            if ($periodoVacacional->save()) {
                                Yii::$app->session->setFlash('success', 'El periodo vacacional ha sido actualizado correctamente.');
                            } else {
                                Yii::$app->session->setFlash('error', 'Hubo un error al actualizar el periodo vacacional.');
                            }
                        }
                    }
    
                    $notificacion = new Notificacion();
                    $notificacion->usuario_id = $model->empleado->usuario_id;
                    $notificacion->mensaje = 'Su solicitud de cambio de periodo vacacional ha sido ' . ($status == 'Aprobado' ? 'aprobada' : 'rechazada') . '.';
                    $notificacion->created_at = date('Y-m-d H:i:s');
                    $notificacion->leido = 0;
    
                    if ($notificacion->save()) {
                        Yii::$app->session->setFlash('success', 'Solicitud modificada correctamente.');
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la notificación.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al modificar la solicitud.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            }
    
            return $this->redirect(['view', 'id' => $model->id]);
        }
    
        return $this->redirect(['index']);
    }





    public function actionAprobarSolicitudMedica($id)
{
    $model = $this->findModel($id);

    if (Yii::$app->request->isPost) {
        $aprobacion = Yii::$app->request->post('aprobacion');
        $comentario = Yii::$app->request->post('comentario');

        $model->aprobacion = $aprobacion;
        $model->comentario = $comentario;

        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

        if ($empleado) {
            $model->fecha_aprobacion = date('Y-m-d H:i:s');
            $model->nombre_aprobante = $empleado->nombre . ' ' . $empleado->apellido;

            if ($model->save()) {
                // Verificar si la solicitud fue aprobada
                if ($aprobacion === 'APROBADO') {
                    // Obtener el email del médico
                    $medicoEmail = $this->getMedicoEmail();
                    
                    // Configurar el mensaje de éxito con el email del médico y el enlace
                    $successMessage = 'La cita médica y la solicitud han sido creadas exitosamente.';
                    $enlaceEmpleado = Yii::$app->urlManager->createUrl(['empleado/view', 'id' => $model->empleado->id]);
                    $successMessage .= " Puedes ver el empleado en el siguiente enlace: <a href='$enlaceEmpleado'>$enlaceEmpleado</a>";

                    if ($medicoEmail) {
                        $successMessage .= " El correo del médico es: $medicoEmail";
                    } else {
                        $successMessage .= " No se encontró el correo del médico.";
                    }

                    // Enviar correo al médico
                    if ($medicoEmail) {
                        Yii::$app->mailer->compose()
                        ->setTo($medicoEmail)
                        ->setFrom(Yii::$app->params['adminEmail'])
                        ->setSubject('Nueva cita médica creada')
                        ->setTextBody("Se ha creado una nueva cita médica para el empleado: {$model->empleado->nombre} {$model->empleado->apellido}. Puedes ver el empleado en el siguiente enlace: http://localhost:82$enlaceEmpleado")
                        ->send();
                    
                    }

                    Yii::$app->session->setFlash('success', $successMessage);
                } else {
                    Yii::$app->session->setFlash('success', 'La solicitud ha sido rechazada.');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al modificar la solicitud.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
        }

        return $this->redirect(['view', 'id' => $model->id]);
    }

    return $this->redirect(['index']);
}


    private function getMedicoEmail()
    {
        $usuarioMedico = Usuario::find()->where(['rol' => 3])->one();
    
        if ($usuarioMedico) {
            $medicoEmpleado = Empleado::find()->where(['usuario_id' => $usuarioMedico->id])->one();
            
            if ($medicoEmpleado) {
                return $medicoEmpleado->email;
            } else {
                Yii::debug("No se encontró un empleado asociado al usuario médico con ID: " . $usuarioMedico->id);
            }
        } else {
            Yii::debug("No se encontró un usuario con rol médico (rol = 3).");
        }
    
        return null;
    }
    
     /**
     * Updates an existing Solicitud model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Solicitud model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Solicitud model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Solicitud the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Solicitud::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    

   
}
