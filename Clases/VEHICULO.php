<?php
class VEHICULO {
	var $id_vehiculo;
	var $descripcion;
	var $CON;
	function VEHICULO($con) {
		$this->CON=$con;
	}

	function contructor($id_vehiculo, $descripcion){
		$this->id_vehiculo = $id_vehiculo;
		$this->descripcion = $descripcion;
	}

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$vehiculo=new VEHICULO();
				$vehiculo->id_vehiculo=$row['id_vehiculo']==null?"":$row['id_vehiculo'];
				$vehiculo->descripcion=$row['descripcion']==null?"":$row['descripcion'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.VEHICULO";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.VEHICULO where id_vehiculo=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_vehiculo){
		$consulta="update taller.VEHICULO set id_vehiculo =".$this->id_vehiculo.", descripcion ='".$this->descripcion."' where id_vehiculo=".$id_vehiculo;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.VEHICULO(id_vehiculo, descripcion) values(".$this->id_vehiculo.",'".$this->descripcion."')";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
