<?php

namespace app\controllers;

use Yii;
use app\models\ContratoParaPersonalEventual;
use app\models\ContratoParaPersonalEventualSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Empleado;
use app\models\Notificacion;
use app\models\Solicitud;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Writer\Html;
use app\models\JuntaGobierno;
use DateTime;

/**
 * ContratoParaPersonalEventualController implements the CRUD actions for ContratoParaPersonalEventual model.
 */
class ContratoParaPersonalEventualController extends Controller
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
     * Lists all ContratoParaPersonalEventual models.
     * @return mixed
     */
    public function actionIndex()
    {
        $usuarioId = Yii::$app->user->identity->id;

        $empleado = Empleado::find()->where(['usuario_id' => $usuarioId])->one();


        if ($empleado !== null) {
            $searchModel = new ContratoParaPersonalEventualSearch();
            $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    
            $dataProvider->query->andFilterWhere(['empleado_id' => $empleado->id]);
    
    
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
     * Displays a single ContratoParaPersonalEventual model.
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

    $searchModel = new ContratoParaPersonalEventualSearch();
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
     * Creates a new ContratoParaPersonalEventual model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id = null)
{
    $this->layout = "main-trabajador";
    $model = new ContratoParaPersonalEventual();
    $solicitudModel = new Solicitud();
    $usuarioId = Yii::$app->user->identity->id;

    // Obtener el empleado
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

    // Obtener el último número de contrato para este empleado
    $ultimoContrato = ContratoParaPersonalEventual::find()
        ->where(['empleado_id' => $empleado->id])
        ->orderBy(['id' => SORT_DESC])
        ->one();

    // Si el último contrato tiene numero_contrato igual a 5, el siguiente será 1
    if ($ultimoContrato && $ultimoContrato->numero_contrato == 5) {
        $numeroContrato = 1;
    } else {
        $numeroContrato = $ultimoContrato ? $ultimoContrato->numero_contrato + 1 : 1;
    }
    $model->numero_contrato = $numeroContrato;

    if ($model->load(Yii::$app->request->post())) {
        if (!empty($model->fecha_inicio) && !empty($model->fecha_termino)) {
            $fechaInicio = new \DateTime($model->fecha_inicio);
            $fechaTermino = new \DateTime($model->fecha_termino);
            $diff = $fechaTermino->diff($fechaInicio);
            $model->duracion = $diff->days + 1;
        }
    

        $transaction = Yii::$app->db->beginTransaction();

        try {
            if ($model->save()) {
                $solicitudModel->empleado_id = $empleado->id;
                $solicitudModel->status = 'Nueva';
                $solicitudModel->comentario = '';
                $solicitudModel->fecha_aprobacion = null;
                $solicitudModel->fecha_creacion = date('Y-m-d H:i:s');
                $solicitudModel->nombre_formato = 'SOLICITUD DE CONTRATO PARA PERSONAL EVENTUAL';

                if ($solicitudModel->save()) {
                    // Asignar la relación solicitud_id al modelo de contrato
                    $model->solicitud_id = $solicitudModel->id;

                    // Guardar el modelo con la solicitud relacionada
                    if ($model->save(false)) { // Usamos false para evitar validaciones redundantes
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
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar el contrato.');
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
        'solicitudModel' => $solicitudModel,
        'empleado' => $empleado,
        'numeroContrato' => $numeroContrato,
    ]);
}

    
    /**
     * Updates an existing ContratoParaPersonalEventual model.
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
     * Deletes an existing ContratoParaPersonalEventual model.
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
     * Finds the ContratoParaPersonalEventual model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return ContratoParaPersonalEventual the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContratoParaPersonalEventual::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }




    public function actionExportHtml($id)
    {
        $this->layout = false;

        $model = ContratoParaPersonalEventual::findOne($id);
    
        if (!$model) {
            throw new NotFoundHttpException('El registro no existe.');
        }
    
        $templatePath = Yii::getAlias('@app/templates/solicitud_contrato_personal_eventual.xlsx');
        $spreadsheet = IOFactory::load($templatePath);
    
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('C5', $model->empleado->numero_empleado);
        $nombre = mb_strtoupper($model->empleado->nombre, 'UTF-8');
        $apellido = mb_strtoupper($model->empleado->apellido, 'UTF-8');
        $nombreCompleto = $apellido . ' ' . $nombre;
        $sheet->setCellValue('C6', $nombreCompleto);

        setlocale(LC_TIME, 'es_419.UTF-8'); 
        $fechaHOY = strftime('%A, %d de %B de %Y'); //formato de fecha
        $sheet->setCellValue('F3', $fechaHOY);

        $sheet->setCellValue('C7', $model->empleado->curp);
        $sheet->setCellValue('C8', $model->empleado->rfc);
        $sheet->setCellValue('C9', $model->empleado->nss);
        $sheet->setCellValue('C10', $model->empleado->edad);
        $sheet->setCellValue('C11', $model->empleado->informacionLaboral->catDptoCargo->nombre_dpto);
        $sheet->setCellValue('C12', $model->empleado->estado_civil);
        $sheet->setCellValue('C13', $model->empleado->calle);
        $sheet->setCellValue('C14', $model->empleado->colonia);
        $sheet->setCellValue('C15', $model->empleado->codigo_postal);
        $sheet->setCellValue('C16', $model->empleado->telefono);


        $estado = ucfirst(mb_strtolower($model->empleado->estado));
        $sheet->setCellValue('C17', $model->empleado->municipio . ', ' . $estado);
        


    
        setlocale(LC_TIME, 'es_419.UTF-8');
       
    
        $nombrePuesto = $model->empleado->informacionLaboral->catPuesto->nombre_puesto;
        $sheet->setCellValue('C21', $nombrePuesto);
    
    
        $nombreDireccion = $model->empleado->informacionLaboral->catDireccion->nombre_direccion;
        $sheet->setCellValue('F5', $nombreDireccion);
    
        $nombreDepartamento = $model->empleado->informacionLaboral->catDepartamento->nombre_departamento;
        $sheet->setCellValue('F6', $nombreDepartamento);
    

        $fecha_ingreso = strftime('%A, %d de %B de %Y', strtotime($model->empleado->informacionLaboral->fecha_ingreso));
        $sheet->setCellValue('C22', $fecha_ingreso);

        $sheet->setCellValue('F21', $model->empleado->informacionLaboral->salario);
        $sheet->setCellValue('F23', $model->empleado->informacionLaboral->numero_cuenta);
        $sheet->setCellValue('F24', $model->empleado->informacionLaboral->dias_laborales);

        //$sheet->setCellValue('F25', $model->empleado->informacionLaboral->horario_laboral_inicio.' a '.$model->empleado->informacionLaboral->horario_laboral_fin);

 $horarioInicio = DateTime::createFromFormat('H:i:s', $model->empleado->informacionLaboral->horario_laboral_inicio)->format('g:i A');
        $horarioFin = DateTime::createFromFormat('H:i:s', $model->empleado->informacionLaboral->horario_laboral_fin)->format('g:i A');
        
        $horarioCompleto = " $horarioInicio A $horarioFin";
        
        $sheet->setCellValue('F25', $horarioCompleto);

        setlocale(LC_TIME, 'es_419.UTF-8');


        $sheet->setCellValue('F26', $model->empleado->informacionLaboral->juntaGobierno->empleado->profesion.' '.$model->empleado->informacionLaboral->juntaGobierno->empleado->nombre.' '.$model->empleado->informacionLaboral->juntaGobierno->empleado->apellido);




        $sheet->setCellValue('C41', $model->empleado->informacionLaboral->juntaGobierno->empleado->profesion.' '.$model->empleado->informacionLaboral->juntaGobierno->empleado->nombre.' '.$model->empleado->informacionLaboral->juntaGobierno->empleado->apellido);
        
        $juntaDirectorDireccion = JuntaGobierno::find()
        ->where(['nivel_jerarquico' => 'Director'])
        ->andWhere(['cat_direccion_id' => $model->empleado->informacionLaboral->cat_direccion_id])
        ->one();

        $sheet->setCellValue('D41', $juntaDirectorDireccion->empleado->profesion . ' ' .$juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido);
        $sheet->setCellValue('F41',$juntaDirectorDireccion->empleado->profesion . ' ' .$juntaDirectorDireccion->empleado->nombre . ' ' . $juntaDirectorDireccion->empleado->apellido);

        //return strftime('%A, %d de %B de %Y', strtotime($model->fecha_creacion));

       
        $sheet->setCellValue('C23', $model->numero_contrato);
       
        $sheet->setCellValue('F9', $model->numero_contrato);



        $fecha_inicio = strftime('%A, %d de %B de %Y', strtotime($model->fecha_inicio));
        $sheet->setCellValue('C24', $fecha_inicio);
    
        $fecha_termino = strftime('%A, %d de %B de %Y', strtotime($model->fecha_termino));
        $sheet->setCellValue('C25', $fecha_termino);
           
        $sheet->setCellValue('C26', $model->duracion);

        $sheet->setCellValue('F22', $model->modalidad);



      

        $actividadesTextoPlano = strip_tags($model->actividades_realizar);
        $actividadesTextoPlano = html_entity_decode($actividadesTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $sheet->setCellValue('B29', $actividadesTextoPlano);

        $origenTextoPlano = strip_tags($model->origen_contrato);
        $origenTextoPlano = html_entity_decode($origenTextoPlano, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $sheet->setCellValue('B33', $origenTextoPlano);


       // $sheet->setCellValue('B33', $model->origen_contrato);
        // Establecer el área de impresión
        $htmlWriter = new Html($spreadsheet);
        $htmlWriter->setSheetIndex(0); 
        $htmlWriter->setPreCalculateFormulas(false);
    
        $fullHtmlContent = $htmlWriter->generateHtmlAll();
    
        $fullHtmlContent = str_replace('&nbsp;', ' ', $fullHtmlContent);


        return $this->render('excel-html', ['htmlContent' => $fullHtmlContent, 'model' => $model]);
    
    }
    

}
