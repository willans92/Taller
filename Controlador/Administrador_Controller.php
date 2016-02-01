<?php
    include_once "../Clases/CONN.php";
    include_once "../Clases/PERSONAL.php";
    include_once "../Clases/PAGO.php";
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
    if($proceso==="buscarEmpleado"){
        $text=$_POST["text"];
        $estado=$_POST["estado"];
        $resultado=$personal->BuscarPersonal($text,$estado);
    }
    if($proceso==="actualizarPersonal"){
        $ci=$_POST["ci"];
        $nombre=$_POST["nombre"];
        $direccion=$_POST["direccion"];
        $correo=$_POST["correo"];
        $cumpleano=$_POST["cumpleano"];
        $sueldo=$_POST["sueldo"];
        $cargo=$_POST["cargo"];
        $estado=$_POST["estado"];
        $perfil=$_POST["perfil"];
        $contrasena=$_POST["contra"];
        $retirado=$_POST["retirado"];
        $id=$_POST["id"];
        $personal->contructor(0, $perfil, $ci, $nombre, $direccion, $correo   
        , $cumpleano,'', '', $contrasena,$sueldo, $cargo, 0, $estado, $retirado);  
        $resultado=$personal->modificar($id);
    }
    if($proceso==="buscarEmpleadoPago"){
        $text=$_POST["text"];
        $estado=$_POST["estado"];
        $ano=$_POST["ano"];
        $mes=$_POST["mes"];
        $resultado=$personal->BuscarPersonalAPagar($text,$estado,$ano,$mes);
    }
    if($proceso==="datosPersonal"){
        $id=$_POST["personal"];
        $mes=$_POST["mes"];
        $ano=$_POST["ano"];
        $resultado=array();
        $pago=new PAGO($con);
        $resultado["persona"]=$personal->buscarXID($id);
        $resultado["tabla"]=$pago->buscarXPersona($id,$mes,$ano);
    }
    if($proceso==="pagarSueldo"){
        $id=$_POST["personal"];
        $monto=$_POST["monto"];
        $fecha=$_POST["fecha"];
        $fechacorresponde=$_POST["fechacorresponde"];
        $descripcion=$_POST["descripcion"];
        $pago=new PAGO($con);
        $pago->insertarPagoPersonal($id, $monto, $fecha, $fechacorresponde, $descripcion);
    }
    if($proceso==="buscarOtroPago"){
        $text=$_POST["text"];
        $estado=$_POST["estado"];
        $ano=$_POST["ano"];
        $mes=$_POST["mes"];
        $pago=new PAGO($con);
        $resultado=$pago->buscarOtroPago($text, $mes, $ano, $estado);
    }
    if($proceso==="pagarotros"){
        $fecha=$_POST["fecha"];
        $monto=$_POST["monto"];
        $desc=$_POST["desc"];
        $pago=new PAGO($con);
        $resultado=$pago->insertarPagoOtros($monto, $fecha, $desc);
    }
    $reponse = array("error" => $error,"result"=>$resultado);
    echo $_GET['callback'].json_encode($reponse);
    
    
    
         
    
    
    