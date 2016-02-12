<?php
    include_once "../Clases/CONN.php";
    include_once "../Clases/PERSONAL.php";
    include_once "../Clases/PAGO.php";
    include_once "../Clases/EMPRESA.php";
    error_reporting(0);
    $error="";
    $resultado="";
    session_start();
    $personalsession=$_SESSION["personal"];
    $empresasession=$_SESSION["empresa"];
    $con=new CONN("taller1", "wdigital");
    if(!$con->estado){
        $error="No se pudo establecer conexion. Intente nuevamente.";
        $reponse = array("error" => $error,"result"=>$resultado);
        echo $_GET['callback'].json_encode($reponse);
        return;
    }
    if($personalsession==null || $empresasession==null){
        $error="Error Session";
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
                , $fechaingreso, $cuenta, $contrasena, $sueldo, $cargo, $empresasession);
        if(!$personal->insertar()){
            $error="No se pudo registrar al personal. Intente nuevamente.";
        }
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
        , $cumpleano,'', '', $contrasena,$sueldo, $cargo, $empresasession, $estado, $retirado);  
        if(!$personal->modificar($id)){
            $error="No se pudo actualizar los datos del empleado "+$nombre;
        }
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
        if(!$pago->insertarPagoPersonal($id, $monto, $fecha, $fechacorresponde, $descripcion)){
            $error="No se pudo registrar el pago. Intente nuevamente.";
        }
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
        if($resultado=$pago->insertarPagoOtros($monto, $fecha, $desc)){
            $error="No se pudo registrar el pago. Intente nuevamente.";
        }
    }
    if($proceso==="datosEmpresa"){
        $empresa=new EMPRESA($con);
        $resultado=$empresa->buscarXID($empresasession);
    }
    if($proceso==="actualizardatosEmpresa"){
        $nombre=$_POST["nombre"];
        $rz=$_POST["rz"];
        $logo=$_POST["logo"];
        $aniversario=$_POST["aniversario"];
        $nit=$_POST["nit"];
        $direccion=$_POST["direccion"];
        $fechadosificacion=$_POST["fechadosificacion"];
        $llave=$_POST["llave"];
        $autorizacion=$_POST["autorizacion"];
        $telefono=$_POST["telefono"];
        $empresa=new EMPRESA($con);
        $aux=str_replace('/', '-', $fechadosificacion);
        $fecha_finDosificacion = strtotime ( '+6 month' ,strtotime ($aux)) ;
        $fecha_finDosificacion = date ( 'j/m/Y' , $fecha_finDosificacion );
        $fecha_finDosificacion = strlen($fecha_finDosificacion)==9?"0$fecha_finDosificacion":$fecha_finDosificacion; 
        $resultado=$empresa->contructor($empresasession, $nombre, $rz, $logo, $aniversario, 0, $nit, $direccion, 0, $fechadosificacion, $llave, $autorizacion, $telefono, $fecha_finDosificacion);
        if(!$empresa->modificar($empresasession)){
            $error="No se pudieron actualizar los datos de la empresa. Intenete nuevamente.";
        }else{
            $resultado=$fecha_finDosificacion;
        }
    }
    $reponse = array("error" => $error,"result"=>$resultado);
    echo $_GET['callback'].json_encode($reponse);
    
    
    
         
    
    
    