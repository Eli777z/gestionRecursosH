<?php

namespace app\controllers;

use Yii;
use app\models\Aviso;
use app\models\AvisoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;
/**
 * AvisoController implements the CRUD actions for Aviso model.
 */
class AvisoController extends Controller
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
     * Lists all Aviso models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new AvisoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Aviso model.
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
     * Creates a new Aviso model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
   
     //FUNCIÓN QUE PERMITE EXTRAER Y MOSTRAR LA IMAGEN DEL REGISTRO
     public function actionVerImagen($nombre)
{
    //OBTIENE LA RUTA Y BUSCA LA IMAGEN
    $filePath = Yii::getAlias('@runtime/imagenes_avisos/' . $nombre);

    if (file_exists($filePath)) {
        return Yii::$app->response->sendFile($filePath);
    } else {
        throw new \yii\web\NotFoundHttpException('La imagen no existe.');
    }
}

//SE ENCARGA DE MOSTRAR TODO LOS REGISTROS DE AVISOS
public function actionCarruselAvisos()
{
    $avisos = \app\models\Aviso::find()->all();

    return $this->render('carrusel-avisos', [
        'avisos' => $avisos,
    ]);
}


    //FUNCION QUE CREA EL REGISTRO DEL AVISO Y SE ENCARGA DE LA SUBIDA DE IMAGENES
    public function actionCreate()
    {
        $model = new Aviso();
    
        if ($model->load(Yii::$app->request->post())) {
            // Manejar la subida de la imagen
            $image = UploadedFile::getInstance($model, 'imagen');
    
            if ($image) {
                // Crear la carpeta si no existe
                $directory = Yii::getAlias('@runtime/imagenes_avisos');
                if (!is_dir($directory)) {
                    FileHelper::createDirectory($directory);
                }
    
                // Generar un nombre único para la imagen
                $fileName = uniqid() . '.' . $image->extension;
                $filePath = $directory . '/' . $fileName;
    
                // Guardar la imagen en la carpeta
                if ($image->saveAs($filePath)) {
                    // Asigna el nombre del archivo al modelo
                    $model->imagen = $fileName;
                } else {
                    // Manejar el error si la imagen no se puede guardar
                    $model->addError('imagen', 'No se pudo subir la imagen.');

                }
            }
    
            // Guardar el modelo si no hay errores
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Aviso Publicado.');

                return $this->redirect(['//site/portalgestionrh']);
            }
            else{

                Yii::$app->session->setFlash('error', 'No se puedo crear el aviso.');

            }
        }
    
        return $this->render('create', [
            'model' => $model,
        ]);
    }
    
    /**
     * Updates an existing Aviso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
 
    
    /**
     * Updates an existing Aviso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
  
    
    /**
     * Updates an existing Aviso model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
    
        // Guardar la imagen actual para referencia si no se sube una nueva
        $currentImage = $model->imagen;
    
        // Manejar la carga del formulario
        if ($model->load(Yii::$app->request->post())) {
            // Manejar la subida de la nueva imagen si existe
            $image = UploadedFile::getInstance($model, 'imagen');
    
            if ($image) {
                // Crear la carpeta si no existe
                $directory = Yii::getAlias('@runtime/imagenes_avisos');
                if (!is_dir($directory)) {
                    FileHelper::createDirectory($directory);
                }
    
                // Generar un nombre único para la imagen
                $fileName = uniqid() . '.' . $image->extension;
                $filePath = $directory . '/' . $fileName;
    
                // Guardar la nueva imagen en la carpeta
                if ($image->saveAs($filePath)) {
                    // Eliminar la imagen anterior si existe
                    if ($currentImage && file_exists($directory . '/' . $currentImage)) {
                        unlink($directory . '/' . $currentImage);
                    }
                    // Asigna el nombre del archivo al modelo
                    $model->imagen = $fileName;
                } else {
                    // Manejar el error si la imagen no se puede guardar
                    $model->addError('imagen', 'No se pudo subir la imagen.');
                    // Si hubo un error, se mantendrá la imagen actual
                    $model->imagen = $currentImage;
                }
            } else {
                // Si no se subió una nueva imagen, mantener la imagen actual
                $model->imagen = $currentImage;
            }
    
            // Guardar el modelo si no hay errores
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Aviso Actualizado');

                return $this->redirect(['//site/portalgestionrh']);
            }
            else{

                Yii::$app->session->setFlash('error', 'No se puedo actualizar el aviso .');

            }
        }
    
        return $this->render('update', [
            'model' => $model,
        ]);
    }
    
    /**
     * Deletes an existing Aviso model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */

     //FUNCIÓN PARA ELMINAR EL REGISTRO ASOCIADO
  public function actionDelete($id)
{
    $model = $this->findModel($id);

    if ($model->delete()) {
        // Puedes agregar una notificación de éxito aquí si lo deseas
        Yii::$app->session->setFlash('success', 'Se quito el aviso correctamente.');

    } else {
        // Manejar el error si el modelo no se puede eliminar
        Yii::$app->session->setFlash('error', 'No se puedo eliminar el aviso .');

    }

    return $this->redirect(['//site/portalgestionrh']);

}



    /**
     * Finds the Aviso model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Aviso the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Aviso::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException(Yii::t('app', 'The requested page does not exist.'));
    }
}
