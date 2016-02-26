<?php

class Herramientas {

    function HERRAMIENTASPHP() {
        
    }

    function validar($tipo, $texto) {
        $texto=trim($texto);
        switch ($tipo) {
            case "texto":
                $expresion = '/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i';
                if (!preg_match('/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i', $texto)) {
                    return true;
                }

                break;
            case "vacio":
                if (strlen($texto) > 0) {
                    return true;
                }
                break;
            case "entero":
                if (!is_int($texto)) {
                    return true;
                }
                break;
            case "decimal":
                /* if(is_float($texto)){
                  return true;
                  } */
                return true;
                break;
            case "texto y entero":
                $expresion = "/^[0-9a-zA-Z\.\,\s-_º()=?¿/%$@!:;{}óíáéúñÍÁÉÚÓ]+$/";
                if (!preg_match($expresion, $texto)) {
                    return true;
                }
                break;
            case "correo":
                if (!filter_var($texto, FILTER_VALIDATE_EMAIL) === false) {
                     return true;
                } 
                break;
        }
        return false;
    }

}
