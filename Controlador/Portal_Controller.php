<?php
include_once "../Clases/CONN.php";
include_once "../Clases/PERSONAL.php";

error_reporting(0);
$error = "";
$resultado = "";
session_start();
$personalsession = $_SESSION["personal"];
$empresasession = $_SESSION["empresa"];
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
if ($proceso == "perfil") {
    $personal=new PERSONAL($con);
    $resultado=$personal->buscarXID($personalsession);
}
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);








