<?php
$servidor = 'localhost';
$base = 'nueces_don_pedro';
$usuario = 'root';
$clave = '';

try {
    $conexion = new PDO(
        "mysql:host=$servidor;dbname=$base;charset=utf8mb4",
        $usuario,
        $clave,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
} catch (PDOException $error) {
    exit('No fue posible conectar con la base de datos. Verifique config/conexion.php.');
}
