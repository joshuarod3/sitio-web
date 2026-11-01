<?php
require __DIR__ . '/proteger.php';
require __DIR__ . '/../config/conexion.php';

$estados = ['Pendiente', 'Confirmado', 'Entregado', 'Cancelado'];

// Actualizar el estado de un pedido (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id     = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
    $estado = $_POST['estado'] ?? '';
    if ($id && in_array($estado, $estados, true)) {
        $conexion->prepare('UPDATE pedidos SET estado = :estado WHERE id = :id')
                 ->execute([':estado' => $estado, ':id' => $id]);
    }
    header('Location: pedidos.php');
    exit;
}

$sql = 'SELECT p.id, p.cliente, p.telefono, p.correo, p.cantidad, p.mensaje, p.fecha, p.estado,
               pr.nombre AS producto
        FROM pedidos p
        INNER JOIN productos pr ON p.producto_id = pr.id
        ORDER BY p.fecha DESC';
$pedidos = $conexion->query($sql)->fetchAll();

$titulo = 'Pedidos recibidos';
$ruta = '../';
include __DIR__ . '/../includes/encabezado.php';
?>
<main class="contenedor">
    <h2>Pedidos recibidos</h2>
    <p><a href="productos.php">Administrar productos</a> |
       <a href="../logout.php">Cerrar sesión</a></p>

    <?php if (!$pedidos): ?>
        <p class="aviso">Aún no se han recibido pedidos.</p>
    <?php else: ?>
    <div class="tabla-responsive">
    <table class="tabla-admin">
        <thead>
            <tr><th>Cliente</th><th>Producto</th><th>Cantidad</th><th>Teléfono</th>
                <th>Indicaciones</th><th>Fecha</th><th>Estado</th></tr>
        </thead>
        <tbody>
        <?php foreach ($pedidos as $pedido): ?>
            <tr>
                <td><?= htmlspecialchars($pedido['cliente']) ?></td>
                <td><?= htmlspecialchars($pedido['producto']) ?></td>
                <td><?= (int)$pedido['cantidad'] ?> kg</td>
                <td><?= htmlspecialchars($pedido['telefono']) ?></td>
                <td><?= htmlspecialchars($pedido['mensaje'] ?? '') ?></td>
                <td><?= htmlspecialchars($pedido['fecha']) ?></td>
                <td>
                    <form method="post" class="form-estado">
                        <input type="hidden" name="id" value="<?= (int)$pedido['id'] ?>">
                        <select name="estado">
                            <?php foreach ($estados as $e): ?>
                                <option value="<?= $e ?>" <?= $pedido['estado'] === $e ? 'selected' : '' ?>><?= $e ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" class="boton boton-mini">Guardar</button>
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
