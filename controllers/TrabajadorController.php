<?php

namespace app\controllers;

use Yii;
use app\models\Trabajador;
use app\models\Usuario;
use app\models\TrabajadorSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\db\Exception;

/**
 * TrabajadorController implements the CRUD actions for Trabajador model.
 */
class TrabajadorController extends Controller
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
     * Lists all Trabajador models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new TrabajadorSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Trabajador model.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id, $idusuario)
    {
        return $this->render('view', [
            'model' => $this->findModel($id, $idusuario),
        ]);
    }

    /**
     * Creates a new Trabajador model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Trabajador();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idusuario' => $model->idusuario]);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }
    

    /**
     * Updates an existing Trabajador model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $idusuario)
    {
        $model = $this->findModel($id, $idusuario);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'idusuario' => $model->idusuario]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Trabajador model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $idusuario)
    {
        $trabajador = $this->findModel($id, $idusuario);
    
    // Obtén el ID del usuario asociado al trabajador
    $idUsuario = $trabajador->idusuario;
    
    // Elimina tanto el trabajador como su usuario asociado
    $transaction = Yii::$app->db->beginTransaction();
    
    try {
        // Primero elimina el trabajador
        $trabajador->delete();
        
        // Luego elimina su usuario asociado
        Usuario::findOne($idUsuario)->delete();
        
        $transaction->commit();
        
        Yii::$app->session->setFlash('success', 'Trabajador eliminado exitosamente.');
    } catch (Exception $e) {
        $transaction->rollBack();
        
        Yii::$app->session->setFlash('error', 'Error al eliminar el trabajadory su usuario.');
    }
    
    return $this->redirect(['index']);
    }


    /**
     * Finds the Trabajador model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $idusuario Idusuario
     * @return Trabajador the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $idusuario)
    {
        if (($model = Trabajador::findOne(['id' => $id, 'idusuario' => $idusuario])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
