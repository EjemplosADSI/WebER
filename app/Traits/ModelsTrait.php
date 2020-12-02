<?php


namespace App\Traits;

use App\Models\GeneralFunctions;

class ModelsTrait
{

    public function __call($name, $arguments)
    {
        GeneralFunctions::logFile('Metodo Inexistente','El método '.$name." en la clase ".__CLASS__." no existe");
    }

    /*
    public function __set($name, $value)
    {
        if(property_exists(get_called_class(), $name)){
            $this->$name = $value;
        }else{
            GeneralFunctions::console('Propiedad '.$name." no existe");
        }
    }

    public function __get($name)
    {
        if (property_exists(get_called_class(), $name)) {
            return $this->$name;
        }else{
            return null;
        }
    }
    */

}