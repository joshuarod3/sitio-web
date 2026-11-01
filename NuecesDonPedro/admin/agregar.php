<?php
require __DIR__ . '/proteger.php';
require __DIR__ . '/../config/conexion.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $existencia  = filter_input(INPUT_POST, 'existencia', FILTER_VALIDATE_INT);
    $imagen      = basename(trim($_POST['imagen'] ?? ''));

    if ($nombre === '' || $descripcion === '' || $precio === false ||
        $precio <= 0 || $existencia === false || $existencia < 0) {
        $error = 'Revise los datos capturados.';
    } else {
        $sql = 'INSERT INTO productos (nombre, descripcion, precio, existencia, imagen)
                VALUES (:nombre, :descripcion, :precio, :existencia, :imagen)';
        $sentencia = $conexion->prepare($sql);
        $sentencia->execute(compact('nombre', 'descripcion', 'precio', 'existencia', 'imagen'));
        header('Location: productos.php');
        exit;
    }
}

$titulo = 'Agregar producto';
$ruta = '../';
include __DIR__ . '/../includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Agregar producto</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form class="formulario" method="post">
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" maxlength="100" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" maxlength="255" required></textarea>

        <label for="precio">Precio por kg</label>
        <input id="precio" name="precio" type="number" min="0.01" step="0.01" required>

        <label for="existencia">Existencia (kg)</label>
        <input id="existencia" name="existencia" type="number" min="0" required>

        <label for="imagen">Archivo de imagen (carpeta imagenes/)</label>
        <input id="imagen" name="imagen" maxlength="150" placeholder="ejemplo.jpg">

        <button class="boton" type="submit">Guardar</button>
    </form>
</main>
<?php include __DIR__ . '/../includes/pie.php'; ?>
