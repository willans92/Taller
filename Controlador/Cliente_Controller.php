<?php
include_once "../Clases/CONN.php";
include_once "../Clases/CLIENTE.php";
include_once "../Clases/HERRAMIENTASPHP.php";
include_once "../Clases/VEHICULO.php";
include_once "../Clases/MARCA.php";
include_once "../Clases/AUTO.php";
include_once "../Clases/ACCESORIO.php";
include_once "../Clases/TRABAJO.php";
include_once "../Clases/PERSONAL.php";
include_once "../Clases/REPARACION.php";
include_once "../Clases/DETALLE_REPARACION.php";
include_once "../Clases/ACCESORIO_REPARACION.php";
include_once "../Clases/PAGO.php";

error_reporting(0);
$error = "";
$resultado = "";
session_start();
$personalsession = $_SESSION["personal"];
$empresasession = $_SESSION["empresa"];
$Herramienta = new Herramientas();
$proceso = $_POST["proceso"];
$con = new CONN("taller1", "wdigital");
if (!$con->estado) {
    $error = "No se pudo establecer conexion. Intente nuevamente.";
    $reponse = array("error" => $error, "result" => $resultado);
    echo $_GET['callback'] . json_encode($reponse);
    return;
}
if ($personalsession == null || $empresasession == null) {
    $error = "Error Session";
    $reponse = array("error" => $error, "result" => $resultado);
    echo $_GET['callback'] . json_encode($reponse);
    return;
}
if ($proceso == "buscarClientes") {
    $cliente = new CLIENTE($con);
    $text = $_POST['text'];
    if (!$Herramienta->validar("texto y entero", $text)) {
        $error = "<p>El criterio de busqueda no puede tener caracteres especiales.</p>";
    } else {
        $resultado = $cliente->buscarTexto($text, $empresasession);
    }
}
if ($proceso == "crearMarca") {
    $marca=new MARCA($con);
    $text = $_POST['text'];
    if (!$Herramienta->validar("texto y entero", $text)) {
        $text.="<p>No se aceptan caracteres especiales en la descripcion de la marca.</p>";
    }else{
        $marca->descripcion=$text;
        $marca->empresa_id=$empresasession;
        $resultado=$marca->insertar();
        if($resultado==0){
            $error="No se pudo registrar la marca. Intente nuevamente";
        }
    }
}
if ($proceso == "crearVehiculo") {
    $vehiculo=new VEHICULO($con);
    $text = $_POST['text'];
    if (!$Herramienta->validar("texto y entero", $text)) {
        $text.="<p>No se aceptan caracteres especiales en la descripcion del Vehiculo.</p>";
    }else{
        $vehiculo->descripcion=$text;
        $vehiculo->empresa_id=$empresasession;
        $resultado=$vehiculo->insertar();
        if($resultado==0){
            $error="No se pudo registrar el vehiculo. Intente nuevamente";
        }
    }
}
if ($proceso == "eliminarAuto") {
    $auto = new AUTO($con);
    $id = $_POST['id'];
    if (!$auto->eliminar($id)) {
        $error = "<p>No se puede eliminar este auto ya posee historial.</p>";
    } 
}
if ($proceso == "cliente") {
    $cliente = new CLIENTE($con);
    $vehiculo = new VEHICULO($con);
    $marca = new MARCA($con);
    $auto = new AUTO($con);
    $id = $_POST['id'];
    $resultado = array();
    $resultado["cliente"] = $cliente->buscarXID($id);
    if ($resultado == null) {
        $error = "Se corto la conexion mientras se traían los datos. Intente nuevamente.";
    } else {
        $resultado["vehiculo"] = $vehiculo->buscarXEmpresa($empresasession);
        $resultado["marca"] = $marca->buscarXEmpresa($empresasession);
        $resultado["auto"] = $auto->buscarXCliente($id);
    }
}
if ($proceso == "abrirReparacion") {
    $auto = new AUTO($con);
    $personal = new PERSONAL($con);
    $resultado = array();
    $trabajoReparacion=new DETALLE_REPARACION($con);
    $accesorioReparacion=new ACCESORIO_REPARACION($con);
    $reparacion= new REPARACION($con);
    $id = $_POST['auto'];
    $idreparacion= $_POST['idreparacion'];
    $resultado["auto"] = $auto->buscarXID($id);
    $resultado["mecanico"] = $personal->BuscarMecanico();
    if($idreparacion==0){
        $resultado["reparacion"] = $reparacion->buscarXAuto($id);
    }else{
        $resultado["reparacion"] = $reparacion->buscarXId($idreparacion);
    }
    if($resultado["reparacion"]==null){
        $resultado["trabajo"]=null;
        $resultado["accesorio"]=null;
    }else{
        $reparacionid=$resultado["reparacion"]->id_reparacion;
        $resultado["trabajo"] = $trabajoReparacion->buscarXReparacion($reparacionid);
        $resultado["accesorio"] = $accesorioReparacion->buscarXReparacion($reparacionid);
    }
}
if ($proceso == "lsitaAccesorios") {
    $accesorios = new ACCESORIO($con);
    $resultado = $accesorios->buscarXEmpresa($empresasession);
}
if ($proceso == "registrarReparacion") {
    $accesorio = $_POST['accesorio'];
    $reparacion= new REPARACION($con);
    $trabajo = $_POST['trabajo'];
    $mecanico = $_POST['mecanico'];
    $ot = $_POST['ot'];
    $km = $_POST['km'];
    $auto = $_POST['auto'];
    $ingreso = $_POST['ingreso'];
    $salida = $_POST['salida'];
    $combustible = $_POST['combustible'];
    $estado= $_POST['estado'];
    $idreparacion = $_POST['idreparacion'];
    $con->transacion();
    $reparacion->contructor($idreparacion, $ingreso,$salida, $km, $combustible, $ot, $auto, $total,$estado, $mecanico);
    if($idreparacion==0){
        $resultado=$reparacion->insertar();
        $idreparacion=$resultado;
    }else{
        $resultado=$reparacion->modificar($idreparacion);
    }
    $total=  0.0;
    if($resultado==0){
        $error="No se pudo registrar la reparacion del auto.";
        $con->rollback();
    }else{
        $trabajoReparacion=new DETALLE_REPARACION($con);
        $trabajoReparacion->eliminar($idreparacion);
        for ($i = 0; $i < count($trabajo); $i++) {
            $trabajoReparacion=new DETALLE_REPARACION($con);
            $total=$total+  floatval($trabajo[$i]["precio"]);
            $trabajoReparacion->contructor($idreparacion, $trabajo[$i]["id"],$trabajo[$i]["precio"]);
            if(!$trabajoReparacion->insertar()){
                $error="No se pudo registrar la reparacion del auto.";
                $con->rollback();
                break;
            }
        }
        if($error==""){
            if($estado=="activo"){
                $pago=new PAGO($con);
                $pagado=floatval($pago->buscarXReparacionTotal($idreparacion));
                if($total>$pagado){
                    $estado="activo falta pago";
                }
            }
            if(!$reparacion->modificarTotal($idreparacion,$total,$estado)){
                $error="No se pudo registrar la reparacion del auto.";
                $con->rollback();
            }else{
                $accesorioReparacion=new ACCESORIO_REPARACION($con);
                $accesorioReparacion->eliminar($idreparacion);
                for ($i = 0; $i < count($accesorio); $i++) {
                    $accesorioReparacion=new ACCESORIO_REPARACION($con);
                    $accesorioReparacion->contructor($idreparacion, $accesorio[$i]);
                    if(!$accesorioReparacion->insertar()){
                        $error="No se pudo registrar la reparacion del auto.";
                        $con->rollback();
                        break;
                    }
                }    
            }
        }
    }
    if($error==""){
        $con->commit();
    }
}
if ($proceso == "listaTrabajo") {
    $trabajo= new TRABAJO($con);
    $resultado = $trabajo->buscarXEmpresa($empresasession);
}
if ($proceso == "detallepagoreparacion") {
    $pago= new PAGO($con);
    $id= $_POST['reparacion'];
    $resultado = $pago->buscarXReparacion($id);
}
if ($proceso == "historialReparacion") { 
    $rep= new REPARACION($con);
    $de= $_POST['de'];
    $hasta= $_POST['hasta'];
    $auto= $_POST['auto'];
    $cliente= $_POST['cliente'];
    if($auto==0){
        $resultado = $rep->buscarXHistorial($cliente,$de,$hasta);
    }else{
        $resultado = $rep->buscarXHistorial($cliente,$de,$hasta);
    }
}
if ($proceso == "pagarReparacion") {
    $pago= new PAGO($con);
    $id= $_POST['reparacion'];
    $monto= $_POST['monto'];
    $fecha= $_POST['fecha'];
    $desc= $_POST['desc'];
    $pago->contructor(0, $fecha, $monto, $id, "REPARACION",$desc, $personalsession, "ACTIVO", "");
    $resultado = $pago->insertarPagoReparacion();
    if(!$resultado){
        $error="No se pudo registrar el pago. Intenete nuevamente";
    }
}
if ($proceso == "crearTrabajo") {
    $trabajo= new TRABAJO($con);
    $desc = $_POST['desc'];
    $precio = $_POST['precio'];
    $idtrabajo = $_POST['trabajo'];
    if (!$Herramienta->validar("texto y entero",$desc)){
        $error.="<p>La descripcion no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("decimal",$precio)){
        $error.="<p>El precio solo acepta datos numericos.</p>";
    }
    if(floatval($precio)<0){
        $error.="<p>El precio no puede ser negativo.</p>";
    }
    $trabajo->contructor($idtrabajo, $desc, $precio, $empresasession);
    if($error==""){
        if($idtrabajo==0){
            $resultado=$trabajo->insertar();
            if($resultado==0){
                $error="No se pudo insertar el nuevo trabajo. Intente nuevamente.";
            }
        }else{
            $resultado=$trabajo->modificar($idtrabajo);
            if($resultado==false){
                $error="No se pudo modificar el trabajo. Intente nuevamente.";
            }
        }   
    }
}
if ($proceso == "crearAccesorio") {
    $accesorios = new ACCESORIO($con);
    $text = $_POST['text'];
    if (!$Herramienta->validar("texto y entero",text)){
        $error.="No puede tener caracteres especiales el nuevo accesorio.";
    }
    if(strlen($text)>50){
        $error.="El nuevo accesorio no puede exceder de los 50 caracteres.";
    }
    if(strlen($error)==0){
        $accesorios->id_empresa=$empresasession;
        $accesorios->descripcion=$text;
        $resultado = $accesorios->insertar();
        if($resultado==0){
            $error="No se pudo crear el nuevo accesorio. Intenete nuevamente.";
        }
    }
}
if ($proceso == "registroCliente") {
    $ci = $_POST['ci'];
    $nombre = $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $celular = $_POST['celular'];
    $casa = $_POST['casa'];
    $clienteId = $_POST['cliente'];
    $oficina = $_POST['oficina'];
    $foto = $_POST['foto'];
    $text = "";
    if (!$Herramienta->validar("texto y entero", ci)) {
        $text.="<p>El ci no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("vacio", $nombre)) {
        $text.="<p>El nombre no puede ser vacío.</p>";
    }
    if (!$Herramienta->validar("texto y entero", nombre)) {
        $text.="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("texto y entero", direccion)) {
        $text.="<p>La direccion no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("entero", casa)) {
        $text.="<p>El telefono de la casa del cliente solo acepta numero.</p>";
    }
    if (!$Herramienta->validar("entero", oficina)) {
        $text.="<p>El telefono de la oficina del cliente solo acepta numero.</p>";
    }
    if (!$Herramienta->validar("entero", celular)) {
        $text.="<p>El telefono celular del cliente solo acepta numero.</p>";
    }
    if (strlen($text) > 0) {
        $error = $text;
        $reponse = array("error" => $error, "result" => $resultado);
        echo $_GET['callback'] . json_encode($reponse);
        return;
    }
    $cliente = new CLIENTE($con);
    $cliente->contructor($clienteId, $foto, $nombre, $direccion, $casa, $oficina, $celular, $ci, $empresasession);
    $resultado = array();
    if ($clienteId == "0") {
        $vehiculo = new VEHICULO($con);
        $marca = new MARCA($con);
        $resultado["cliente"] = $cliente->insertar();
        if ($resultado["cliente"] === 0) {
            $error = "<p>No se logro insertar el nuevo cliente. Intenete nuevamente.</p>";
        } else {
            $resultado["vehiculo"] = $vehiculo->buscarXEmpresa($empresasession);
            $resultado["marca"] = $marca->buscarXEmpresa($empresasession);
        }
    } else {
        $con->transacion();
        if ($cliente->modificar($clienteId)) {
            $resultado["cliente"] = $clienteId;
            $listaAuto = $_POST['vehiculos'];
            foreach ($listaAuto as $value) {
                $vehiculo = $value["vehiculo"];
                $marca = $value["marca"];
                $modelo = $value["modelo"];
                $chasis = $value["chasis"];
                $color = $value["color"];
                $placa = $value["placa"];
                $observacion = $value["observacion"];
                $idauto = $value["id"];
                if ($vehiculo == "0") {
                    $error = "<p>No ha seleccionado el tipo de vehiculo de un auto.</p>";
                    break;
                }
                if ($marca == "0") {
                    $error = "<p>No ha seleccionado la marca de un auto.</p>";
                    break;
                }
                if (!$Herramienta->validar("texto y entero", $modelo)) {
                    $error = "<p>El modelo no puede tener caracteres especiales.</p>";
                    break;
                }
                if (!$Herramienta->validar("texto y entero", $chasis)) {
                    $error = "<p>El chasis no puede tener caracteres especiales.</p>";
                    break;
                }
                if (!$Herramienta->validar("texto y entero", $color)) {
                    $error = "<p>El color no puede tener caracteres especiales.</p>";
                    break;
                }
                if (!$Herramienta->validar("texto y entero", $placa)) {
                    $error = "<p>El color no puede tener caracteres especiales.</p>";
                    break;
                }
                if (!$Herramienta->validar("texto y entero", $observacion)) {
                    $error = "<p>La observacion no puede tener caracteres especiales.</p>";
                    break;
                }
                if (strlen(observacion)>200) {
                    $error = "<p>La observacion no puede tener mas de 200 caracteres.</p>";
                    break;
                }
                $auto = new AUTO($con);
                $auto->contructor($idauto, $modelo, $placa, $color, $chasis, $observacion, $vehiculo, $marca, $clienteId);
                if ($idauto == "0") {
                    if (!$auto->insertar()) {
                        $error = "No se pudo registrar un auto. Intente nuevamente.";
                        break;
                    }
                } else {
                    if (!$auto->modificar($idauto)) {
                        $error = "No se pudo actualizar un auto. Intente nuevamente.";
                        break;
                    }
                }
            }
            if ($error === "") {
                $con->commit();
            } else {
                $con->rollback();
            }
        } else {
            $error = "<p>No se logro actualizar al cliente " . $nombre . ". Intenete nuevamente.</p>";
        }
    }
}
$con->closed();
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);








