<?php
require 'config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: pedidos.php');
    exit;
}

$cliente    = trim($_POST['cliente'] ?? '');
$telefono   = trim($_POST['telefono'] ?? '');
$correo     = filter_input(INPUT_POST, 'correo', FILTER_VALIDATE_EMAIL);
$productoId = filter_input(INPUT_POST, 'producto_id', FILTER_VALIDATE_INT);
$cantidad   = filter_input(INPUT_POST, 'cantidad', FILTER_VALIDATE_INT);
$mensaje    = trim($_POST['mensaje'] ?? '');

$errores = [];
if ($cliente === '')  $errores[] = 'Escriba el nombre del cliente.';
if ($telefono === '') $errores[] = 'Escriba el teléfono.';
if (!$productoId)     $errores[] = 'Seleccione un producto válido.';
if (!$cantidad || $cantidad < 1 || $cantidad > 100) {
    $errores[] = 'La cantidad debe estar entre 1 y 100 kg.';
}
if (($_POST['correo'] ?? '') !== '' && $correo === false) {
    $errores[] = 'El correo no tiene un formato válido.';
}

// Verificar que el producto exista y esté activo
if ($productoId) {
    $verifica = $conexion->prepare('SELECT COUNT(*) FROM productos WHERE id = :id AND activo = 1');
    $verifica->execute([':id' => $productoId]);
    if (!$verifica->fetchColumn()) {
        $errores[] = 'El producto seleccionado no está disponible.';
    }
}

if ($errores) {
    $titulo = 'Error en el pedido';
    $ruta = '';
    include 'includes/encabezado.php';
    echo '<main class="contenedor"><section class="formulario">';
    foreach ($errores as $error) {
        echo '<p class="error">' . htmlspecialchars($error) . '</p>';
    }
    echo '<a class="boton" href="pedidos.php">Regresar</a>';
    echo '</section></main>';
    include 'includes/pie.php';
    exit;
}

$sql = 'INSERT INTO pedidos
        (cliente, telefono, correo, producto_id, cantidad, mensaje)
        VALUES (:cliente, :telefono, :correo, :producto, :cantidad, :mensaje)';
$sentencia = $conexion->prepare($sql);
$sentencia->execute([
    ':cliente'  => $cliente,
    ':telefono' => $telefono,
    ':correo'   => $correo ?: null,
    ':producto' => $productoId,
    ':cantidad' => $cantidad,
    ':mensaje'  => $mensaje
]);

header('Location: gracias.php');
exit;
