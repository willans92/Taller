<?php

class MARCA {

    var $id_marca;
    var $descripcion;
    var $empresa_id;
    var $CON;

    function MARCA($con) {
        $this->CON = $con;
    }

    function contructor($id_marca, $descripcion, $empresa_id) {
        $this->id_marca = $id_marca;
        $this->descripcion = $descripcion;
        $this->empresa_id = $empresa_id;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $marca = new MARCA();
                $marca->id_marca = $row['id_marca'] == null ? "" : $row['id_marca'];
                $marca->descripcion = $row['descripcion'] == null ? "" : $row['descripcion'];
                $marca->empresa_id = $row['empresa_id'] == null ? "" : $row['empresa_id'];
                $lista[] = $marca;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.MARCA";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXID($id) {
        $consulta = "select * from taller.MARCA where id_marca=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }
    function buscarXEmpresa($id) {
        $consulta = "select * from taller.MARCA where empresa_id=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa;
    }

    function modificar($id_marca) {
        $consulta = "update taller.MARCA set id_marca =" . $this->id_marca . ", descripcion ='" . $this->descripcion . "' where id_marca=" . $id_marca;
        $result = $this->CON->consulta($consulta);
        return $ret['cant'];
    }

    function insertar() {
        $consulta = "insert into taller.MARCA(empresa_id,descripcion) values(" . $this->empresa_id . ",'" . $this->descripcion . "')";
        $resultado = $this->CON->manipular($consulta);
        if(!$resultado){
            return 0;
        }
        $consulta = "SELECT LAST_INSERT_ID() as id";
        $resultado = $this->CON->consulta($consulta);
        return $resultado->fetch_assoc()['id'];
    }

}
