<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

// // Ruta absoluta
$ruta_absoluta = $_SESSION['user_imagen'];
// echo ($ruta_absoluta);
// echo '<br>';
// // Convertir a ruta relativa
$ruta_relativa = str_replace('C:\xampp\htdocs\curso_php\mi-proyecto\views/', '', $ruta_absoluta);
// echo ($ruta_relativa);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body {
            margin: 0;
            background-image: url("Imagenes/fondo-murciela.gif");
            background-size: cover;
            background-position: center;
        }
        .caja {
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */
        }
        a {
            text-align: center;
            text-decoration: none; /* Elimina el subrayado del enlace */
            color: white;
            font-size: 20px;
        }
        form {
            place-items: center;
            width: 100%;
            border: 2px solid #ff0000; /* Cambia el color del borde */
            background-color: rgba(255, 255, 255, 0.5); /* Color de fondo con opacidad */
            padding: 20px; /* Espaciado interno */
            border-radius: 1px 40px 1px 10px; /* Bordes redondeados */
        }
        h2 {
            text-align: center;
        }
    </style>
</head>
<body>
<div class="caja">
        <form method="post">
            <h2>Bienvenido  <?php echo htmlspecialchars($_SESSION['user_name']); ?></h2>
    
            <p>Tu rol es: <?php echo htmlspecialchars($_SESSION['user_role']); ?></p>
            <p>Tu correo es: <?php echo htmlspecialchars($_SESSION['user_email']); ?></p>

            <p>Imagen de perfil:</p>
            <img src="<?php echo htmlspecialchars($ruta_relativa); ?>" alt="Imagen de usuario no funciona">
            <br>
            <br>
            <button type="submit"><a href="admin.php">Administración</a></button>
            <button type="submit"><a href="../logout.php">Cerrar Sesión</a></button>
        </form>
    </div>
</body>
</html>