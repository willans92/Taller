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
		$this->CON=$con;
	}

	function contructor($id_reparacion, $fecha_Ingreso, $fecha_salida, $kilometro, $combustible, $OT, $id_auto, $total, $estado, $id_personal){
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

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$reparacion=new REPARACION();
				$reparacion->id_reparacion=$row['id_reparacion']==null?"":$row['id_reparacion'];
				$reparacion->fecha_Ingreso=$row['fecha_Ingreso']==null?"":$row['fecha_Ingreso'];
				$reparacion->fecha_salida=$row['fecha_salida']==null?"":$row['fecha_salida'];
				$reparacion->kilometro=$row['kilometro']==null?"":$row['kilometro'];
				$reparacion->combustible=$row['combustible']==null?"":$row['combustible'];
				$reparacion->OT=$row['OT']==null?"":$row['OT'];
				$reparacion->id_auto=$row['id_auto']==null?"":$row['id_auto'];
				$reparacion->total=$row['total']==null?"":$row['total'];
				$reparacion->estado=$row['estado']==null?"":$row['estado'];
				$reparacion->id_personal=$row['id_personal']==null?"":$row['id_personal'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.REPARACION";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.REPARACION where id_reparacion=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_reparacion){
		$consulta="update taller.REPARACION set id_reparacion =".$this->id_reparacion.", fecha_Ingreso ='".$this->fecha_Ingreso."', fecha_salida ='".$this->fecha_salida."', kilometro ='".$this->kilometro."', combustible ='".$this->combustible."', OT ='".$this->OT."', id_auto =".$this->id_auto.", total =".$this->total.", estado ='".$this->estado."', id_personal =".$this->id_personal." where id_reparacion=".$id_reparacion;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.REPARACION(id_reparacion, fecha_Ingreso, fecha_salida, kilometro, combustible, OT, id_auto, total, estado, id_personal) values(".$this->id_reparacion.",'".$this->fecha_Ingreso."','".$this->fecha_salida."','".$this->kilometro."','".$this->combustible."','".$this->OT."',".$this->id_auto.",".$this->total.",'".$this->estado."',".$this->id_personal.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
