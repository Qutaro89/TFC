<?php
session_start();
error_reporting (E_ALL);
require("conexion.php");
$bd=conectar();
if(isset($_POST['iniciar'])){
    if(empty($_POST['correo']) OR empty($_POST['pw'])){
        $vacio = true;
        header("location: login.php?vacio=1");
    }else{
        $usuarios=$bd->prepare("SELECT CORREO_USUARIO, PS_USUARIO FROM USUARIOS WHERE CORREO_USUARIO = :correo");
        $usuarios->execute(array(':correo'=>$_POST['correo']));
        foreach($usuarios as $usuario){
            if($usuario['CORREO_USUARIO']!=$_POST['correo']){
                session_unset();
                session_destroy();
                header("location: login.php?usuario=1");
                break;
            }
            if($usuario["PS_USUARIO"]==$_POST["pw"]){
                session_regenerate_id();
                $_SESSION['login'] = true;
                $_SESSION['correo']= $_POST["correo"];
                $_SESSION['id']="id";
                header("location: inicio.php");
            }else{
                session_unset();
                session_destroy();
                header("location: login.php?err=1");
                break;
            }
        }

    }
}