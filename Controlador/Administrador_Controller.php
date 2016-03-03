<?php
    include_once "../Clases/CONN.php";
    include_once "../Clases/PERSONAL.php";
    include_once "../Clases/PAGO.php";
    include_once "../Clases/EMPRESA.php";
    include_once "../Clases/HERRAMIENTASPHP.php";
    error_reporting(0);
    $error="";
    $resultado="";
    session_start();
    $personalsession=$_SESSION["personal"];
    $empresasession=$_SESSION["empresa"];
    $Herramienta = new Herramientas();
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
        $recontrasena=$_POST["recontrasena"];
        if(strlen($ci)===0){
            $error.="<p>El carnet no puede ser vacío.</p>";
        }
        if (!$Herramienta->validar("entero",$ci)){
            $error.="<p>Ingrese un carnet valido.</p>";
        }
        if(strlen($nombre)===0){
            $error.="<p>El nombre no puede estar vacío.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$nombre)){
            $error.="<p>El nombre no puede tener caracteres especiales.</p>";
        }
        if(strlen($direccion)===0){
            $error.="<p>La dirección no puede estar vacía.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$direccion)){
            $error.="<p>La dirección no puede tener caracteres especiales.</p>";
        }
        if (!$Herramienta->validar("correo",$correo) && strlen($correo)>0){
            $error.="<p>El correo no es valido.</p>";
        }
        if(floatval($sueldo)<0){
            $error.="<p>No ha especifiado el sueldo.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$cuenta)){
            $error.="<p>La cuenta no puede tener caracteres especiales.</p>";
        }
        if(!(strlen($cuenta)>=4 && strlen($cuenta)<9)){
            $error.="<p>La cuenta tiene que ser mayor a 4 caracteres y menor a 8.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$contrasena)){
            $error.="<p>La contrasena no puede tener caracteres especiales.</p>";
        }
        if(!(strlen($contrasena)>=4 && strlen($contrasena)<9)){
            $error.="<p>La contraseña tiene que ser mayor a 4 caracteres y menor a 8.</p>";
        }
        if($contrasena!==$recontrasena){
            $error.="<p>Las contraseñas no coinciden.</p>";
        }
        if($error==""){
            if($personal->revisarCuenta($cuenta)){
                $error="La cuenta no se encuentra disponible.";
            }else{
                $personal->contructor(0, $perfil, $ci, $nombre, $direccion, $correo, $cumpleano
                        , $fechaingreso, $cuenta, $contrasena, $sueldo, $cargo, $empresasession);
                if(!$personal->insertar()){
                    $error="No se pudo registrar al personal. Intente nuevamente.";
                }    
            }    
        }
        
        
    }
    if($proceso==="buscarEmpleado"){
        $text=$_POST["text"];
         if (!$Herramienta->validar("texto y entero",$text)){
            $error="El criterio de busqueda no puede tener caracteres especiales.";
        }else{
            $estado=$_POST["estado"];
            $resultado=$personal->BuscarPersonal($text,$estado);
        }
        
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
        if(strlen($ci)===0){
            $error.="<p>El carnet no puede ser vacío.</p>";
        }
        if (!$Herramienta->validar("entero",$ci)){
            $error.="<p>Ingrese un carnet valido.</p>";
        }
        if(strlen($nombre)===0){
            $error.="<p>El nombre no puede estar vacío.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$nombre)){
            $error.="<p>El nombre no puede tener caracteres especiales.</p>";
        }
        if(strlen($direccion)===0){
            $error.="<p>La dirección no puede estar vacía.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$direccion)){
            $error.="<p>La dirección no puede tener caracteres especiales.</p>";
        }
        if (!$Herramienta->validar("correo",$correo) && strlen($correo)>0){
            $error.="<p>El correo no es valido.</p>";
        }
        if(floatval($sueldo)<0){
            $error.="<p>No ha especifiado el sueldo.</p>";
        }
        if (!$Herramienta->validar("texto y entero",$contrasena)){
            $error.="<p>La contrasena no puede tener caracteres especiales.</p>";
        }
        if(strlen($contrasena)>0 && !(strlen($contrasena)>=4 && strlen($contrasena)<9)){
            $error.="<p>La contraseña tiene que ser mayor a 4 caracteres y menor a 8.</p>";
        }
        if($error==""){
            $personal->contructor(0, $perfil, $ci, $nombre, $direccion, $correo   
            , $cumpleano,'', '', $contrasena,$sueldo, $cargo, $empresasession, $estado, $retirado);  
            if(!$personal->modificar($id)){
                $error="No se pudo actualizar los datos del empleado "+$nombre;
            }    
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
    if($proceso==="reinicioOT"){
        $empresa=new EMPRESA($con);
        $resultado=$empresa->reinicioOT($empresasession);
    }
    if($proceso==="estadoPago"){
        $id=$_POST["id"];
        $estado=$_POST["estado"];
        $pago=new PAGO($con);
        if(!$pago->modificarestado($id,$estado)){
            $error="No se pudo cambiar el estado al pago. Intente nuevamente.";
        }
    }
    if($proceso==="pagarSueldo"){
        $id=$_POST["personal"];
        $monto=floatval($_POST["monto"]);
        $saldo=floatval($_POST["saldo"]);
        $fecha=$_POST["fecha"];
        $fechacorresponde=$_POST["fechacorresponde"];
        $descripcion=$_POST["descripcion"];
        if($monto<=0){
            $error.="<p>No puede realizar un pago negativo de sueldo.</p>";
        }
        if(monto>saldo){
            $error.="<p>No tiene suficiente saldo para realizar ese pago. Baya al siguiente mes para darle un adelanto.</p>";
        }
        if (!$Herramienta->validar("texto y entero",desc)){
            $error.="<p>La descripción del pago no puede tener caracteres especiales.</p>";
        }
        if($error==""){
            $pago=new PAGO($con);
            if(!$pago->insertarPagoPersonal($id, $monto, $fecha, $fechacorresponde, $descripcion)){
                $error="No se pudo registrar el pago. Intente nuevamente.";
            }   
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
        if (!$Herramienta->validar("texto y entero",$desc)){
            $error.="La descripción no puede tener caracteres especiales";
        }
        if(floatval($monto)<=0){
            $error.="No ha especificado el monto pagado";
        }
        if($error==""){
            $pago=new PAGO($con);
            if(!$resultado=$pago->insertarPagoOtros($monto, $fecha, $desc,$personalsession)){
                $error="No se pudo registrar el pago. Intente nuevamente.";
            }   
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
        $correo=$_POST["correo"];
        if(strlen(nit)===0){
           $error.="<p>El nit no puede estar vacío.</p>";
        }
        if (!$Herramienta->validar("texto y entero",nit)){
           $error.="<p>El nit no puede tener caracteres especiales.</p>";
        }
        if(strlen(nombre)===0){
           $error.="<p>El nombre no puede estar vacío.</p>";
        }
        if (!$Herramienta->validar("texto y entero",nombre)){
           $error.="<p>El nombre no puede tener caracteres especiales.</p>";
        }
        if(strlen(rz)===0){
           $error.="<p>La razon social no puede estar vacía.</p>";
        }
        if (!$Herramienta->validar("texto y entero",rz)){
           $error.="<p>La razon social no puede tener caracteres especiales.</p>";
        }
        if (!$Herramienta->validar("texto y entero",direccion)){
           $error.="<p>El dirección no puede tener caracteres especiales.</p>";
        }
        if (!$Herramienta->validar("texto y entero",telefono)){
           $error.="<p>El telefono no puede tener caracteres especiales.</p>";
        }  
        if (!$Herramienta->validar("texto y entero",autorizacion)){
           $error.="<p>La autorizacion no puede tener caracteres especiales.</p>";
        }
        $empresa=new EMPRESA($con);
        /*$aux=str_replace('/', '-', $fechadosificacion);
        $fecha_finDosificacion = strtotime ( '+6 month' ,strtotime ($aux)) ;
        $fecha_finDosificacion = date ( 'j/m/Y' , $fecha_finDosificacion );
        $fecha_finDosificacion = strlen($fecha_finDosificacion)==9?"0$fecha_finDosificacion":$fecha_finDosificacion; */
        if($error==""){
            $resultado=$empresa->contructor($empresasession, $nombre, $rz, $logo, $aniversario, 0, $nit, $direccion, 0, $fechadosificacion, $llave, $autorizacion, $telefono, $fecha_finDosificacion,0,$correo);
            if(!$empresa->modificar($empresasession)){
                $error="No se pudieron actualizar los datos de la empresa. Intenete nuevamente.";
            }else{
                $resultado=$fecha_finDosificacion;
            }    
        }
    }
    $reponse = array("error" => $error,"result"=>$resultado);
    echo $_GET['callback'].json_encode($reponse);
    
    
    
         
    
    
    