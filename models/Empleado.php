<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "empleado".
 *
 * @property int $id
 * @property int $numero_empleado
 * @property int $usuario_id
 * @property int $informacion_laboral_id
  * @property int|null $cat_nivel_estudio_id

 * @property int|null $cat_nivel_estudio_id
 * @property int|null $parametro_formato_id
 * @property string $nombre
 * @property string $apellido
 * @property string|null $fecha_nacimiento
 * @property int|null $edad
 * @property int|null $sexo
 * @property string|null $foto
 * @property string|null $telefono
 * @property string $email
 * @property int|null $estado_civil
 * @property string|null $colonia
 * @property string|null $calle
 * @property int|null $numero_casa
 * @property int|null $codigo_postal
 * @property string|null $nombre_contacto_emergencia
 * @property int|null $relacion_contacto_emergencia
 * @property string|null $telefono_contacto_emergencia
 * @property string|null $institucion_educativa
 * @property string|null $titulo_grado
  * @property CatNivelEstudio $catNivelEstudio
 * @property int|null $expediente_medico_id

 * @property Documento[] $documentos
 * @property InformacionLaboral $informacionLaboral
 * @property Usuario $usuario
  * @property ExpedienteMedico $expedienteMedico

 */
class Empleado extends \yii\db\ActiveRecord
{

    //ESCENARIO PARA IDENTIFICAR EN QUE MOMENTO SERAN OBLIGATORIOS ALGUNOS CAMPOS
    const SCENARIO_CREATE = 'create';
    const SCENARIO_UPDATE = 'update';
    const SCENARIO_UPDATE_INFO_EDU = 'update';


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'empleado';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['numero_empleado', 'usuario_id', 'informacion_laboral_id', 'nombre', 'apellido', 'email', 'profesion',
      
        
        
        ], 'required'],
            [['numero_empleado', 'usuario_id', 'informacion_laboral_id', 'cat_nivel_estudio_id', 'parametro_formato_id', 'edad', 'codigo_postal', 'expediente_medico_id'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['numero_empleado'], 'unique', 'message' => 'Este número de empleado ya existe.'],
            [['numero_casa'], 'string', 'max' => 4],
            [['estado_civil', 'sexo'], 'string', 'max' => 12],
            [['nombre'], 'string', 'max' => 30],
            [['nombre', 'apellido'], 'match', 'pattern' => '/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/', 'message' => 'Solo se permiten letras y espacios.'],//EXPRESIÓN PARA VALIDAR NOMBRE
            [['email'], 'email'],
            [['apellido'], 'string', 'max' => 60],
            [[ 'email'], 'string', 'max' => 100],
            [['foto'], 'string', 'max' => 255],
            [['telefono', 'telefono_contacto_emergencia'], 'match', 'pattern' => '/^[0-9+\-\(\)\s]*$/', 'message' => 'El teléfono solo puede contener números, espacios y los símbolos +, -, (, ).'], //EXPRESIÓN PARA VALIDAR NUMERO DE CELULAR
            [['telefono', 'telefono_contacto_emergencia'], 'string', 'max' => 15],
            [['colonia'], 'string', 'max' => 50],
            [['profesion'], 'string', 'max' => 15],
            [['calle'], 'string', 'max' => 85],
            [['nombre_contacto_emergencia'], 'string', 'max' => 90],
            [['relacion_contacto_emergencia'], 'string', 'max' => 25],
            [['curp'], 'match', 'pattern' => '/^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/
', 'message' => 'El CURP no tiene un formato válido.'], //EXPRESIÓN PARA VALIDAR CURP
            [['curp'], 'string', 'max' => 18],

            [['rfc'], 'match', 'pattern' => '/^([A-ZÑ&]{3,4}) ?(?:- ?)?(\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])) ?(?:- ?)?([A-Z\d]{2})([A\d])$/
', 'message' => 'El RFC no tiene un formato válido.'], //EXPRESIÓN PARA VALIDAR RFC
            [['rfc'], 'string', 'max' => 13],
            [['nss'], 'match', 'pattern' => '/^\d{11}$/', 'message' => 'El NSS debe contener exactamente 11 dígitos.'],
            [['nss'], 'string', 'max' => 11],

            [['municipio', 'estado'], 'string', 'max' => 45],
            [['cat_nivel_estudio_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatNivelEstudio::class, 'targetAttribute' => ['cat_nivel_estudio_id' => 'id']],

            [['numero_empleado'], 'string', 'max' => 4], 


            [['institucion_educativa', 'titulo_grado'], 'string', 'max' => 65],
            [['foto'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg'],
            [['expediente_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpedienteMedico::class, 'targetAttribute' => ['expediente_medico_id' => 'id']],

            [['informacion_laboral_id'], 'exist', 'skipOnError' => true, 'targetClass' => InformacionLaboral::class, 'targetAttribute' => ['informacion_laboral_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
        
        
        
        
            [['numero_empleado', 'usuario_id', 'nombre', 'apellido', 'email', 'profesion'], 'required', 'on' => self::SCENARIO_CREATE],
            [['fecha_nacimiento', 'sexo', 'estado_civil', 'curp', 'nss', 'rfc', 'colonia', 'calle', 'codigo_postal', 'numero_casa', 'telefono'], 'required', 'on' => self::SCENARIO_UPDATE],
        
        ];
    }

    public function scenarios()
    {
        //SE ESTABLECEN LOS ESCENARIOS CON LOS CAMPOS QUE PARTICIPARAN EN CADA UNO DE ESTOS
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_CREATE] = ['numero_empleado', 'usuario_id', 'nombre', 'apellido', 'email', 'profesion'];
        $scenarios[self::SCENARIO_UPDATE] = ['numero_empleado', 'usuario_id', 'informacion_laboral_id', 'nombre', 'apellido', 'email', 'profesion','fecha_nacimiento', 'sexo', 'estado_civil', 'curp', 'nss', 'rfc', 'colonia', 'calle', 'codigo_postal', 'numero_casa', 'telefono', 'telefono_contacto_emergencia'];
        return $scenarios;
    }


    /** 
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'numero_empleado' => 'Numero de empleado',
            'usuario_id' => 'Usuario ID',
            'informacion_laboral_id' => 'Informacion Laboral ID',
            'cat_nivel_estudio_id' => 'Cat Nivel Estudio ID',
            'parametro_formato_id' => 'Parametro Formato ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'fecha_nacimiento' => 'Fecha de nacimiento',
            'edad' => 'Edad',
            'sexo' => 'Sexo',
            'foto' => 'Foto',
            'telefono' => 'Telefono',
            'email' => 'Email',
            'estado_civil' => 'Estado Civil',
            'colonia' => 'Colonia',
            'calle' => 'Calle',
            'numero_casa' => 'Numero Casa',
            'codigo_postal' => 'Codigo Postal',
            'nombre_contacto_emergencia' => 'Nombre',
            'relacion_contacto_emergencia' => 'Parentesco',
            'telefono_contacto_emergencia' => 'Telefono',
            'institucion_educativa' => 'Institucion Educativa',
            'titulo_grado' => 'Titulo Grado',
            'profesion' => 'Profesion',
            'curp' => 'CURP',
            'rfc' => 'RFC',
            'nss' => 'NSS',
            'municipio' => 'Municipio',
            'estado' => 'Estado',
            'expediente_medico_id' => 'Expediente Medico ID',





        ];
    }

    /**
     * Gets query for [[Documentos]].
     *
     * @return \yii\db\ActiveQuery
     */
   
     // RELACIONES
    public function getDocumentos()
    {
        return $this->hasMany(Documento::class, ['empleado_id' => 'id']);
    }

    /**
     * Gets query for [[InformacionLaboral]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getInformacionLaboral()
    {
        return $this->hasOne(InformacionLaboral::class, ['id' => 'informacion_laboral_id']);
    }

    /**
     * Gets query for [[Usuario]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuario::class, ['id' => 'usuario_id']);
    }

    public function getCatNivelEstudio()
    {
        return $this->hasOne(CatNivelEstudio::class, ['id' => 'cat_nivel_estudio_id']);
    }
    public function getExpedienteMedico()
    {
        return $this->hasOne(ExpedienteMedico::class, ['id' => 'expediente_medico_id']);
    }


}
