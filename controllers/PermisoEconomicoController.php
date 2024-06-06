<?php

namespace app\controllers;

use Yii;
use app\models\PermisoEconomico;
use app\models\PermisoEconomicoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use app\models\Notificacion;
use app\models\MotivoFechaPermiso;
use app\models\Solicitud;
use app\models\CambioDiaLaboral;
use app\models\CambioDiaLaboralSearch;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * PermisoEconomicoController implements the CRUD actions for PermisoEconomico model.
 */
class PermisoEconomicoController extends Controller
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
     * Lists all PermisoEconomico models.
     * @return mixed
     */
    public function actionIndex()
    {
        
    $usuarioId = Yii::$app->user->identity->id;

    $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

    if ($empleado !== null) {
        $searchModel = new PermisoEconomicoSearch();
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
     * Displays a single PermisoEconomico model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $this->layout = "main-trabajador";

        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new PermisoEconomico model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "main-trabajador";
        $model = new PermisoEconomico();
        $motivoFechaPermisoModel = new MotivoFechaPermiso();
        $solicitudModel = new Solicitud();
        $motivoFechaPermisoModel->fecha_permiso = date('Y-m-d');
    
        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
    
        if ($empleado) {
            $model->empleado_id = $empleado->id;
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']);
        }
    
        $permisoAnterior = PermisoEconomico::find()
            ->joinWith('solicitud')
            ->where(['permiso_economico.empleado_id' => $empleado->id])
            ->orderBy(['permiso_economico.id' => SORT_DESC])
            ->one();
    
        if ($permisoAnterior) {
            $noPermisoAnterior = $permisoAnterior->no_permiso_anterior + 1;
            $fechaPermisoAnterior = $permisoAnterior->motivoFechaPermiso->fecha_permiso;
        } else {
            $noPermisoAnterior = null;
            $fechaPermisoAnterior = null;
        }
    
        $model->no_permiso_anterior = $noPermisoAnterior;
        $model->fecha_permiso_anterior = $fechaPermisoAnterior;
    
        if ($motivoFechaPermisoModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
    
            $model->load(Yii::$app->request->post());
            try {
                if ($motivoFechaPermisoModel->save()) {
                    $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;
                    $solicitudModel->empleado_id = $empleado->id;
                    $solicitudModel->status = 'En Proceso';
                    $solicitudModel->comentario = '';
                    $solicitudModel->fecha_aprobacion = null;
                    $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                    $solicitudModel->nombre_formato = 'PERMISO SIN SUELDO';
    
                    if ($solicitudModel->save()) {
                        $model->solicitud_id = $solicitudModel->id;
    
                        if ($model->jefe_departamento_id) {
                            $jefeDepartamento = JuntaGobierno::findOne($model->jefe_departamento_id);
                            $model->nombre_jefe_departamento = $jefeDepartamento ? $jefeDepartamento->profesion . ' ' . $jefeDepartamento->empleado->nombre . ' ' . $jefeDepartamento->empleado->apellido : null;
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
                    } else {
                        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la solicitud.');
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al guardar el motivo y la fecha del permiso.');
                }
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
            'noPermisoAnterior' => $noPermisoAnterior,
            'fechaPermisoAnterior' => $fechaPermisoAnterior,
        ]);
    }
      
    /**
     * Updates an existing PermisoEconomico model.
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
     * Deletes an existing PermisoEconomico model.
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
     * Finds the PermisoEconomico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return PermisoEconomico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PermisoEconomico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($id)
{
    $model = PermisoEconomico::findOne($id);

    if (!$model) {
        throw new NotFoundHttpException('El registro no existe.');
    }

    $templatePath = Yii::getAlias('@app/templates/permiso_economico.xlsx');
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
    $sheet->setCellValue('H14', $fecha_permiso);

    $permisoAnterior = PermisoEconomico::find()
        ->where(['empleado_id' => $model->empleado->id])
        ->andWhere(['<', 'id', $id])
        ->orderBy(['id' => SORT_DESC])
        ->one();

    if ($permisoAnterior) {
        $noPermisoAnterior = $permisoAnterior->no_permiso_anterior + 1;
        $fechaPermisoAnterior = $permisoAnterior->motivoFechaPermiso->fecha_permiso;
    } else {
        $noPermisoAnterior = null;
        $fechaPermisoAnterior = null;
    }

    if ($fechaPermisoAnterior === null && $noPermisoAnterior === null) {
        $sheet->setCellValue('H15', 'AUN NO TIENE PERMISOS ANTERIORES');
        $sheet->setCellValue('H16', 'AUN NO TIENE PERMISOS ANTERIORES');
    } else {
        $fecha_permiso_anterior = strftime('%A, %B %d, %Y', strtotime($fechaPermisoAnterior));
        $sheet->setCellValue('H15', $fecha_permiso_anterior);
        $sheet->setCellValue('H16', $noPermisoAnterior);
    }

    $sheet->setCellValue('H17', $model->motivoFechaPermiso->motivo);
    $sheet->setCellValue('A25', $nombreCompleto);
    $sheet->setCellValue('A26', $nombrePuesto);

    $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

    if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
        $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
        $sheet->setCellValue('H25', $nombreCompletoJefe);
    } else {
        $sheet->setCellValue('H25', null);
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

        $sheet->setCellValue('N25', $nombreCompletoDirector);

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

        $sheet->setCellValue('N26', $tituloDireccion);
    } else {
        $sheet->setCellValue('N25', null);
        $sheet->setCellValue('N26', null);
    }

    // Crear estructura de carpetas y guardar archivo
    $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->empleado->nombre . '_' . $model->empleado->apellido;
    if (!is_dir($nombreCarpetaTrabajador)) {
        mkdir($nombreCarpetaTrabajador, 0775, true);
    }
    $nombreIncidenciasCarpeta = $nombreCarpetaTrabajador . '/solicitudes_incidencias_empleado';
    if (!is_dir($nombreIncidenciasCarpeta)) {
        mkdir($nombreIncidenciasCarpeta, 0775, true);
    }

    $timestamp = time();
    $archivoPermiso = $nombreIncidenciasCarpeta . '/permiso_economico_' . $id . '_' . $timestamp . '.xlsx';

    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($archivoPermiso);

    return Yii::$app->response->sendFile($archivoPermiso);
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
        $model = PermisoEconomico::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
        $templatePath = Yii::getAlias('@app/templates/permiso_economico.xlsx');
    
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
$sheet->setCellValue('H14', $fecha_permiso);

$permiso = PermisoEconomico::findOne($id);
if (!$permiso) {
    Yii::$app->session->setFlash('error', 'Permiso no encontrado.');
    return $this->redirect(['index']);
}

$empleado = $permiso->empleado;
if (!$empleado) {
    Yii::$app->session->setFlash('error', 'Empleado no encontrado.');
    return $this->redirect(['index']);
}

$permisoAnterior = PermisoEconomico::find()
    ->joinWith('solicitud')
    ->where(['permiso_economico.empleado_id' => $empleado->id, 'solicitud.status' => 'Aprobado'])
    ->andWhere(['<', 'permiso_economico.id', $id])
    ->orderBy(['permiso_economico.id' => SORT_DESC])
    ->one();

if ($permisoAnterior) {
    $noPermisoAnterior = $permisoAnterior->no_permiso_anterior + 1;
    $fechaPermisoAnterior = $permisoAnterior->motivoFechaPermiso->fecha_permiso;
} else {
    $noPermisoAnterior = null;
    $fechaPermisoAnterior = null;
}

if ($fechaPermisoAnterior === null && $noPermisoAnterior === null) {
    $sheet->setCellValue('H15', 'AUN NO TIENE PERMISOS ANTERIORES');
    $sheet->setCellValue('H16', 'AUN NO TIENE PERMISOS ANTERIORES');
} else {
    $fecha_permiso_anterior = strftime('%A, %B %d, %Y', strtotime($fechaPermisoAnterior));
    $sheet->setCellValue('H15', $fecha_permiso_anterior);
    $sheet->setCellValue('H16', $noPermisoAnterior);
}



$sheet->setCellValue('H17', $model->motivoFechaPermiso->motivo);





$sheet->setCellValue('A25', $nombreCompleto);


$sheet->setCellValue('A26', $nombrePuesto);

//$direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

//if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
  //  $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
    //$sheet->setCellValue('H25', $nombreCompletoJefe);
//} else {
  //  $sheet->setCellValue('H25', null);
//}




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
$sheet->setCellValue('N25', $nombreCompleto);
} else {

}

$sheet->setCellValue('N26', 'DIRECTOR GENERAL');


$tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/permiso_economico.xlsx');
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save($tempFileName);


return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }

    public function actionImprimir($id)
    {
        $model = PermisoEconomico::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
    
        $templatePath = Yii::getAlias('@app/templates/permiso_economico.xlsx');
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
        $sheet->setCellValue('H14', $fecha_permiso);
    
        $permisoAnterior = PermisoEconomico::find()
            ->where(['empleado_id' => $model->empleado->id])
            ->andWhere(['<', 'id', $id])
            ->orderBy(['id' => SORT_DESC])
            ->one();
    
        if ($permisoAnterior) {
            $noPermisoAnterior = $permisoAnterior->no_permiso_anterior + 1;
            $fechaPermisoAnterior = $permisoAnterior->motivoFechaPermiso->fecha_permiso;
        } else {
            $noPermisoAnterior = null;
            $fechaPermisoAnterior = null;
        }
    
        if ($fechaPermisoAnterior === null && $noPermisoAnterior === null) {
            $sheet->setCellValue('H15', 'AUN NO TIENE PERMISOS ANTERIORES');
            $sheet->setCellValue('H16', 'AUN NO TIENE PERMISOS ANTERIORES');
        } else {
            $fecha_permiso_anterior = strftime('%A, %B %d, %Y', strtotime($fechaPermisoAnterior));
            $sheet->setCellValue('H15', $fecha_permiso_anterior);
            $sheet->setCellValue('H16', $noPermisoAnterior);
        }
    
        $sheet->setCellValue('H17', $model->motivoFechaPermiso->motivo);
        $sheet->setCellValue('A25', $nombreCompleto);
        $sheet->setCellValue('A26', $nombrePuesto);
    
        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);
    
        if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
            $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
            $sheet->setCellValue('H25', $nombreCompletoJefe);
        } else {
            $sheet->setCellValue('H25', null);
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
    
            $sheet->setCellValue('N25', $nombreCompletoDirector);
    
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
    
            $sheet->setCellValue('N26', $tituloDireccion);
        } else {
            $sheet->setCellValue('N25', null);
            $sheet->setCellValue('N26', null);
        }
    
        // Crear estructura de carpetas y guardar archivo
        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->empleado->nombre . '_' . $model->empleado->apellido;
        if (!is_dir($nombreCarpetaTrabajador)) {
            mkdir($nombreCarpetaTrabajador, 0775, true);
        }
        $nombreIncidenciasCarpeta = $nombreCarpetaTrabajador . '/solicitudes_incidencias_empleado';
        if (!is_dir($nombreIncidenciasCarpeta)) {
            mkdir($nombreIncidenciasCarpeta, 0775, true);
        }
        $archivoPermiso = $nombreIncidenciasCarpeta . '/permiso_economico_' . $id . '.xlsx';
    
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($archivoPermiso);
    
        // Convertir Excel a PDF usando COM
        try {
            $excel = new \COM("Excel.Application");
            $excel->Visible = false;
            $workbook = $excel->Workbooks->Open($archivoPermiso);
    
            // Ajustar la configuración de impresión
            foreach ($workbook->Worksheets as $worksheet) {
                $worksheet->PageSetup->Zoom = false;  // Desactivar el ajuste de zoom
                $worksheet->PageSetup->FitToPagesWide = 1;  // Ajustar al ancho de una página
                $worksheet->PageSetup->FitToPagesTall = false;  // Ajustar al alto de una página (o mantener el ajuste original)
            }
    
            $pdfPath = $nombreIncidenciasCarpeta . '/permiso_economico_' . $id . '.pdf';
            $workbook->ExportAsFixedFormat(0, $pdfPath);
            $workbook->Close(false);
            $excel->Quit();
    
            // Asegúrate de liberar los objetos COM
            unset($workbook);
            unset($excel);
    
            if (file_exists($pdfPath)) {
                return Yii::$app->response->sendFile($pdfPath, null, ['inline' => true]);
            } else {
                throw new NotFoundHttpException('El archivo PDF no pudo ser generado.');
            }
        } catch (\Exception $e) {
            Yii::$app->session->setFlash('error', 'Error al convertir el archivo a PDF: ' . $e->getMessage());
            return $this->redirect(['view', 'id' => $model->id]);
        }
    }
    
    
}
