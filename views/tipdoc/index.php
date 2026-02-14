<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mantenimiento Tipo Documento</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container mt-4">
        <h2>Tipos de Documento</h2>
        <a href="index.php?module=dashboard" class="btn btn-secondary mb-3">Volver</a>
        <button class="btn btn-primary mb-3" data-bs-toggle="modal" data-bs-target="#modalTipdoc"
            onclick="document.getElementById('formTipdoc').reset(); document.getElementById('is_update').value=''; document.getElementById('IDTIPO').readOnly=false;">
            Nuevo
        </button>

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Código</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody id="tablaTipdocBody">
                <!-- AJAX Content -->
            </tbody>
        </table>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalTipdoc" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tipo de Documento</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="formTipdoc">
                        <input type="hidden" id="is_update" name="is_update">

                        <div class="mb-3">
                            <label for="IDTIPO" class="form-label">Código</label>
                            <input type="text" class="form-control" id="IDTIPO" name="IDTIPO" maxlength="2" required>
                        </div>

                        <div class="mb-3">
                            <label for="DESCRIPCION" class="form-label">Descripción</label>
                            <input type="text" class="form-control" id="DESCRIPCION" name="DESCRIPCION" required>
                        </div>

                        <!-- TODO: Add other 11 columns here -->
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                    <button type="button" class="btn btn-primary" id="btnGuardar">Guardar</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="public/js/tipdoc.js"></script>
</body>

</html>