<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro</title>
    <style>
        img{
            width: 10%;
            height: auto;
        }
        #contenedor{
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: .613rem .938rem;
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            width: 50%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-right: auto;
            margin-left: auto;
        }
        #registro{
            position: relative;
            background-color: #E21921;
            color: white;
            transition: background-color 200ms ease-in;
            transition: color 200ms ease-in;
            font-size: 1rem;
            line-height: .875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            height: 56px;
            
        }
        #registro:hover{
            background-color: #BC0A11;
        }
        table{
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: .613rem .938rem;
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            width: 50%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-right: auto;
            margin-left: auto;
        }
        #registrarse{
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: .613rem .938rem;
            --bs-gutter-x: 1.5rem;
            --bs-gutter-y: 0;
            width: 50%;
            padding-right: calc(var(--bs-gutter-x) * .5);
            padding-left: calc(var(--bs-gutter-x) * .5);
            margin-right: auto;
            margin-left: auto;
            position: relative;
            border: 1px solid #E21921;
            color: #E21921;
            transition: background-color 200ms ease-in;
            transition: color 200ms ease-in;
            font-size: 1rem;
            line-height: .875rem;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            cursor: pointer;
            height: 56px;
        }
        #registrarse:hover{
            border: 1px solid #BC0A11;
            color: #BC0A11;
        }
        h1{
            color:#E21921;
            font-size: 1rem;
        }
    </style>
</head>
<body>
<div id="contenedor">
<form method="POST" action="registro_usuario.php">
<div id="logo"><a href="inicio.html"><img src="recursos/img/main-logo.png"></a></div>
    <h2>Rellene el formulario para crear su cuenta:</h2>
    
    <table>
        <tr>
        <td><label for="nombre">nombre de usuario</label></td>
        <td><input name="nombre" type="text" id="nombre"></td>
        </tr>
        <tr>
            <td><label for="correo">Correo electrónico</label></td>
            <td><input name="correo" type="text" id="correo"></td>
        </tr>
        <tr>
            <td><label for="pw">Contraseña</label></td>
            <td><input name="pw" type="password" id="pw"></td>
        </tr>
        <tr>
            <td><label for ="DNI">DNI</label></td>
            <td><input name="DNI" type="text" id="DNI" pattern="[0-9]{8}[A-Z]" ></td>
        </tr>
        <tr>
            <td><label for ="tlfn">nº tlfn</label></td>
            <td><input name="tlfn" type="tel" id="tlfn" pattern="[+][0-9]{2}[ ][0-9]{9}"></td>
        </tr>
    </table>
    <input name="registrarse" type="submit" id="registrarse" value="Regristar Cuenta">
</form>
<?php
    if(isset($_GET['vacio']) && ($_GET['vacio']==1)){echo "<h1> complete todos los datos del formulario </h1>";}
    if(isset($_GET['correoexistente']) && ($_GET['correoexistente']==1)){echo "<h1> este correo electrónico ya ha sido registrado </h1>";}
    if(isset($_GET['dni']) && ($_GET['dni']==1)){echo "<h1> este dni esta en uso por otro usuario </h1>";}

    ?>
</div>
</body>
</html>