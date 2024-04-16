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
use yii\web\UploadedFile;
use app\models\Expediente;
use yii\helpers\FileHelper;
use app\models\Infolaboral;
use app\models\Departamento;

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


    public function actionView($id)
    {
        $modelTrabajador = $this->findModel2($id);

        return $this->render('view', [
            'model' => $modelTrabajador,
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
        $user = new Usuario();
        $infolaboral = new Infolaboral();
        $departamento = new Departamento();
        $user->scenario = Usuario::SCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post()) && $infolaboral->load(Yii::$app->request->post()) && $departamento->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction();
            $user->username = $model->nombre . $model->apellido;
            $nombres = explode(" ", $model->nombre);
            $apellidos = explode(" ", $model->apellido);
            $usernameBase = strtolower($nombres[0][0] . $apellidos[0] . (isset($apellidos[1]) ? $apellidos[1][0] : ''));
            $user->username = $usernameBase;
            $counter = 1;
            while (Usuario::find()->where(['username' => $user->username])->exists()) {
                $user->username = $usernameBase . $counter;
                $counter++;
            }
            $user->password = 'contrasena';
            $user->status = 10;
            $user->nuevo = 4;
            $hash = Yii::$app->security->generatePasswordHash($user->password);
            $user->password = $hash;
            try {
                $infolaboral->iddepartamento = $departamento->nombre;

                
                if (!$infolaboral->save()) {
                    throw new \yii\db\Exception('Error al guardar Infolaboral');
                }

               
                $model->idinfolaboral = $infolaboral->id;

                if ($user->save()) {
                    $model->idusuario = $user->id;
                    $auth = \Yii::$app->authManager;
                    $authorRole = $auth->getRole('trabajador');
                    $auth->assign($authorRole, $user->id);

                   

                    $upload = UploadedFile::getInstance($model, 'foto');

                    if (is_object($upload)) {
                      
                        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $model->nombre . '_' . $model->apellido;
                        if (!is_dir($nombreCarpetaTrabajador)) {
                            mkdir($nombreCarpetaTrabajador, 0775, true);
                        }

                     
                        $nombreCarpetaUserProfile = $nombreCarpetaTrabajador . '/foto_Trabajador';
                        if (!is_dir($nombreCarpetaUserProfile)) {
                            mkdir($nombreCarpetaUserProfile, 0775, true);
                        }

                      
                        $upload_filename = $nombreCarpetaUserProfile . '/' . $upload->baseName . '.' . $upload->extension;
                        $upload->saveAs($upload_filename);
                        $model->foto = $upload_filename;
                    }



                    if ($model->save()) {

                        Yii::$app->mailer->compose()
                            ->setFrom('elitaev7@gmail.com')
                            ->setTo($model->email)
                            ->setSubject('Datos de acceso al sistema')
                            ->setTextBody("Nos comunicamos con usted, {$model->nombre}.\nAquí están tus datos de acceso:\nNombre de Usuario: {$user->username}\nContraseña: contrasena") // Reemplaza 'contrasena' con la contraseña generada si es diferente
                            ->send();
                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Trabajador y usuario creados con éxito.");
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                    //
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }
        return $this->render('create', [
            'model' => $model,
            'user' => $user,
            'infolaboral' => $infolaboral,
            'departamento' => $departamento,
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
    public function actionUpdate($id)
    {
        $model = $this->findModel2($id);
        $user = Usuario::findOne($model->idusuario); // Encuentra el usuario asociado
        $previous_photo = $model->foto;

        if ($model->load(Yii::$app->request->post()) && $user->load(Yii::$app->request->post())) {
            $transaction = Yii::$app->db->beginTransaction(); 

            try {
                $user->username = $user->getOldAttribute('username');
                $user->status = $user->getOldAttribute('status');
                if (!empty($user->password)) {
                    $hash = Yii::$app->security->generatePasswordHash($user->password);
                    $user->password = $hash;
                } else {

                    $user->password = $user->getOldAttribute('password');
                }

                if ($user->save()) {
                    $upload = UploadedFile::getInstance($model, 'foto');
                    if (is_object($upload)) {
                        
                        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $model->nombre . '_' . $model->apellido;
                        if (!is_dir($nombreCarpetaTrabajador)) {
                            mkdir($nombreCarpetaTrabajador, 0775, true);
                        }

                        $nombreCarpetaUserProfile = $nombreCarpetaTrabajador . '/foto_Trabajador';
                        if (!is_dir($nombreCarpetaUserProfile)) {
                            mkdir($nombreCarpetaUserProfile, 0775, true);
                        }

                     
                        $upload_filename = $nombreCarpetaUserProfile . '/' . $upload->baseName . '.' . $upload->extension;

                     
                        if ($upload->saveAs($upload_filename)) {
                            
                            if ($previous_photo && $previous_photo !== $upload_filename && file_exists($previous_photo)) {
                                @unlink($previous_photo);
                            }
                            $model->foto = $upload_filename;
                        }
                    } else {
                        $model->foto = $previous_photo; 
                    }


                    if ($model->save()) {
                        $transaction->commit(); 
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        throw new \Exception('Error al guardar el modelo Trabajador');
                    }
                } else {
                    throw new \Exception('Error al guardar el modelo Usuario');
                }
            } catch (\Exception $e) {
                $transaction->rollBack(); 
                Yii::$app->session->setFlash('error', $e->getMessage());
            }
        }
        $user->password = '';
        return $this->render('update', [
            'model' => $model,
            'user' => $user, 
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

        $idUsuario = $trabajador->idusuario;

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($trabajador->expedientes as $expediente) {

                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/trabajadores/' . $trabajador->nombre . '_' . $trabajador->apellido;
                if (is_dir($nombreCarpetaTrabajador)) {
                    FileHelper::removeDirectory($nombreCarpetaTrabajador);
                }
                $expediente->delete();
            }

            
            $trabajador->delete();

            $usuario = Usuario::findOne($idUsuario);
            if ($usuario) {
                $usuario->delete();
            }

            $transaction->commit();

            Yii::$app->session->setFlash('success', 'Trabajador, expedientes, carpeta asociada y usuario eliminados exitosamente.');
        } catch (Exception $e) {
            $transaction->rollBack();

            Yii::$app->session->setFlash('error', 'Error al eliminar el trabajador, sus expedientes, la carpeta asociada y el usuario.');
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


    public function actionFotoTrabajador($id)
    {
        $model = $this->findModel2($id); 

        if (file_exists($model->foto) && @getimagesize($model->foto)) {
            return Yii::$app->response->sendFile($model->foto);
        } else {
            throw new \yii\web\NotFoundHttpException('La imagen no existe.');
        }
    }

    protected function findModel2($id)
    {
        if (($model = Trabajador::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
    protected function findModel($id, $idusuario)
    {
        if (($model = Trabajador::findOne(['id' => $id, 'idusuario' => $idusuario])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }


    
    //public function actionView($id, $idusuario)
    //{
    //  return $this->render('view', [
    //    'model' => $this->findModel($id, $idusuario),
    //]);
    //}


    public function actionDesactivarUsuario($id)
    {
        $trabajador = Trabajador::findOne($id);

        if (!$trabajador) {
            throw new NotFoundHttpException('El trabajador no existe.');
        }

        $usuarioId = $trabajador->idusuario;

        $usuario = Usuario::findOne($usuarioId);

        if (!$usuario) {
            throw new NotFoundHttpException('El usuario asociado al trabajador no existe.');
        }

        $usuario->status = 0;

        if ($usuario->save()) {
            Yii::$app->session->setFlash('success', 'El usuario ha sido desactivado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al desactivar el usuario.');
        }

        return $this->redirect(['view', 'id' => $id]);
    }

    public function actionToggleActivation($id)
    {
        $model = $this->findModel2($id);
        
        // Cambia el estado del usuario
        if ($model->idusuario0->status == 10) {
            $model->idusuario0->status = 0; // Inactiva el usuario
        } else {
            $model->idusuario0->status = 10; // Activa el usuario
        }

        if ($model->idusuario0->save()) {
            Yii::$app->session->setFlash('success', 'El estado del usuario se ha cambiado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Se produjo un error al cambiar el estado del usuario.');
        }

        return $this->redirect(['index']);
    }

}


