// public/js/tipdoc.js

document.addEventListener('DOMContentLoaded', function() {
    cargarTipdocs();

    // Evento para guardar
    document.getElementById('btnGuardar').addEventListener('click', guardarTipdoc);
});

function cargarTipdocs() {
    fetch('index.php?module=tipdoc&action=listar')
        .then(response => response.json())
        .then(data => {
            const tbody = document.getElementById('tablaTipdocBody');
            tbody.innerHTML = '';
            
            data.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td>${item.IDTIPO}</td>
                    <td>${item.DESCRIPCION}</td>
                    <td>
                        <button class="btn btn-sm btn-warning" onclick="editarTipdoc('${item.IDTIPO}')">Editar</button>
                        <button class="btn btn-sm btn-danger" onclick="eliminarTipdoc('${item.IDTIPO}')">Eliminar</button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        })
        .catch(error => console.error('Error cargando datos:', error));
}

function guardarTipdoc() {
    const form = document.getElementById('formTipdoc');
    
    // Recopilar datos manual o via FormData (si convertimos a objeto)
    const formData = new FormData(form);
    const data = Object.fromEntries(formData.entries());
    
    // Checkbox fix if needed (bootstrap switch etc)
    
    fetch('index.php?module=tipdoc&action=guardar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            // Cerrar modal
            const modalEl = document.getElementById('modalTipdoc');
            const modal = bootstrap.Modal.getInstance(modalEl);
            modal.hide();
            
            // Refrescar tabla
            cargarTipdocs();
            
            // Limpiar form
            form.reset();
            document.getElementById('is_update').value = '';
            document.getElementById('IDTIPO').readOnly = false;
        } else {
            alert('Error: ' + result.message);
        }
    })
    .catch(error => console.error('Error guardando:', error));
}

function editarTipdoc(id) {
    fetch(`index.php?module=tipdoc&action=obtener&id=${id}`)
        .then(response => response.json())
        .then(data => {
            if (data) {
                // Llenar formulario
                document.getElementById('IDTIPO').value = data.IDTIPO;
                document.getElementById('DESCRIPCION').value = data.DESCRIPCION;
                
                // Setear flag update
                document.getElementById('is_update').value = '1';
                document.getElementById('IDTIPO').readOnly = true;
                
                // Mostrar modal
                const modal = new bootstrap.Modal(document.getElementById('modalTipdoc'));
                modal.show();
            }
        });
}

function eliminarTipdoc(id) {
    if (confirm('¿Está seguro de eliminar este registro?')) {
        fetch('index.php?module=tipdoc&action=eliminar', {
            method: 'POST', // O DELETE si soportado y configurado
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ id: id })
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                cargarTipdocs();
            } else {
                alert('Error al eliminar: ' + result.message || result.error);
            }
        });
    }
}
