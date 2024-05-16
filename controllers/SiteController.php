<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
//use app\models\User;
use app\models\PermisoFueraTrabajo;
use app\models\Usuario;
use app\models\Notificacion;
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
                'only' => ['logout', 'user', 'admin'],
                'rules' => [
                    [
                       
                        'actions' => ['logout', 'admin'],

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
            } else {
                return $this->redirect(["site/portalempleado"]);
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
                }else{

                    return $this->redirect(["site/portalempleado"]);
                }

               
            }
        } else {
            $model->password = '';
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

   
    

    public function actionViewNotificaciones()
    {
        $notificaciones = Notificacion::find()
            ->where(['usuario_id' => Yii::$app->user->identity->id])
            ->orderBy(['created_at' => SORT_DESC])
            ->all();
    
        // Marcar todas las notificaciones como leídas
        Notificacion::updateAll(['leido' => 1], ['usuario_id' => Yii::$app->user->identity->id]);
    
        return $this->render('view-notificaciones', [
            'notificaciones' => $notificaciones,
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

        return $this->goHome();
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
        return $this->render('portalgestionrh');
    }

    public function actionPortalempleado()
    {
        return $this->render('portalempleado');
    }

   


    public function actionPortaltrabajador()
    {
        
        $modelPermisoFueraTrabajo = new PermisoFueraTrabajo();
    
       
        return $this->render('portaltrabajador', [
            'modelPermisoFueraTrabajo' => $modelPermisoFueraTrabajo,
        ]);
    }


    

    public function actionCambiarcontrasena()
    {
        return $this->render('usuario/cambiarcontrasena');
    }
    public function actionSetActiveTab()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $activeTabIndex = $request->post('activeTabIndex');
            Yii::$app->session->set('activeTabIndex', $activeTabIndex);
            return true;
        }
        return false;
    }
    
}
