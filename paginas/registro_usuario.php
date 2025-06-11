<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/x-icon" href="recursos/img/RAYO.png">
    <title>Registro de usuario | Rayo Vallecano</title>
</head>
<body>
<?php
error_reporting (E_ALL);
require("conexion.php");
$bd=conectar();
$vacio=false;
    if(isset($_POST['registrarse'])){
        if(empty($_POST['nombre']) OR empty($_POST['correo']) OR empty($_POST['pw']) OR empty($_POST['DNI']) OR empty($_POST['tlfn'])){
            $vacio = true;
            header("location: registro.php?vacio=1");
        }
        $correos=$bd->prepare("SELECT * FROM USUARIOS");
        $correos->execute();
                foreach($correos as $correo){
                    if($correo['CORREO_USUARIO']==$_POST['correo']){
                        header("location: registro.php?correoexistente=1");
                        exit();
                    }
                    if($correo['DNI_USUARIO']==$_POST['DNI']){
                        header("location: registro.php?dni=1");
                        exit();
                    }
                }
                $user_contador=$correos->rowCount();
                $user_temp=$bd->prepare("SELECT NUM_USUARIO, NOMBRE_USUARIO, PS_USUARIO, DNI_USUARIO, TLFN_USUARIO, CORREO_USUARIO, FECHA_ALTA_USUARIO FROM USUARIOS");
$sql="INSERT INTO `tfc`.`USUARIOS`(`NUM_USUARIO`, `NOMBRE_USUARIO`, `PS_USUARIO`, `DNI_USUARIO`, `TLFN_USUARIO`, `CORREO_USUARIO`, `FECHA_ALTA_USUARIO`) VALUES('".$user_contador."', '".$_POST['nombre']."', '".$_POST['pw']."', '".$_POST['DNI']."', '".$_POST['tlfn']."', '".$_POST['correo']."' , '".date('Y-m-d')."')";
                $insertar=$bd->query($sql);
                session_start();
                session_regenerate_id();
                    $_SESSION['login'] = true;
                    $_SESSION['correo']= $_POST["correo"];
                    $_SESSION['id']="id";
                header("location: inicio.php");
    }