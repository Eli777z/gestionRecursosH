<?php

namespace app\controllers;

use Yii;
use app\models\DocumentoMedico;
use app\models\DocumentoMedicoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\Url;
use app\models\Empleado;
use yii\web\UploadedFile;
/**
 * DocumentoMedicoController implements the CRUD actions for DocumentoMedico model.
 */
class DocumentoMedicoController extends Controller
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
     * Lists all DocumentoMedico models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentoMedicoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single DocumentoMedico model.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new DocumentoMedico model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id)
{
    $model = new DocumentoMedico();
    $model->empleado_id = $empleado_id;

    if (Yii::$app->request->isPost) {
        $model->load(Yii::$app->request->post());
        $file = UploadedFile::getInstance($model, 'ruta');

        if ($file) {
            $empleado = Empleado::findOne($model->empleado_id);
            $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
            if (!is_dir($nombreCarpetaTrabajador)) {
                mkdir($nombreCarpetaTrabajador, 0775, true);
            }
            $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/documentos_medicos';
            if (!is_dir($nombreCarpetaExpedientes)) {
                mkdir($nombreCarpetaExpedientes, 0775, true);
            }
            $rutaArchivo = $nombreCarpetaExpedientes . '/' . $file->baseName . '.' . $file->extension;
            $file->saveAs($rutaArchivo);

            $model->ruta = $rutaArchivo;
            $model->fecha_subida = date('Y-m-d H:i:s');

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'El documento médico se ha subido exitosamente.');
                $url = Url::to(['empleado/view', 'id' => $empleado->id]) . '#documentos-medicos';
                return $this->redirect($url);
            }
        }
    }

    return $this->render('create', [
        'model' => $model,
    ]);
}

    /**
     * Updates an existing DocumentoMedico model.
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
     * Deletes an existing DocumentoMedico model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $empleado_id)
    {
        $model = $this->findModel($id, $empleado_id);
        
        $rutaArchivo = $model->ruta;
        
        $empleado = Empleado::findOne($model->empleado_id);
        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
        $nombreCarpetaPapelera = $nombreCarpetaTrabajador . '/papelera';
        
        
        if (!is_dir($nombreCarpetaPapelera)) {
            mkdir($nombreCarpetaPapelera, 0775, true);
        }
        
        
        $nombreArchivo = basename($rutaArchivo);
        
   
        $rutaPapelera = $nombreCarpetaPapelera . '/' . $nombreArchivo;
        rename($rutaArchivo, $rutaPapelera);
        
        
        $model->delete();
    
        
        Yii::$app->session->setFlash('success', 'El documento medico se ha eliminado exitosamente.');
                $url = Url::to(['empleado/view', 'id' => $empleado->id]) . '#documentos-medicos';
                return $this->redirect($url);
    }

    /**
     * Finds the DocumentoMedico model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return DocumentoMedico the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = DocumentoMedico::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionOpen($id)
    {
        $model = DocumentoMedico::findOne($id);
        if ($model) {
            $rutaArchivo = $model->ruta;
            if (file_exists($rutaArchivo)) {
                return Yii::$app->response->sendFile($rutaArchivo, $model->nombre, ['inline' => true]);
            }
        }
        Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
        return $this->redirect(['empleado/index']); 
    }

    public function actionDownload($id)
{
    $model = DocumentoMedico::findOne($id);
    if ($model) {
        $rutaArchivo = $model->ruta;
        if (file_exists($rutaArchivo)) {
            $nombreArchivo = $model->nombre; 
            $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION); 
            $nombreCompleto = $nombreArchivo . '.' . $extension; 
            return Yii::$app->response->sendFile($rutaArchivo, $nombreCompleto);
        }
    }
    Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
    return $this->redirect(['documento-medico/index']); 
}


}
