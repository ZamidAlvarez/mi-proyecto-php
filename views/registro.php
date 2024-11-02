<?php
session_start();
require_once '../inc/conexion.php';
require_once '../inc/funciones.php';

$errores = [
    'nombre' => '',
    'email' => '',
    'password' => '',
    'rol' => '',
    'exito' => ''
];

$nombre = '';
$email = '';
$password = '';
$rol = 'viewer'; // Valor por defecto

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = limpiar_dato($_POST['nombre']);
    $email = limpiar_dato($_POST['email']);
    $password = $_POST['password'];
    $rol = limpiar_dato($_POST['rol']); // Capturar el rol seleccionado

    // Validaciones
    if (empty($nombre)) {
        $errores['nombre'] = 'El nombre es obligatorio.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El email no es válido.';
    }
    if (strlen($password) < 6) {
        $errores['password'] = 'La contraseña debe tener al menos 6 caracteres.';
    }
    if (!in_array($rol, ['admin', 'viewer'])) { // Validar rol
        $errores['rol'] = 'El rol seleccionado no es válido.';
    }

    // Verificar si el email ya existe en la base de datos
    $sqlVerificacion = "SELECT COUNT(*) FROM usuarios WHERE email = :email";
    $stmtVerificacion = $conexion->prepare($sqlVerificacion);
    $stmtVerificacion->bindParam(':email', $email);
    $stmtVerificacion->execute();
    $emailExiste = $stmtVerificacion->fetchColumn();

    if ($emailExiste) {
        $errores['email'] = 'El correo electrónico ya está registrado.';
    }

    // Si no hay errores, proceder con el registro
    if (empty(array_filter($errores))) {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nombre, email, password, rol) VALUES (:nombre, :email, :password, :rol)";
        $stmt = $conexion->prepare($sql);

        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':password', $passwordHash);
        $stmt->bindParam(':rol', $rol); // Insertar el rol

        if ($stmt->execute()) {
            $errores['exito'] = 'Usuario registrado exitosamente.';
        } else {
            echo "Error al registrar el usuario.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Usuario</title>
    <link rel="stylesheet" href="../css/estilos.css">
    <style>
        body {
            margin: 0;
            background-image: url("Imagenes/cara-halloween.gif");
            background-size: cover;
            background-position: center;
        }
        .caja {
            display: grid;
            place-items: center;
            min-height: 100vh;
        }
        header {
            background: white;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            height: 50px;
        }
        a {
            padding-right: 20px;
            text-decoration: none;
            color: black;
            font-size: 27px;
        }
        form {
            width: 100%;
            border: 2px solid #ff0000;
            background-color: rgba(255, 255, 255, 0.5);
            padding: 20px;
            border-radius: 1px 40px 1px 10px;
        }
        h2 {
            text-align: center;
        }
        .exito {
            text-align: center;
            color: green;
            font-weight: bold;
        }
        input, select {
            width: -webkit-fill-available;
        }
        .error {
            color: red;
            font-size: 0.9em;
        }

        /* Estilos específicos para el campo de selección de rol */
        .select-container {
            margin-bottom: 8px;
            display: flex;
            justify-content: space-between;
            gap: 5px; /* Espacio entre las cajas */
            position: relative;
            place-items: center;
            text-align: center;
            justify-content: center;
            width: 100%;
            max-width: 300px; /* Ajusta el ancho según sea necesario */
        }

        .select-container select {  
            border: 1px solid;
            font-size: 14px;
            background-color: #fff;
        }
        .selector-imagen{
            border: 1px solid;
            font-size: 14px;
            background-color: #fff;
        }
        .caja-rol{
            padding: 13.5px;
            border: 1px, solid #ccc;
            font-size: 16px;
            width: 100%;
        }
        .caja-imagen{
            padding: 13.5px;
            border: 1px, solid #ccc;
            font-size: 16px;
            width: 100%;
        }
        label[for="rol"] {
            width: 100%;
            margin-right: 5px;
            padding: 15px;
            border: 1px, solid #ccc;
            font-size: 14px;
            margin-bottom: 0;
            display: inline-block;
        }
        label[for="imagen"] {
            width: 100%;
            margin-right: 5px;
            padding: 15px;
            border: 1px, solid #ccc;
            font-size: 14px;
            margin-bottom: 0;
            display: inline-block;
        }
    </style>
</head>
<body>
    <header>
        <a href="../index.php">Index</a>
        <a href="login.php">Login</a>
    </header>

    <div class="caja">
        <form method="post">
            <h2>Registro de Usuario</h2>
            <?php if (!empty($errores['exito'])): ?>
                <p class="exito"><?php echo $errores['exito']; ?></p>
            <?php endif; ?>

            <label for="nombre">Nombre:</label>
            <input type="text" name="nombre" id="nombre" value="<?php echo htmlspecialchars($nombre); ?>">
            <?php if (!empty($errores['nombre'])): ?>
                <p class="error"><?php echo $errores['nombre']; ?></p>
            <?php endif; ?>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($email); ?>">
            <?php if (!empty($errores['email'])): ?>
                <p class="error"><?php echo $errores['email']; ?></p>
            <?php endif; ?>

            <label for="password">Contraseña:</label>
            <input type="password" name="password" id="password">
            <?php if (!empty($errores['password'])): ?>
                <p class="error"><?php echo $errores['password']; ?></p>
            <?php endif; ?>

            <div class="select-container">
                <label for="rol">Rol:</label>
                <div class="caja-rol">
                    <select name="rol" id="rol">
                        <option value="viewer" <?php echo $rol === 'viewer' ? 'selected' : ''; ?>>Invitado</option>
                        <option value="admin" <?php echo $rol === 'admin' ? 'selected' : ''; ?>>Administrador</option>
                    </select>
                </div>
            </div>
            <div class="select-container">
                <label for="imagen">Imagen de Perfil:</label>
                <div class="caja-imagen">
                    <div class="selector-imagen" onclick="document.getElementById('fileInput').click();">
                        Elegir Archivo
                    </div>
                </div>
                <input type="file" id="fileInput" style="display: none;" accept="image/*" onchange="mostrarImagen(event)">
            </div>
            <?php if (!empty($errores['rol'])): ?>
                <p class="error"><?php echo $errores['rol']; ?></p>
            <?php endif; ?>
            <?php if (!empty($errores['imagen'])): ?>
                <p class="error"><?php echo $errores['imagen']; ?></p>
            <?php endif; ?>
            <button type="submit">Registrar</button>
        </form>
    </div>
</body>
</html>
