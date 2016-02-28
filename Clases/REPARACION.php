<?php

class REPARACION {

    var $id_reparacion;
    var $fecha_Ingreso;
    var $fecha_salida;
    var $kilometro;
    var $combustible;
    var $OT;
    var $id_auto;
    var $total;
    var $estado;
    var $id_personal;
    var $CON;

    function REPARACION($con) {
        $this->CON = $con;
    }

    function contructor($id_reparacion, $fecha_Ingreso, $fecha_salida, $kilometro, $combustible, $OT, $id_auto, $total, $estado, $id_personal) {
        $this->id_reparacion = $id_reparacion;
        $this->fecha_Ingreso = $fecha_Ingreso;
        $this->fecha_salida = $fecha_salida;
        $this->kilometro = $kilometro;
        $this->combustible = $combustible;
        $this->OT = $OT;
        $this->id_auto = $id_auto;
        $this->total = $total;
        $this->estado = $estado;
        $this->id_personal = $id_personal;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $reparacion = new REPARACION();
                $reparacion->id_reparacion = $row['id_reparacion'] == null ? "" : $row['id_reparacion'];
                $reparacion->fecha_Ingreso = $row['fecha_Ingreso'] == null ? "" : $row['fecha_Ingreso'];
                $reparacion->fecha_salida = $row['fecha_salida'] == null ? "" : $row['fecha_salida'];
                $reparacion->kilometro = $row['kilometro'] == null ? "" : $row['kilometro'];
                $reparacion->combustible = $row['combustible'] == null ? "" : $row['combustible'];
                $reparacion->OT = $row['OT'] == null ? "" : $row['OT'];
                $reparacion->id_auto = $row['id_auto'] == null ? "" : $row['id_auto'];
                $reparacion->total = $row['total'] == null ? "" : $row['total'];
                $reparacion->estado = $row['estado'] == null ? "" : $row['estado'];
                $reparacion->id_personal = $row['id_personal'] == null ? "" : $row['id_personal'];
                $lista[] = $reparacion;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.REPARACION";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }

    function buscarXId($id) {
        $consulta = "select * from taller.REPARACION where id_reparacion=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }
    function buscarMoroso($text,$de,$hasta,$empresa) {
        $consulta = "select reparacion.estado,reparacion.id_auto,reparacion.id_reparacion,reparacion.ot,cliente.ci,cliente.nombre,reparacion.total 
                          ,personal.nombre as mecanico,reparacion.fecha_ingreso
                          ,reparacion.fecha_salida
                    from taller.personal 
			right join taller.reparacion on personal.id_personal =reparacion.id_personal 
                        inner join taller.auto on auto.id_auto=reparacion.id_auto
                        inner join taller.cliente on auto.id_cliente=cliente.id_cliente
                   where STR_TO_DATE(reparacion.fecha_ingreso,'%e/%c/%Y') between
                          STR_TO_DATE('$de','%e/%c/%Y') and STR_TO_DATE('$hasta','%e/%c/%Y')
                          and reparacion.estado like '% falta pago' and cliente.id_empresa=$empresa
                          and (cliente.ci like '%$text%' or cliente.nombre like '%$text%' or reparacion.ot like '%$text%')";
        $resultado = $this->CON->consulta($consulta);
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $reparacion = array();
                $reparacion["id_reparacion"]= $row['id_reparacion'] == null ? "" : $row['id_reparacion'];
                $reparacion["id_auto"]= $row['id_auto'] == null ? "" : $row['id_auto'];
                $reparacion["ot"]= $row['ot'] == null ? "" : $row['ot'];
                $reparacion["ci"]= $row['ci'] == null ? "" : $row['ci'];
                $reparacion["nombre"]= $row['nombre'] == null ? "" : $row['nombre'];
                $reparacion["total"]= $row['total'] == null ? "" : $row['total'];
                $reparacion["mecanico"]= $row['mecanico'] == null ? "" : $row['mecanico'];
                $reparacion["fecha_ingreso"]= $row['fecha_ingreso'] == null ? "" : $row['fecha_ingreso'];
                $reparacion["fecha_salida"]= $row['fecha_salida'] == null ? "" : $row['fecha_salida'];
                $reparacion["estado"]= $row['estado'] == null ? "" : $row['estado'];
                $consulta="select sum(pago.monto) as total from taller.pago where id_reparacion=".$reparacion["id_reparacion"];
                $pagado = $this->CON->consulta($consulta);
                $monto=$pagado->fetch_assoc()["total"];
                $reparacion["pagado"]=$monto==null?0:$monto;
                $lista[] = $reparacion;
            }
            return $lista;
        } else {
            return null;
        }
    }
    function buscarXHistorial($cliente,$de,$hasta) {
        $consulta = "select reparacion.* 
                    from taller.REPARACION, taller.auto  
                    where auto.id_cliente=$cliente 
                              and STR_TO_DATE(reparacion.fecha_ingreso,'%e/%c/%Y') between
                          STR_TO_DATE('$de','%e/%c/%Y') and STR_TO_DATE('$hasta','%e/%c/%Y') and reparacion.id_auto=auto.id_auto";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        return $empresa;
    }
    
    function modificar($id_reparacion) {
        $empleado="";
        if($this->id_personal!=0){
            $empleado=", id_personal=$this->id_personal";
        }
        $consulta = "update taller.REPARACION set fecha_Ingreso ='" . $this->fecha_Ingreso . "', fecha_salida='$this->fecha_salida', kilometro ='" . $this->kilometro . "', combustible ='" . $this->combustible . "', OT ='" . $this->OT . "', estado ='" . $this->estado . "' $empleado where id_reparacion=" . $id_reparacion;
        return $this->CON->manipular($consulta);
    }
    function modificarTotal($id_reparacion,$total,$estado) {
        $consulta = "update taller.REPARACION set total=$total, estado='$estado' where id_reparacion=" . $id_reparacion;
        return $this->CON->manipular($consulta);
    }
    function modificarEstado($id_reparacion,$estado) {
        $consulta = "update taller.REPARACION set estado='$estado' where id_reparacion=" . $id_reparacion;
        return $this->CON->manipular($consulta);
    }
    function buscarXAuto($id) {
        $consulta = "select * from taller.REPARACION where id_auto=$id and estado like 'activo%'";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }
    function insertar() {
        $empleado="";
        $empleadoid="";
        if($this->id_personal!=0){
            $empleado=", id_personal";
            $empleadoid=", $this->id_personal";    
        }
        $consulta = "insert into taller.REPARACION(id_reparacion, fecha_Ingreso, kilometro, combustible, OT, id_auto, estado $empleado) values(" . $this->id_reparacion . ",'" . $this->fecha_Ingreso . "','" . $this->kilometro . "','" . $this->combustible . "','" . $this->OT . "'," . $this->id_auto . ",'" . $this->estado . "'  $empleadoid)";
        $resultado = $this->CON->manipular($consulta);
        if(!$resultado){
            return 0;
        }
        $consulta = "SELECT LAST_INSERT_ID() as id";
        $resultado = $this->CON->consulta($consulta);
        return $resultado->fetch_assoc()['id'];
    }

}
