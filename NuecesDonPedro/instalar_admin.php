<?php
// ARCHIVO TEMPORAL: crea el usuario administrador con contraseña cifrada.
// Ábralo UNA vez en el navegador y después ELIMÍNELO.
require 'config/conexion.php';

$usuario = 'admin';
$clave   = 'Nuez2026*';   // Cámbiela por una contraseña propia

$hash = password_hash($clave, PASSWORD_DEFAULT);

$existe = $conexion->prepare('SELECT id FROM usuarios WHERE usuario = :u');
$existe->execute([':u' => $usuario]);

if ($existe->fetch()) {
    $conexion->prepare('UPDATE usuarios SET contrasena = :c WHERE usuario = :u')
             ->execute([':c' => $hash, ':u' => $usuario]);
    echo 'Contraseña del usuario "' . htmlspecialchars($usuario) . '" actualizada.';
} else {
    $conexion->prepare('INSERT INTO usuarios (usuario, contrasena) VALUES (:u, :c)')
             ->execute([':u' => $usuario, ':c' => $hash]);
    echo 'Usuario "' . htmlspecialchars($usuario) . '" creado.';
}
echo '<br><strong>Ahora elimine este archivo (instalar_admin.php).</strong>';
