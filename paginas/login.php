<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
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
        #enviar{
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
        #enviar:hover{
            border: 1px solid #BC0A11;
            color: #BC0A11;
        }
        a{
            outline: none;
            text-decoration: none;
            color: white;
        }
    </style>
</head>
<body>
<div id="contenedor">
<form method="POST" action="session.php">
<div id="logo"><a href="inicio.php"><img src="recursos/img/main-logo.png"></a></div>

    <a id="registro" href="registro.php">Registrese ahora</a>
    <h2>Inicia sesión con tu email</h2>
    <table>
        <tr>
        <td rowspan="4";><?php if(isset($_GET['vacio']) && ($_GET['vacio']==1)){echo "Faltan datos";}?></td>
        </tr>
        <tr>
            <td><label for="correo">Correo electrónico</label></td>
            <td><input name="correo" type="text" id="correo"></td>
            <td rowspan="2";><?php if(isset($_GET['usuario']) && ($_GET['usuario']==1)){echo "el correo introducido no esta registrado ";}?></td>
        </tr>
        <tr>
            <td><label for="pw">Contraseña</label></td>
            <td><input name="pw" type="password" id="pw"></td>
            <td rowspan="2";><?php if(isset($_GET['err']) && ($_GET['err']==1)){echo "Contraseña incorrecta";}?></td>
        </tr>
    </table>
    <input name="iniciar" type="submit" id="enviar" value="Iniciar sesión">
</form>
</div>
</body>
</html>