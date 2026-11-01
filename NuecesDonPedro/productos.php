<?php
require 'config/conexion.php';

$titulo = 'Productos | Nueces Don Pedro';
$ruta = '';

$consulta = $conexion->prepare(
    'SELECT id, nombre, descripcion, precio, existencia, imagen
     FROM productos WHERE activo = 1 ORDER BY nombre'
);
$consulta->execute();
$productos = $consulta->fetchAll();

include 'includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Productos disponibles</h2>

    <?php if (!$productos): ?>
        <p class="aviso">Por el momento no hay productos disponibles.</p>
    <?php else: ?>
    <section class="contenedor-tarjetas">
        <?php foreach ($productos as $producto): ?>
            <article class="tarjeta">
                <img src="images/<?= htmlspecialchars($producto['imagen'] ?? '') ?>"
                     alt="<?= htmlspecialchars($producto['nombre']) ?>"
                     onerror="this.onerror=null;this.src='images/sin-imagen.svg';">
                <h3><?= htmlspecialchars($producto['nombre']) ?></h3>
                <p><?= htmlspecialchars($producto['descripcion']) ?></p>
                <p><strong>Precio:</strong>
                   $<?= number_format($producto['precio'], 2) ?> por kg</p>
                <p>Existencia: <?= (int)$producto['existencia'] ?> kg</p>
                <a class="boton" href="pedidos.php?producto=<?= (int)$producto['id'] ?>">Solicitar</a>
            </article>
        <?php endforeach; ?>
    </section>
    <?php endif; ?>
</main>
<?php include 'includes/pie.php'; ?>
