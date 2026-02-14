<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Maestro de Cuentas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2>Maestro de Cuentas</h2>
        <a href="index.php?module=dashboard" class="btn btn-secondary mb-3">Volver</a>
        <a href="index.php?module=cuentas&action=form" class="btn btn-primary mb-3">Nueva Cuenta</a>
        
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Cuenta</th>
                    <th>Descripción</th>
                    <th>Tipo</th>
                    <th>Nivel</th>
                    <th>Grupo</th>
                    <th>Control</th>
                    <th>Estatus</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($cuentas)): ?>
                    <?php foreach ($cuentas as $cta): ?>
                        <tr>
                            <td><?= htmlspecialchars($cta['Cuenta']) ?></td>
                            <td><?= htmlspecialchars($cta['Descripcion']) ?></td>
                            <td><?= htmlspecialchars($cta['Tipo']) ?></td>
                            <td><?= htmlspecialchars($cta['Nivel']) ?></td>
                            <td><?= htmlspecialchars($cta['Grupo']) ?></td>
                            <td><?= htmlspecialchars($cta['Control']) ?></td>
                            <td><?= htmlspecialchars($cta['ESTATUS']) ?></td>
                            <td>
                                <a href="index.php?module=cuentas&action=form&id=<?= $cta['Cuenta'] ?>" class="btn btn-sm btn-warning">Editar</a>
                                <a href="index.php?module=cuentas&action=eliminar&id=<?= $cta['Cuenta'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('¿Seguro de eliminar?')">Eliminar</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="8" class="text-center">No hay cuentas registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
