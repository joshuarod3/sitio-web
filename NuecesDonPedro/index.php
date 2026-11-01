<?php
require 'config/conexion.php';

$titulo = 'Inicio | Nueces Don Pedro';
$ruta = '';

// Tres productos destacados desde la base de datos
$destacados = $conexion->query(
    'SELECT id, nombre, descripcion, precio, imagen
     FROM productos WHERE activo = 1 ORDER BY id LIMIT 3'
)->fetchAll();

include 'includes/encabezado.php';
?>
<main class="contenedor">
    <section class="portada">
        <h2>Bienvenido a Nueces Don Pedro</h2>
        <p>Somos productores de nuez en Atlautla, Estado de México. Cultivamos,
           seleccionamos y entregamos nuez fresca con la calidad de una tradición familiar.</p>
        <a class="boton" href="productos.php">Ver productos</a>
        <a class="boton boton-secundario" href="pedidos.php">Hacer un pedido</a>
    </section>

    <h2>Productos destacados</h2>
    <?php if (!$destacados): ?>
        <p class="aviso">Por el momento no hay productos disponibles.</p>
    <?php else: ?>
        <section class="contenedor-tarjetas">
            <?php foreach ($destacados as $p): ?>
                <article class="tarjeta">
                    <img src="images/<?= htmlspecialchars($p['imagen'] ?? '') ?>"
                         alt="<?= htmlspecialchars($p['nombre']) ?>"
                         onerror="this.onerror=null;this.src='images/sin-imagen.svg';">
                    <h3><?= htmlspecialchars($p['nombre']) ?></h3>
                    <p><?= htmlspecialchars($p['descripcion']) ?></p>
                    <p><strong>$<?= number_format($p['precio'], 2) ?></strong> por kg</p>
                    <a class="boton" href="pedidos.php?producto=<?= (int)$p['id'] ?>">Solicitar</a>
                </article>
            <?php endforeach; ?>
        </section>
    <?php endif; ?>
</main>
<?php include 'includes/pie.php'; ?>
