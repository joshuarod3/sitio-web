<?php
session_start();
require 'config/conexion.php';

// Si ya hay sesión, enviar al panel
if (!empty($_SESSION['usuario_id'])) {
    header('Location: admin/productos.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usuario = trim($_POST['usuario'] ?? '');
    $clave   = $_POST['clave'] ?? '';

    $sql = 'SELECT id, usuario, contrasena FROM usuarios WHERE usuario = :usuario';
    $sentencia = $conexion->prepare($sql);
    $sentencia->execute([':usuario' => $usuario]);
    $registro = $sentencia->fetch();

    if ($registro && password_verify($clave, $registro['contrasena'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $registro['id'];
        $_SESSION['usuario']    = $registro['usuario'];
        header('Location: admin/productos.php');
        exit;
    }
    $error = 'Usuario o contraseña incorrectos.';
}

$titulo = 'Administración | Nueces Don Pedro';
$ruta = '';
include 'includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Acceso administrativo</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form class="formulario" method="post">
        <label for="usuario">Usuario</label>
        <input id="usuario" name="usuario" required>

        <label for="clave">Contraseña</label>
        <input id="clave" name="clave" type="password" required>

        <button class="boton" type="submit">Ingresar</button>
    </form>
</main>
<?php include 'includes/pie.php'; ?>
