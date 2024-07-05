<?php
namespace app\controllers;
use Yii;
use app\models\ComisionEspecial;
use app\models\ComisionEspecialSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\JuntaGobierno;
use app\models\CatDireccion;
use app\models\Notificacion;
use app\models\Solicitud;
use app\models\Empleado;
use app\models\MotivoFechaPermiso;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Mpdf\Mpdf;
/**
 * ComisionEspecialController implements the CRUD actions for ComisionEspecial model.
 */
class ComisionEspecialController extends Controller
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
     * Lists all ComisionEspecial models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
        if ($empleado !== null) {
            $searchModel = new ComisionEspecialSearch();
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
     * Displays a single ComisionEspecial model.
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
     * Creates a new ComisionEspecial model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $this->layout = "main-trabajador";

        $model = new ComisionEspecial();
        $motivoFechaPermisoModel = new MotivoFechaPermiso();
        $solicitudModel = new Solicitud();
        $motivoFechaPermisoModel->fecha_permiso = date('Y-m-d');
        //  $model->fecha_a_reponer = date('Y-m-d');
        $usuarioId = Yii::$app->user->identity->id;

        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();

        if ($empleado) {
            $model->empleado_id = $empleado->id;
        } else {
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']);
        }

        if ($motivoFechaPermisoModel->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            try {
                if ($motivoFechaPermisoModel->save()) {
                    $model->motivo_fecha_permiso_id = $motivoFechaPermisoModel->id;


                    $solicitudModel->empleado_id = $empleado->id;
                    $solicitudModel->status = 'En Proceso';
                    $solicitudModel->comentario = '';
                    $solicitudModel->fecha_aprobacion = null;
                    $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                    $solicitudModel->nombre_formato = 'COMISION ESPECIAL';

                    if ($solicitudModel->save()) {
                        $model->solicitud_id = $solicitudModel->id;
                        $model->load(Yii::$app->request->post());
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
        ]);
    }


    /**
     * Updates an existing ComisionEspecial model.
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
     * Deletes an existing ComisionEspecial model.
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
     * Finds the ComisionEspecial model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ComisionEspecial the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ComisionEspecial::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionExport($id)
{
    $model = ComisionEspecial::findOne($id);

    if (!$model) {
        throw new NotFoundHttpException('El registro no existe.');
    }

    $templatePath = Yii::getAlias('@app/templates/comision_especial.xlsx');
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
    $sheet->setCellValue('H15', $model->motivoFechaPermiso->motivo);
    $sheet->setCellValue('A23', $nombreCompleto);
    $sheet->setCellValue('A24', $nombrePuesto);

    $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

    if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
        $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
        $sheet->setCellValue('H23', $nombreCompletoJefe);
    } else {
        $sheet->setCellValue('H23', null);
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

        $sheet->setCellValue('N23', $nombreCompletoDirector);

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

        $sheet->setCellValue('N24', $tituloDireccion);
    } else {
        $sheet->setCellValue('N23', null);
        $sheet->setCellValue('N24', null);
    }

    // Establecer el área de impresión
    $spreadsheet->getActiveSheet()->getPageSetup()->setPrintArea('A1:U24'); // Ajusta esto según el área de impresión requerida

    // Guardar el archivo temporal de Excel
    $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/comision_especial.xlsx');
    $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
    $writer->save($tempFileName);

    // Convertir el archivo Excel a HTML
    $spreadsheet = IOFactory::load($tempFileName);
    $writer = IOFactory::createWriter($spreadsheet, 'Html');
    ob_start();
    $writer->save('php://output');
    $html = ob_get_clean();

    // Generar el PDF usando mPDF
    $mpdf = new Mpdf();

    // Establecer configuraciones de mPDF, como el tamaño de página y margenes si es necesario
    $mpdf->SetDefaultFontSize('8'); // Ejemplo de establecer tamaño de letra

    $mpdf->WriteHTML($html);
    $pdfFilePath = Yii::getAlias('@app/runtime/archivos_temporales/comision_especial.pdf');
    $mpdf->Output($pdfFilePath, \Mpdf\Output\Destination::FILE);

    // Eliminar el archivo Excel temporal
    unlink($tempFileName);

    return $this->redirect(['download', 'filename' => basename($pdfFilePath)]);
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

        $model = ComisionEspecial::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        $templatePath = Yii::getAlias('@app/templates/comision_especial.xlsx');


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
        $sheet->setCellValue('H15', $model->motivoFechaPermiso->motivo);
        $sheet->setCellValue('A23', $nombreCompleto);
        $sheet->setCellValue('A24', $nombrePuesto);


        
//        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

       // if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
         //   $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
           // $sheet->setCellValue('H23', $nombreCompletoJefe);
        //} else {
          //  $sheet->setCellValue('H23', null);
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
            $sheet->setCellValue('N23', $nombreCompleto);
        } else {
          
        }
        
        $sheet->setCellValue('N24', 'DIRECTOR GENERAL');
        
        

        $tempFileName = Yii::getAlias('@app/runtime/archivos_temporales/comision_especial.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);
    
        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }
   













    public function actionExportPdf($id)
    {
        $model = ComisionEspecial::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }

        $templatePath = Yii::getAlias('@app/templates/comision_especial.xlsx');


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
        $sheet->setCellValue('H15', $model->motivoFechaPermiso->motivo);
        $sheet->setCellValue('A23', $nombreCompleto);
        $sheet->setCellValue('A24', $nombrePuesto);

        $direccion = CatDireccion::findOne($model->empleado->informacionLaboral->cat_direccion_id);

        if ($direccion && $direccion->nombre_direccion !== '1.- GENERAL' && $model->nombre_jefe_departamento) {
            $nombreCompletoJefe = mb_strtoupper($model->nombre_jefe_departamento, 'UTF-8');
            $sheet->setCellValue('H23', $nombreCompletoJefe);
        } else {
            $sheet->setCellValue('H23', null);
        }

        $juntaDirectorDireccion = JuntaGobierno::find()
            ->where(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
            ->andWhere(['or', ['nivel_jerarquico' => 'Director'], ['nivel_jerarquico' => 'Jefe de unidad']])
            ->one();

        if ($juntaDirectorDireccion) {
            $nombreDirector = mb_strtoupper($juntaDirectorDireccion->empleado->nombre, 'UTF-8');
            $apellidoDirector = mb_strtoupper($juntaDirectorDireccion->empleado->apellido, 'UTF-8');
            $profesionDirector = mb_strtoupper($juntaDirectorDireccion->profesion, 'UTF-8');
            $nombreCompletoDirector = $profesionDirector . ' ' . $apellidoDirector . ' ' . $nombreDirector;

            $sheet->setCellValue('N23', $nombreCompletoDirector);

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
                    $tituloDireccion = ''; // Otra dirección no especificada
            }

            $sheet->setCellValue('N24', $tituloDireccion);
        } else {
            $sheet->setCellValue('N24', null);
            $sheet->setCellValue('N24', null);
        }

       
        $mpdf = new Mpdf(['mode' => 'utf-8','format' => [215.9, 279.4]]);

       
        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        $writer->save('php://output');
        $htmlContent = ob_get_clean();
        
       
        $mpdf->WriteHTML($htmlContent);
        
     
        $tempPdfFileName = Yii::getAlias('@app/runtime/archivos_temporales/comision_especial.pdf');
        $mpdf->Output($tempPdfFileName, 'F'); 
        
       
        return $this->redirect(['download', 'filename' => basename($tempPdfFileName)]);
 
 
 
    }



    
}
