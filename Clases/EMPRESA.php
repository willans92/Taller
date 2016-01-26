<?php
class EMPRESA {
	var $id_empresa;
	var $nombre;
	var $razon_social;
	var $logo;
	var $aniversario;
	var $fecha_afiliacion;
	var $nit;
	var $direccion;
	var $nro_factura;
	var $fecha_factura;
	var $llave_dosificacion;
	var $nro_autorizacion;
	var $CON;
	function EMPRESA($con) {
		$this->CON=$con;
	}

	function contructor($id_empresa, $nombre, $razon_social, $logo, $aniversario, $fecha_afiliacion, $nit, $direccion, $nro_factura, $fecha_factura, $llave_dosificacion, $nro_autorizacion){
		$this->id_empresa = $id_empresa;
		$this->nombre = $nombre;
		$this->razon_social = $razon_social;
		$this->logo = $logo;
		$this->aniversario = $aniversario;
		$this->fecha_afiliacion = $fecha_afiliacion;
		$this->nit = $nit;
		$this->direccion = $direccion;
		$this->nro_factura = $nro_factura;
		$this->fecha_factura = $fecha_factura;
		$this->llave_dosificacion = $llave_dosificacion;
		$this->nro_autorizacion = $nro_autorizacion;
	}

	function rellenar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$empresa=new EMPRESA();
				$empresa->id_empresa=$row['id_empresa']==null?"":$row['id_empresa'];
				$empresa->nombre=$row['nombre']==null?"":$row['nombre'];
				$empresa->razon_social=$row['razon_social']==null?"":$row['razon_social'];
				$empresa->logo=$row['logo']==null?"":$row['logo'];
				$empresa->aniversario=$row['aniversario']==null?"":$row['aniversario'];
				$empresa->fecha_afiliacion=$row['fecha_afiliacion']==null?"":$row['fecha_afiliacion'];
				$empresa->nit=$row['nit']==null?"":$row['nit'];
				$empresa->direccion=$row['direccion']==null?"":$row['direccion'];
				$empresa->nro_factura=$row['nro_factura']==null?"":$row['nro_factura'];
				$empresa->fecha_factura=$row['fecha_factura']==null?"":$row['fecha_factura'];
				$empresa->llave_dosificacion=$row['llave_dosificacion']==null?"":$row['llave_dosificacion'];
				$empresa->nro_autorizacion=$row['nro_autorizacion']==null?"":$row['nro_autorizacion'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.EMPRESA";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.EMPRESA where id_empresa=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_empresa){
		$consulta="update taller.EMPRESA set id_empresa =".$this->id_empresa.", nombre ='".$this->nombre."', razon_social ='".$this->razon_social."', logo ='".$this->logo."', aniversario ='".$this->aniversario."', fecha_afiliacion ='".$this->fecha_afiliacion."', nit ='".$this->nit."', direccion ='".$this->direccion."', nro_factura ='".$this->nro_factura."', fecha_factura ='".$this->fecha_factura."', llave_dosificacion ='".$this->llave_dosificacion."', nro_autorizacion ='".$this->nro_autorizacion."' where id_empresa=".$id_empresa;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.EMPRESA(id_empresa, nombre, razon_social, logo, aniversario, fecha_afiliacion, nit, direccion, nro_factura, fecha_factura, llave_dosificacion, nro_autorizacion) values(".$this->id_empresa.",'".$this->nombre."','".$this->razon_social."','".$this->logo."','".$this->aniversario."','".$this->fecha_afiliacion."','".$this->nit."','".$this->direccion."','".$this->nro_factura."','".$this->fecha_factura."','".$this->llave_dosificacion."','".$this->nro_autorizacion."')";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
