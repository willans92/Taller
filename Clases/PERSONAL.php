<?php
class PERSONAL {

    var $id_personal;
    var $foto;
    var $carnet;
    var $nombre;
    var $direccion;
    var $correo;
    var $cumpleano;
    var $fecha_ingreso;
    var $cuenta;
    var $contrasena;
    var $sueldo=0;
    var $rol;
    var $id_empresa;
    var $estado;
    var $fecha_retirado;
    var $CON;

    function PERSONAL($con) {
        $this->CON = $con;
    }

    function contructor($id_personal, $foto, $carnet, $nombre, $direccion, $correo, $cumpleano, $fecha_ingreso, $cuenta, $contrasena, $sueldo, $rol, $id_empresa, $estado, $fecha_retirado) {
                
        $this->id_personal = $id_personal;
        $this->foto = $foto;
        $this->carnet = $carnet;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->correo = $correo;
        $this->cumpleano = $cumpleano;
        $this->fecha_ingreso = $fecha_ingreso;
        $this->cuenta = $cuenta;
        $this->contrasena = $contrasena;
        $this->sueldo = $sueldo;
        $this->rol = $rol;
        $this->id_empresa = $id_empresa;
        $this->estado = $estado;
        $this->fecha_retirado = $fecha_retirado;
    }

    function rellenar($resultado) {
        if ($resultado->num_rows > 0) {
            $lista = array();
            while ($row = $resultado->fetch_assoc()) {
                $personal = new PERSONAL();
                $personal->id_personal = $row['id_personal'] == null ? "" : $row['id_personal'];
                $personal->foto = $row['foto'] == null ? "" : $row['foto'];
                $personal->carnet = $row['carnet'] == null ? "" : $row['carnet'];
                $personal->nombre = $row['nombre'] == null ? "" : $row['nombre'];
                $personal->direccion = $row['direccion'] == null ? "" : $row['direccion'];
                $personal->correo = $row['correo'] == null ? "" : $row['correo'];
                $personal->cumpleano = $row['cumpleano'] == null ? "" : $row['cumpleano'];
                $personal->fecha_ingreso = $row['fecha_ingreso'] == null ? "" : $row['fecha_ingreso'];
                $personal->cuenta = $row['cuenta'] == null ? "" : $row['cuenta'];
                $personal->contrasena = $row['contrasena'] == null ? "" : $row['contrasena'];
                $personal->sueldo = $row['sueldo'] == null ? "" : $row['sueldo'];
                $personal->rol = $row['rol'] == null ? "" : $row['rol'];
                $personal->id_empresa = $row['id_empresa'] == null ? "" : $row['id_empresa'];
                $personal->estado = $row['estado'] == null ? "" : $row['estado'];
                $personal->fecha_retirado = $row['fecha_retirado'] == null ? "" : $row['fecha_retirado'];
                $lista[] = $personal;
            }
            return $lista;
        } else {
            return null;
        }
    }

    function todo() {
        $consulta = "select * from taller.PERSONAL";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }
    function revisarCuenta($cuenta) {
        $consulta = "select count(*) as cant from taller.PERSONAL where cuenta='$cuenta'";
        $resultado= $this->CON->consulta($consulta);
        if($resultado->fetch_assoc()["cant"]=="0"){
            return false;
        }
        return true;
    }
    function BuscarPersonal($text,$estado) {
        $consulta = "select * from taller.PERSONAL where PERSONAL.carnet like '%$text%' and PERSONAL.nombre like '%$text%' and PERSONAL.cuenta like '%$text%' and PERSONAL.estado='$estado'";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }
    function BuscarMecanico() {
        $consulta = "select * from taller.PERSONAL where PERSONAL.rol='Mecanico'";
        $result = $this->CON->consulta($consulta);
        return $this->rellenar($result);
    }
    function BuscarPersonalAPagar($text,$estado,$ano,$mes) {
        $consulta ="";
        if($estado==="PAGADO"){
            $consulta="select personal.carnet,personal.nombre,personal.sueldo,personal.id_personal,sum(pago.monto) as pagado, max(pago.fecha) as ultimoPago
                                from taller.pago join taller.personal on personal.id_personal=pago.id_personal
                                where pago.tipo='SUELDO' and pago.estado='ACTIVO'
                                        and (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))>$ano 
                                        or (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))=$ano 
                                        and month(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))<=$mes))
                                        and (personal.fecha_retirado='' or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>$ano 
                                        or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))=$ano 
                                        and month(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>=$mes)))
                                        and YEAR(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y'))=$ano
                                        and month(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y')) =$mes
                                        and personal.carnet like '%$text%' and personal.nombre like '%$text%'
                                group by personal.carnet,personal.nombre,personal.sueldo,personal.id_personal
                                having sum(pago.monto)=personal.sueldo";
            $result = $this->CON->consulta($consulta);
            if ($result->num_rows > 0) {
                $lista = array();
                while ($row = $result->fetch_assoc()) {
                    $personal = array();
                    $monto= 0;
                    $sueldo= $row['sueldo'] == null ? 0 : $row['sueldo'];
                    if($row["pagado1"]!=null){
                        $monto= $row['pagado1'] == null ? 0 : $row['pagado1'];
                        $personal['ultimoPago'] = $row['ultimoPago1'] == null ? "" : $row['ultimoPago1'];
                    }else{
                        $personal['ultimoPago'] = $row['ultimoPago'] == null ? "" : $row['ultimoPago'];
                        $monto= $row['pagado'] == null ? 0 : $row['pagado'];
                    }
                    if($monto==$sueldo && $estado!=="PAGADO")continue;
                    $personal['id_personal'] = $row['id_personal'] == null ? "" : $row['id_personal'];
                    $personal['carnet'] = $row['carnet'] == null ? "" : $row['carnet'];
                    $personal['nombre'] = $row['nombre'] == null ? "" : $row['nombre'];
                    $personal['sueldo'] =$sueldo;
                    $personal['pagado'] = $monto;
                    $personal['saldo'] = $sueldo-$monto;
                    $lista[] = $personal;
                }
                return $lista;
            }
        }else{
            $consulta = "select personal.id_personal,personal.carnet,personal.nombre,personal.sueldo,personal.id_personal,sum(pago.monto) as pagado, max(pago.fecha) as ultimoPago1
		from taller.pago join taller.personal on personal.id_personal=pago.id_personal
		where pago.tipo='SUELDO' and pago.estado='ACTIVO'
				and (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))>$ano 
				or (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))=$ano 
				and month(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))<=$mes))
				and (personal.fecha_retirado='' or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>$ano 
				or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))=$ano 
				and month(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>=$mes)))
				and YEAR(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y'))=$ano
				and month(STR_TO_DATE(pago.fecha_Corresponde,'%e/%c/%Y')) =$mes
				and personal.carnet like '%$result%' and personal.nombre like '%$result%'
		group by personal.carnet,personal.nombre,personal.sueldo,personal.id_personal";
            $result = $this->CON->consulta($consulta);
            $registrados="";
            $lista = array();
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $personal = array();
                    $monto= 0;
                    $sueldo= $row['sueldo'] == null ? 0 : $row['sueldo'];
                    $monto= $row['pagado'] == null ? 0 : $row['pagado'];
                    $registrados.=$row['id_personal'].",";    
                    if($monto==$sueldo && $estado!=="PAGADO")continue;
                    $personal['ultimoPago'] = $row['ultimoPago'] == null ? "" : $row['ultimoPago'];
                    $personal['carnet'] = $row['carnet'] == null ? "" : $row['carnet'];
                    $personal['id_personal'] = $row['id_personal'] == null ? "" : $row['id_personal'];
                    $personal['nombre'] = $row['nombre'] == null ? "" : $row['nombre'];
                    $personal['sueldo'] =$sueldo;
                    $personal['pagado'] = $monto;
                    $personal['saldo'] = $sueldo-$monto;
                    $lista[] = $personal;
                }
            }
            if(strlen($registrados)>0){
                $registrados=" and personal.id_personal not in(".substr($registrados,0,strlen($registrados)-1).")";
            }
            $consulta = "select personal.id_personal,personal.carnet,personal.nombre,personal.sueldo,personal.id_personal,0 as pagado, '--/--/----' as ultimoPago "; 
            $consulta .= "from taller.personal ";
            $consulta .= "where (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))>$ano "; 
	    $consulta .= "or (YEAR(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))=$ano "; 
	    $consulta .= "and month(STR_TO_DATE(personal.fecha_ingreso,'%e/%c/%Y'))<=$mes)) ";
	    $consulta .= "and (personal.fecha_retirado='' or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>$ano "; 
	    $consulta .= "or (YEAR(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))=$ano ";
	    $consulta .= "and month(STR_TO_DATE(personal.fecha_retirado,'%e/%c/%Y'))>=$mes))) ";
	    $consulta .= "and personal.carnet like '%$text%' and personal.nombre like '%$text%' $registrados ";
            $result = $this->CON->consulta($consulta);
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    $personal = array();
                    $monto= 0;
                    $sueldo= $row['sueldo'] == null ? 0 : $row['sueldo'];
                    $personal['ultimoPago'] = $row['ultimoPago'] == null ? "" : $row['ultimoPago'];
                    $personal['id_personal'] = $row['id_personal'] == null ? "" : $row['id_personal'];
                    $monto= $row['pagado'] == null ? 0 : $row['pagado'];
                    $personal['carnet'] = $row['carnet'] == null ? "" : $row['carnet'];
                    $personal['nombre'] = $row['nombre'] == null ? "" : $row['nombre'];
                    $personal['sueldo'] =$sueldo;
                    $personal['pagado'] = $monto;
                    $personal['saldo'] = $sueldo-$monto;
                    $lista[] = $personal;
                }
            }
            return $lista;
        }
        return null;
        
    }
    

    function buscarXID($id) {
        $consulta = "select * from taller.PERSONAL where id_personal=$id";
        $result = $this->CON->consulta($consulta);
        $empresa = $this->rellenar($result);
        if ($empresa == null) {
            return null;
        }
        return $empresa[0];
    }

    function modificar($id_personal) {
        $contra="";
        if(!$this->contrasena==""){
            $contra=", contrasena =MD5('" . $this->contrasena . "')";
        }
        $consulta = "update taller.PERSONAL set cumpleano='" . $this->cumpleano . "', fecha_retirado ='" . $this->fecha_retirado . "', foto ='" . $this->foto . "', carnet ='" . $this->carnet . "', nombre ='" . $this->nombre . "', direccion ='" . $this->direccion . "', correo ='" . $this->correo . "' $contra, sueldo =" . $this->sueldo . ", rol ='" . $this->rol . "',estado ='" . $this->estado . "' where id_personal=" . $id_personal;
        $result = $this->CON->manipular($consulta);
        return $result;
    }

    function insertar() {
        $consulta = "insert into taller.PERSONAL(id_personal, foto, carnet, nombre, direccion, correo, cumpleano, fecha_ingreso, cuenta, contrasena, sueldo, rol, id_empresa, estado, fecha_retirado) values(" . $this->id_personal . ",'" . $this->foto . "','" . $this->carnet . "','" . $this->nombre . "','" . $this->direccion . "','" . $this->correo . "','" . $this->cumpleano . "','" . $this->fecha_ingreso . "','" . $this->cuenta . "',MD5('$this->contrasena')," . $this->sueldo . ",'" . $this->rol . "'," . $this->id_empresa . ",'ACTIVO','" . $this->fecha_retirado . "')";
        $resultado = $this->CON->manipular($consulta);
        return $resultado;
    }
    function logear($cuenta,$contrasena) {
        $consulta = "select count(*) as cant from taller.PERSONAL where cuenta='$cuenta' and contrasena=MD5('$contrasena')";
        $result = $this->CON->consulta($consulta);
        $empresa = $result->fetch_assoc()['cant'];
        return $empresa;
    }
    function estadoUsuario($cuenta,$contrasena) {
        $consulta = "select id_empresa ,id_personal from taller.PERSONAL where cuenta='$cuenta' and contrasena=MD5('$contrasena') and estado='ACTIVO'";
        $result = $this->CON->consulta($consulta);
        if ($result->num_rows > 0) {
            $empresa=array();
            $row=$result->fetch_assoc();
            $empresa["empresa"] = $row['id_empresa'];
            $empresa["personal"] = $row['id_personal'];
            return $empresa;
        }else{
            return null;
        }
    }

}
