<?php
class PAGO {
	var $id_pago_cliente;
	var $fecha;
	var $monto;
	var $id_reparacion;
	var $tipo;
	var $descripcion;
	var $id_personal;
	var $CON;
	function PAGO($con) {
		$this->CON=$con;
	}

	function contructor($id_pago_cliente, $fecha, $monto, $id_reparacion, $tipo, $descripcion, $id_personal){
		$this->id_pago_cliente = $id_pago_cliente;
		$this->fecha = $fecha;
		$this->monto = $monto;
		$this->id_reparacion = $id_reparacion;
		$this->tipo = $tipo;
		$this->descripcion = $descripcion;
		$this->id_personal = $id_personal;
	}

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$pago=new PAGO();
				$pago->id_pago_cliente=$row['id_pago_cliente']==null?"":$row['id_pago_cliente'];
				$pago->fecha=$row['fecha']==null?"":$row['fecha'];
				$pago->monto=$row['monto']==null?"":$row['monto'];
				$pago->id_reparacion=$row['id_reparacion']==null?"":$row['id_reparacion'];
				$pago->tipo=$row['tipo']==null?"":$row['tipo'];
				$pago->descripcion=$row['descripcion']==null?"":$row['descripcion'];
				$pago->id_personal=$row['id_personal']==null?"":$row['id_personal'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.PAGO";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.PAGO where id_pago_cliente=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_pago_cliente){
		$consulta="update taller.PAGO set id_pago_cliente =".$this->id_pago_cliente.", fecha ='".$this->fecha."', monto =".$this->monto.", id_reparacion =".$this->id_reparacion.", tipo ='".$this->tipo."', descripcion ='".$this->descripcion."', id_personal =".$this->id_personal." where id_pago_cliente=".$id_pago_cliente;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.PAGO(id_pago_cliente, fecha, monto, id_reparacion, tipo, descripcion, id_personal) values(".$this->id_pago_cliente.",'".$this->fecha."',".$this->monto.",".$this->id_reparacion.",'".$this->tipo."','".$this->descripcion."',".$this->id_personal.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
