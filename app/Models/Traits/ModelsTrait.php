<?php


namespace App\Models\Traits;
use App\Models\GeneralFunctions;

class ModelsTrait
{

    public function __call($name, $arguments)
    {
        GeneralFunctions::console('Método '.$name." no existe");
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