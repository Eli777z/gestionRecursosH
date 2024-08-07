<?php

namespace app\controllers;

use Yii;
use app\models\NotificacionMedico;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\NotFoundHttpException;
use app\models\ExpedienteMedico;
use app\models\AntecedenteGinecologico;
use app\models\AntecedenteHereditario;
use app\models\AntecedenteNoPatologico;
use app\models\AntecedentePatologico;
use app\models\AntecedenteObstrectico;
use app\models\CatAntecedenteHereditario;
use app\models\ExploracionFisica;
use app\models\Alergia;
use app\models\AntecedentePerinatal;
use app\models\PeriodoVacacional;
use app\models\InterrogatorioMedico;
use app\models\Documento;
use app\models\PeriodoVacacionalHistorial;
//use app\models\User;
use app\models\PermisoFueraTrabajo;
use app\models\Usuario;
use app\models\Notificacion;
use app\models\Empleado;
use app\models\CambiarContrasenaForm;
use app\models\CatDepartamentoSearch;
use app\models\DocumentoMedico;
use yii\helpers\ArrayHelper;
use app\models\JuntaGobierno;
use app\models\ParametroFormatoSearch;
use app\models\CatPuestoSearch;
use yii\widgets\Breadcrumbs;

class SiteController extends Controller

{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout', 'user', 'admin', 'medico'],
                'rules' => [
                    [
                       
                        'actions' => ['logout', 'admin', 'medico'],

                        'allow' => true,
                        'roles' => ['@'],
                        'matchCallback' => function ($rule, $action) {
                            return Usuario::isUserAdmin(Yii::$app->user->identity->id);
                        },
                    ],
                    [
                       'actions' => ['logout', 'user'],
                       'allow' => true,
                      
                       'roles' => ['@'],
                      
                       'matchCallback' => function ($rule, $action) {

                          return Usuario::isUserSimple(Yii::$app->user->identity->id);
                      },
                   ],

                   [
                    'actions' => ['logout', 'medico'],
                    'allow' => true,
                   
                    'roles' => ['@'],
                   
                    'matchCallback' => function ($rule, $action) {

                       return Usuario::isUserMedico(Yii::$app->user->identity->id);
                   },
                ],
                ],
            ],
    
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
           
            if ($user->nuevo == 4) {
                return $this->redirect(['usuario/cambiarcontrasena']);
            }
            elseif (Usuario::isUserAdmin($userId)) {
                return $this->redirect(["site/portalgestionrh"]);
            } elseif(Usuario:: isUserSimple($userId)) {
                return $this->redirect(["site/portalempleado"]);
            }else{
                return $this->redirect(["site/portal-medico"]);

            }
        }
        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $userId = Yii::$app->user->identity->id;
            $user = Usuario::findOne($userId);
            $userId = Yii::$app->user->identity->id;
            if  ($user->nuevo == 4) {
                return $this->redirect(["usuario/cambiarcontrasena"]);
            } else {
                if (Usuario::isUserAdmin($userId)) {
                    return $this->redirect(['site/portalgestionrh']);
                }elseif (Usuario::isUserSimple($userId)) {

                    return $this->redirect(["site/portalempleado"]);
                }
                else{

                    return $this->redirect(["site/portal-medico"]);

                }

               
            }
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    public function actionCambiarcontrasena()
{
    // Aquí asumimos que tienes un modelo de formulario para cambiar la contraseña.
    $model = new CambiarContrasenaForm();

    if ($model->load(Yii::$app->request->post()) && $model->changePassword()) {
        // Aquí manejamos la redirección después de que la contraseña ha sido cambiada.
        $userId = Yii::$app->user->identity->id;
        $user = Usuario::findOne($userId);

        if (Usuario::isUserAdmin($userId)) {
            return $this->redirect(['site/portalgestionrh']);
        } 
        elseif (Usuario::isUserMedico($userId)) {
            return $this->redirect(['site/portal-medico']);
        }
        elseif (Usuario::isUserSimple($userId)) {
            return $this->redirect(['site/portalempleado']);
        }
    }

    return $this->render('cambiarcontrasena', [
        'model' => $model,
    ]);
}

   
    

    public function actionViewNotificaciones()
    {
        $this->layout = "main-trabajador";

        $notificaciones = Notificacion::find()
            ->where(['usuario_id' => Yii::$app->user->identity->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    
        Notificacion::updateAll(['leido' => 1], ['usuario_id' => Yii::$app->user->identity->id]);
    
        return $this->render('view-notificaciones', [
            'notificaciones' => $notificaciones,
        ]);
    }


    public function actionConfiguracion()
    {
        // Para el GridView de Parámetro Formato
        $parametroFormatoSearchModel = new ParametroFormatoSearch();
        $parametroFormatoDataProvider = $parametroFormatoSearchModel->search(Yii::$app->request->queryParams);
    
        // Para el GridView de Cat Puesto
        $catPuestoSearchModel = new CatPuestoSearch();
        $catPuestoDataProvider = $catPuestoSearchModel->search(Yii::$app->request->queryParams);

    
        $catDepartamentoSearchModel = new CatDepartamentoSearch();
        $catDepartamentoDataProvider = $catDepartamentoSearchModel->search(Yii::$app->request->queryParams);
    
        return $this->render('configuracion', [
            'parametroFormatoSearchModel' => $parametroFormatoSearchModel,
            'parametroFormatoDataProvider' => $parametroFormatoDataProvider,
            'catPuestoSearchModel' => $catPuestoSearchModel,
            'catPuestoDataProvider' => $catPuestoDataProvider,
            'catDepartamentoSearchModel' => $catDepartamentoSearchModel,
            'catDepartamentoDataProvider' => $catDepartamentoDataProvider
        ]);
    }
    

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();
        return $this->redirect(['site/login']);
    }
    

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
    public function actionPortalgestionrh()
    {
        $solicitudesRecientes = \app\models\Solicitud::find()
            ->orderBy(['fecha_creacion' => SORT_DESC])
            ->limit(10) 
            ->all();
    
        return $this->render('portalgestionrh', [
            'solicitudesRecientes' => $solicitudesRecientes,
        ]);
    }
    

   
        public function actionPortalMedico()
    {
        $solicitudesRecientes = \app\models\Solicitud::find()
            ->where(['nombre_formato' => 'CITA MEDICA'])
            ->orderBy(['fecha_creacion' => SORT_DESC])
            ->limit(10)
            ->all();

        $usuarioId = Yii::$app->user->identity->id;
        $notificacionesCount = NotificacionMedico::countUnread($usuarioId);

        return $this->render('portal-medico', [
            'solicitudesRecientes' => $solicitudesRecientes,
            'notificacionesCount' => $notificacionesCount,
        ]);
    }

    
    

   


    public function actionPortaltrabajador()
    {
        
        $modelPermisoFueraTrabajo = new PermisoFueraTrabajo();
    
       
        return $this->render('portaltrabajador', [
            'modelPermisoFueraTrabajo' => $modelPermisoFueraTrabajo,
        ]);
    }


    

   

    public function actionGetEmpleadoFoto($filename)
    {
        $filePath = Yii::getAlias('@runtime') . '/empleados/' . $filename;
        if (file_exists($filePath)) {
            return Yii::$app->response->sendFile($filePath);
        } else {
            throw new \yii\web\NotFoundHttpException("File not found");
        }
    }
    public function actionPortalempleado()
    {
        $usuario = Yii::$app->user->identity;
        $modelEmpleado = $usuario->empleado;
        
        if (!$modelEmpleado) {
            throw new NotFoundHttpException('El empleado no existe.');
        }
    
        // Replicamos la lógica de la acción view del controlador empleado
        $documentos = $modelEmpleado->documentos;
        $documentoModel = new Documento();
        $documentoMedicoModel = new DocumentoMedico();
        $historial = PeriodoVacacionalHistorial::find()->where(['empleado_id' => $modelEmpleado->id])->all();
    
        $searchModel = new \app\models\SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['empleado_id' => $modelEmpleado->id]);
    
        $searchModelConsultas = new \app\models\ConsultaMedicaSearch();
        $dataProviderConsultas = $searchModelConsultas->search(Yii::$app->request->queryParams);
        $dataProviderConsultas->query->andWhere(['expediente_medico_id' => $modelEmpleado->expedienteMedico->id]);
    
        $expedienteMedico = $modelEmpleado->expedienteMedico;
        $antecedentes = AntecedenteHereditario::find()->where(['expediente_medico_id' => $expedienteMedico->id])->all();
        $catAntecedentes = CatAntecedenteHereditario::find()->all();
    
        $antecedentePatologico = AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedentePatologico(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedenteNoPatologico = AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteNoPatologico(['expediente_medico_id' => $expedienteMedico->id]);
        $ExploracionFisica = ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new ExploracionFisica(['expediente_medico_id' => $expedienteMedico->id]);
        $InterrogatorioMedico = InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new InterrogatorioMedico(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedentePerinatal = AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedentePerinatal(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedenteGinecologico = AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteGinecologico(['expediente_medico_id' => $expedienteMedico->id]);
        $AntecedenteObstrectico = AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new AntecedenteObstrectico(['expediente_medico_id' => $expedienteMedico->id]);
        $Alergia = Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]) ?: new Alergia(['expediente_medico_id' => $expedienteMedico->id]);
    
        return $this->render('portalempleado', [
            'model' => $modelEmpleado,
            'documentos' => $documentos,
            'documentoModel' => $documentoModel,
            'historial' => $historial,
            'searchModelConsultas' => $searchModelConsultas,
            'dataProviderConsultas' => $dataProviderConsultas,
            'searchModel' => $searchModel,
            'documentoMedicoModel' => $documentoMedicoModel,
            'dataProvider' => $dataProvider,
            'expedienteMedico' => $expedienteMedico,
            'antecedentes' => $antecedentes,
            'catAntecedentes' => $catAntecedentes,
            'antecedenteNoPatologico' => $antecedenteNoPatologico, 
            'ExploracionFisica' => $ExploracionFisica,
            'InterrogatorioMedico' => $InterrogatorioMedico,
            'AntecedentePerinatal' => $AntecedentePerinatal,
            'AntecedenteGinecologico' => $AntecedenteGinecologico,
            'AntecedenteObstrectico' => $AntecedenteObstrectico,
            'Alergia' => $Alergia,
            'antecedentePatologico' => $antecedentePatologico,
        ]);
    }
    
    
    
}
