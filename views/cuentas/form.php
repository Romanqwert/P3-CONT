<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title><?= isset($cuenta) ? 'Editar' : 'Nueva' ?> Cuenta</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-4">
        <h2><?= isset($cuenta) ? 'Editar' : 'Nueva' ?> Cuenta</h2>
        
        <form action="index.php?module=cuentas&action=guardar" method="POST">
            <?php if (isset($cuenta)): ?>
                <input type="hidden" name="is_update" value="1">
            <?php endif; ?>

            <div class="mb-3">
                <label for="Cuenta" class="form-label">Cuenta</label>
                <input type="text" class="form-control" id="Cuenta" name="Cuenta" 
                       value="<?= isset($cuenta) ? htmlspecialchars($cuenta['Cuenta']) : '' ?>" 
                       <?= isset($cuenta) ? 'readonly' : '' ?> required>
            </div>

            <div class="mb-3">
                <label for="Descripcion" class="form-label">Descripción</label>
                <input type="text" class="form-control" id="Descripcion" name="Descripcion" 
                       value="<?= isset($cuenta) ? htmlspecialchars($cuenta['Descripcion']) : '' ?>" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Tipo" class="form-label">Tipo</label>
                    <select class="form-control" id="Tipo" name="Tipo">
                        <option value="A" <?= (isset($cuenta) && $cuenta['Tipo'] == 'A') ? 'selected' : '' ?>>Activo</option>
                        <option value="P" <?= (isset($cuenta) && $cuenta['Tipo'] == 'P') ? 'selected' : '' ?>>Pasivo</option>
                        <option value="C" <?= (isset($cuenta) && $cuenta['Tipo'] == 'C') ? 'selected' : '' ?>>Capital</option>
                        <option value="I" <?= (isset($cuenta) && $cuenta['Tipo'] == 'I') ? 'selected' : '' ?>>Ingreso</option>
                        <option value="G" <?= (isset($cuenta) && $cuenta['Tipo'] == 'G') ? 'selected' : '' ?>>Gasto</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Nivel" class="form-label">Nivel</label>
                    <input type="number" class="form-control" id="Nivel" name="Nivel" 
                           value="<?= isset($cuenta) ? htmlspecialchars($cuenta['Nivel']) : '1' ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="Grupo" class="form-label">Grupo</label>
                    <input type="text" class="form-control" id="Grupo" name="Grupo" 
                           value="<?= isset($cuenta) ? htmlspecialchars($cuenta['Grupo']) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="Padre" class="form-label">Cuenta Padre</label>
                    <input type="text" class="form-control" id="Padre" name="Padre" 
                           value="<?= isset($cuenta) ? htmlspecialchars($cuenta['Padre']) : '' ?>">
                </div>
            </div>

            <div class="mb-3">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="Control" name="Control" value="S"
                           <?= (isset($cuenta) && $cuenta['Control'] == 'S') ? 'checked' : '' ?>>
                    <label class="form-check-label" for="Control">Es Cuenta Control</label>
                </div>
            </div>

            <div class="mb-3">
                <label for="ESTATUS" class="form-label">Estatus</label>
                <select class="form-control" id="ESTATUS" name="ESTATUS">
                    <option value="A" <?= (isset($cuenta) && $cuenta['ESTATUS'] == 'A') ? 'selected' : '' ?>>Activo</option>
                    <option value="I" <?= (isset($cuenta) && $cuenta['ESTATUS'] == 'I') ? 'selected' : '' ?>>Inactivo</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Guardar</button>
            <a href="index.php?module=cuentas&action=index" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
