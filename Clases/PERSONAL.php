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
	var $sueldo;
	var $rol;
	var $id_empresa;
	var $CON;
	function PERSONAL($con) {
		$this->CON=$con;
	}

	function contructor($id_personal, $foto, $carnet, $nombre, $direccion, $correo, $cumpleano, $fecha_ingreso, $cuenta, $contrasena, $sueldo, $rol, $id_empresa){
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
	}

	function cargar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$personal=new PERSONAL();
				$personal->id_personal=$row['id_personal']==null?"":$row['id_personal'];
				$personal->foto=$row['foto']==null?"":$row['foto'];
				$personal->carnet=$row['carnet']==null?"":$row['carnet'];
				$personal->nombre=$row['nombre']==null?"":$row['nombre'];
				$personal->direccion=$row['direccion']==null?"":$row['direccion'];
				$personal->correo=$row['correo']==null?"":$row['correo'];
				$personal->cumpleano=$row['cumpleano']==null?"":$row['cumpleano'];
				$personal->fecha_ingreso=$row['fecha_ingreso']==null?"":$row['fecha_ingreso'];
				$personal->cuenta=$row['cuenta']==null?"":$row['cuenta'];
				$personal->contrasena=$row['contrasena']==null?"":$row['contrasena'];
				$personal->sueldo=$row['sueldo']==null?"":$row['sueldo'];
				$personal->rol=$row['rol']==null?"":$row['rol'];
				$personal->id_empresa=$row['id_empresa']==null?"":$row['id_empresa'];
				$lista[]=$empresa;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.PERSONAL";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.PERSONAL where id_personal=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_personal){
		$consulta="update taller.PERSONAL set id_personal =".$this->id_personal.", foto ='".$this->foto."', carnet ='".$this->carnet."', nombre ='".$this->nombre."', direccion ='".$this->direccion."', correo ='".$this->correo."', cumpleano ='".$this->cumpleano."', fecha_ingreso ='".$this->fecha_ingreso."', cuenta ='".$this->cuenta."', contrasena ='".$this->contrasena."', sueldo =".$this->sueldo.", rol ='".$this->rol."', id_empresa =".$this->id_empresa." where id_personal=".$id_personal;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.PERSONAL(id_personal, foto, carnet, nombre, direccion, correo, cumpleano, fecha_ingreso, cuenta, contrasena, sueldo, rol, id_empresa) values(".$this->id_personal.",'".$this->foto."','".$this->carnet."','".$this->nombre."','".$this->direccion."','".$this->correo."','".$this->cumpleano."','".$this->fecha_ingreso."','".$this->cuenta."','".$this->contrasena."',".$this->sueldo.",'".$this->rol."',".$this->id_empresa.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
