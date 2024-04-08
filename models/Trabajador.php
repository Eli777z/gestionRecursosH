<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "trabajador".
 *
 * @property int $id
 * @property string $nombre
 * @property string $apellido
 * @property string $email
 * @property string $fecha_nacimiento
 * @property int $codigo_postal
 * @property string $calle
 * @property int $numero_casa
 * @property string $telefono
 * @property string $colonia
 * @property string $foto
 * @property int $idusuario
 *
 * @property Expediente[] $expedientes
 * @property Usuario $idusuario0
 */
class Trabajador extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'trabajador';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['nombre', 'apellido', 'email', 'fecha_nacimiento', 'codigo_postal', 'calle', 'numero_casa', 'telefono', 'colonia', 'idusuario'], 'required'],
            [['fecha_nacimiento'], 'safe'],
            [['codigo_postal', 'numero_casa', 'idusuario'], 'integer'],
            [['nombre'], 'string', 'max' => 30],
            [['apellido'], 'string', 'max' => 60],
            [['email', 'foto'], 'string', 'max' => 100],
            [['email'], 'email'],
            [['calle'], 'string', 'max' => 85],
            [['telefono'], 'string', 'max' => 15],
            ['telefono', 'match', 'pattern' => '/^[0-9]{10}$/'], 
            [['colonia'], 'string', 'max' => 35],
            [['foto'], 'file', 'skipOnEmpty' => TRUE, 'extensions' => 'png, jpg'],
            
            [['idusuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuario::class, 'targetAttribute' => ['idusuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'nombre' => 'Nombre',
            'apellido' => 'Apellido',
            'email' => 'Email',
            'fecha_nacimiento' => 'Fecha Nacimiento',
            'codigo_postal' => 'Codigo Postal',
            'calle' => 'Calle',
            'numero_casa' => 'Numero Casa',
            'telefono' => 'Telefono',
            'colonia' => 'Colonia',
            'foto' => 'Foto',
            'idusuario' => 'Idusuario',
        ];
    }

    /**
     * Gets query for [[Expedientes]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getExpedientes()
    {
        return $this->hasMany(Expediente::class, ['idtrabajador' => 'id']);
    }

    /**
     * Gets query for [[Idusuario0]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getIdusuario0()
    {
        return $this->hasOne(Usuario::class, ['id' => 'idusuario']);
    }
}
