<?php

class PAGO {

    var $id_pago;
    var $fecha;
    var $monto;
    var $id_reparacion;
    var $tipo;
    var $descripcion;
    var $id_personal;
    var $estado;
    var $fecha_Corresponde;
    var $CON;

    function PAGO($con) {
        $this->CON = $con;
    }

    function contructor($id_pago, $fecha, $monto, $id_reparacion, $tipo, $descripcion, $id_personal,$estado,$fecha_Corresponde) {
        $this->id_pago = $id_pago;
        $this->fecha = $fecha;
        $this->monto = $monto;
        $this->id_reparacion = $id_reparacion;
        $this->tipo = $tipo;
        $this->descripcion = $descripcion;
        $this->id_personal = $id_personal;
        $this->fecha_Corresponde= $fecha_Corresponde;
        $this->estado = $estado;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $pago = new PAGO();
                $pago->id_pago = $row['id_pago'] == null ? "" : $row['id_pago'];
                $pago->fecha = $row['fecha'] == null ? "" : $row['fecha'];
                $pago->monto = $row['monto'] == null ? "" : $row['monto'];
                $pago->id_reparacion = $row['id_reparacion'] == null ? "" : $row['id_reparacion'];
                $pago->tipo = $row['tipo'] == null ? "" : $row['tipo'];
                $pago->descripcion = $row['descripcion'] == null ? "" : $row['descripcion'];
                $pago->id_personal = $row['id_personal'] == null ? "" : $row['id_personal'];
                $pago->estado= $row['estado'] == null ? "" : $row['estado'];
                $pago->fecha_Corresponde= $row['fecha_Corresponde'] == null ? "" : $row['fecha_Corresponde'];
                $lista[] = $pago;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.PAGO";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXID($id) {
        $consulta = "select * from taller.PAGO where id_pago=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }
    function buscarXPersona($id,$mes,$ano) {
        $consulta = "select * from taller.PAGO where id_personal=$id "
                   . "and YEAR(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y'))=$ano
                     and month(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y')) =$mes";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa;
    }
    function buscarOtroPago($text,$mes,$ano,$estado) {
        $consulta = "select * from taller.PAGO where pago.descripcion like '%$text%'"
                    . " and  pago.estado like '$estado' AND pago.tipo like 'OTROS PAGOS'"
                    . "and YEAR(STR_TO_DATE(pago.fecha,'%e/%c/%Y'))=$ano
                    and month(STR_TO_DATE(pago.fecha,'%e/%c/%Y')) =$mes order by id_pago DESC";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa;
    }

    function modificar($id_pago) {
        $consulta = "update taller.PAGO set id_pago =" . $this->id_pago . ", fecha ='" . $this->fecha . "', monto =" . $this->monto . ", id_reparacion =" . $this->id_reparacion . ", tipo ='" . $this->tipo . "', descripcion ='" . $this->descripcion . "', id_personal =" . $this->id_personal . " where id_pago=" . $id_pago;
        $result = $this->CON->consulta($consulta);
        return $ret['cant'];
    }

    function insertar() {
        $consulta = "insert into taller.PAGO(id_pago, fecha, monto, id_reparacion, tipo, descripcion, id_personal) values(" . $this->id_pago . ",'" . $this->fecha . "'," . $this->monto . "," . $this->id_reparacion . ",'" . $this->tipo . "','" . $this->descripcion . "'," . $this->id_personal . ")";
        $resultado = $this->CON->consulta($consulta);
        $consulta = "SELECT LAST_INSERT_ID() as id";
        $resultado = $this->CON->consulta($consulta);
        return $resultado->fetch_assoc()['id'];
    }
    function insertarPagoPersonal($personal,$monto,$fecha,$fechacorresponde,$descripcion) {
        $consulta = "insert into taller.PAGO(fecha, monto, tipo, descripcion, id_personal,fecha_Corresponde) "
                . "values('$fecha',$monto,'SUELDO','$descripcion',$personal,'$fechacorresponde')";
        $resultado = $this->CON->manipular($consulta);
        return $resultado;
    }
    function insertarPagoOtros($monto,$fecha,$descripcion) {
        $consulta = "insert into taller.PAGO(fecha, monto, tipo, descripcion) "
                . "values('$fecha',$monto,'OTROS PAGOS','$descripcion')";
        $resultado = $this->CON->manipular($consulta);
        return $resultado;
    }

}
