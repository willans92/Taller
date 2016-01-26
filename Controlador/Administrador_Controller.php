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
    $proceso=$_POST["proceso"];
    if($proceso==="registroPersonal"){
        $ci=$_POST["ci"];
        $nombre=$_POST["nombre"];
        $direccion=$_POST["direccion"];
        $correo=$_POST["correo"];
        $cumpleano=$_POST["cumpleano"];
        $fechaingreso=$_POST["fechaingreso"];
        $sueldo=$_POST["sueldo"];
        $cargo=$_POST["cargo"];
        $cuenta=$_POST["cuenta"];
        $perfil=$_POST["perfil"];
        $contrasena=$_POST["contrasena"];
        $personal->contructor(0, $perfil, $ci, $nombre, $direccion, $correo, $cumpleano
                , $fechaingreso, $cuenta, $contrasena, $sueldo, $cargo, 1);//id empresa el ultimo
        $personal->insertar();
    }
    $reponse = array("error" => $error,"result"=>$resultado);
    echo $_GET['callback'].json_encode($reponse);
    
    
    
         
    
    
    