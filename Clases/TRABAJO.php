<?php
class TRABAJO {
	var $id_trabajo;
	var $descripcion;
	var $costo;
	var $CON;
	function TRABAJO($con) {
		$this->CON=$con;
	}

	function contructor($id_trabajo, $descripcion, $costo){
		$this->id_trabajo = $id_trabajo;
		$this->descripcion = $descripcion;
		$this->costo = $costo;
	}

	function rellenar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$trabajo=new TRABAJO();
				$trabajo->id_trabajo=$row['id_trabajo']==null?"":$row['id_trabajo'];
				$trabajo->descripcion=$row['descripcion']==null?"":$row['descripcion'];
				$trabajo->costo=$row['costo']==null?"":$row['costo'];
				$lista[]=$trabajo;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.TRABAJO";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.TRABAJO where id_trabajo=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_trabajo){
		$consulta="update taller.TRABAJO set id_trabajo =".$this->id_trabajo.", descripcion ='".$this->descripcion."', costo =".$this->costo." where id_trabajo=".$id_trabajo;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.TRABAJO(id_trabajo, descripcion, costo) values(".$this->id_trabajo.",'".$this->descripcion."',".$this->costo.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
