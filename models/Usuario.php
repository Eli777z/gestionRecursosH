<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuario".
 *
 * @property int $id
 * @property string $username
 * @property string $password
 * @property int $status
 * @property int $rol
 */
class Usuario extends \yii\db\ActiveRecord implements \yii\web\IdentityInterface
{
 
   
    const SCENARIO_CREATE = 'create';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuario';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username','status', 'rol'], 'required'],
            [['status', 'rol'], 'integer'],
            [['username'], 'string', 'max' => 30],
            [['password'], 'string', 'max' => 64],
            [['username', 'password', 'status'], 'required', 'on'=> self:: SCENARIO_CREATE],
        ];
    }
 
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Username',
            'password' => 'Password',
            'status' => 'Status',
            'rol' => 'Rol',
        ];
    }
   // public $rol;

   public static function isUserAdmin($id)
   {
       $user = self::findOne(['id' => $id, 'status' => '10']);
       return $user !== null && $user->rol === 2;
   }

   public static function isUserSimple($id)
   {
       $user = self::findOne(['id' => $id, 'status' => '10']);
       return $user !== null && $user->rol === 1;
   }




      //Este lo pide pero lo dejamos como null por que no lo usamos por el momento
      public function getAuthKey() {
        return null;
       //return $this->auth_key;
    }    
    
    // interface
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() == $authKey;
    }
    
    // interface
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new \yii\base\NotSupportedException("'findIdentityByAccessToken' is not implemented");
    }
    
    /* Identity Interface */
    public function getId(){
        return $this->id;
    }
        
    public static function findIdentity($id) {
        return static::findOne(['id' => $id]);
    }
    
   // Utilizamos el mismo nombre de método que definimos como el nombre de usuario
    public static function findByUserName($username) {
        return static::findOne(['username' => $username]);
    }
    
    
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password);
    }
}
