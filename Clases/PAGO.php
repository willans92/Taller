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
    function buscarXReparacion($id) {
        $consulta = "select * from taller.PAGO where id_reparacion=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        return $empresa;
    }
    function buscarXReparacionTotal($id) {
        $consulta = "select sum(monto) as total from taller.PAGO where id_reparacion=$id";
        $result = $this->CON->consulta($consulta);
        $r= $result->fetch_assoc()["total"];
        return $r;
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
                    . " and  pago.estado ='$estado' AND pago.tipo like 'OTROS PAGOS'"
                    . "and YEAR(STR_TO_DATE(pago.fecha,'%e/%c/%Y'))=$ano
                    and month(STR_TO_DATE(pago.fecha,'%e/%c/%Y')) =$mes order by id_pago DESC";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa;
    }
    function buscarMovimientoReparacion($text,$de,$hasta) {
        $consulta = "

            select pago.id_pago,reparacion.ot,cliente.ci,cliente.nombre,personal.nombre as adm, pago.monto,pago.fecha,pago.descripcion
            from taller.PAGO,taller.reparacion,taller.cliente ,taller.personal, taller.auto
            where pago.id_reparacion=reparacion.id_reparacion and personal.id_personal=pago.id_personal 
                    and auto.id_auto=reparacion.id_auto and auto.id_cliente=cliente.id_cliente
                    and pago.estado ='ACTIVO' AND pago.tipo like 'REPARACION'
                    and STR_TO_DATE(pago.fecha,'%e/%c/%Y') between STR_TO_DATE('$de','%e/%c/%Y')
                and STR_TO_DATE('$hasta','%e/%c/%Y') and (cliente.ci like '%$text%' or cliente.nombre like '%$text%'
                or personal.nombre like '%$text%' or reparacion.ot like '%$text%') order by pago.id_pago desc 
";
        $resultado = $this->CON->consulta($consulta);
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $pago = array();
                $pago["fecha"] = $row['fecha'] == null ? "" : $row['fecha'];
                $pago["monto"] = $row['monto'] == null ? "" : $row['monto'];
                $pago["adm"] = $row['adm'] == null ? "" : $row['adm'];
                $pago["nombre"] = $row['nombre'] == null ? "" : $row['nombre'];
                $pago["ci"] = $row['ci'] == null ? "" : $row['ci'];
                $pago["ot"] = $row['ot'] == null ? "" : $row['ot'];
                $pago["id_pago"] = $row['id_pago'] == null ? "" : $row['id_pago'];
                $pago["nro"] = $row['descripcion'] == null ? "" : $row['descripcion'];
                $lista[] = $pago;
            }
            return $lista;
        } else {
            return null;
        }
    }
    function buscarMovimientoSueldo($text,$de,$hasta) {
        $consulta = "select pago.id_pago,personal.nombre,personal.carnet,pago.fecha_Corresponde, pago.monto,pago.fecha,pago.descripcion
from taller.PAGO, taller.personal
where personal.id_personal=pago.id_personal 
	and pago.estado ='ACTIVO' AND pago.tipo like 'SUELDO'
	and STR_TO_DATE(pago.fecha,'%e/%c/%Y') between STR_TO_DATE('$de','%e/%c/%Y')
    and STR_TO_DATE('$hasta','%e/%c/%Y') and (personal.carnet like '%$text%' or personal.nombre like '%$text%' or pago.descripcion like '%$text%') ";
        $resultado = $this->CON->consulta($consulta);
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $pago = array();
                $pago["nombre"] = $row['nombre'] == null ? "" : $row['nombre'];
                $pago["carnet"] = $row['carnet'] == null ? "" : $row['carnet'];
                $corresponde= $row['fecha_Corresponde'] == null ? "" : $row['fecha_Corresponde'];
                $corresponde=substr($corresponde, 3);
                $pago["fechaC"] = $corresponde;
                $pago["monto"] = $row['monto'] == null ? "" : $row['monto'];
                $pago["fecha"] = $row['fecha'] == null ? "" : $row['fecha'];
                $pago["descripcion"] = $row['descripcion'] == null ? "" : $row['descripcion'];
                $lista[] = $pago;
            }
            return $lista;
        } else {
            return null;
        }
    }
    function buscarMovimientoOtro($text,$de,$hasta) {
        $consulta = "select pago.id_pago,personal.nombre, pago.monto,pago.fecha,pago.descripcion
from taller.PAGO, taller.personal
where personal.id_personal=pago.id_personal 
	and pago.estado ='ACTIVO' AND pago.tipo like 'OTROS PAGOS'
	and STR_TO_DATE(pago.fecha,'%e/%c/%Y') between STR_TO_DATE('$de','%e/%c/%Y')
    and STR_TO_DATE('$hasta','%e/%c/%Y') and (personal.carnet like '%$text%' or personal.nombre like '%$text%' or pago.descripcion like '%$text%') ";
        $resultado = $this->CON->consulta($consulta);
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $pago = array();
                $pago["nombre"] = $row['nombre'] == null ? "" : $row['nombre'];
                $pago["monto"] = $row['monto'] == null ? "" : $row['monto'];
                $pago["fecha"] = $row['fecha'] == null ? "" : $row['fecha'];
                $pago["descripcion"] = $row['descripcion'] == null ? "" : $row['descripcion'];
                $lista[] = $pago;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function modificar($id_pago) {
        $consulta = "update taller.PAGO set id_pago =" . $this->id_pago . ", fecha ='" . $this->fecha . "', monto =" . $this->monto . ", id_reparacion =" . $this->id_reparacion . ", tipo ='" . $this->tipo . "', descripcion ='" . $this->descripcion . "', id_personal =" . $this->id_personal . " where id_pago=" . $id_pago;
        $result = $this->CON->consulta($consulta);
        return $ret['cant'];
    }
    function modificarestado($id_pago,$estado) {
        $consulta = "update taller.PAGO set estado='$estado' where id_pago=" . $id_pago;
        return $this->CON->manipular($consulta);
    }

    function insertar() {
        $consulta = "insert into taller.PAGO(id_pago, fecha, monto, id_reparacion, tipo, descripcion, id_personal) values(" . $this->id_pago . ",'" . $this->fecha . "'," . $this->monto . "," . $this->id_reparacion . ",'" . $this->tipo . "','" . $this->descripcion . "'," . $this->id_personal . ")";
        $resultado = $this->CON->consulta($consulta);
        $consulta = "SELECT LAST_INSERT_ID() as id";
        $resultado = $this->CON->consulta($consulta);
        return $resultado->fetch_assoc()['id'];
    }
    function insertarPagoReparacion() {
        $consulta = "insert into taller.PAGO(id_pago, fecha, monto, id_reparacion, tipo, id_personal,descripcion) values(" . $this->id_pago . ",'" . $this->fecha . "'," . $this->monto . "," . $this->id_reparacion . ",'" . $this->tipo . "'," . $this->id_personal . ",'" . $this->descripcion . "')";
        return $this->CON->manipular($consulta);
    }
    function insertarPagoPersonal($personal,$monto,$fecha,$fechacorresponde,$descripcion) {
        $consulta = "insert into taller.PAGO(fecha, monto, tipo, descripcion, id_personal,fecha_Corresponde) "
                . "values('$fecha',$monto,'SUELDO','$descripcion',$personal,'$fechacorresponde')";
        $resultado = $this->CON->manipular($consulta);
        return $resultado;
    }
    function insertarPagoOtros($monto,$fecha,$descripcion,$id_personal) {
        $consulta = "insert into taller.PAGO(fecha, monto, tipo, descripcion,id_personal) "
                . "values('$fecha',$monto,'OTROS PAGOS','$descripcion',$id_personal)";
        return $this->CON->manipular($consulta);
    }

}
