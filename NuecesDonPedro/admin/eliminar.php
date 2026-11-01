<?php
require __DIR__ . '/proteger.php';
require __DIR__ . '/../config/conexion.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: productos.php');
    exit;
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id) {
    try {
        $sentencia = $conexion->prepare('DELETE FROM productos WHERE id = :id');
        $sentencia->execute([':id' => $id]);
    } catch (PDOException $error) {
        // Si el producto tiene pedidos, se conserva y se desactiva.
        $sentencia = $conexion->prepare('UPDATE productos SET activo = 0 WHERE id = :id');
        $sentencia->execute([':id' => $id]);
    }
}

header('Location: productos.php');
exit;
