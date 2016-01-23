<?php
class MARCA {
	var $id_marca;
	var $descripcion;
	var $CON;
	function MARCA($con) {
		$this->CON=$con;
	}

	function contructor($id_marca, $descripcion){
		$this->id_marca = $id_marca;
		$this->descripcion = $descripcion;
	}

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$marca=new MARCA();
				$marca->id_marca=$row['id_marca']==null?"":$row['id_marca'];
				$marca->descripcion=$row['descripcion']==null?"":$row['descripcion'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.MARCA";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.MARCA where id_marca=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_marca){
		$consulta="update taller.MARCA set id_marca =".$this->id_marca.", descripcion ='".$this->descripcion."' where id_marca=".$id_marca;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.MARCA(id_marca, descripcion) values(".$this->id_marca.",'".$this->descripcion."')";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
