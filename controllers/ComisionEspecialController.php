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
        // Obtener el ID del usuario que ha iniciado sesión
        $usuarioId = Yii::$app->user->identity->id;
        // Buscar el modelo de Empleado asociado al usuario actual
        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();
        // Verificar si se encontró el empleado
        if ($empleado !== null) {
            // Si se encontró el empleado, utilizar su ID para filtrar los registros
            $searchModel = new ComisionEspecialSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
            // Filtrar los registros por el ID del empleado
            $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
            $this->layout = "main-trabajador";
            return $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $dataProvider,
            ]);
        } else {
            // Si no se encontró el empleado, mostrar un mensaje de error o redireccionar
            Yii::$app->session->setFlash('error', 'No se pudo encontrar el empleado asociado al usuario actual.');
            return $this->redirect(['index']); // Redirecciona a la página de índice u otra página apropiada
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
                            $model->nombre_jefe_departamento = $jefeDepartamento ? $jefeDepartamento->profesion . ' ' . $jefeDepartamento->empleado->nombre . ' ' . $jefeDepartamento->empleado->apellido : null;
                        }


                        if ($model->save()) {
                            $transaction->commit();
                            Yii::$app->session->setFlash('success', 'Su solicitud ha sido generada exitosamente.');

                            // Crear notificación
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

        $tempFileName = Yii::getAlias('@app/runtime/comision_especial.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($tempFileName);
    
        // Luego, proporciona un enlace para que el usuario descargue el archivo
        // Puedes redirigir a una acción que presente el enlace o generar directamente el enlace aquí mismo
        return $this->redirect(['download', 'filename' => basename($tempFileName)]);
    }
    

    public function actionDownload($filename)
    {
        $filePath = Yii::getAlias("@app/runtime/$filename");
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new NotFoundHttpException('El archivo solicitado no existe.');
        }
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

        // Código para establecer los valores en la plantilla igual que en actionExport

        // Crear un objeto Mpdf
        $mpdf = new Mpdf();

        // Obtener el contenido HTML del archivo Excel
        ob_start();
        $writer = IOFactory::createWriter($spreadsheet, 'Html');
        $writer->save('php://output');
        $htmlContent = ob_get_clean();
    
        // Escribir el contenido HTML en el objeto Mpdf
        $mpdf->WriteHTML($htmlContent);
    
        // Configurar las cabeceras para descargar el archivo PDF
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="comision_especial.pdf"');
        header('Cache-Control: max-age=0');
    
        // Salida del PDF generado
        $mpdf->Output();
 
 
 
    }



    
}
