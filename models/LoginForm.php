<?php
// IMPORTACIONES
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read User|null $user
 *
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;

    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }
    public function attributeLabels()
    {
        return [
           
            'password' => 'Contraseña',
          
            'username' => 'Usuario',

            'rememberMe' => 'Recuerdame'
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
   
   /// FUNCION PARA VALIDAR LA CONTRASEÑA
     public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

          

          //  if(!$user || $user->status === 0 ){
               
            //   $this->addError($attribute, 'Usuario inactivo');
              
           //}
           if(!$user){
            $this->addError($attribute, 'Usuario no encontrado.');
           }
           elseif( $user->status === 0){
            $this->addError($attribute, 'Usuario inactivo.');

        }
           else
           {
            if(!$user->validatePassword($this->password))
                $this->addError($attribute, 'Usuario o Contraseña incorrecta.');
                //echo "$user->status";
                 echo '<script>console.log("'.$user->status.'");</script>';
           
            
               // $this->addError($attribute, 'usuario activo.');
            }           

        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    //FUNCION QUE VALIDA EL USUARIO
    public function login()
    {
        $usuario = $this->getUser(); 
        if ($this->validate()) {
            if($usuario){ 
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600*24*30 : 0);
            }
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    // FUNCION QUE OBTIENE Y VERIFICA SI EXISTE REGISTRO DE LAS CREDENCIALES INGRESADAS POR EL USUARIO
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuario::findByUsername($this->username); 
        }

        return $this->_user;
    }
}
