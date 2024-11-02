<?php
    
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="/mi-proyecto/css/estilos.css">
    <style>
        body{
            margin: 0; /* Elimina márgenes por defecto */
            background-image: url("views/Imagenes/cara-halloween.gif");
            background-size: cover;
            background-position: center;
            

        }
        .caja{
            font-family:'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            color: white;
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */
        }

        header{
            background: white;
            display: flex; /* Activa el modo flexbox */
            justify-content: flex-end; /* Alinea horizontalmente el contenido a la derecha */
            align-items: center; /* Centra verticalmente el contenido dentro del header */
            height: 50px;
        }

        a{
            padding-right: 20px;
            text-decoration: none; /* Elimina el subrayado del enlace */
            color: black;
            font-size: 27px;
        }
        
        h2{
            text-align: center;
        }

    </style>
</head>
<body>

    <header>
        <a href="/curso_php/mi-proyecto/views/login.php">Login</a>
        <a href="/curso_php/mi-proyecto/views/Registro.php">Registrar</a>
    </header>

    <div class="caja">
        <h1> 🎃 -> ¡BIENVENIDO! <- 🎃</h1>
        
    </div>
</body>
</html>
