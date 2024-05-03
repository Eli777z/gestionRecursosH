<?php

namespace app\controllers;

use Yii;
use app\models\Documento;
use app\models\DocumentoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

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
    public function actionCreate()
    {
        $model = new Expediente();
        $model->idtrabajador = $idtrabajador;
        if (Yii::$app->request->isPost) {
            $model->load(Yii::$app->request->post());
            $file = UploadedFile::getInstance($model, 'ruta');
            
            if ($file) {
                $trabajador = Trabajador::findOne($model->idtrabajador);
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $trabajador->nombre . '_' . $trabajador->apellido;
                
                if (!is_dir($nombreCarpetaTrabajador)) {
                    mkdir($nombreCarpetaTrabajador, 0775, true);
                }
                
                $nombreCarpetaExpedientes = $nombreCarpetaTrabajador . '/expedientes';
                if (!is_dir($nombreCarpetaExpedientes)) {
                    mkdir($nombreCarpetaExpedientes, 0775, true);
                }
                
                $rutaArchivo = $nombreCarpetaExpedientes . '/' . $file->baseName . '.' . $file->extension;
                $file->saveAs($rutaArchivo);
                
                $model->ruta = $rutaArchivo;
                $model->tipo = $file->extension;
                $model->fechasubida = date('Y-m-d H:i:s'); 
              //  $model->idusuario = idtrabajador;
                if ($model->save()) {
                    
                    return $this->redirect(['trabajador/view','id' => $trabajador->id]);
                }
            }
        }
    
        return $this->renderAjax('create', [
            'model' => $model,
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
        $this->findModel($id, $empleado_id)->delete();

        return $this->redirect(['index']);
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
}
