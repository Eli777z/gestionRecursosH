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
use yii\helpers\Url;
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
        //CARGA LOS MODELOS
        $model = new Documento();
        $cat_tipo_documento = new CatTipoDocumento();
    
        $model->empleado_id = $empleado_id;
    
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($model, 'ruta');
            $tipoDocumentoIdSeleccionado = Yii::$app->request->post('Documento')['cat_tipo_documento_id'];
            $nombreDocumento = Yii::$app->request->post('Documento')['nombre'];
    
            // Verificar si se seleccionó 'OTRO' y se ingresó un nuevo nombre de documento
            if ($tipoDocumentoIdSeleccionado == 4 && !empty($nombreDocumento)) {
                $nuevoTipoDocumento = new CatTipoDocumento();
                $nuevoTipoDocumento->nombre_tipo = $nombreDocumento;
                if ($nuevoTipoDocumento->save()) {
                    // Crear el nuevo tipo de documento y obtener su ID
                    $tipoDocumentoIdSeleccionado = $nuevoTipoDocumento->id;
                    Yii::$app->session->setFlash('success', 'Nuevo tipo de documento creado exitosamente.');
                } else {
                    Yii::$app->session->setFlash('error', 'No se pudo crear el nuevo tipo de documento.');
                    return $this->render('create', [
                        'model' => $model,
                        'cat_tipo_documento' => $cat_tipo_documento,
                    ]);
                }
            }
    
            $model->cat_tipo_documento_id = $tipoDocumentoIdSeleccionado;
    
            // Asignar el nombre del documento solo si es 'OTRO'
            if ($tipoDocumentoIdSeleccionado != 4) {
                $nombreDocumento = CatTipoDocumento::findOne($tipoDocumentoIdSeleccionado)->nombre_tipo;
            }
            $model->nombre = $nombreDocumento;
            //LOGICA DE DIRECTORIOS
            if ($file && $model->cat_tipo_documento_id !== null) {
                $empleado = Empleado::findOne($model->empleado_id);
                //BUSCA EL DIRECTORIO DEL EMPLEADO
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
                if (!is_dir($nombreCarpetaTrabajador)) {
                    mkdir($nombreCarpetaTrabajador, 0775, true);
                }
                //CREA LA CARPETA DE DOCUMNETOS EN EL DIRECTORIO
                $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/documentos';
                if (!is_dir($nombreCarpetaExpedientes)) {
                    mkdir($nombreCarpetaExpedientes, 0775, true);
                }
                //GUARDA EL ARCHIVO EN EL DIRECTORIO
                $rutaArchivo = $nombreCarpetaExpedientes . '/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($rutaArchivo);
    
                $model->ruta = $rutaArchivo;
                $model->fecha_subida = date('Y-m-d H:i:s');
    
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', 'El documento se ha subido exitosamente.');
                    $url = Url::to(['empleado/view', 'id' => $empleado->id]) . '#documentos';
                    return $this->redirect($url);
                }
            }
        }
    
        return $this->render('create', [
            //RENDERIZA LA VISTA Y CARGA LOS MODELOS CORRESPONDIENTES
            'model' => $model,
            'cat_tipo_documento' => $cat_tipo_documento,
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
    
        
        Yii::$app->session->setFlash('success', 'El documento se ha eliminado exitosamente.');
                $url = Url::to(['empleado/view', 'id' => $empleado->id]) . '#documentos';
                return $this->redirect($url);
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

//ABRIR DOCUMENTO
    public function actionOpen($id)
{
    //EN CASO DE QUE SE TRATE DE UN TIPO DE DOCUMENTO QUE SE PUEDA ABRIR O VISUALIZAR 
    //DESDE EL NAVEGADOR, ESTA FUNCION SE ENCARGAR
    $model = Documento::findOne($id);
    if ($model) {
        $rutaArchivo = $model->ruta;
        //BUSCA LA RUTA DEL ARCHIVO
        if (file_exists($rutaArchivo)) {
            return Yii::$app->response->sendFile($rutaArchivo, $model->nombre, ['inline' => true]);
        }
    }
    Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
    return $this->redirect(['empleado/index']); 
}
//DESCARGAR DOCUMENTO
public function actionDownload($id)
{
    //SI SE DESEA DESCARGAR EL DOCUMENTO, ESTA FUNCION
    //BUSCA EL REGISTRO DEL DOCUMENTO, PARA QUE USANDO LA RUTA
    //ENCONTRAR Y OBTENER EL ARCHIVO, ASI PODERLO DESCARGAR
    $model = Documento::findOne($id);
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
    return $this->redirect(['documento/index']); 
}
}
