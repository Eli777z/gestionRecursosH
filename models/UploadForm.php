<?php

namespace app\models;

use yii\base\Model;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    public $selectedName;

    /**
     * @var UploadedFile
     */
    public $file;

    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls, xlsx'],
            [['file', 'selectedName'], 'required'], // Ambos campos son requeridos
            ['selectedName', 'validateSelectedName'], // Validación personalizada para asegurar que se seleccione al menos un nombre
            
        ];
    }


    public function attributeLabels()
    {
        return [
            
            'selectedName' => 'Nombre del formato',
        ];
    }
    public function validateSelectedName($attribute, $params)
    {
        if (empty($this->$attribute)) {
            $this->addError($attribute, 'Debes seleccionar al menos un nombre.');
        }
    }


    public function upload()
    {
        if ($this->validate()) {
            // Si selectedName es un array, lo unimos en una cadena separada por comas
            $selectedNames = is_array($this->selectedName) ? implode('_', $this->selectedName) : $this->selectedName;
            $filename = $selectedNames . '.' . $this->file->extension; // Nombre seleccionado
            $path = \Yii::getAlias('@app/templates/') . $filename;
            if ($this->file->saveAs($path)) {
                return true;
            }
        }
        return false;
    }
    
}
