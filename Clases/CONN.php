<?php

class CONN {
   var $servername = "localhost";
   var $username = "root";
   var $password = "";
   var $dbname="chiquifest";
   var $conn;
   var $estado;
   
   function CONN($cuenta,$contrasena){
        try {
            $this->username=$cuenta;
            $this->password=$contrasena;
            $this->conn = new mysqli($this->servername,$cuenta, $contrasena,$this->dbname);
            if($this->conn->connect_errno){
                $this->estado= false;
            }else{
                $this->estado= true;
            }
        }
        catch(PDOException $e)
        {
            $this->estado= false;
        }  
   }
   function transacion(){
       $this->conn->autocommit(false);
   }
   function manipular($query){
        if ($this->conn->query($query) === TRUE) {
            return true;
        } else {
            return false;
        }
   }
   function consulta($sql){
        $result = $this->conn->query($sql);
        if ($result->num_rows > 0) {
            return $result;
        } else {
            return null;
        }
   }
   function cerrarConexion(){
       try {
           $close = $conn->close();
       } catch (Exception $ex) {
           throw $ex;
       }
   }
   function commit(){
       $this->conn->commit();
   }
   function rollback(){
       $this->conn->rollback();
   }
}