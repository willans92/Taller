<?php
class CLIENTE {
	var $id_cliente;
	var $foto;
	var $nombre;
	var $direccion;
	var $Telefono_Casa;
	var $Telefono_Oficina;
	var $Telefono_Celular;
	var $Ci;
	var $id_empresa;
	var $CON;
	function CLIENTE($con) {
		$this->CON=$con;
	}

	function contructor($id_cliente, $foto, $nombre, $direccion, $Telefono_Casa, $Telefono_Oficina, $Telefono_Celular, $Ci, $id_empresa){
		$this->id_cliente = $id_cliente;
		$this->foto = $foto;
		$this->nombre = $nombre;
		$this->direccion = $direccion;
		$this->Telefono_Casa = $Telefono_Casa;
		$this->Telefono_Oficina = $Telefono_Oficina;
		$this->Telefono_Celular = $Telefono_Celular;
		$this->Ci = $Ci;
		$this->id_empresa = $id_empresa;
	}

	function rellenar($resultado){
		if ($resultado->num_rows > 0) {
			$lista=array();
			while($row = $resultado->fetch_assoc()) {
				$cliente=new CLIENTE();
				$cliente->id_cliente=$row['id_cliente']==null?"":$row['id_cliente'];
				$cliente->foto=$row['foto']==null?"":$row['foto'];
				$cliente->nombre=$row['nombre']==null?"":$row['nombre'];
				$cliente->direccion=$row['direccion']==null?"":$row['direccion'];
				$cliente->Telefono_Casa=$row['Telefono_Casa']==null?"":$row['Telefono_Casa'];
				$cliente->Telefono_Oficina=$row['Telefono_Oficina']==null?"":$row['Telefono_Oficina'];
				$cliente->Telefono_Celular=$row['Telefono_Celular']==null?"":$row['Telefono_Celular'];
				$cliente->Ci=$row['Ci']==null?"":$row['Ci'];
				$cliente->id_empresa=$row['id_empresa']==null?"":$row['id_empresa'];
				$lista[]=$cliente;
			}
			return $lista;
		}else{
			return null;
		}
	}

	function todo(){
		$consulta="select * from taller.CLIENTE";
		$result=$this->CON->consulta($consulta);
		return $this->rellenar($result);
	}


	function buscarXID($id){
		$consulta="select * from taller.CLIENTE where id_cliente=$id";
		$result=$this->CON->consulta($consulta);
		$empresa=$this->rellenar($result);
		if($empresa==null){
			return null;
		}
return $empresa[0];
	}

	function modificar($id_cliente){
		$consulta="update taller.CLIENTE set id_cliente =".$this->id_cliente.", foto ='".$this->foto."', nombre ='".$this->nombre."', direccion ='".$this->direccion."', Telefono_Casa ='".$this->Telefono_Casa."', Telefono_Oficina ='".$this->Telefono_Oficina."', Telefono_Celular ='".$this->Telefono_Celular."', Ci ='".$this->Ci."', id_empresa =".$this->id_empresa." where id_cliente=".$id_cliente;
		$result=$this->CON->consulta($consulta);
		return $ret['cant'];
	}

	function insertar(){
		$consulta="insert into taller.CLIENTE(id_cliente, foto, nombre, direccion, Telefono_Casa, Telefono_Oficina, Telefono_Celular, Ci, id_empresa) values(".$this->id_cliente.",'".$this->foto."','".$this->nombre."','".$this->direccion."','".$this->Telefono_Casa."','".$this->Telefono_Oficina."','".$this->Telefono_Celular."','".$this->Ci."',".$this->id_empresa.")";
		$resultado=$this->CON->consulta($consulta);
		$consulta="SELECT LAST_INSERT_ID() as id";
		$resultado=$this->CON->consulta($consulta);
		return $resultado->fetch_assoc()['id'];
	}

}
