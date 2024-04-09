<?php

namespace app\controllers;

use Yii;
use app\models\Expediente;
use app\models\ExpedienteSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;
use app\models\Trabajador;
/**
 * ExpedienteController implements the CRUD actions for Expediente model.
 */
class ExpedienteController extends Controller
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
     * Lists all Expediente models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ExpedienteSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Expediente model.
     * @param int $id ID
     * @param int $idtrabajador Idtrabajador
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $idtrabajador)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $idtrabajador),
        ]);
    }

    /**
     * Creates a new Expediente model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($idtrabajador)
    {
        $model = new Expediente();
        $model->idtrabajador = $idtrabajador;
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($model, 'ruta');
            
            if ($file) {
                $trabajador = Trabajador::findOne($model->idtrabajador);
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $trabajador->nombre . '_' . $trabajador->apellido;
                
                // Verifica si la carpeta del trabajador existe, si no, la crea
                if (!is_dir($nombreCarpetaTrabajador)) {
                    mkdir($nombreCarpetaTrabajador, 0775, true);
                }
                
                // Define la ruta de la carpeta de expedientes dentro de la carpeta del trabajador
                $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/expedientes';
                if (!is_dir($nombreCarpetaExpedientes)) {
                    mkdir($nombreCarpetaExpedientes, 0775, true);
                }
                
                // Guarda el archivo en la carpeta de expedientes del trabajador
                $rutaArchivo = $nombreCarpetaExpedientes . '/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($rutaArchivo);
                
                // Guarda la ruta del archivo en el modelo
                $model->ruta = $rutaArchivo;
                $model->tipo = $file->extension;
                $model->fechasubida = date('Y-m-d H:i:s'); // O la fecha que desees registrar
              //  $model->idusuario = idtrabajador;
                if ($model->save()) {
                    return $this->redirect(['trabajador/view','id' => $trabajador->id]);
                }
            }
        }
    
        return $this->renderAjax('_form', [
            'model' => $model,
        ]);
    }
    
    

    /**
     * Updates an existing Expediente model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $idtrabajador Idtrabajador
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $idtrabajador)
    {
        $model = $this->findModel($id, $idtrabajador);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idtrabajador' => $model->idtrabajador]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Expediente model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $idtrabajador Idtrabajador
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $idtrabajador)
    {
        $this->findModel($id, $idtrabajador)->delete();

        return $this->redirect(['index']);
    }


public function actionOpen($id)
{
    $model = Expediente::findOne($id);
    if ($model) {
        $rutaArchivo = $model->ruta;
        if (file_exists($rutaArchivo)) {
            return Yii::$app->response->sendFile($rutaArchivo, $model->nombre, ['inline' => true]);
        }
    }
    // Si el archivo no se encuentra, puedes redirigir o mostrar un mensaje de error
    Yii::$app->session->setFlash('error', 'El archivo solicitado no se encuentra disponible.');
    return $this->redirect(['trabajador/index']); // Cambia esto por la acción o la vista que desees
}

    /**
     * Finds the Expediente model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $idtrabajador Idtrabajador
     * @return Expediente the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $idtrabajador)
    {
        if (($model = Expediente::findOne(['id' => $id, 'idtrabajador' => $idtrabajador])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
