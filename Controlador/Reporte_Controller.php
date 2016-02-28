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
if ($proceso == "morosos") {
    $reparacion = new REPARACION($con);
    $text = $_POST['text'];
    $de = $_POST['de'];
    $hasta = $_POST['hasta'];
    if (!$Herramienta->validar("texto y entero", $text)) {
        $error = "<p>El criterio de busqueda no puede tener caracteres especiales.</p>";
    } else {
        $resultado = $reparacion->buscarMoroso($text, $de,$hasta,$empresasession);
    }
}
if ($proceso == "Movimiento") {
    $pago= new PAGO($con);
    $text = $_POST['text'];
    $de = $_POST['de'];
    $hasta = $_POST['hasta'];
    $tipo = $_POST['tipo'];
    if (!$Herramienta->validar("texto y entero", $text)) {
        $error = "<p>El criterio de busqueda no puede tener caracteres especiales.</p>";
    } else {
        if($tipo=="reparacion"){
            $resultado = $pago->buscarMovimientoReparacion($text, $de,$hasta,$empresasession);
        }
        if($tipo=="sueldo"){
            $resultado = $pago->buscarMovimientoSueldo($text, $de,$hasta,$empresasession);
        }
        if($tipo=="otros pagos"){
            $resultado = $pago->buscarMovimientoOtro($text, $de,$hasta,$empresasession);
        }
    }
}
if ($proceso == "detallepagoreparacion") {
    $pago= new PAGO($con);
    $id= $_POST['reparacion'];
    $reparacion=new REPARACION($con);
    $resultado=array();
    $resultado["detalle"] = $pago->buscarXReparacion($id);
    $resultado["reparacion"] = $reparacion->buscarXId($id);
}
if ($proceso == "pagarReparacion") {
    $pago= new PAGO($con);
    $id= $_POST['reparacion'];
    $monto= $_POST['monto'];
    $fecha= $_POST['fecha'];
    $estado= $_POST['estado'];
    $desc= $_POST['desc'];
    $con->transacion();
    $pago->contructor(0, $fecha, $monto, $id, "REPARACION",$desc, $personalsession, "ACTIVO", "");
    $resultado = $pago->insertarPagoReparacion();
    if(!$resultado){
        $error="No se pudo registrar el pago. Intenete nuevamente";
        $con->rollback();
    }else{
        $reparacion=new REPARACION($con);
        $resultado = $reparacion->modificarEstado($id,$estado);
        if(!$resultado){
            $error="No se pudo registrar el pago. Intenete nuevamente";
            $con->rollback();
        }else{
            $con->commit();
        }
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
    $resultado["reparacion"] = $reparacion->buscarXId($idreparacion);
    $reparacionid=$resultado["reparacion"]->id_reparacion;
    $resultado["trabajo"] = $trabajoReparacion->buscarXReparacion($reparacionid);
    $resultado["accesorio"] = $accesorioReparacion->buscarXReparacion($reparacionid);
}
$con->closed();
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);








