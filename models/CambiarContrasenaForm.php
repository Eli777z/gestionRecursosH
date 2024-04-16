<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 
 */
class CambiarContrasenaForm extends Model
{
    public $oldPassword;
    public $newPassword;
    public $repeatNewPassword;

    /**
     * @return array las reglas de validación.
     */
    public function rules()
    {
        return [
            [['oldPassword', 'newPassword', 'repeatNewPassword'], 'required'],
            ['oldPassword', 'validateOldPassword'],
            ['newPassword', 'string', 'min' => 8],
            ['repeatNewPassword', 'compare', 'compareAttribute' => 'newPassword', 'message' => "Las contraseñas no coinciden."],
        ];
    }

    /**
     * Valida la contraseña antigua.
     */
    public function validateOldPassword($attribute, $params)
    {
        $user = Usuario::findOne(Yii::$app->user->identity->id);
        if (!$user || !Yii::$app->security->validatePassword($this->oldPassword, $user->password)) {
            $this->addError($attribute, 'La contraseña antigua es incorrecta.');
        }
    }

    /**
     * @return array etiquetas personalizadas de los atributos.
     */
    public function attributeLabels()
    {
        return [
            'oldPassword' => 'Contraseña Antigua',
            'newPassword' => 'Nueva Contraseña',
            'repeatNewPassword' => 'Repetir Nueva Contraseña',
        ];
    }
}
