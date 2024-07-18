<?php

namespace app\controllers;

use Yii;
use app\models\CambioHorarioTrabajo;
use app\models\CambioHorarioTrabajoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\MotivoFechaPermiso;
use app\models\Solicitud;
use app\models\JuntaGobierno;
use app\models\Notificacion;
use app\models\CatDireccion;
use app\models\CambioDiaLaboral;
use app\models\CambioDiaLaboralSearch;
use PhpOffice\PhpSpreadsheet\IOFactory;
use DateTime;
/**
 * CambioHorarioTrabajoController implements the CRUD actions for CambioHorarioTrabajo model.
 */
class CambioHorarioTrabajoController extends Controller
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
     * Lists all CambioHorarioTrabajo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;

        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

        if ($empleado !== null) {
            $searchModel = new CambioHorarioTrabajoSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

            $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);

            $this->layout = "main-trabajador";

            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']);
        }
    }

    /**
     * Displays a single CambioHorarioTrabajo model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $empleado = Empleado::findOne($model->empleado_id);
    
        return $this->render('view', [
            'model' => $model,
            'empleado' => $empleado, // Pasar empleado a la vista
        ]);
    }
    

    public function actionHistorial($empleado_id= null)
    {
        $empleado = Empleado::findOne($empleado_id);
    
        if ($empleado === null) {
            throw new NotFoundHttpException('El empleado seleccionado no existe.');
        }
    
        $searchModel = new CambioHorarioTrabajoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
    
        $this->layout = "main-trabajador";
    
        return $this->render('historial', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'empleado' => $empleado,
        ]);
    }

    /**
     * Creates a new CambioHorarioTrabajo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
    {
        $model = new CambioHorarioTrabajo();
        $this->layout = "main-trabajador";


        $motivoFechaPermisoModel = new MotivoFechaPermiso();
        $solicitudModel = new Solicitud();
       // $motivoFechaPermisoModel->fecha_permiso = date('Y-m-d');
        // $model->fecha_a_reponer = date('Y-m-d');
        $usuarioId = Yii::$app->user->identity->id;

        if ($empleado_id) {
            $empleado = Empleado::findOne($empleado_id);
        } else {
            $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
        }
    
        if ($empleado) {
            $model->empleado_id = $empleado->id;
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado.');
            return $this->redirect(['index']);
        }

        if ($model->load(Yii::$app->request->post()) && $motivoFechaPermisoModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($motivoFechaPermisoModel->save()) {
                    $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;
                    $model->horario_inicio = date('H:i:s', strtotime($model->horario_inicio));
                    $model->horario_fin = date('H:i:s', strtotime($model->horario_fin));


                    $solicitudModel->empleado_id = $empleado->id;
                    $solicitudModel->status = 'En Proceso';
                    $solicitudModel->comentario = '';
                    $solicitudModel->fecha_aprobacion = null;
                    $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                    $solicitudModel->nombre_formato = 'CAMBIO DE HORARIO DE TRABAJO';
                    if ($solicitudModel->save()) {
                        $model->solicitud_id = $solicitudModel->id;

                        if ($model->jefe_departamento_id) {
                            $jefeDepartamento = JuntaGobierno::findOne($model->jefe_departamento_id);
                            $model->nombre_jefe_departamento = $jefeDepartamento ? $jefeDepartamento->empleado->profesion . ' ' . $jefeDepartamento->empleado->nombre . ' ' . $jefeDepartamento->empleado->apellido : null;
                        }

                        if ($model->save()) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Su solicitud ha sido generada exitosamente.');

                            $notificacion = new Notificacion();
                            $notificacion->usuario_id = $model->empleado->usuario_id;
                            $notificacion->mensaje = 'Tienes una nueva solicitud pendiente de revisión.';
                            $notificacion->created_at = date('Y-m-d H:i:s');
                            $notificacion->leido = 0;
                            if ($notificacion->save()) {
                                return $this->redirect(['view', 'id' => $model->id]);
                            } else {
                                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la notificación.');
                            }
                        } else {
                            Yii::$app->session->setFlash('error', 'Hubo un error al guardar la solicitud.');
                        }
                    }
                }
                $transaction->rollBack();
            } catch (\Exception $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro: ' . $e->getMessage());
            } catch (\Throwable $e) {
                $transaction->rollBack();
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro: ' . $e->getMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
            'motivoFechaPermisoModel' => $motivoFechaPermisoModel,
            'solicitudModel' => $solicitudModel,
            'empleado' => $empleado, // Pasar empleado a la vista

        ]);
    }

    /**
     * Updates an existing CambioHorarioTrabajo model.
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
     * Deletes an existing CambioHorarioTrabajo model.
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
     * Finds the CambioHorarioTrabajo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CambioHorarioTrabajo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CambioHorarioTrabajo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }



    public function actionExport($id)
    {
        $model = CambioHorarioTrabajo::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        $templatePath = Yii::getAlias('@app/templates/cambio_horario_trabajo.xlsx');

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('F6', $model->empleado->numero_empleado);


        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('H7', $nombreCompleto);


        setlocale(LC_TIME, 'es_419.UTF-8');
        $fechaHOY = strftime('%A, %B %d, %Y');
        $sheet->setCellValue('N6', $fechaHOY);

        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('H8', $nombrePuesto);

        $nombreCargo = $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto;
        $sheet->setCellValue('H9', $nombreCargo);

        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('H10', $nombreDireccion);

        $nombreDepartamento = $model->empleado->informacionLaboral->catDepartamento->nombre_departamento;
        $sheet->setCellValue('H11', $nombreDepartamento);

        $nombreTipoContrato = $model->empleado->informacionLaboral->catTipoContrato->nombre_tipo;
        $sheet->setCellValue('H12', $nombreTipoContrato);



       
if ($model->turno === 'MATUTINO') {
    $sheet->setCellValue('N14', 'X');
    $sheet->setCellValue('Q14', '');
} elseif ($model->turno === 'VESPERTINO') {
    $sheet->setCellValue('N14', '');
    $sheet->setCellValue('Q14', 'X');
} 
$horarioInicio = DateTime::createFromFormat('H:i:s', $model->horario_inicio)->format('g:i A');
$horarioFin = DateTime::createFromFormat('H:i:s', $model->horario_fin)->format('g:i A');

$horarioCompleto = "DE $horarioInicio A $horarioFin";

$sheet->setCellValue('M17', $horarioCompleto);

setlocale(LC_TIME, 'es_419.UTF-8');


$fechaInicio = DateTime::createFromFormat('Y-m-d', $model->fecha_inicio);
$fechaTermino = DateTime::createFromFormat('Y-m-d', $model->fecha_termino);

$formatoFecha = '%A, %B %d, %Y'; 
$fechaInicioFormateada = strftime($formatoFecha, $fechaInicio->getTimestamp());
$fechaTerminoFormateada = strftime($formatoFecha, $fechaTermino->getTimestamp());

if ($fechaInicio == $fechaTermino) {
    $mensaje = "SOLAMENTE ESE DÍA";
} else {
    $mensaje = "Del $fechaInicioFormateada al $fechaTerminoFormateada";
}

$sheet->setCellValue('M18', $mensaje);


        $fecha_permiso = strftime('%A, %B %d, %Y', strtotime($model->motivoFechaPermiso->fecha_permiso));
        $sheet->setCellValue('M16', $fecha_permiso);





        $sheet->setCellValue('M19', $model->motivoFechaPermiso->motivo);






        $sheet->setCellValue('B25', $nombreCompleto);


        $sheet->setCellValue('B26', $nombrePuesto);

        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

        if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
            $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
            $sheet->setCellValue('I25', $nombreCompletoJefe);
        } else {
            $sheet->setCellValue('I25', null);
        }




        $juntaDirectorDireccion = JuntaGobierno::find()
            ->where(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
            ->andWhere(['or', ['nivel_jerarquico' => 'Director'], ['nivel_jerarquico' => 'Jefe de unidad']])
            ->one();

        if ($juntaDirectorDireccion) {
            $nombreDirector = mb_strtoupper($juntaDirectorDireccion->empleado->nombre, 'UTF-8');
            $apellidoDirector = mb_strtoupper($juntaDirectorDireccion->empleado->apellido, 'UTF-8');
            $profesionDirector = mb_strtoupper($juntaDirectorDireccion->empleado->profesion, 'UTF-8');
            $nombreCompletoDirector = $profesionDirector . ' ' . $apellidoDirector . ' ' . $nombreDirector;

            $sheet->setCellValue('P25', $nombreCompletoDirector);

            $nombreDireccion = $juntaDirectorDireccion->catDireccion->nombre_direccion;
            switch ($nombreDireccion) {
                case '1.- GENERAL':
                    if ($juntaDirectorDireccion->nivel_jerarquico == 'Jefe de unidad') {
                        $tituloDireccion = 'JEFE DE ' . $juntaDirectorDireccion->catDepartamento->nombre_departamento;
                    } else {
                        $tituloDireccion = 'DIRECTOR GENERAL';
                    }
                    break;
                case '2.- ADMINISTRACIÓN':
                    $tituloDireccion = 'DIRECTOR DE ADMINISTRACIÓN';
                    break;
                case '4.- OPERACIONES':
                    $tituloDireccion = 'DIRECTOR DE OPERACIONES';
                    break;
                case '3.- COMERCIAL':
                    $tituloDireccion = 'DIRECTOR COMERCIAL';
                    break;
                case '5.- PLANEACION':
                    $tituloDireccion = 'DIRECTOR DE PLANEACION';
                    break;
                default:
                    $tituloDireccion = ''; 
            }

            $sheet->setCellValue('P26', $tituloDireccion);
        } else {
            $sheet->setCellValue('P25', null);
            $sheet->setCellValue('P26', null);
        }

        $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/cambio_horario_trabajo.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);

        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }


    public function actionDownload($filename)
    {
        $filePath = Yii::getAlias("@app/runtime/archivos_temporales/$filename");
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new NotFoundHttpException('El archivo solicitado no existe.');
        }
    }



    public function actionExportSegundoCaso($id)
    {
        $model = CambioHorarioTrabajo::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        $templatePath = Yii::getAlias('@app/templates/cambio_horario_trabajo.xlsx');

        $spreadsheet = IOFactory::load($templatePath);

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('F6', $model->empleado->numero_empleado);


        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('H7', $nombreCompleto);


        setlocale(LC_TIME, 'es_419.UTF-8');
        $fechaHOY = strftime('%A, %B %d, %Y');
        $sheet->setCellValue('N6', $fechaHOY);

        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('H8', $nombrePuesto);

        $nombreCargo = $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto;
        $sheet->setCellValue('H9', $nombreCargo);

        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('H10', $nombreDireccion);

        $nombreDepartamento = $model->empleado->informacionLaboral->catDepartamento->nombre_departamento;
        $sheet->setCellValue('H11', $nombreDepartamento);

        $nombreTipoContrato = $model->empleado->informacionLaboral->catTipoContrato->nombre_tipo;
        $sheet->setCellValue('H12', $nombreTipoContrato);



        $fecha_permiso = strftime('%A, %B %d, %Y', strtotime($model->motivoFechaPermiso->fecha_permiso));
        $sheet->setCellValue('M16', $fecha_permiso);



        $sheet->setCellValue('M19', $model->motivoFechaPermiso->motivo);

        if ($model->turno === 'MATUTINO') {
            $sheet->setCellValue('N14', 'X');
            $sheet->setCellValue('Q14', '');
        } elseif ($model->turno === 'VESPERTINO') {
            $sheet->setCellValue('N14', '');
            $sheet->setCellValue('Q14', 'X');
        } 
        
        $horarioInicio = DateTime::createFromFormat('H:i:s', $model->horario_inicio)->format('g:i A');
        $horarioFin = DateTime::createFromFormat('H:i:s', $model->horario_fin)->format('g:i A');
        
        $horarioCompleto = "DE $horarioInicio A $horarioFin";
        
        $sheet->setCellValue('M17', $horarioCompleto);

        setlocale(LC_TIME, 'es_419.UTF-8');

      
        $fechaInicio = DateTime::createFromFormat('Y-m-d', $model->fecha_inicio);
        $fechaTermino = DateTime::createFromFormat('Y-m-d', $model->fecha_termino);
        
        $formatoFecha = '%A, %B %d, %Y';        $fechaInicioFormateada = strftime($formatoFecha, $fechaInicio->getTimestamp());
        $fechaTerminoFormateada = strftime($formatoFecha, $fechaTermino->getTimestamp());
        
 
        if ($fechaInicio == $fechaTermino) {
            $mensaje = "SOLAMENTE ESE DÍA";
        } else {
            $mensaje = "Del $fechaInicioFormateada al $fechaTerminoFormateada";
        }
        
        $sheet->setCellValue('M18', $mensaje);



        $sheet->setCellValue('B25', $nombreCompleto);


        $sheet->setCellValue('B26', $nombrePuesto);



    
//        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

       
  //      if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
    //        $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
      //      $sheet->setCellValue('I25', $nombreCompletoJefe);
       // } else {
         //   $sheet->setCellValue('I25', null);
       // }




       $juntaGobierno = JuntaGobierno::find()
->where(['nivel_jerarquico' => 'Director'])
->all();

$directorGeneral = null;

foreach ($juntaGobierno as $junta) {
$empleado = Empleado::findOne($junta->empleado_id);

if ($empleado && $empleado->informacionLaboral->catPuesto->nombre_puesto === 'DIRECTOR GENERAL') {
    $directorGeneral = $empleado;
    break;
}
}

if ($directorGeneral) {
$nombre = mb_strtoupper($directorGeneral->nombre, 'UTF-8');
$apellido = mb_strtoupper($directorGeneral->apellido, 'UTF-8');
$nombreCompleto = $apellido . ' ' . $nombre;
$sheet->setCellValue('P25', $nombreCompleto);
} else {

}

$sheet->setCellValue('P26', 'DIRECTOR GENERAL');

        $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/cambio_horario_trabajo.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);

       
        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }
}
