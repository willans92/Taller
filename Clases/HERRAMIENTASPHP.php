<?php
class Herramientas{
function HERRAMIENTASPHP(){}
function validar($tipo,$texto){
    $texto.=" ";
    switch ($tipo){
        case "texto":
            $expresion='/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i';
            if(!preg_match('/^[A-Z üÜáéíóúÁÉÍÓÚñÑ]{1,50}$/i',$texto)){
                return true;
            }
         
            break;
        case "vacio":
           if(strlen($texto)>0){
              return true;
            }
            break;
        case "entero":
            
            if(!preg_match("/^[0-9\s]+$/", $texto)){
               return true;
            }
            break;
        case "texto y entero":
            $expresion="/^[0-9a-zA-Z\.\,\s-_º()=?¿/%$@!:;{}óíáéúñÍÁÉÚÓ]+$/";
            if(!preg_match($expresion, $texto)){
                  return true;
            }                        
            break;  
             case "email":
           $expresion="/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/";
            if(!preg_match($expresion, $texto)){
                return true;
            }
            break;
    }
     return false;
}
}
