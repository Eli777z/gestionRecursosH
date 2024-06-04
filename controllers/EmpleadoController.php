<?php

namespace app\controllers;

use Yii;
use app\models\Empleado;
use app\models\EmpleadoSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models\Usuario;
use yii\web\UploadedFile;
use app\models\InformacionLaboral;
use app\models\CatDepartamento;
use yii\helpers\FileHelper;
use yii\db\Exception;
use app\models\Documento;
use app\models\JuntaGobierno;
use yii\helpers\Json;
use app\models\UploadForm;
use app\models\Vacaciones;
use yii\data\ArrayDataProvider;
use app\models\PeriodoVacacional;
use app\models\SegundoPeriodoVacacional;
use yii\helpers\Url;
use app\models\CatTipoContrato;
use app\models\PeriodoVacacionalHistorial;

/**
 * EmpleadoController implements the CRUD actions for Empleado model.
 */
class EmpleadoController extends Controller
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
     * Lists all Empleado models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new EmpleadoSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $juntaGobiernoModel = new JuntaGobierno();

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'juntaGobiernoModel' => $juntaGobiernoModel
        ]);
    }

    /**
     * Displays a single Empleado model.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        $modelEmpleado = $this->findModel2($id);

        $documentos = $modelEmpleado->documentos;

        $documentoModel = new Documento();
        $historial = PeriodoVacacionalHistorial::find()->where(['empleado_id' => $modelEmpleado->id])->all();

        $searchModel = new \app\models\SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['empleado_id' => $id]);
        return $this->render('view', [
            'model' => $modelEmpleado,
            'documentos' => $documentos,
            'documentoModel' => $documentoModel,
            'historial' => $historial,
            'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Creates a new Empleado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Empleado();
        $usuario = new Usuario();
        $informacion_laboral = new InformacionLaboral();
        $vacaciones = new Vacaciones();
        $periodoVacacional = new PeriodoVacacional();
        $segundoPeriodoVacacional = new SegundoPeriodoVacacional();
        $juntaGobiernoModel = new JuntaGobierno();

        $usuario->scenario = Usuario::SCENARIO_CREATE;

        if ($model->load(Yii::$app->request->post()) && $usuario->load(Yii::$app->request->post()) && $informacion_laboral->load(Yii::$app->request->post()) && $juntaGobiernoModel->load(Yii::$app->request->post())) { 
            $transaction = Yii::$app->db->beginTransaction();
            $usuario->username = $model->nombre . $model->apellido;
            $nombres = explode(" ", $model->nombre);
            $apellidos = explode(" ", $model->apellido);
            $usernameBase = strtolower($nombres[0][0] . $apellidos[0] . (isset($apellidos[1]) ? $apellidos[1][0] : ''));
            $usuario->username = $usernameBase;
            $counter = 1;
            while (Usuario::find()->where(['username' => $usuario->username])->exists()) {
                $usuario->username = $usernameBase . $counter;
                $counter++;
            }
            $usuario->password = 'contrasena';
            $usuario->status = 10;
            $usuario->nuevo = 4;
            $hash = Yii::$app->security->generatePasswordHash($usuario->password);
            $usuario->password = $hash;

            try {
                $totalDiasVacaciones = $this->calcularDiasVacaciones($informacion_laboral->fecha_ingreso, $informacion_laboral->cat_tipo_contrato_id);
                $diasPorPeriodo = ceil($totalDiasVacaciones / 2);

              
                $periodoVacacional->dias_vacaciones_periodo = $diasPorPeriodo;
                if (!$periodoVacacional->save()) {
                    throw new \yii\db\Exception('Error al guardar PeriodoVacacional');
                }

                $segundoPeriodoVacacional->dias_vacaciones_periodo = $totalDiasVacaciones - $diasPorPeriodo; 
                if (!$segundoPeriodoVacacional->save()) {
                    throw new \yii\db\Exception('Error al guardar SegundoPeriodoVacacional');
                }

                $vacaciones->periodo_vacacional_id = $periodoVacacional->id;
                $vacaciones->segundo_periodo_vacacional_id = $segundoPeriodoVacacional->id;
                $vacaciones->total_dias_vacaciones = $totalDiasVacaciones;
                if (!$vacaciones->save()) {
                    throw new \yii\db\Exception('Error al guardar Vacaciones');
                }

                $informacion_laboral->vacaciones_id = $vacaciones->id;
                if (!$informacion_laboral->save()) {
                    throw new \yii\db\Exception('Error al guardar InformacionLaboral');
                }

                $model->informacion_laboral_id = $informacion_laboral->id;
                if ($usuario->save()) {
                    
                    $model->usuario_id = $usuario->id;
                    $rol = ($usuario->rol == 1) ? 'empleado' : 'administrador';

                    $auth = Yii::$app->authManager;
                    $authorRole = $auth->getRole($rol);
                    $auth->assign($authorRole, $usuario->id);

                    $upload = UploadedFile::getInstance($model, 'foto');
                    if (is_object($upload)) {
                        $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->nombre . '_' . $model->apellido;
                        if (!is_dir($nombreCarpetaTrabajador)) {
                            mkdir($nombreCarpetaTrabajador, 0775, true);
                        }
                        $nombreCarpetaUsuarioProfile = $nombreCarpetaTrabajador . '/foto_empleado';
                        if (!is_dir($nombreCarpetaUsuarioProfile)) {
                            mkdir($nombreCarpetaUsuarioProfile, 0775, true);
                        }
                        $upload_filename = $nombreCarpetaUsuarioProfile . '/' . $upload->baseName . '.' . $upload->extension;
                        $upload->saveAs($upload_filename);
                        $model->foto = $upload_filename;
                    }

                    if ($model->save()) {
                       
                        $departamento = CatDepartamento::findOne($informacion_laboral->cat_departamento_id);
                        if ($departamento) {
                            $informacion_laboral->cat_direccion_id = $departamento->cat_direccion_id;
                        
                            if (!empty($juntaGobiernoModel->nivel_jerarquico) && $juntaGobiernoModel->nivel_jerarquico !== 'Comun') { // Validación adicional
                                $juntaGobiernoModel->empleado_id = $model->id;
                                $juntaGobiernoModel->cat_departamento_id = $informacion_laboral->cat_departamento_id;
                                $juntaGobiernoModel->cat_direccion_id = $informacion_laboral->cat_direccion_id;
                        
                                if (!$juntaGobiernoModel->save()) {
                                    $transaction->rollBack();
                                    throw new \yii\db\Exception('Error al guardar JuntaGobierno: ' . json_encode($juntaGobiernoModel->errors));
                                }
                            }
                        
                            if (!$informacion_laboral->save()) {
                                $transaction->rollBack();
                                throw new \yii\db\Exception('Error al guardar InformacionLaboral: ' . json_encode($informacion_laboral->errors));
                            }
                        }
                
                        Yii::$app->mailer->compose()
                            ->setFrom('elitaev7@gmail.com')
                            ->setTo($model->email)
                            ->setSubject('Datos de acceso al sistema')
                            ->setTextBody("Nos comunicamos con usted, {$model->nombre}.\nAquí están tus datos de acceso:\nNombre de Usuario: {$usuario->username}\nContraseña: contrasena") // Reemplaza 'contrasena' con la contraseña generada si es diferente
                            ->send();

                        $transaction->commit();
                        Yii::$app->session->setFlash('success', "Trabajador y usuario creados con éxito.");
                        return $this->redirect(['view', 'id' => $model->id]);
                    } else {
                        $transaction->rollBack();
                    }
                }
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            }
        }

        return $this->render('create', [
            'model' => $model,
            'usuario' => $usuario,
            'informacion_laboral' => $informacion_laboral,
            'juntaGobiernoModel' => $juntaGobiernoModel

        ]);
    }


    private function calcularDiasVacaciones($fechaIngreso, $tipoContratoId)
    {
        $fechaIngreso = new \DateTime($fechaIngreso);
        $fechaActual = new \DateTime();
        $intervalo = $fechaIngreso->diff($fechaActual);
        $aniosTrabajados = $intervalo->y;

        $tipoContrato = CatTipoContrato::findOne($tipoContratoId);

        if (!$tipoContrato) {
            return 0; 
        }

        switch ($tipoContrato->nombre_tipo) {
            case 'Eventual':
                return $this->calcularVacacionesEventual($aniosTrabajados);
            case 'Confianza':
                return $this->calcularVacacionesConfianza($aniosTrabajados);
            case 'Sindicalizado':
                return $this->calcularVacacionesSindicalizado($aniosTrabajados);
            default:
                return 0;
        }
    }

    private function calcularVacacionesEventual($anios)
    {
        if ($anios < 1) {
            return 0;
        } elseif ($anios == 1) {
            return 12;
        } elseif ($anios == 2) {
            return 14;
        } elseif ($anios == 3) {
            return 16;
        } elseif ($anios == 4) {
            return 18;
        } elseif ($anios == 5) {
            return 20;
        } elseif ($anios <= 10) {
            return 22;
        } elseif ($anios <= 15) {
            return 24;
        } else {
            return 24 + floor(($anios - 15) / 5) * 2;
        }
    }

    private function calcularVacacionesConfianza($anios)
    {
        if ($anios < 1) {
            return 0;
        } elseif ($anios <= 10) {
            return 20;
        } elseif ($anios <= 15) {
            return 22;
        } else {
            return 22 + floor(($anios - 10) / 5) * 2;
        }
    }

    private function calcularVacacionesSindicalizado($anios)
    {
        if ($anios < 1) {
            return 0;
        } elseif ($anios <= 10) {
            return 22;
        } elseif ($anios <= 15) {
            return 24;
        } else {
            return 24 + floor(($anios - 10) / 5) * 2;
        }
    }

    /**
     * Updates an existing Empleado model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id, $usuario_id, $informacion_laboral_id)
    {
        $model = $this->findModel($id, $usuario_id, $informacion_laboral_id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id, 'usuario_id' => $model->usuario_id, 'informacion_laboral_id' => $model->informacion_laboral_id]);
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Empleado model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id, $usuario_id)
    {
        $empleado = $this->findModel3($id, $usuario_id);
        $usuario_id = $empleado->usuario_id;
        $informacion_laboral_id = $empleado->informacion_laboral_id;

        // $junta_gobierno_id = $empleado->informacionLaboral->junta_gobierno_id;
        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($empleado->documentos as $documento) {
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
                if (is_dir($nombreCarpetaTrabajador)) {
                    FileHelper::removeDirectory($nombreCarpetaTrabajador);
                }
                $documento->delete();
            }

          
            //  $empleado->informacionLaboral->delete();
            $empleado->delete();

       
            $usuario = Usuario::findOne($usuario_id);
            if ($usuario) {
                $usuario->delete();
            }

            // $junta_gobierno = JuntaGobierno::findOne($junta_gobierno_id);
            //if ($junta_gobierno) {
            //  $junta_gobierno->delete();
            // }

            $informacion_laboral = InformacionLaboral::findOne($informacion_laboral_id);
            if ($informacion_laboral) {
                $informacion_laboral->delete();
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
     * Finds the Empleado model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @param int $usuario_id Usuario ID
     * @param int $informacion_laboral_id Informacion Laboral ID
     * @return Empleado the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id, $usuario_id, $informacion_laboral_id)
    {
        if (($model = Empleado::findOne(['id' => $id, 'usuario_id' => $usuario_id, 'informacion_laboral_id' => $informacion_laboral_id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel2($id)
    {
        if (($model = Empleado::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function findModel3($id, $usuario_id)
    {
        if (($model = Empleado::findOne(['id' => $id, 'usuario_id' => $usuario_id,])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }




 
    

    public function actionDesactivarUsuario($id)
    {
        $empleado = Empleado::findOne($id);

        if (!$empleado) {
            throw new NotFoundHttpException('El trabajador no existe.');
        }

        $usuario_id = $empleado->usuario_id;

        $usuario = Usuario::findOne($usuario_id);

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

        if ($model->usuario->status == 10) {
            $model->usuario->status = 0; // Inactiva el usuario
        } else {
            $model->usuario->status = 10; // Activa el usuario
        }

        if ($model->usuario->save()) {
            Yii::$app->session->setFlash('success', 'El estado del usuario se ha cambiado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Se produjo un error al cambiar el estado del usuario.');
        }

        return $this->redirect(['index']);
    }

    public function actionSaveEditable($id)
    {
        if (Yii::$app->request->isAjax) {
            $model = $this->findModel2($id);
            $attribute = Yii::$app->request->post('name');
            $value = Yii::$app->request->post('value');

            $model->$attribute = $value;

            if ($model->save()) {
                return json_encode(['status' => 'success']);
            } else {
                return json_encode(['status' => 'error', 'msg' => 'Error al guardar']);
            }
        }
    }

    public function actionFotoEmpleado($id)
{
    $model = $this->findModel2($id);

    if (file_exists($model->foto) && @getimagesize($model->foto)) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $model->foto);
        finfo_close($finfo);

        Yii::$app->response->headers->set('Content-Type', $mimeType);
        return Yii::$app->response->sendFile($model->foto);
    } else {
        throw new \yii\web\NotFoundHttpException('La imagen no existe.');
    }
}

public function actionCambio($id)
{
    $model = $this->findModel2($id);

    if (Yii::$app->request->isPost) {
        $uploadedFile = UploadedFile::getInstance($model, 'foto');

        if ($uploadedFile && $model->validate(['foto'])) {
            $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->nombre . '_' . $model->apellido;
            if (!is_dir($nombreCarpetaTrabajador)) {
                mkdir($nombreCarpetaTrabajador, 0775, true);
            }

            $nombreCarpetaUsuarioProfile = $nombreCarpetaTrabajador . '/foto_empleado';
            if (!is_dir($nombreCarpetaUsuarioProfile)) {
                mkdir($nombreCarpetaUsuarioProfile, 0775, true);
            }

            if ($model->foto && file_exists(Yii::getAlias('@runtime') . $model->foto)) {
                unlink(Yii::getAlias('@runtime') . $model->foto);
            }

            $rutaFoto = $nombreCarpetaUsuarioProfile . '/' . $uploadedFile->baseName . '.' . $uploadedFile->extension;
            if ($uploadedFile->saveAs($rutaFoto)) {
                $model->foto = $rutaFoto; 
                if ($model->save(false)) {
                    Yii::$app->session->setFlash('success', 'La foto del trabajador se actualizó correctamente.');
                } else {
                    Yii::$app->session->setFlash('error', 'Error al guardar la foto: ' . json_encode($model->errors));
                }
            } else {
                Yii::$app->session->setFlash('error', 'Error al guardar la imagen.');
            }
        } else {
            Yii::$app->session->setFlash('error', 'Error al validar la imagen: ' . json_encode($model->errors));
        }
    }

    return $this->redirect(['view', 'id' => $id]);
}

    

    public function actionActualizarInformacion($id)
    {
        $model = $this->findModel2($id);

        if ($model->load(Yii::$app->request->post())) {
            $fechaNacimiento = new \DateTime($model->fecha_nacimiento);
            $hoy = new \DateTime();
            $diferencia = $hoy->diff($fechaNacimiento);
            $model->edad = $diferencia->y; 

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Los cambios de la información personal han sido actualizados correctamente.');

                $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_personal';
                return $this->redirect($url);
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información del trabajador.');
                $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_personal';
                return $this->redirect($url);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }


    public function actionActualizarInformacionContacto($id)
    {
        $model = $this->findModel2($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Yii::$app->session->setFlash('success', 'La información del trabajador ha sido actualizada correctamente.');
            Yii::$app->session->setFlash('success', 'Los cambios de la información de contacto han sido actualizados correctamente.');

            $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_contacto';
            return $this->redirect($url);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información del trabajador.');
            $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_contacto';
            return $this->redirect($url);
        }
    }



    public function actionActualizarInformacionLaboral($id)
    {
        $model = $this->findModel2($id);
        $informacion_laboral = InformacionLaboral::findOne($model->informacion_laboral_id);

        if ($informacion_laboral->load(Yii::$app->request->post()) && $informacion_laboral->save()) {
            $vacaciones = Vacaciones::findOne($informacion_laboral->vacaciones_id);
            $totalDiasVacaciones = $this->calcularDiasVacaciones($informacion_laboral->fecha_ingreso, $informacion_laboral->cat_tipo_contrato_id);
            $vacaciones->total_dias_vacaciones = $totalDiasVacaciones;

            if ($vacaciones->save()) {
                Yii::$app->session->setFlash('success', 'Los cambios de la información laboral han sido actualizados correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al actualizar los días de vacaciones.');
            }

            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_laboral';
            return $this->redirect($url);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información laboral del trabajador.');
            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_laboral';
            return $this->redirect($url);
        }
    }





    public function actionActualizarPrimerPeriodo($id)
    {
        $model = $this->findModel2($id);
        $periodoVacacional = $model->informacionLaboral->vacaciones->periodoVacacional;

        if ($periodoVacacional->load(Yii::$app->request->post())) {
            if ($periodoVacacional->dateRange) {
                list($fechaInicio, $fechaFin) = explode(' a ', $periodoVacacional->dateRange);
                $fechaInicio = new \DateTime($fechaInicio);
                $fechaFin = new \DateTime($fechaFin);
                $diasSeleccionados = $fechaInicio->diff($fechaFin)->days + 1;

                $diasDisponibles = $periodoVacacional->dias_vacaciones_periodo - $diasSeleccionados;
                $periodoVacacional->dias_disponibles = $diasDisponibles;
            }

            if ($periodoVacacional->save()) {
                Yii::$app->session->setFlash('success', 'El primer periodo ha sido actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información de vacaciones.');
            }

            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_vacaciones';
            return $this->redirect($url);
        }

        $url = Url::to(['view', 'id' => $model->id]) . '#informacion_vacaciones';
        return $this->redirect($url);
    }



    public function actionActualizarSegundoPeriodo($id)
    {
        $model = $this->findModel2($id);
        $periodoVacacional = $model->informacionLaboral->vacaciones->segundoPeriodoVacacional;

        if ($periodoVacacional->load(Yii::$app->request->post())) {
            if ($periodoVacacional->dateRange) {
                list($fechaInicio, $fechaFin) = explode(' a ', $periodoVacacional->dateRange);
                $fechaInicio = new \DateTime($fechaInicio);
                $fechaFin = new \DateTime($fechaFin);
                $diasSeleccionados = $fechaInicio->diff($fechaFin)->days + 1;

                $diasDisponibles = $periodoVacacional->dias_vacaciones_periodo - $diasSeleccionados;
                $periodoVacacional->dias_disponibles = $diasDisponibles;
            }

            if ($periodoVacacional->save()) {
                Yii::$app->session->setFlash('success', 'El primer periodo ha sido actualizado correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información de vacaciones.');
            }

            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_vacaciones';
            return $this->redirect($url);
        }

        $url = Url::to(['view', 'id' => $model->id]) . '#informacion_vacaciones';
        return $this->redirect($url);
    }




    public function actionDatosJuntaGobierno($direccion_id)
    {
        $datosJuntaGobierno = JuntaGobierno::find()
            ->where(['nivel_jerarquico' => 'Jefe de unidad'])
            ->andWhere(['cat_direccion_id' => $direccion_id])
            ->all();

        $result = [];
        foreach ($datosJuntaGobierno as $juntaGobierno) {
            $result[] = [
                'id' => $juntaGobierno->id,
                'text' => $juntaGobierno->empleado->nombre . ' ' . $juntaGobierno->empleado->apellido,
            ];
        }

        return Json::encode(['results' => $result]);
    }




    public function actionFormatos()
    {
        $model = new UploadForm();

        if (Yii::$app->request->isPost) {
            $selectedName = Yii::$app->request->post('UploadForm')['selectedName'];
            $model->selectedName = $selectedName;

            $model->load(Yii::$app->request->post());
            $model->file = UploadedFile::getInstance($model, 'file');
            if ($model->upload()) {
                Yii::$app->session->setFlash('uploadSuccess', 'Archivo subido exitosamente.');
                return $this->redirect(['formatos']);
            }
        }

        $files = glob(Yii::getAlias('@app/templates/*'));
        $fileData = [];
        foreach ($files as $file) {
            $fileData[] = [
                'filename' => basename($file),
                'path' => $file,
            ];
        }

        $dataProvider = new ArrayDataProvider([
            'allModels' => $fileData,
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('formatos', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }



    public function actionDeleteFormato()
    {
        $filename = Yii::$app->request->post('filename');
        $filePath = Yii::getAlias('@app/templates/') . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
            Yii::$app->session->setFlash('deleteSuccess', 'Archivo eliminado exitosamente.');
        } else {
            Yii::$app->session->setFlash('deleteError', 'El archivo no existe.');
        }
        return $this->redirect(['formatos']);
    }
    public function actionDownloadFormato($filename)
    {
        $filePath = Yii::getAlias('@app/templates/') . $filename;
        if (file_exists($filePath)) {
            Yii::$app->response->sendFile($filePath)->send();
        } else {
            Yii::$app->session->setFlash('error', 'El archivo no existe.');
            return $this->redirect(['formatos']);
        }
    }
}
