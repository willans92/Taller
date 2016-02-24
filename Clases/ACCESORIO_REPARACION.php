<?php

class ACCESORIO_REPARACION {

    var $id_reparacion;
    var $id_accesorio;
    var $CON;

    function ACCESORIO_REPARACION($con) {
        $this->CON = $con;
    }

    function contructor($id_reparacion, $id_accesorio) {
        $this->id_reparacion = $id_reparacion;
        $this->id_accesorio = $id_accesorio;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $accesorio_reparacion = new ACCESORIO_REPARACION();
                $accesorio_reparacion->id_reparacion = $row['id_reparacion'] == null ? "" : $row['id_reparacion'];
                $accesorio_reparacion->id_accesorio = $row['id_accesorio'] == null ? "" : $row['id_accesorio'];
                $accesorio_reparacion->descripcion= $row['descripcion'] == null ? "" : $row['descripcion'];
                $lista[] = $accesorio_reparacion;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.ACCESORIO_REPARACION";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXReparacion($id) {
        $consulta = "select * from taller.ACCESORIO_REPARACION, taller.accesorio where id_reparacion=$id and accesorio.id_accesorio=ACCESORIO_REPARACION.id_accesorio";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        return $empresa;
    }

    function modificar($id_accesorio) {
        $consulta = "update taller.ACCESORIO_REPARACION set id_reparacion =" . $this->id_reparacion . ", id_accesorio =" . $this->id_accesorio . " where id_accesorio=" . $id_accesorio;
        $result = $this->CON->consulta($consulta);
        return $ret['cant'];
    }

    function eliminar($id_reparacion) {
        $consulta = "delete from taller.ACCESORIO_REPARACION where id_reparacion =$id_reparacion";
        return $this->CON->manipular($consulta);
    }

    function insertar() {
        $consulta = "insert into taller.ACCESORIO_REPARACION(id_reparacion, id_accesorio) values(" . $this->id_reparacion . "," . $this->id_accesorio . ")";
        return $this->CON->manipular($consulta);
    }

}
