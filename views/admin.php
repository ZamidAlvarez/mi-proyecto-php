<?php
session_start();
require_once '../inc/funciones.php';
require_once '../inc/conexion.php';

if (!verificar_rol('admin')) {
    echo "Acceso denegado.";
    exit;
}

$errores = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $titulo = $_POST['titulo'] ?? '';
    $descripcion = $_POST['descripcion'] ?? '';
    $user_id = $_SESSION['user_id'];

    if (empty($titulo)) {
        $errores['titulo'] = "Ingrese un título.";
    }
    if (empty($descripcion)) {
        $errores['descripcion'] = "Ingrese una descripción.";
    }

    if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
        $nombreImagen = basename($_FILES["imagen"]["name"]);
        $directorioDestino = "../uploads/";
        $rutaArchivo = $directorioDestino . $nombreImagen;

        if (!move_uploaded_file($_FILES["imagen"]["tmp_name"], $rutaArchivo)) {
            $errores['imagen'] = "Error en la subida de la imagen.";
        }
    } else {
        $errores['imagen'] = "Ingrese una imagen.";
    }

    if (empty($errores)) {
        try {
            $consulta = "INSERT INTO posts (titulo, descripcion, imagen, user_id) VALUES (:titulo, :descripcion, :imagen, :user_id)";
            $stmt = $conexion->prepare($consulta);
            $stmt->bindParam(':titulo', $titulo);
            $stmt->bindParam(':descripcion', $descripcion);
            $stmt->bindParam(':imagen', $nombreImagen);
            $stmt->bindParam(':user_id', $user_id);

            if ($stmt->execute()) {
                header("Location: posts_creados.php");
                exit;
            } else {
                echo "Error en la inserción de datos.";
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administración</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body{
            margin: 0; /* Elimina márgenes por defecto */
            background-image: url("Imagenes/fondo-murciela.gif");
            background-size: cover;
            background-position: center;

        }
        .caja{
            display: grid; /* Activa el modo de grid */
            place-items: center; /* Centra el contenido horizontal y verticalmente */
            min-height: 100vh; /* Asegura que el body tenga al menos la altura completa de la pantalla */ 
        }
        form {
            place-items: center;
            width: 100%;
            border: 2px solid #ff0000; /* Cambia el color del borde */
            background-color: rgba(255, 255, 255, 0.5); /* Color de fondo con opacidad */
            padding: 20px; /* Espaciado interno */
            border-radius: 1px 40px 1px 10px; /* Bordes redondeados */
        }
        header{
            display: flex; /* Activa el modo flexbox */
            justify-content: flex-end; /* Alinea horizontalmente el contenido a la derecha */
            align-items: center; /* Centra verticalmente el contenido dentro del header */
            height: 50px;
            background-color: #f0f0f0; /* Color de fondo opcional */
        }
        .error {
            color: red;
            font-size: 0.8em;
            margin-top: -10px;
            margin-bottom: 10px;
            display: block;
            text-align: left;
        }
        a{
            padding-right: 20px;
            text-decoration: none; /* Elimina el subrayado del enlace */
            color: black;
            font-size: 27px;
        }
        .button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <header>
        <a href="dashboard.php">Volver al Dashboard</a>
        <a href="post-creados.php">Posts Creados</a>
    </header>

    <div class="caja">
        <form method="post" enctype="multipart/form-data">
            <h2>Área de Administración</h2>
            <p>Formulario para la creación de un post<br>asociado al ID:<?php echo htmlspecialchars($_SESSION['user_id']); ?>, con conexión activa.</p>
    
            <label for="titulo">Titulo:</label>
            <input type="text" name="titulo" id="titulo">

            <?php if (!empty($errores['titulo'])): ?>
            <p class="error"><?php echo $errores['titulo']; ?></p>
            <?php endif; ?>
    
            <label for="descripcion">Descripción:</label>
            <input type="text" name="descripcion" id="descripcion">

            <?php if (!empty($errores['descripcion'])): ?>
            <p class="error"><?php echo $errores['descripcion']; ?></p>
            <?php endif; ?>

            <label for="imagen">Imagen:</label>
            <input type="file" name="imagen" id="imagen">
            <br>

            <?php if (!empty($errores['imagen'])): ?>
            <p class="error"><?php echo $errores['imagen']; ?></p>
            <?php endif; ?>

            <button type="submit" class="button">Crear</button>
        </form>
    </div>
</body>
</html>