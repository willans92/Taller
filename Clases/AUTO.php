<?php
class AUTO {
	var $id_auto;
	var $modelo;
	var $placa;
	var $color;
	var $nro_chasis;
	var $observacion;
	var $id_vehiculo;
	var $id_marca;
	var $id_cliente;
	var $CON;
	function AUTO($con) {
		$this->CON=$con;
	}

	function contructor($id_auto, $modelo, $placa, $color, $nro_chasis, $observacion, $id_vehiculo, $id_marca, $id_cliente){
		$this->id_auto = $id_auto;
		$this->modelo = $modelo;
		$this->placa = $placa;
		$this->color = $color;
		$this->nro_chasis = $nro_chasis;
		$this->observacion = $observacion;
		$this->id_vehiculo = $id_vehiculo;
		$this->id_marca = $id_marca;
		$this->id_cliente = $id_cliente;
	}

	function rellenar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$auto=new AUTO();
				$auto->id_auto=$row['id_auto']==null?"":$row['id_auto'];
				$auto->modelo=$row['modelo']==null?"":$row['modelo'];
				$auto->placa=$row['placa']==null?"":$row['placa'];
				$auto->color=$row['color']==null?"":$row['color'];
				$auto->nro_chasis=$row['nro_chasis']==null?"":$row['nro_chasis'];
				$auto->observacion=$row['observacion']==null?"":$row['observacion'];
				$auto->id_vehiculo=$row['id_vehiculo']==null?"":$row['id_vehiculo'];
				$auto->id_marca=$row['id_marca']==null?"":$row['id_marca'];
				$auto->id_cliente=$row['id_cliente']==null?"":$row['id_cliente'];
				$lista[]=$auto;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.AUTO";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.AUTO where id_auto=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_auto){
		$consulta="update taller.AUTO set id_auto =".$this->id_auto.", modelo ='".$this->modelo."', placa ='".$this->placa."', color ='".$this->color."', nro_chasis ='".$this->nro_chasis."', observacion ='".$this->observacion."', id_vehiculo =".$this->id_vehiculo.", id_marca =".$this->id_marca.", id_cliente =".$this->id_cliente." where id_auto=".$id_auto;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.AUTO(id_auto, modelo, placa, color, nro_chasis, observacion, id_vehiculo, id_marca, id_cliente) values(".$this->id_auto.",'".$this->modelo."','".$this->placa."','".$this->color."','".$this->nro_chasis."','".$this->observacion."',".$this->id_vehiculo.",".$this->id_marca.",".$this->id_cliente.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
