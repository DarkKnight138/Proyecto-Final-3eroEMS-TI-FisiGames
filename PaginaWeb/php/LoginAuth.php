<?php
class Login_Auth {
   private $usuarioLogin;
   private $contraseñaLogin;


   public function __construct($usuario, $contraseña){
       $this->usuarioLogin=$usuario;
       $this->contraseñaLogin=$contraseña;
   }


   public function getUsuario(){
       return $this->usuarioLogin;
   }
   public function setUsuario($usuario=$_POST['usuario']){
       $this->usuarioLogin=$usuario;
   }
   public function getContraseña(){
       return $this->contraseñaLogin;
   }
   public function setContraseñaLogin($contraseña=$_POST['contraseña']){
       $this->contraseñaLogin=$contraseña;
   }
  




}
?>
