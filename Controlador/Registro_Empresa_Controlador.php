<?php

include_once "../Clases/CONN.php";
include_once "../Clases/RESTAURANTE.php";
include_once "../Clases/HERRAMIENTASPHP.php";
include_once "../Clases/PERSONAL.php";
include_once "../Clases/REGIONAL.php.php";


error_reporting(0);
$restaurante = new RESTAURANTE($con);
$error = "";
$resultado = "";
$proceso = $_POST["proceso"];
$con = new CONN("rest", "wdigital");
if ($proceso == "insertarRestaurante") {
    $nombres = $_POST['nombres'];
    $razonsocial = $_POST['razonsocial'];
    $nombrer = $_POST['nombrer'];
    $regional = $_POST['regional'];
    $nit = $_POST['nit'];
    $contrasena = $_POST['password'];
    $rcontraseña = $_POST['rcontraseña'];
    $direccions = $_POST['direccions'];
    $direccionp = $_POST['direccionp'];
    $cuenta = $_POST['cuenta'];
    $rol = $_POST['rol'];
    $sueldo = $_POST['sueldo'];
    $nombrep = $_POST['nombrep'];
    $telefono = $_POST['telefono'];
    $logo = $_POST['logo'];
    $fechacreacion = date("d/m/Y");
    $fechacontratado= $_POST['fechacontratado'];
    $text = "";
    $Herramienta = new Herramientas();
    if (!$Herramienta->validar("vacio", $nombrer)) {
        $text .= "<p>el campo nombre no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto", nombrer)) {
        $text .= "<p>por favor instrodusca su nombre correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", nombrep)) {
        $text .= "<p>el campo nombre del personal no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto", nombrep)) {
        $text .= "<p>por favor instrodusca el nombre del personal correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", razonsocial)) {
        $text .= "<p>el campo razon social no debe estar vacio</p>";
    }
    if (!$Herramienta->validar("texto", razonsocial)) {
        $text .= "<p>por favor instroduzca su direccion correctamente, no estan permitidos los carateres especiales</p>";
    }
    if (!$Herramienta->validar("vacio", nombres)) {
        $text .= "<p>el campo nombre en el registro de sucursal no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto", nombres)) {
        $text .= "<p>por favor instrodusca su nombre correctamente</p>";
    }
    if (!$Herramienta->validar("entero", nit)) {
        $text .= "<p>por favor instrodusca su nit correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", nit)) {
        $text .= "<p>el campo NIT no puede estar vacio</p>";
    }
    if (!$Herramienta->validar("texto y entero", direccions)) {
        $text .= "<p>por favor instrodusca su direccion correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", direccions)) {
        $text .= "<p>el campo direccion en el registro sucursal no puede quedar vacio</p>";
    }
    if (!$Herramienta->validar("texto y entero", direccionp)) {
        $text .= "<p>por favor instrodusca la direccion del personal correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", direccionp)) {
        $text .= "<p>el campo direccion en el registro personal no puede quedar vacio</p>";
    }
    if (!$Herramienta->validar("vacio", regional)) {
        $text .= "<p>debe de seleccionar una regional</p>";
    }
    if (!$Herramienta->validar("texto y entero", cuenta)) {
        $text .= "<p>por favor instroduzca su cuenta correctamente</p>";
    }
    if (!$Herramienta->validar("vacio", cuenta)) {
        $text .= "<p>el campo cuenta no puede quedar vacio</p>";
    }
    if (!$Herramienta->validar("vacio", sueldo)) {
        $text .= "<p>el campo sueldo no puede quedar vacio</p>";
    }
    if (!$Herramienta->validar("entero", sueldo)) {
        $text .= "<p>por favor introduzca el sueldo correctamente</p>";
    }
    if (!$Herramienta->validar("entero", telefono)) {
        $text .= "<p>por favor introduzca el sueldo correctamente</p>";
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
        $restaurante->contructor(0, $nombre, $razonsocial, $logo, $fechacreacion);
        $restaurante->CON = $con;
        $con->transacion();
        $insert = $restaurante->insertar();
        if ($insert !== 0) {
            $sucu = new SUCURSAL();
            $sucu->id_sucursal = 0;
            $sucu->regional_id = $insert;
            $sucu->Nombre = $nombres;
            $sucu->direccion = $direccions;
            $sucu->regional_id = $regional;          
            $sucu->CON = $con;
            $insert = $sucu->insertar();
               if ($insert !== 0) {
                   $perso= new PERSONAL();
                   $perso->id_personal=0;
                   $perso->nombre=$nombrep;
                   $perso->cuenta=$cuenta;
                   $perso->contrasena=$contrasena;
                   $perso->rol=$rol;
                    $perso->telefono=$telefono;
                    $perso->direccion=$direccionp;
                    $perso->fechaContratado=$fechacontratado;
               }
               
            if ($insert == 0) {
                $con->rollback();
                $error = "Se corto la conexion con el servidor. Intente nuevamente.";
            } else
                $con->commit();
        } else
            $error = "Se corto la conexion con el servidor. Intente nuevamente.";
    }
}
$con->closed();
$reponse = array("error" => $error, "result" => $resultado);
echo $_POST['callback'] . json_encode($reponse);


