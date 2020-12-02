<?php

namespace App\Traits;

use HtmlSanitizer\Sanitizer;

trait SanitizerFields
{

    public function sanitize(array $arrayFields = [])
    {
        $fieldsClear = [];
        if(!empty($arrayFields)){
            $sanitizer = Sanitizer::create(['extensions' => ['basic']]);
            foreach ($arrayFields as $name => $value){
                if(!empty($value)){
                    $fieldsClear[$name] = $sanitizer->sanitize($value);
                }else{
                    $fieldsClear[$name] = $value;
                }
            }
        }
        return $fieldsClear;
    }
}