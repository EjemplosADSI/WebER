<?php


namespace App\Modelos;


class GeneralFunctions
{

    public function getVersionPackage()
    {
        //TODO: Generar una funcion para obtener la ruta absoluta del proyecto

        $data = array();
        $packages = json_decode(file_get_contents('../vendor/composer/installed.json'));
        // Assuming that the project root is one level above the web root.
        foreach ($packages as $package) {
            $data[$package['name']] = $package['version'];
        }
    }
    
}