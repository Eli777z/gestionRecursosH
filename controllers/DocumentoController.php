<?php

namespace app\controllers;

use app\models\CatTipoDocumento;
use Yii;
use app\models\Documento;
use app\models\DocumentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Empleado;
/**
 * DocumentoController implements the CRUD actions for Documento model.
 */
class DocumentoController extends Controller
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
     * Lists all Documento models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Documento model.
     * @param int $id ID
     * @param int $empleado_id Empleado ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $empleado_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $empleado_id),
        ]);
    }

    /**
     * Creates a new Documento model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($empleado_id)
{
    $model = new Documento();
    $cat_tipo_documento = new CatTipoDocumento();

    $model->empleado_id = $empleado_id;

    if (Yii::$app->request->isPost) {
        $model->load(Yii::$app->request->post());
        $file = UploadedFile::getInstance($model, 'ruta');
        
        // Obtener el ID del tipo de documento seleccionado del formulario
        $tipoDocumentoIdSeleccionado = Yii::$app->request->post('Documento')['cat_tipo_documento_id'];

        if ($file && $tipoDocumentoIdSeleccionado !== null) {
            // Asignar el ID del tipo de documento seleccionado al modelo Documento
            $model->cat_tipo_documento_id = $tipoDocumentoIdSeleccionado;

            $empleado = Empleado::findOne($model->empleado_id);
            $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
            if (!is_dir($nombreCarpetaTrabajador)) {
                mkdir($nombreCarpetaTrabajador, 0775, true);
            }
            $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/documentos';
            if (!is_dir($nombreCarpetaExpedientes)) {
                mkdir($nombreCarpetaExpedientes, 0775, true);
            }
            $rutaArchivo = $nombreCarpetaExpedientes . '/' . $file->baseName . '.' . $file->extension;
            $file->saveAs($rutaArchivo);
            
            $model->ruta = $rutaArchivo;
       
            $model->fecha_subida = date('Y-m-d H:i:s'); 
        
            if ($model->save()) {
                return $this->redirect(['empleado/view', 'id' => $empleado->id]);
            }
        }
    }

    return $this->renderAjax('create', [
        'model' => $model,
        'cat_tipo_documento'=> $cat_tipo_documento,
    ]);
}

    

    /**
     * Updates an existing Documento model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $empleado_id Empleado ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $empleado_id)
    {
        $model = $this->findModel($id, $empleado_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'empleado_id' => $model->empleado_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Documento model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $empleado_id Empleado ID
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
    
        
        return $this->redirect(['empleado/view', 'id' => $empleado_id]);
    }

    /**
     * Finds the Documento model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $empleado_id Empleado ID
     * @return Documento the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $empleado_id)
    {
        if (($model = Documento::findOne(['id' => $id, 'empleado_id' => $empleado_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    public function actionOpen($id)
{
    $model = Documento::findOne($id);
    if ($model) {
        $rutaArchivo = $model->ruta;
        if (file_exists($rutaArchivo)) {
            return Yii::$app->response->sendFile($rutaArchivo, $model->nombre, ['inline' => true]);
        }
    }
    // Si el archivo no se encuentra, puedes redirigir o mostrar un mensaje de error
    Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
    return $this->redirect(['empleado/index']); // Cambia esto por la acción o la vista que desees
}

public function actionDownload($id)
{
    $model = Documento::findOne($id);
    if ($model) {
        $rutaArchivo = $model->ruta;
        if (file_exists($rutaArchivo)) {
            $nombreArchivo = $model->nombre; // Nombre del archivo sin la extensión
            $extension = pathinfo($rutaArchivo, PATHINFO_EXTENSION); // Obtener la extensión del archivo
            $nombreCompleto = $nombreArchivo . '.' . $extension; // Nombre completo con la extensión
            return Yii::$app->response->sendFile($rutaArchivo, $nombreCompleto);
        }
    }
    // Si el archivo no se encuentra, puedes redirigir o mostrar un mensaje de error
    Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
    return $this->redirect(['documento/index']); // Cambia esto por la acción o la vista que desees
}
}
