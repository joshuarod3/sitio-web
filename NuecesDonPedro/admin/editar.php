<?php
require __DIR__ . '/proteger.php';
require __DIR__ . '/../config/conexion.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if (!$id) exit('Identificador no válido.');

$sentencia = $conexion->prepare('SELECT * FROM productos WHERE id = :id');
$sentencia->execute([':id' => $id]);
$producto = $sentencia->fetch();
if (!$producto) exit('Producto no encontrado.');

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre      = trim($_POST['nombre'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $precio      = filter_input(INPUT_POST, 'precio', FILTER_VALIDATE_FLOAT);
    $existencia  = filter_input(INPUT_POST, 'existencia', FILTER_VALIDATE_INT);
    $imagen      = basename(trim($_POST['imagen'] ?? ''));
    $activo      = isset($_POST['activo']) ? 1 : 0;

    if ($nombre !== '' && $descripcion !== '' && $precio !== false && $precio > 0
        && $existencia !== false && $existencia >= 0) {
        $sql = 'UPDATE productos SET nombre = :nombre, descripcion = :descripcion,
                precio = :precio, existencia = :existencia, imagen = :imagen, activo = :activo
                WHERE id = :id';
        $conexion->prepare($sql)->execute([
            ':nombre' => $nombre, ':descripcion' => $descripcion, ':precio' => $precio,
            ':existencia' => $existencia, ':imagen' => $imagen, ':activo' => $activo, ':id' => $id
        ]);
        header('Location: productos.php');
        exit;
    }
    $error = 'Revise los datos capturados.';
    $producto = array_merge($producto, [
        'nombre' => $nombre, 'descripcion' => $descripcion,
        'precio' => $_POST['precio'] ?? '', 'existencia' => $_POST['existencia'] ?? '',
        'imagen' => $imagen, 'activo' => $activo
    ]);
}

$titulo = 'Editar producto';
$ruta = '../';
include __DIR__ . '/../includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Editar producto</h2>
    <?php if ($error): ?><p class="error"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form class="formulario" method="post">
        <label for="nombre">Nombre</label>
        <input id="nombre" name="nombre" maxlength="100"
               value="<?= htmlspecialchars($producto['nombre']) ?>" required>

        <label for="descripcion">Descripción</label>
        <textarea id="descripcion" name="descripcion" maxlength="255" required><?= htmlspecialchars($producto['descripcion']) ?></textarea>

        <label for="precio">Precio por kg</label>
        <input id="precio" name="precio" type="number" min="0.01" step="0.01"
               value="<?= htmlspecialchars((string)$producto['precio']) ?>" required>

        <label for="existencia">Existencia (kg)</label>
        <input id="existencia" name="existencia" type="number" min="0"
               value="<?= htmlspecialchars((string)$producto['existencia']) ?>" required>

        <label for="imagen">Archivo de imagen</label>
        <input id="imagen" name="imagen" maxlength="150"
               value="<?= htmlspecialchars($producto['imagen'] ?? '') ?>">

        <label class="casilla">
            <input type="checkbox" name="activo" <?= $producto['activo'] ? 'checked' : '' ?>>
            Producto activo (visible en el catálogo)
        </label>

        <button class="boton" type="submit">Actualizar</button>
    </form>
</main>
<?php include __DIR__ . '/../includes/pie.php'; ?>
