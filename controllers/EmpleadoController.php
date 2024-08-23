<?php

namespace app\controllers;

use app\models\Alergia;
use app\models\AntecedenteGinecologico;
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
use app\models\ExpedienteMedico;
use app\models\CatAntecedenteHereditario;
use app\models\AntecedenteHereditario;
use app\models\AntecedentePatologico;
use app\models\AntecedenteNoPatologico;
use app\models\AntecedenteObstrectico;
use app\models\AntecedentePerinatal;
use app\models\DocumentoMedico;
use app\models\ExploracionFisica;
use app\models\InterrogatorioMedico;
use app\models\CatPuesto;
use yii\helpers\ArrayHelper;
     use yii2tech\csvgrid\CsvGrid;
     use PhpOffice\PhpSpreadsheet\Spreadsheet;
     use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
     use PhpOffice\PhpSpreadsheet\IOFactory;
  
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
     
         // Obtener el id del departamento del empleado actual
         $idDepartamentoEmpleadoActual = Yii::$app->user->identity->empleado->informacionLaboral->cat_departamento_id;
         $idDireccionEmpleadoActual = Yii::$app->user->identity->empleado->informacionLaboral->cat_direccion_id;
     
         // Configurar el dataProvider para mostrar empleados del mismo departamento si tiene el permiso correspondiente
         if (Yii::$app->user->can('ver-empleados-departamento')) {
             $dataProvider->query->andWhere(['il.cat_departamento_id' => $idDepartamentoEmpleadoActual]);
         } else if (Yii::$app->user->can('ver-empleados-direccion')) {
             $dataProvider->query->andWhere(['il.cat_direccion_id' => $idDireccionEmpleadoActual]);
         }
     
         // Obtener los empleados filtrados para los filtros Select2
         $empleadosFiltrados = $dataProvider->query->all();
     
         // Obtener nombres de empleados únicos
         $nombresEmpleados = \yii\helpers\ArrayHelper::map($empleadosFiltrados, 'id', function ($model) {
             return $model->apellido . ' ' . $model->nombre;
         });
     
         // Obtener números de empleados únicos
         $numeroEmpleados = \yii\helpers\ArrayHelper::map($empleadosFiltrados, 'numero_empleado', function ($model) {
             return $model->numero_empleado;
         });
     
         // Obtener departamentos únicos
         $departamentoIds = \yii\helpers\ArrayHelper::getColumn($empleadosFiltrados, function($model) {
             return $model->informacionLaboral ? $model->informacionLaboral->cat_departamento_id : null;
         });
         $departamentos = CatDepartamento::find()->where(['id' => $departamentoIds])->all();
         $departamentosData = \yii\helpers\ArrayHelper::map($departamentos, 'id', 'nombre_departamento');
     
         return $this->render('index', [
             'searchModel' => $searchModel,
             'dataProvider' => $dataProvider,
             'juntaGobiernoModel' => $juntaGobiernoModel,
             'departamentosData' => $departamentosData,
             'nombresEmpleadosData' => $nombresEmpleados,
             'numeroEmpleadosData' => $numeroEmpleados,
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
        //ENCUENTRA EL REGISTRO DEL EMPLEADO SOLICITADO
        $modelEmpleado = $this->findModel2($id);
        $modelEmpleado->scenario = Empleado::SCENARIO_UPDATE;
        
  //CARGA LOS MODELOS
        $documentos = $modelEmpleado->documentos;
        $documentoModel = new Documento();
        $documentoMedicoModel = new DocumentoMedico();
        $historial = PeriodoVacacionalHistorial::find()->where(['empleado_id' => $modelEmpleado->id])->all();

        $searchModel = new \app\models\SolicitudSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $dataProvider->query->andWhere(['empleado_id' => $id]);


        $searchModelConsultas = new \app\models\ConsultaMedicaSearch();
        $dataProviderConsultas = $searchModelConsultas->search(Yii::$app->request->queryParams);
        $dataProviderConsultas->query->andWhere(['expediente_medico_id' => $modelEmpleado->expedienteMedico->id]);

//CREA EL REGISTRO DE ANTECEDENTE HEREDITARIO Y LO ASOCIA AL EXPEDIENTE MEDICO DEL EMPLEADO
        $expedienteMedico = $modelEmpleado->expedienteMedico;
        $antecedenteHereditario = AntecedenteHereditario::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedenteHereditario) {
            $antecedenteHereditario = new AntecedenteHereditario();
            $antecedenteHereditario->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedenteHereditario->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedente hereditario');
            } else {

                $expedienteMedico->antecedente_hereditario_id = $antecedenteHereditario->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }

        $antecedentes = AntecedenteHereditario::find()->where(['expediente_medico_id' => $expedienteMedico->id])->all();
        $catAntecedentes = CatAntecedenteHereditario::find()->all();

        // Obtener o crear el registro de antecedentes patológicos
        $antecedentePatologico = AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedentePatologico) {
            $antecedentePatologico = new AntecedentePatologico();
            $antecedentePatologico->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedentePatologico->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes patológicos.');
            } else {

                $expedienteMedico->antecedente_patologico_id = $antecedentePatologico->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }
        // Obtener o crear el registro de antecedentes no patológicos
        $antecedenteNoPatologico = AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedenteNoPatologico) {
            $antecedenteNoPatologico = new AntecedenteNoPatologico();
            $antecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedenteNoPatologico->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes no patológicos.');
            } else {

                $expedienteMedico->antecedente_no_patologico_id = $antecedenteNoPatologico->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }



        $exploracionFisica = ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$exploracionFisica) {
            $exploracionFisica = new ExploracionFisica();
            $exploracionFisica->expediente_medico_id = $expedienteMedico->id;
            if (!$exploracionFisica->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes no patológicos.');
            } else {

                $expedienteMedico->exploracion_fisica_id = $exploracionFisica->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }


        $interrogatorioMedico = InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$interrogatorioMedico) {
            $interrogatorioMedico = new InterrogatorioMedico();
            $interrogatorioMedico->expediente_medico_id = $expedienteMedico->id;
            if (!$interrogatorioMedico->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes no patológicos.');
            } else {

                $expedienteMedico->interrogatorio_medico_id = $interrogatorioMedico->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }


        $antecedentePerinatal = AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedentePerinatal) {
            $antecedentePerinatal = new AntecedentePerinatal();
            $antecedentePerinatal->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedentePerinatal->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes no patológicos.');
            } else {

                $expedienteMedico->antecedente_perinatal_id = $antecedentePerinatal->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }




        $antecedenteGinecologico = AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedenteGinecologico) {
            $antecedenteGinecologico = new AntecedenteGinecologico();
            $antecedenteGinecologico->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedenteGinecologico->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes ginecologicos.');
            } else {

                $expedienteMedico->antecedente_ginecologico_id = $antecedenteGinecologico->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }

      


        $antecedenteObstrectico = AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$antecedenteObstrectico) {
            $antecedenteObstrectico = new AntecedenteObstrectico();
            $antecedenteObstrectico->expediente_medico_id = $expedienteMedico->id;
            if (!$antecedenteObstrectico->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de antecedentes obstrecticos.');
            } else {

                $expedienteMedico->antecedente_obstrectico_id = $antecedenteObstrectico->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }

        $alergia = Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$alergia) {
            $alergia = new Alergia();
            $alergia->expediente_medico_id = $expedienteMedico->id;
            if (!$alergia->save()) {
                Yii::$app->session->setFlash('error', 'Hubo un error al crear el registro de alergia.');
            } else {

                $expedienteMedico->alergia_id = $alergia->id;
                $expedienteMedico->save(false); // Guardar sin validaciones adicionales
            }
        }

        $expedienteMedico = $modelEmpleado->expedienteMedico;
        if ($expedienteMedico && !$expedienteMedico->empleado_id) {
            $expedienteMedico->empleado_id = $modelEmpleado->id;
            if ($expedienteMedico->save(false)) {
                //  Yii::$app->session->setFlash('success', 'Empleado asignado al expediente médico.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al asignar el empleado al expediente médico.');
            }
        }




        if (Yii::$app->request->isPost) {
            if ($post = Yii::$app->request->post('AntecedenteHereditario')) {
                $observacionGeneral = Yii::$app->request->post('observacion_general');

              //  AntecedenteHereditario::deleteAll(['expediente_medico_id' => $expedienteMedico->id]);

                foreach ($post as $catAntecedenteId => $parentezcos) {
                    foreach ($parentezcos as $parentezco => $value) {
                        $antecedente = new AntecedenteHereditario();
                        $antecedente->expediente_medico_id = $expedienteMedico->id;
                        $antecedente->cat_antecedente_hereditario_id = $catAntecedenteId;
                        $antecedente->parentezco = $parentezco;
                        $antecedente->observacion = $observacionGeneral;
                        $antecedente->save();
                    }
                }

                Yii::$app->session->setFlash('success', 'Información de antecedentes hereditarios guardada correctamente.');
                $url = Url::to(['view', 'id' => $id]) . '#hereditarios';
                return $this->redirect($url);
            }

            if ($descripcionAntecedentes = Yii::$app->request->post('descripcion_antecedentes')) {
                $antecedentePatologico->descripcion_antecedentes = $descripcionAntecedentes;

                if ($antecedentePatologico->save()) {
                    Yii::$app->session->setFlash('success', 'Información de antecedentes patológicos guardada correctamente.');
                } else {
                    Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes patológicos.');
                }

                $url = Url::to(['view', 'id' => $id]) . '#expediente_medico';
                return $this->redirect($url);
            }
        }

        return $this->render('view', [
            'model' => $modelEmpleado,
            'documentos' => $documentos,
            'documentoModel' => $documentoModel,
            'documentoMedicoModel' => $documentoMedicoModel,
            'historial' => $historial,
            'searchModelConsultas' => $searchModelConsultas,
            'dataProviderConsultas' => $dataProviderConsultas,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,

            'expedienteMedico' => $expedienteMedico,
            'antecedentes' => $antecedentes,
            'catAntecedentes' => $catAntecedentes,
            'antecedenteNoPatologico' => $antecedenteNoPatologico,
            'ExploracionFisica' => $exploracionFisica,
            'InterrogatorioMedico' => $interrogatorioMedico,
            'AntecedentePerinatal' => $antecedentePerinatal,
            'AntecedenteGinecologico' => $antecedenteGinecologico,
            'AntecedenteObstrectico' => $antecedenteObstrectico,
            'Alergia' => $alergia,
            'antecedentePatologico' => $antecedentePatologico,

        ]);
    }

    public function actionAntecedentePatologico($id)
{
    if (!Yii::$app->user->can('editar-expediente-medico')) {
        Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
        return $this->redirect(['view', 'id' => $id]);
    }

    $modelEmpleado = $this->findModel2($id);
    $expedienteMedico = $modelEmpleado->expedienteMedico;

    // Verificar si expedienteMedico existe
    if ($expedienteMedico === null) {
        Yii::$app->session->setFlash('error', 'No se encontró el expediente médico.');
        return $this->redirect(['view', 'id' => $id]);
    }

    $modelAntecedentePatologico = AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
    if (!$modelAntecedentePatologico) {
        $modelAntecedentePatologico = new AntecedentePatologico();
        $modelAntecedentePatologico->expediente_medico_id = $expedienteMedico->id;
    }

    if ($modelAntecedentePatologico->load(Yii::$app->request->post()) && $modelAntecedentePatologico->save()) {
        // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
    } else {
        Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedente patológico.');
    }

    $url = Url::to(['view', 'id' => $id]) . '#patologicos';
    return $this->redirect($url);

    // El código de renderizado ya no es necesario ya que rediriges inmediatamente
}



    public function actionNoPatologicos($id)
    {
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelAntecedenteNoPatologico = AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelAntecedenteNoPatologico) {
            $modelAntecedenteNoPatologico = new AntecedenteNoPatologico();
            $modelAntecedenteNoPatologico->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelAntecedenteNoPatologico->load(Yii::$app->request->post())) {
            // Verificar y asignar manualmente los campos de checkbox que no estén en el POST
            $checkboxFields = [
                'p_ejercicio',
                'p_deporte',
                'p_dormir_dia',
                'p_desayuno',
                'p_cafe',
                'p_refresco',
                'p_dieta',
                'p_alcohol',
                'p_ex_alcoholico',
                'p_fuma',
                'p_ex_fumador',
                'p_fumador_pasivo',
                'p_drogas',
                'p_ex_adicto',
                'p_droga_intravenosa'



            ];
            foreach ($checkboxFields as $field) {
                if (!isset(Yii::$app->request->post('AntecedenteNoPatologico')[$field])) {
                    $modelAntecedenteNoPatologico->$field = 0;
                }
            }

            if ($modelAntecedenteNoPatologico->save()) {

 // Actualizar primera_revision del expediente médico
 $expedienteMedico->primera_revision = 1;
 if (!$expedienteMedico->save()) {
     Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
 } else {
     Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
 }

                Yii::$app->session->setFlash('success', 'Información de antecedentes no patológicos guardada correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes no patológicos.');
            }

            $url = Url::to(['view', 'id' => $id]) . '#nopatologicos';
            return $this->redirect($url);
        }

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelAntecedenteNoPatologico' => $modelAntecedenteNoPatologico,
        ]);
    }




    public function actionExploracionFisica($id)
    {
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelExploracionFisica = ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelExploracionFisica) {
            $modelExploracionFisica = new ExploracionFisica();
            $modelExploracionFisica->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelExploracionFisica->load(Yii::$app->request->post()) && $modelExploracionFisica->save()) {
             // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
            Yii::$app->session->setFlash('success', 'Información de exploración fisica guardada correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes no patológicos.');
        }

        $url = Url::to(['view', 'id' => $id]) . '#exploracion_fisica';
        return $this->redirect($url);

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelExploracionFisica' => $modelExploracionFisica, // Asegúrate de pasar este modelo
        ]);
    }

    public function actionAntecedentePerinatal($id)
    {
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelAntecedentePerinatal = AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelAntecedentePerinatal) {
            $modelAntecedentePerinatal = new AntecedentePerinatal();
            $modelAntecedentePerinatal->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelAntecedentePerinatal->load(Yii::$app->request->post())) {
            // Verificar y asignar manualmente los campos de checkbox que no estén en el POST
            $checkboxFields = [
                'p_apnea_neonatal',
                'p_cianosis',
                'p_ictericia',
                'p_hemorragias',
                'p_convulsiones',
                'p_anestesia',
                'test',
                'p_parto',
                'p_cesarea'




            ];
            foreach ($checkboxFields as $field) {
                if (!isset(Yii::$app->request->post('AntecedentePerinatal')[$field])) {
                    $modelAntecedentePerinatal->$field = 0;
                }
            }

            if ($modelAntecedentePerinatal->save()) {
                 // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
                Yii::$app->session->setFlash('success', 'Información de antecedente perinatal guardada correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes perinatales.');
            }

            $url = Url::to(['view', 'id' => $id]) . '#perinatales';
            return $this->redirect($url);
        }

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelAntecedentePerinatal' => $modelAntecedentePerinatal,
        ]);
    }


    public function actionAntecedenteGinecologico($id)
    {
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelAntecedenteGinecologico = AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelAntecedenteGinecologico) {
            $modelAntecedenteGinecologico = new AntecedenteGinecologico();
            $modelAntecedenteGinecologico->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelAntecedenteGinecologico->load(Yii::$app->request->post())) {
            // Verificar y asignar manualmente los campos de checkbox que no estén en el POST
            $checkboxFields = [
                'p_vaginits',
                'p_cervicitis_mucopurulenta',
                'p_chancroide',
                'p_clamidia',
                'p_eip',
                'p_gonorrea',
                'p_hepatitis',
                'p_herpes',
                'p_lgv',
                'p_molusco_cont',
                'p_ladillas',
                'p_sarna',
                'p_sifilis',
                'p_tricomoniasis',
                'p_vb',
                'p_vih',
                'p_vph',





            ];
            foreach ($checkboxFields as $field) {
                if (!isset(Yii::$app->request->post('AntecedenteGinecologico')[$field])) {
                    $modelAntecedenteGinecologico->$field = 0;
                }
            }

            if ($modelAntecedenteGinecologico->save()) {
                 // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
                Yii::$app->session->setFlash('success', 'Información de antecedente ginecologico guardada correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes ginecologicos.');
            }

            $url = Url::to(['view', 'id' => $id]) . '#ginecologicos';
            return $this->redirect($url);
        }

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelAntecedenteGinecologico' => $modelAntecedenteGinecologico,
        ]);
    }

    public function actionAntecedenteObstrectico($id)
    {
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelAntecedenteObstrectico = AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelAntecedenteObstrectico) {
            $modelAntecedenteObstrectico = new AntecedenteGinecologico();
            $modelAntecedenteObstrectico->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelAntecedenteObstrectico->load(Yii::$app->request->post())) {
            // Verificar y asignar manualmente los campos de checkbox que no estén en el POST
            $checkboxFields = [
                'p_intergenesia',
                'p_malformaciones',
                'p_atencion_prenatal',
                'p_parto_prematuro',
                'p_isoinmunizacion',






            ];
            foreach ($checkboxFields as $field) {
                if (!isset(Yii::$app->request->post('AntecedenteObstrectico')[$field])) {
                    $modelAntecedenteObstrectico->$field = 0;
                }
            }

            if ($modelAntecedenteObstrectico->save()) {
                 // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
                Yii::$app->session->setFlash('success', 'Información de antecedente obstrectico guardada correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de antecedentes obstrecticos.');
            }

            $url = Url::to(['view', 'id' => $id]) . '#obstrecticos';
            return $this->redirect($url);
        }

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelAntecedenteObstrectico' => $modelAntecedenteObstrectico,
        ]);
    }






    public function actionInterrogatorioMedico($id)
    {

        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelInterrogatorioMedico = InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelInterrogatorioMedico) {
            $modelInterrogatorioMedico = new InterrogatorioMedico();
            $modelInterrogatorioMedico->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelInterrogatorioMedico->load(Yii::$app->request->post()) && $modelInterrogatorioMedico->save()) {
             // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
            Yii::$app->session->setFlash('success', 'Información de interrogatorio medico guardada correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de interrogatorio medico.');
        }

        $url = Url::to(['view', 'id' => $id]) . '#interrogatorio_medico';
        return $this->redirect($url);

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelInterrogatorioMedico' => $modelInterrogatorioMedico, // Asegúrate de pasar este modelo
        ]);
    }


    public function actionAlergia($id)
    {
        // Verificar si el usuario tiene el permiso
        if (!Yii::$app->user->can('editar-expediente-medico')) {
            Yii::$app->session->setFlash('error', 'No tiene permitido realizar esta acción.');
            return $this->redirect(['view', 'id' => $id]);
        }

        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;

        $modelAlergia = Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        if (!$modelAlergia) {
            $modelAlergia = new Alergia();
            $modelAlergia->expediente_medico_id = $expedienteMedico->id;
        }

        if ($modelAlergia->load(Yii::$app->request->post()) && $modelAlergia->save()) {
             // Actualizar primera_revision del expediente médico
        $expedienteMedico->primera_revision = 1;
        if (!$expedienteMedico->save()) {
            Yii::$app->session->setFlash('error', 'Error al actualizar el expediente médico.');
        } else {
            Yii::$app->session->setFlash('success', 'Información antecedente patológico médico guardada correctamente.');
        }
            Yii::$app->session->setFlash('success', 'Información alergica medico guardada correctamente.');
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al guardar la información de alergias.');
        }

        $url = Url::to(['view', 'id' => $id]) . '#alergias';
        return $this->redirect($url);

        return $this->render('view', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'modelAlergia' => $modelAlergia,
        ]);
    }


    public function actionInformeMedicoGeneral($id)
    {
        $modelEmpleado = $this->findModel2($id);
        $expedienteMedico = $modelEmpleado->expedienteMedico;
    
        // Otras consultas para obtener los datos necesarios
        $alergia = Alergia::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedentePatologico = AntecedentePatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $exploracionFisica = ExploracionFisica::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $interrogatorioMedico = InterrogatorioMedico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedenteNoPatologico = AntecedenteNoPatologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedentePerinatal = AntecedentePerinatal::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedenteGinecologico = AntecedenteGinecologico::findOne(['expediente_medico_id' => $expedienteMedico->id]);
        $antecedenteObstrectico = AntecedenteObstrectico::findOne(['expediente_medico_id' => $expedienteMedico->id]);

        // Obtener antecedentes hereditarios
        $antecedentes = AntecedenteHereditario::findAll(['expediente_medico_id' => $expedienteMedico->id]);
    
        $antecedentesExistentes = [];
        if ($antecedentes) {
            foreach ($antecedentes as $antecedente) {
                $antecedentesExistentes[$antecedente->cat_antecedente_hereditario_id][$antecedente->parentezco] = true;
            }
        }
    
        // Obtener categorías de antecedentes hereditarios
        $catAntecedentes = CatAntecedenteHereditario::find()->all();
    
        return $this->render('informe_medico_general', [
            'model' => $modelEmpleado,
            'expedienteMedico' => $expedienteMedico,
            'alergia' => $alergia,
            'antecedentePatologico' => $antecedentePatologico,
            'exploracionFisica' => $exploracionFisica,
            'interrogatorioMedico' => $interrogatorioMedico,
            'catAntecedentes' => $catAntecedentes,
            'antecedentesExistentes' => $antecedentesExistentes,
            'antecedenteNoPatologico' => $antecedenteNoPatologico,
                'antecedentePerinatal' =>$antecedentePerinatal,
                'antecedenteObstrectico' => $antecedenteObstrectico,
                'antecedenteGinecologico' => $antecedenteGinecologico,
        ]);
    }
    

    /**
     * Creates a new Empleado model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */



      // Ejemplo para generar CSV (puedes usar otros como Pdf o Excel)
     
    
      
      
      public function actionGenerarReporte()
      {
          $searchModel = new EmpleadoSearch();
          $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
      
          // Eliminar la paginación para exportar todos los registros filtrados
          $dataProvider->pagination = false;
      
          // Crear un nuevo objeto Spreadsheet
          $spreadsheet = new Spreadsheet();
          $sheet = $spreadsheet->getActiveSheet();
      
          // Encabezados del reporte
          $sheet->setCellValue('A1', 'ID');
          $sheet->setCellValue('B1', 'Nombre');
          $sheet->setCellValue('C1', 'Apellido');
          $sheet->setCellValue('D1', 'Número de Empleado');
          $sheet->setCellValue('E1', 'Departamento');
          $sheet->setCellValue('F1', 'Dirección');
          // Añadir más encabezados si es necesario
      
          // Iterar sobre los empleados y agregar los datos al reporte
          $row = 2;
          foreach ($dataProvider->getModels() as $empleado) {
              $sheet->setCellValue('A' . $row, $empleado->id);
              $sheet->setCellValue('B' . $row, $empleado->nombre);
              $sheet->setCellValue('C' . $row, $empleado->apellido);
              $sheet->setCellValue('D' . $row, $empleado->numero_empleado);
              $sheet->setCellValue('E' . $row, $empleado->informacionLaboral->catDepartamento->nombre_departamento ?? 'N/A');
              $sheet->setCellValue('F' . $row, $empleado->informacionLaboral->catDireccion->nombre_direccion ?? 'N/A');
              $row++;
          }
      
          // Crear el archivo Excel
          $writer = new Xlsx($spreadsheet);
          $fileName = 'empleados_reporte_' . date('Y-m-d_His') . '.xlsx';
      
          // Enviar el archivo al navegador para su descarga
          header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          header('Content-Disposition: attachment;filename="' . $fileName . '"');
          header('Cache-Control: max-age=0');
      
          $writer->save('php://output');
          exit;
      }
      

      
      


     //FUNCION PARA CREAR EL EMPLEADO Y SU USUARIO, TAMBIEN LOS REGISTROS ASOCIADOS
    public function actionCreate()
{
    //SE CARGAN LOS MODELOS ASOCIADOS AL EMPLEADO
    $model = new Empleado();
    $usuario = new Usuario();
    $informacion_laboral = new InformacionLaboral();
    $vacaciones = new Vacaciones();
    $periodoVacacional = new PeriodoVacacional();
    $segundoPeriodoVacacional = new SegundoPeriodoVacacional();
    $juntaGobiernoModel = new JuntaGobierno();
    $expedienteMedico = new ExpedienteMedico();
    $antecedenteHereditario = new AntecedenteHereditario();
    

    $usuario->scenario = Usuario::SCENARIO_CREATE;
    $model->scenario = Empleado::SCENARIO_CREATE;

    if ($model->load(Yii::$app->request->post()) && $usuario->load(Yii::$app->request->post()) && $informacion_laboral->load(Yii::$app->request->post())) {
       //INICIAMOS LA TRANSACCIÓN
        $transaction = Yii::$app->db->beginTransaction();
        $usuario->username = $model->nombre . $model->apellido;
        $nombres = explode(" ", $model->nombre);
        $apellidos = explode(" ", $model->apellido);
        $usernameBase = strtolower($nombres[0][0] . $apellidos[0] . (isset($apellidos[1]) ? $apellidos[1][0] : ''));
        //SE GENERA EL NOMBRE DE USUARIO QUE TENDRA EL EMPLEADO EN BASE A SU NOMBRE Y APELLIDO
        $usuario->username = $usernameBase;
        $counter = 1;
       
        while (Usuario::find()->where(['username' => $usuario->username])->exists()) {
            $usuario->username = $usernameBase . $counter;
            $counter++;
        }
        //SE ESTABLECE LA CONTRASEÑA POR DEFECTO 
        $usuario->password = 'contrasena';
        $usuario->status = 10;
        $usuario->nuevo = 4;
        $hash = Yii::$app->security->generatePasswordHash($usuario->password);
        $usuario->password = $hash;

        try {
            // Calcula los días de vacaciones y guarda la información laboral
            $totalDiasVacaciones = $this->calcularDiasVacaciones($informacion_laboral->fecha_ingreso, $informacion_laboral->cat_tipo_contrato_id);
            $diasPorPeriodo = ceil($totalDiasVacaciones / 2);

            //GUARDA LA INFORMACION DE LOS PERIODOS VACAIONALES
            $periodoVacacional->dias_vacaciones_periodo = $diasPorPeriodo;
            if (!$periodoVacacional->save()) {
                Yii::$app->session->setFlash('error', 'Error al guardar PeriodoVacacional.');
                throw new \yii\db\Exception('Error al guardar PeriodoVacacional');
            }

            $segundoPeriodoVacacional->dias_vacaciones_periodo = $totalDiasVacaciones - $diasPorPeriodo;
            if (!$segundoPeriodoVacacional->save()) {
                Yii::$app->session->setFlash('error', 'Error al guardar SegundoPeriodoVacacional.');
                throw new \yii\db\Exception('Error al guardar SegundoPeriodoVacacional');
            }

            $vacaciones->periodo_vacacional_id = $periodoVacacional->id;
            $vacaciones->segundo_periodo_vacacional_id = $segundoPeriodoVacacional->id;
            $vacaciones->total_dias_vacaciones = $totalDiasVacaciones;
            if (!$vacaciones->save()) {
                Yii::$app->session->setFlash('error', 'Error al guardar Vacaciones.');
                throw new \yii\db\Exception('Error al guardar Vacaciones');
            }

            //IDENTIFICA EL DEPARTAMENTO QUE SE MANDO Y ASIGNA A INFORMACION LABORAL LA DIREECION Y DPTO AL QUE PERTENECE
            $departamento = CatDepartamento::findOne($informacion_laboral->cat_departamento_id);
            if ($departamento) {
                $informacion_laboral->cat_direccion_id = $departamento->cat_direccion_id;
                $informacion_laboral->cat_dpto_cargo_id = $departamento->cat_dpto_id;
            }

            $informacion_laboral->vacaciones_id = $vacaciones->id;
            if (!$informacion_laboral->save()) {
                Yii::$app->session->setFlash('error', 'Error al guardar InformacionLaboral.');
                throw new \yii\db\Exception('Error al guardar InformacionLaboral');
            }

            //VERIFICA Y GUARDA EL ROL QUE SE ESTABLECIO
            $model->informacion_laboral_id = $informacion_laboral->id;
            if ($usuario->save()) {
                $model->usuario_id = $usuario->id;

                if ($usuario->rol == 1) {
                    $rol = 'empleado';
                } elseif ($usuario->rol == 2) {
                    $rol = 'gestor-rh';
                } elseif ($usuario->rol == 3) {
                    $rol = 'medico';
                } else {
                    $rol = 'empleado';
                }

                $auth = Yii::$app->authManager;
                $authorRole = $auth->getRole($rol);
                if ($authorRole) {
                    $auth->assign($authorRole, $usuario->id);
                } else {
                    Yii::$app->session->setFlash('error', "El rol $rol no existe en el sistema de RBAC.");
                    throw new \Exception("El rol $rol no existe en el sistema de RBAC.");
                }

                // Subir foto del empleado si se proporcionó
                ///RECIBE LA FOTO DEL EMPLEADO, ADEMAS SE CREAN LAS CARPETAS DEL EMPLEADO EN RUNTIME
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

                    $nombreIncidenciasCarpeta = $nombreCarpetaTrabajador . '/solicitudes_incidencias_empleado';
                    if (!is_dir($nombreIncidenciasCarpeta)) {
                        mkdir($nombreIncidenciasCarpeta, 0775, true);
                    }
                    $upload_filename = $nombreCarpetaUsuarioProfile . '/' . $upload->baseName . '.' . $upload->extension;
                    $upload->saveAs($upload_filename);
                    $model->foto = $upload_filename;
                }

                // Asignar primera_revision a 0 antes de guardar el expediente médico
                $expedienteMedico->antecedente_hereditario_id = $antecedenteHereditario->id;
                $expedienteMedico->primera_revision = 0; // Establecer primera_revision a 0

                if ($expedienteMedico->save()) {
                    $model->expediente_medico_id = $expedienteMedico->id;

                    if ($model->save()) {
                        // Determinar el nivel jerárquico basado en el puesto
                        $puesto = CatPuesto::findOne($informacion_laboral->cat_puesto_id);
                        if ($puesto) {
                            $nivelJerarquico = 'Comun';
                            if (stripos($puesto->nombre_puesto, 'DIRECTOR') !== false) {
                                $nivelJerarquico = 'Director';
                            } elseif (stripos($puesto->nombre_puesto, 'JEFE DE UNIDAD') !== false) {
                                $nivelJerarquico = 'Jefe de unidad';
                            } elseif (stripos($puesto->nombre_puesto, 'JEFE DEL DEPARTAMENTO') !== false) {
                                $nivelJerarquico = 'Jefe de departamento';
                            }

                            // Guardar la información en junta_gobierno si aplica
                            if ($nivelJerarquico !== 'Comun') {
                                $juntaGobiernoModel->empleado_id = $model->id;
                                $juntaGobiernoModel->nivel_jerarquico = $nivelJerarquico;
                                $juntaGobiernoModel->cat_departamento_id = $informacion_laboral->cat_departamento_id;
                                $juntaGobiernoModel->cat_direccion_id = $informacion_laboral->cat_direccion_id; // Asigna la dirección correcta
                                if (!$juntaGobiernoModel->save()) {
                                    Yii::$app->session->setFlash('error', 'Error al guardar JuntaGobierno.');
                                    $transaction->rollBack();
                                    throw new \yii\db\Exception('Error al guardar JuntaGobierno: ' . json_encode($juntaGobiernoModel->errors));
                                }
                            }
                                //MANDA AL CORREO DEL EMPLEADO LAS CREDENCIALES PARA INICIAR SESION
                            Yii::$app->mailer->compose()
                                ->setFrom('elitaev7@gmail.com')
                                ->setTo($model->email)
                                ->setSubject('Datos de acceso al sistema')
                                ->setTextBody("Nos comunicamos con usted, {$model->nombre}.\nAquí están tus datos de acceso:\nUsuario: {$usuario->username}\nContraseña: contrasena")
                                ->send();

                            $transaction->commit();
                            Yii::$app->session->setFlash('success', "Se guardó la información correctamente.");
                            return $this->redirect(['view', 'id' => $model->id]);
                        } else {
                            Yii::$app->session->setFlash('error', 'Error al guardar Empleado.');
                            throw new \yii\db\Exception('Error al guardar Empleado');
                        }
                    } else {
                       // Yii::$app->session->setFlash('error', 'Error al guardar ExpedienteMedico.');
                       // throw new \yii\db\Exception('Error al guardar ExpedienteMedico');
                    }
                } else {
                    //Yii::$app->session->setFlash('error', 'Error al guardar AntecedenteHereditario.');
                    throw new \yii\db\Exception('Error al guardar AntecedenteHereditario');
                }
            } else {
                Yii::$app->session->setFlash('error', 'Error al guardar Usuario.');
                throw new \yii\db\Exception('Error al guardar Usuario');
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', 'Ocurrió un error al guardar la información: ' . $e->getMessage());
        }
    }

    return $this->render('create', [
//RENDERIZA LA VISTA, CARGANDO LOS MODELOS CORRESPONDIENTES
        'model' => $model,
        'usuario' => $usuario,
        'informacion_laboral' => $informacion_laboral,
        'vacaciones' => $vacaciones,
        'periodoVacacional' => $periodoVacacional,
        'segundoPeriodoVacacional' => $segundoPeriodoVacacional,
        'expedienteMedico' => $expedienteMedico,
        'antecedenteHereditario' => $antecedenteHereditario,
        'puestos' => CatPuesto::find()->all(), 
        'departamentos' => CatDepartamento::find()->all(), 
        'juntaGobiernoModel' => $juntaGobiernoModel,
    ]);
}


public function actionDesactivados()
{
    $searchModel = new EmpleadoSearch();
    $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
    $juntaGobiernoModel = new JuntaGobierno();
    // Filtrar empleados desactivados
 
    $dataProvider->query->andWhere(['usuario.status' => 0]); // 0 o el valor correspondiente para desactivados

    return $this->render('desactivados', [
        'searchModel' => $searchModel,
        'dataProvider' => $dataProvider,
        'juntaGobiernoModel' => $juntaGobiernoModel,
   
    ]);
}


//FUNCION QUE DEFINE EL DIA DE VACACIONES EN BASE AL NOMBRE DEL TIPO DE FORMATO
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
//CANTIDAD DE VACACIONES QUE SE CALCULAN PARA EL TIPO DE CONTRATO DE EVENTUAL
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
//CANTIDAD DE VACACIONES QUE SE CALCULAN PARA EL TIPO DE CONTRATO DE CONFIANZA
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
//CANTIDAD DE VACACIONES QUE SE CALCULAN PARA EL TIPO DE CONTRATO SINDICALIZADO
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

        $transaction = Yii::$app->db->beginTransaction();

        try {
            foreach ($empleado->documentos as $documento) {
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $empleado->nombre . '_' . $empleado->apellido;
                if (is_dir($nombreCarpetaTrabajador)) {
                    FileHelper::removeDirectory($nombreCarpetaTrabajador);
                }
                $documento->delete();
            }

            $empleado->delete();
            $usuario = Usuario::findOne($usuario_id);
            if ($usuario) {
                $usuario->delete();
            }


            $informacion_laboral = InformacionLaboral::findOne($informacion_laboral_id);
            if ($informacion_laboral) {
                $informacion_laboral->delete();
            }


            $transaction->commit();

            Yii::$app->session->setFlash('warning', '<i class="fa fa-bell" aria-hidden="true"></i> Empleado, eliminado exitosamente.');
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>
 Error al eliminar el empleado.');
        }

        return $this->redirect(['index']);
    }


    public function actionChangePhoto($id)
    {
        $model = $this->findModel2($id);

        if (Yii::$app->request->isPost) {
            $upload = UploadedFile::getInstance($model, 'foto');
            if ($upload) {
                $nombreCarpetaTrabajador = Yii::getAlias('@runtime') . '/empleados/' . $model->nombre . '_' . $model->apellido;
                if (!is_dir($nombreCarpetaTrabajador)) {
                    mkdir($nombreCarpetaTrabajador, 0775, true);
                }
                $nombreCarpetaUsuarioProfile = $nombreCarpetaTrabajador . '/foto_empleado';
                if (!is_dir($nombreCarpetaUsuarioProfile)) {
                    mkdir($nombreCarpetaUsuarioProfile, 0775, true);
                }

                // Eliminar la foto anterior si existe
                if (!empty($model->foto) && file_exists($model->foto)) {
                    unlink($model->foto);
                }

                $upload_filename = $nombreCarpetaUsuarioProfile . '/' . $upload->baseName . '.' . $upload->extension;
                if ($upload->saveAs($upload_filename)) {
                    $model->foto = $upload_filename;
                    if ($model->save(false)) {
                        return $this->redirect(['view', 'id' => $model->id]);
                    }
                }
            }
        }

        return $this->redirect(['view', 'id' => $model->id]);
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
            Yii::$app->session->setFlash('success', '<i class="fa fa-bell" aria-hidden="true"></i> El usuario ha sido desactivado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>Hubo un error al desactivar el usuario.');
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
            Yii::$app->session->setFlash('success', '<i class="fa fa-bell" aria-hidden="true"></i> El estado del usuario se ha cambiado correctamente.');
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> Hubo un error al cambiar el estado del usuario.');
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

    //FUNCION PARA CAMBIAR LA FOTO DE PERFIL DEL EMPLEADO
    public function actionCambio($id)
    {
        $model = $this->findModel2($id);

        if (Yii::$app->request->isPost) {
            $uploadedFile = UploadedFile::getInstance($model, 'foto');
                //RECIBE LA FOTO DEL EMPLEADO, Y SE ALMACENA EN EL DIRECTORIO CORRESPONDIENTE
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


//FUNCION QUE PERMITE EDITAR Y ACTUALZAR LA INFORMACION PERSONAL DEL EMPLEADO
    public function actionActualizarInformacion($id)
    {
        $model = $this->findModel2($id);

        if ($model->load(Yii::$app->request->post())) {
            $fechaNacimiento = new \DateTime($model->fecha_nacimiento);
            $hoy = new \DateTime();
            $diferencia = $hoy->diff($fechaNacimiento);
            $model->edad = $diferencia->y;

            if ($model->save()) {
                Yii::$app->session->setFlash('success', '<i class="fa fa-bell" aria-hidden="true"></i>
 Los cambios de información personal han sido actualizados correctamente.');

                $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_personal';
                return $this->redirect($url);
            } else {
                Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No se pudieron realizar los cambios de información personal .');
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
            Yii::$app->session->setFlash('success', '<i class="fa fa-bell" aria-hidden="true"></i>
 Los cambios de información de contacto han sido actualizados correctamente.');

            $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_contacto';
            return $this->redirect($url);
        } else {
            Yii::$app->session->setFlash('error', '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i> No se pudieron realizar los cambios de información de contacto');
            $url = Url::to(['view', 'id' => $model->id,]) . '#informacion_contacto';
            return $this->redirect($url);
        }
    }



//FUNCION QUE PERMITE EDITAR Y ACTUALZAR LA INFORMACION LABORAL DEL EMPLEADO
    public function actionActualizarInformacionLaboral($id)
    {
        $model = $this->findModel2($id);
        $informacion_laboral = InformacionLaboral::findOne($model->informacion_laboral_id);
        
        if ($informacion_laboral->load(Yii::$app->request->post())) {
            // Obtener el nuevo departamento
            $departamento = CatDepartamento::findOne($informacion_laboral->cat_departamento_id);
        
            if ($departamento) {
                // Asignar la dirección y el departamento correspondientes al departamento seleccionado
                $informacion_laboral->cat_direccion_id = $departamento->cat_direccion_id;
                $informacion_laboral->cat_dpto_cargo_id = $departamento->cat_dpto_id;
            }
        
            // Guardar los cambios en InformacionLaboral
            if ($informacion_laboral->save()) {
                // Actualizar los días de vacaciones
                $vacaciones = Vacaciones::findOne($informacion_laboral->vacaciones_id);
                $totalDiasVacaciones = $this->calcularDiasVacaciones($informacion_laboral->fecha_ingreso, $informacion_laboral->cat_tipo_contrato_id);
                $vacaciones->total_dias_vacaciones = $totalDiasVacaciones;
        
                if (!$vacaciones->save()) {
                    Yii::$app->session->setFlash('error', 'Hubo un error al actualizar los días de vacaciones.');
                }
    
                // Manejar el cambio en el puesto
                $nuevoPuesto = CatPuesto::findOne($informacion_laboral->cat_puesto_id);
                $juntaGobiernoModel = JuntaGobierno::findOne(['empleado_id' => $model->id, 'cat_direccion_id' => $informacion_laboral->cat_direccion_id]);
    
                // Verifica si el puesto requiere estar en junta_gobierno
                if ($nuevoPuesto) {
                    $nuevoNivelJerarquico = $this->determinarNivelJerarquico($nuevoPuesto->nombre_puesto);
    
                    if ($nuevoNivelJerarquico !== 'Comun') {
                        // Si no existe en junta_gobierno, se agrega
                        if (!$juntaGobiernoModel) {
                            $juntaGobiernoModel = new JuntaGobierno();
                            $juntaGobiernoModel->empleado_id = $model->id;
                            $juntaGobiernoModel->nivel_jerarquico = $nuevoNivelJerarquico;
                            $juntaGobiernoModel->cat_departamento_id = $informacion_laboral->cat_departamento_id;
                            $juntaGobiernoModel->cat_direccion_id = $informacion_laboral->cat_direccion_id;
                            if (!$juntaGobiernoModel->save()) {
                                Yii::$app->session->setFlash('error', 'Error al guardar JuntaGobierno.');
                            }
                        }
                    } else {
                        // Si el puesto no requiere estar en junta_gobierno, eliminar del registro
                        if ($juntaGobiernoModel) {
                            $juntaGobiernoModel->delete();
                        }
                    }
                }
                
                Yii::$app->session->setFlash('success', 'Los cambios de la información laboral han sido actualizados correctamente.');
            } else {
                Yii::$app->session->setFlash('error', 'Hubo un error al actualizar la información laboral del trabajador.');
            }
    
            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_laboral';
            return $this->redirect($url);
        } else {
            Yii::$app->session->setFlash('error', 'Hubo un error al cargar los datos de la solicitud.');
            $url = Url::to(['view', 'id' => $model->id]) . '#informacion_laboral';
            return $this->redirect($url);
        }
    }
//ESTA FUNCION PERMITE DETERINAR SI ES JEFE DE UNIDAD, DEPARTAMENTO O SI ES DIRECTOR, EN BASE AL NOMBRE DEL PUESTO QUE TIENE ESTABLECIDO EL EMPLEADO
    protected function determinarNivelJerarquico($nombrePuesto)
{
    if (stripos($nombrePuesto, 'DIRECTOR') !== false) {
        return 'Director';
    } elseif (stripos($nombrePuesto, 'JEFE DE UNIDAD') !== false) {
        return 'Jefe de unidad';
    } elseif (stripos($nombrePuesto, 'JEFE DE DEPARTAMENTO') !== false) {
        return 'Jefe de departamento';
    }
    return 'Comun';
}

    

//FUNCION QUE PERMITE EDITAR Y ACTUALZAR LA INFORMACION DE LOS PERIODOS VACACIONALES DEL EMPLEADO
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
