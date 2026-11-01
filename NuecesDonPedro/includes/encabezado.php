<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($titulo ?? 'Nueces Don Pedro') ?></title>
    <link rel="stylesheet" href="<?= $ruta ?? '' ?>css/estilos.css">
</head>
<body>
<header class="encabezado">
    <h1>Nueces Don Pedro</h1>
    <p>Nuez de tradición familiar</p>
</header>
<nav class="menu">
    <ul>
        <li><a href="<?= $ruta ?? '' ?>index.php">Inicio</a></li>
        <li><a href="<?= $ruta ?? '' ?>productos.php">Productos</a></li>
        <li><a href="<?= $ruta ?? '' ?>pedidos.php">Pedidos</a></li>
        <?php if (session_status() === PHP_SESSION_ACTIVE && !empty($_SESSION['usuario_id'])): ?>
            <li><a href="<?= $ruta ?? '' ?>admin/productos.php">Admin: Productos</a></li>
            <li><a href="<?= $ruta ?? '' ?>admin/pedidos.php">Admin: Pedidos</a></li>
            <li><a href="<?= $ruta ?? '' ?>logout.php">Cerrar sesión</a></li>
        <?php else: ?>
            <li><a href="<?= $ruta ?? '' ?>login.php">Administración</a></li>
        <?php endif; ?>
    </ul>
</nav>
