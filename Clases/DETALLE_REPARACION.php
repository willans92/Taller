<?php
class DETALLE_REPARACION {
	var $id_reparacion;
	var $id_trabajos;
	var $CON;
	function DETALLE_REPARACION($con) {
		$this->CON=$con;
	}

	function contructor($id_reparacion, $id_trabajos){
		$this->id_reparacion = $id_reparacion;
		$this->id_trabajos = $id_trabajos;
	}

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$detalle_reparacion=new DETALLE_REPARACION();
				$detalle_reparacion->id_reparacion=$row['id_reparacion']==null?"":$row['id_reparacion'];
				$detalle_reparacion->id_trabajos=$row['id_trabajos']==null?"":$row['id_trabajos'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.DETALLE_REPARACION";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.DETALLE_REPARACION where id_reparacion=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_reparacion){
		$consulta="update taller.DETALLE_REPARACION set id_reparacion =".$this->id_reparacion.", id_trabajos =".$this->id_trabajos." where id_reparacion=".$id_reparacion;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.DETALLE_REPARACION(id_reparacion, id_trabajos) values(".$this->id_reparacion.",".$this->id_trabajos.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
