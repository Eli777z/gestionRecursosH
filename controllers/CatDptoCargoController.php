<?php

namespace app\controllers;

use Yii;
use app\models\CatDptoCargo;
use app\models\CatDptoCargoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\InformacionLaboral;
use app\models\JuntaGobierno;

/**
 * CatDptoCargoController implements the CRUD actions for CatDptoCargo model.
 */
class CatDptoCargoController extends Controller
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
     * Lists all CatDptoCargo models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new CatDptoCargoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatDptoCargo model.
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
     * Creates a new CatDptoCargo model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatDptoCargo();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['create']);
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing CatDptoCargo model.
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
     * Deletes an existing CatDptoCargo model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
    
        // Establecer a NULL el campo `junta_gobierno_id` en la tabla `informacion_laboral`
        InformacionLaboral::updateAll(['cat_dpto_cargo_id' => null], ['cat_dpto_cargo_id' => $id]);
      //  JuntaGobierno::updateAll(['cat_dpto_cargo_id' => null], ['cat_dpto_cargo_id' => $id]);


        if ($model->delete()) {
            Yii::$app->session->setFlash('success', 'Empleado eliminado de junta gobierno.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al eliminar el registro.');
        }
    
       

        return $this->redirect(['index']);
    }


    /**
     * Finds the CatDptoCargo model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return CatDptoCargo the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatDptoCargo::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
