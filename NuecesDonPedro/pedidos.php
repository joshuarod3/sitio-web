<?php
require 'config/conexion.php';

$titulo = 'Realizar pedido | Nueces Don Pedro';
$ruta = '';

$productos = $conexion->query(
    'SELECT id, nombre FROM productos WHERE activo = 1 ORDER BY nombre'
)->fetchAll();

$seleccionado = filter_input(INPUT_GET, 'producto', FILTER_VALIDATE_INT);

include 'includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Solicitar un producto</h2>

    <?php if (!$productos): ?>
        <p class="aviso">Por el momento no hay productos disponibles para pedido.</p>
    <?php else: ?>
    <form class="formulario" action="guardar_pedido.php" method="post">
        <label for="cliente">Nombre completo</label>
        <input id="cliente" name="cliente" type="text" maxlength="120" required>

        <label for="telefono">Teléfono</label>
        <input id="telefono" name="telefono" type="tel" maxlength="20" required>

        <label for="correo">Correo electrónico</label>
        <input id="correo" name="correo" type="email" maxlength="120">

        <label for="producto_id">Producto</label>
        <select id="producto_id" name="producto_id" required>
            <option value="">Seleccione una opción</option>
            <?php foreach ($productos as $producto): ?>
                <option value="<?= (int)$producto['id'] ?>"
                    <?= $seleccionado === (int)$producto['id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($producto['nombre']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="cantidad">Cantidad en kilogramos</label>
        <input id="cantidad" name="cantidad" type="number" min="1" max="100" required>

        <label for="mensaje">Indicaciones</label>
        <textarea id="mensaje" name="mensaje" maxlength="500"></textarea>

        <button class="boton" type="submit">Enviar pedido</button>
    </form>
    <?php endif; ?>
</main>
<?php include 'includes/pie.php'; ?>
