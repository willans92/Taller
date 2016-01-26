<?php
class ACCESORIO {
	var $id_accesorio;
	var $descripcion;
	var $id_empresa;
	var $CON;
	function ACCESORIO($con) {
		$this->CON=$con;
	}

	function contructor($id_accesorio, $descripcion, $id_empresa){
		$this->id_accesorio = $id_accesorio;
		$this->descripcion = $descripcion;
		$this->id_empresa = $id_empresa;
	}

	function rellenar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$accesorio=new ACCESORIO();
				$accesorio->id_accesorio=$row['id_accesorio']==null?"":$row['id_accesorio'];
				$accesorio->descripcion=$row['descripcion']==null?"":$row['descripcion'];
				$accesorio->id_empresa=$row['id_empresa']==null?"":$row['id_empresa'];
				$lista[]=$accesorio;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.ACCESORIO";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.ACCESORIO where id_accesorio=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_accesorio){
		$consulta="update taller.ACCESORIO set id_accesorio =".$this->id_accesorio.", descripcion ='".$this->descripcion."', id_empresa =".$this->id_empresa." where id_accesorio=".$id_accesorio;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.ACCESORIO(id_accesorio, descripcion, id_empresa) values(".$this->id_accesorio.",'".$this->descripcion."',".$this->id_empresa.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
