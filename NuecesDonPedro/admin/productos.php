<?php
require __DIR__ . '/proteger.php';
require __DIR__ . '/../config/conexion.php';

$productos = $conexion->query('SELECT * FROM productos ORDER BY id DESC')->fetchAll();

$titulo = 'Administrar productos';
$ruta = '../';
include __DIR__ . '/../includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Administrar productos</h2>
    <p>Sesión: <?= htmlspecialchars($_SESSION['usuario']) ?> |
       <a href="pedidos.php">Ver pedidos</a> |
       <a href="../logout.php">Cerrar sesión</a></p>
    <p><a class="boton" href="agregar.php">Agregar producto</a></p>

    <?php if (!$productos): ?>
        <p class="aviso">Todavía no hay productos registrados.</p>
    <?php else: ?>
    <div class="tabla-responsive">
    <table class="tabla-admin">
        <thead>
            <tr><th>ID</th><th>Nombre</th><th>Precio</th><th>Existencia</th><th>Activo</th><th>Acciones</th></tr>
        </thead>
        <tbody>
        <?php foreach ($productos as $producto): ?>
            <tr>
                <td><?= (int)$producto['id'] ?></td>
                <td><?= htmlspecialchars($producto['nombre']) ?></td>
                <td>$<?= number_format($producto['precio'], 2) ?></td>
                <td><?= (int)$producto['existencia'] ?> kg</td>
                <td><?= $producto['activo'] ? 'Sí' : 'No' ?></td>
                <td>
                    <a href="editar.php?id=<?= (int)$producto['id'] ?>">Editar</a>
                    <form action="eliminar.php" method="post" class="form-inline">
                        <input type="hidden" name="id" value="<?= (int)$producto['id'] ?>">
                        <button type="submit" onclick="return confirm('¿Eliminar producto?')">Eliminar</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
    <?php endif; ?>
</main>
<?php include __DIR__ . '/../includes/pie.php'; ?>
