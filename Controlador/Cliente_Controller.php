<?php

include_once "../Clases/CONN.php";
include_once "../Clases/CLIENTE.php";
include_once "../Clases/HERRAMIENTASPHP.php";
include_once "../Clases/CLIENTE.php";

error_reporting(0);
$error = "";
$resultado = "";
session_start();
$personalsession=$_SESSION["personal"];
$empresasession=$_SESSION["empresa"];
$proceso = $_POST["proceso"];
$con = new CONN("taller1", "wdigital");
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
if ($proceso == "buscarClientes") {
    $cliente = new CLIENTE($con);
     $text = $_POST['text'];
    $resultado=$cliente->buscarTexto($text,$empresasession);
}
if ($proceso == "cliente") {
    $cliente = new CLIENTE($con);
    $id= $_POST['id'];
    $resultado=$cliente->buscarXID($id);
    if($resultado==null){
        $error="Se corto la conexion mientras se traían los datos. Intente nuevamente.";
    }
}
if ($proceso == "registroCliente") {
    $ci = $_POST['ci'];
    $nombre= $_POST['nombre'];
    $direccion = $_POST['direccion'];
    $celular= $_POST['celular'];
    $casa= $_POST['casa'];
    $clienteId= $_POST['cliente'];
    $oficina= $_POST['oficina'];
    $foto = $_POST['foto'];
    $text = "";
    $Herramienta = new Herramientas();
    if (!$Herramienta->validar("texto y entero",ci)){
        $text.="<p>El ci no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("vacio", $nombre)){
        $text.="<p>El nombre no puede ser vacío.</p>";
    }
    if (!$Herramienta->validar("texto y entero",nombre)){
        $text.="<p>El nombre no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("texto y entero",direccion)){
        $text.="<p>La direccion no puede tener caracteres especiales.</p>";
    }
    if (!$Herramienta->validar("entero",casa)){
        $text.="<p>El telefono de la casa del cliente solo acepta numero.</p>";
    }
    if (!$Herramienta->validar("entero",oficina)){
        $text.="<p>El telefono de la oficina del cliente solo acepta numero.</p>";
    }
    if (!$Herramienta->validar("entero",celular)){
        $text.="<p>El telefono celular del cliente solo acepta numero.</p>";
    }
    if (strlen($text) > 0) {
        $error=$text;
        $reponse = array("error" => $error,"result"=>$resultado);
        echo $_GET['callback'].json_encode($reponse);
        return;
    }
    $cliente = new CLIENTE($con);
    $cliente->contructor($clienteId, $foto, $nombre, $direccion, $casa, $oficina, $celular, $ci, $empresasession);
    if($clienteId=="0"){
        $resultado=$cliente->insertar();
        if($resultado===0){
            $error="<p>No se logro insertar el nuevo cliente. Intenete nuevamente.</p>";
        }
    }else{
        if($cliente->modificar($clienteId)){
            $resultado=$clienteId;
        }else{
            $error="<p>No se logro actualizar al cliente "+$nombre+". Intenete nuevamente.</p>";
        }
    }
}
$con->closed();
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);








