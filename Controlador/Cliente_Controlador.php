<?php

include_once "../Clases/CONN.php";
include_once "../Clases/CLIENTE.php";
include_once "../Clases/HERRAMIENTASPHP.php";
include_once "../Clases/DIRECCION.php";

error_reporting(0);
$cliente = new CLIENTE($con);
$error = "";
$resultado = "";
$proceso = $_POST["proceso"];
$con = new CONN("rest", "wdigital");

if ($proceso == "insertarCliente") {

    $nombre = $_POST['nombre'];
    $correo = $_POST['correo'];
    $direccion = $_POST['direccion'];
    $cuenta = $_POST['cuenta'];
    $ci = $_POST['ci'];
    $contrasena = $_POST['contrasena'];
    $rcontraseña = $_POST['rcontraseña'];
    $telefono = $_POST['telefono'];
    $foto   = $_POST['foto'];
    $fechanacimiento= $_POST['fechanacimiento'];
    $text = "";
    $Herramienta = new Herramientas();
    if (!$Herramienta->validar("vacio", $nombre)) {
        $text .= "<p>el campo nombre completo no puede estar vacio</p>";
    }
    if ($Herramienta->validar("texto", $nombre)===FALSE) {
        $text .= "<p>por favor instrodusca su nombre correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", $correo)) {
        $text .= "<p>el correo electronico no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto y entero", $correo)) {
        $text .= "<p>por favor instroduzca su correo electronico correctamente, no estan permitidos los carateres especiales</p>";
    }
    if (!$Herramienta->validar("vacio", $ci)) {
        $text .= "<p>el C.I. no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto y entero", $ci)) {
        $text .= "<p>por favor instroduzca su C.I. correctamente, no estan permitidos los carateres especiales</p>";
    }

    if (!$Herramienta->validar("entero", $telefono)) {
        $text .= "<p>por favor instroduzca su telefono correctamente, no estan permitidos los carateres especiales</p>";
    }
    if (!$Herramienta->validar("texto y entero", $direccion)) {
        $text .= "<p>por favor instroduzca su direccion correctamente, no estan permitidos los carateres especiales</p>";
    }
    if (!$Herramienta->validar("texto y entero", $cuenta)) {
        $text .= "<p>no estan permitidos los caracteres especiales en el campo cuenta</p>";
    }
    if (!$Herramienta->validar("vacio", $cuenta)) {
        $text .= "<p>el campo cuenta tiene que tener mayor a 4  y menor a 8 caracteres</p>";
    }
    if (strlen($contrasena) < 4 || strlen($contrasena) > 8) {
        $text .= "<p>el password tiene que tener mayor a 4  y menor a 8 caracteres</p>";
    }
    if ($rcontraseña !== $contrasena) {
        $text .= "<p>las contraseñas no coinciden, vuelva a intentarlo</p>";
    }
    if (strlen($text) > 0) {
        $error = $text;
    } else {
        
        $cliente->contructor(0, $nombre, $ci, $cuenta, $contrasena, $correo, $telefono,$fechanacimiento,$foto);
        $cliente->CON = $con;
        $con->transacion();
        $insert = $cliente->insertar();
        if ($insert !== 0) {
            $dire = new DIRECCION();
            $dire->id_direccion=0;
            $dire->cliente_id = $insert;
            $dire->descripcion = $direccion;
            $dire->CON = $con;
            $insert= $dire->insertar();
     
            if ($insert == 0) {
                $con->rollback();
                $error = "Se corto la conexion con el servidor. Intente nuevamente.";
            }
            else
                $con->commit ();
        } else
            $error = "Se corto la conexion con el servidor. Intente nuevamente.";
    }
}
$con->closed();
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);








