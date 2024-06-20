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
            [['numero_empleado', 'usuario_id', 'informacion_laboral_id', 'nombre', 'apellido', 'email'], 'required'],
            [['numero_empleado', 'usuario_id', 'informacion_laboral_id', 'cat_nivel_estudio_id', 'parametro_formato_id', 'edad', 'codigo_postal', 'expediente_medico_id'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['numero_casa'], 'string', 'max' => 4],
            [['estado_civil', 'sexo'], 'string', 'max' => 12],
            [['nombre'], 'string', 'max' => 30],
            [['apellido'], 'string', 'max' => 60],
            [['foto', 'email'], 'string', 'max' => 100],
            [['telefono', 'telefono_contacto_emergencia'], 'string', 'max' => 15],
            [['colonia'], 'string', 'max' => 50],
            [['profesion'], 'string', 'max' => 15],
            [['calle'], 'string', 'max' => 85],
            [['nombre_contacto_emergencia'], 'string', 'max' => 90],
            [['relacion_contacto_emergencia'], 'string', 'max' => 25],
            [['curp'], 'string', 'max' => 18],
            [['rfc'], 'string', 'max' => 13],
            [['nss'], 'string', 'max' => 13],
            [['municipio', 'estado'], 'string', 'max' => 45],
            [['cat_nivel_estudio_id'], 'exist', 'skipOnError' => true, 'targetClass' => CatNivelEstudio::class, 'targetAttribute' => ['cat_nivel_estudio_id' => 'id']],




            [['institucion_educativa', 'titulo_grado'], 'string', 'max' => 65],
            [['foto'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg, jpeg'],
            [['expediente_medico_id'], 'exist', 'skipOnError' => true, 'targetClass' => ExpedienteMedico::class, 'targetAttribute' => ['expediente_medico_id' => 'id']],

            [['informacion_laboral_id'], 'exist', 'skipOnError' => true, 'targetClass' => InformacionLaboral::class, 'targetAttribute' => ['informacion_laboral_id' => 'id']],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['usuario_id' => 'id']],
        ];
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
