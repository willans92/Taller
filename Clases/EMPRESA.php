<?php

class EMPRESA {

    var $id_empresa;
    var $nombre;
    var $razon_social;
    var $logo;
    var $aniversario;
    var $fecha_afiliacion;
    var $nit;
    var $direccion;
    var $nro_factura;
    var $fecha_factura;
    var $llave_dosificacion;
    var $nro_autorizacion;
    var $telefono;
    var $fecha_finDosificacion;
    var $ot;
    var $correo;
    var $CON;

    function EMPRESA($con) {
        $this->CON = $con;
    }

    function contructor($id_empresa, $nombre, $razon_social, $logo, $aniversario, $fecha_afiliacion, $nit, $direccion, $nro_factura, $fecha_factura, $llave_dosificacion, $nro_autorizacion, $telefono, $fecha_finDosificacion,$ot,$correo) {
        $this->id_empresa = $id_empresa;
        $this->nombre = $nombre;
        $this->razon_social = $razon_social;
        $this->logo = $logo;
        $this->aniversario = $aniversario;
        $this->fecha_afiliacion = $fecha_afiliacion;
        $this->nit = $nit;
        $this->direccion = $direccion;
        $this->nro_factura = $nro_factura;
        $this->ot= $ot;
        $this->fecha_factura = $fecha_factura;
        $this->llave_dosificacion = $llave_dosificacion;
        $this->nro_autorizacion = $nro_autorizacion;
        $this->fecha_finDosificacion = $fecha_finDosificacion;
        $this->telefono = $telefono;
        $this->correo = $correo;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $empresa = new EMPRESA();
                $empresa->id_empresa = $row['id_empresa'] == null ? "" : $row['id_empresa'];
                $empresa->nombre = $row['nombre'] == null ? "" : $row['nombre'];
                $empresa->razon_social = $row['razon_social'] == null ? "" : $row['razon_social'];
                $empresa->logo = $row['logo'] == null ? "" : $row['logo'];
                $empresa->aniversario = $row['aniversario'] == null ? "" : $row['aniversario'];
                $empresa->fecha_afiliacion = $row['fecha_afiliacion'] == null ? "" : $row['fecha_afiliacion'];
                $empresa->nit = $row['nit'] == null ? "" : $row['nit'];
                $empresa->direccion = $row['direccion'] == null ? "" : $row['direccion'];
                $empresa->ot = $row['ot'] == null ? "0" : $row['ot'];
                $empresa->nro_factura = $row['nro_factura'] == null ? "" : $row['nro_factura'];
                $empresa->fecha_factura = $row['fecha_factura'] == null ? "" : $row['fecha_factura'];
                $empresa->llave_dosificacion = $row['llave_dosificacion'] == null ? "" : $row['llave_dosificacion'];
                $empresa->nro_autorizacion = $row['nro_autorizacion'] == null ? "" : $row['nro_autorizacion'];
                $empresa->fecha_finDosificacion = $row['fecha_finDosificacion'] == null ? "" : $row['fecha_finDosificacion'];
                $empresa->telefono = $row['telefono'] == null ? "" : $row['telefono'];
                $empresa->correo = $row['correo'] == null ? "" : $row['correo'];
                $lista[] = $empresa;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.EMPRESA";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXID($id) {
        $consulta = "select * from taller.EMPRESA where id_empresa=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }
    function obtenerOT($id) {
        $consulta = "select empresa.ot, MONTH (NOW()) as mes, YEAR(NOW()) as ano from taller.EMPRESA where id_empresa=$id";
        $result = $this->CON->consulta($consulta);
        $result= $result->fetch_assoc();
        $mes="";
        switch ($result["mes"]){
            case 1:
                $mes="ENE";
                break;
            case 2:
                $mes="FEB";
                break;
            case 3:
                $mes="MAR";
                break;
            case 4:
                $mes="ABR";
                break;
            case 5:
                $mes="MAY";
                break;
            case 6:
                $mes="JUN";
                break;
            case 7:
                $mes="JUL";
                break;
            case 8:
                $mes="AGO";
                break;
            case 9:
                $mes="SEP";
                break;
            case 10:
                $mes="OCT";
                break;
            case 11:
                $mes="NOV";
                break;
            case 12:
                $mes="DIC";
                break;
        }
        return $result["ot"].$mes.$result["ano"];
    }

    function modificar($id_empresa) {
        $consulta = "update taller.EMPRESA set correo='" . $this->correo . "', nombre ='" . $this->nombre . "', razon_social ='" . $this->razon_social . "', logo ='" . $this->logo . "', aniversario ='" . $this->aniversario . "', nit ='" . $this->nit . "', direccion ='" . $this->direccion . "', nro_factura ='" . $this->nro_factura . "', fecha_factura ='" . $this->fecha_factura . "', llave_dosificacion ='" . $this->llave_dosificacion . "', nro_autorizacion ='" . $this->nro_autorizacion . "', telefono='$this->telefono',fecha_finDosificacion='$this->fecha_finDosificacion' where id_empresa=" . $id_empresa;
        return $this->CON->manipular($consulta);
    }
    function reinicioOT($id_empresa) {
        $consulta = "update taller.EMPRESA set ot=1 where id_empresa=" . $id_empresa;
        return $this->CON->manipular($consulta);
    }
    function aumentarOT($id_empresa) {
        $consulta = "update taller.EMPRESA set ot=ot+1 where id_empresa=" . $id_empresa;
        return $this->CON->manipular($consulta);
    }

    function insertar() {
        $consulta = "insert into taller.EMPRESA(id_empresa, nombre, razon_social, logo, aniversario, fecha_afiliacion, nit, direccion, nro_factura, fecha_factura, llave_dosificacion, nro_autorizacion,telefono,fecha_finDosificacion) values(" . $this->id_empresa . ",'" . $this->nombre . "','" . $this->razon_social . "','" . $this->logo . "','" . $this->aniversario . "','" . $this->fecha_afiliacion . "','" . $this->nit . "','" . $this->direccion . "','" . $this->nro_factura . "','" . $this->fecha_factura . "','" . $this->llave_dosificacion . "','" . $this->nro_autorizacion . "','$this->telefono',$this->fecha_finDosificacion)";
        return $resultado = $this->CON->manipular($consulta);
    }

}
