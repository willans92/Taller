<?php
    include_once "../Clases/CONN.php";
    include_once "../Clases/PERSONAL.php";
    error_reporting(0);
    $error="";
    $resultado="";
    $con=new CONN("taller1", "wdigital");
    if(!$con->estado){
        $error="No se pudo establecer conexion. Intente nuevamente.";
        $reponse = array("error" => $error,"result"=>$resultado);
        echo $_GET['callback'].json_encode($reponse);
        return;
    }
    $personal=new PERSONAL($con);
    $cuenta=$_POST["cuenta"];
    $contrasena=$_POST["contra"];
    if($personal->logear($cuenta, $contrasena)>0){
        $re=$personal->estadoUsuario($cuenta, $contrasena);
        if($re!=null){
            session_start();
            $_SESSION["empresa"]=$re["empresa"];
            $_SESSION["personal"]=$re["personal"];
            $resultado="Entro";
        }else{
            $error="Cuenta Bloqueado";
        }
    }else{
        $error="Usuario o Contraseña es incorrecto. Intente nuevamente.";
    }
    $reponse = array("error" => $error,"result"=>$resultado);
    echo $_GET['callback'].json_encode($reponse);
    
    
    
         
    
    
    