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
    </style>
</head>
<body>
<div id="contenedor">
<form method="POST" action="session.php">
<div id="logo"><a href="inicio.html"><img src="recursos/img/main-logo.png"></a></div>
    <h2>Rellene el formulario para crear su cuenta:</h2>
    <table>
        <tr>
            <td><label for="user">Correo electrónico</label></td>
            <td><input name="user" type="text" id="user"></td>
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
    <input name="enviar" type="submit" id="enviar" value="Regristar Cuenta">
</form>
</div>
</body>
</html>