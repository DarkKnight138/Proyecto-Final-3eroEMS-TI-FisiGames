<?php
class BaseDatos {
/********************************************************************************/
   private $servidor;      // En Xampp es "localhost"
   private $usuario;       // En Xampp es "root"
   private $password;      // En Xampp es ""
   private $base_datos;    // base datos en phpmyadmin
   private $conexion;      // Para las operaciones con MySQL
/********************************************************************************/ 
   public function __construct() {
       $this->servidor = "localhost";
       $this->usuario = "root";
       $this->password = "";
       $this->base_datos = "fisigames";
       $this->conexion = $this->nueva($this->servidor, $this->usuario, $this->password, $this->base_datos);
   }
/*******************************************************************************/  
   private function nueva($server,$user,$pass,$base) {
       try {
           $conectar = mysqli_connect($server,$user,$pass,$base);
       } catch (Exception $ex) {
           die($ex->getMessage());
       }
       return $conectar;
   }  
/********************************************************************************/
   public function seleccionarTodos() {
       $resultado = mysqli_query($this->conexion, "select * from cuenta");
       $arreglo = mysqli_fetch_all($resultado,MYSQLI_ASSOC);
       return $arreglo;
   }
/********************************************************************************/
   public function existeUsuario(){
       $existe=false


      
   }
}
?>
