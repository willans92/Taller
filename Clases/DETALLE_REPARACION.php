<?php

class DETALLE_REPARACION {

    var $id_reparacion;
    var $id_trabajos;
    var $descripcion;
    var $costo;
    var $CON;

    function DETALLE_REPARACION($con) {
        $this->CON = $con;
    }

    function contructor($id_reparacion, $id_trabajos, $costo) {
        $this->id_reparacion = $id_reparacion;
        $this->id_trabajos = $id_trabajos;
        $this->costo = $costo;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $detalle_reparacion = new DETALLE_REPARACION();
                $detalle_reparacion->id_reparacion = $row['id_reparacion'] == null ? "" : $row['id_reparacion'];
                $detalle_reparacion->id_trabajos = $row['id_trabajos'] == null ? "" : $row['id_trabajos'];
                $detalle_reparacion->costo = $row['costo'] == null ? "" : $row['costo'];
                $detalle_reparacion->descripcion= $row['descripcion'] == null ? "" : $row['descripcion'];
                $lista[] = $detalle_reparacion;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.DETALLE_REPARACION";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXReparacion($id) {
        $consulta = "select * from taller.DETALLE_REPARACION, taller.trabajo where id_reparacion=$id and detalle_reparacion.id_trabajos=trabajo.id_trabajo";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        return $empresa;
    }

    function modificar($id_reparacion) {
        $consulta = "update taller.DETALLE_REPARACION set id_reparacion =" . $this->id_reparacion . ", id_trabajos =" . $this->id_trabajos . " where id_reparacion=" . $id_reparacion;
        $result = $this->CON->consulta($consulta);
        return $ret['cant'];
    }

    function eliminar($id_reparacion) {
        $consulta = "delete from taller.DETALLE_REPARACION where id_reparacion=" . $id_reparacion;
        return $this->CON->manipular($consulta);
    }

    function insertar() {
        $consulta = "insert into taller.DETALLE_REPARACION(id_reparacion, id_trabajos,costo) values(" . $this->id_reparacion . "," . $this->id_trabajos . ",$this->costo)";
        return $this->CON->manipular($consulta);
    }

}
