// Esperamos a que todo el HTML esté listo
document.addEventListener('DOMContentLoaded', function () {
    const sidebar = document.getElementById('sidebar'); // agarramos el sidebar
    const btnCollapse = document.getElementById('sidebarCollapse'); // agarramos el botón de hamburguesa

    // Cuando hagan clic en el botón de menú (sobre todo en móviles)
    btnCollapse.addEventListener('click', () => {
        sidebar.classList.toggle('active'); // lo abrimos o cerramos
    });
});

// Función para cargar contenido dinámicamente en el dashboard
function cargarContenido(archivo) {
    const contenedor = document.getElementById('contenedor-principal'); // nuestro div principal
    
    // Mostramos un "cargando..." mientras llega la info
    contenedor.innerHTML = '<div class="loader">Cargando módulo...</div>';

    // Hacemos la petición al archivo que queremos cargar
    fetch('views/' + archivo)
        .then(response => {
            if (!response.ok) throw new Error('No se encontró el archivo'); // si da error, lo atrapamos
            return response.text(); // si todo bien, convertimos la respuesta a texto (HTML)
        })
        .then(html => {
            contenedor.innerHTML = html; // insertamos el contenido en el contenedor
            
            // Si estamos en móvil, cerramos el menú para que se vea el contenido
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.remove('active');
            }
        })
        .catch(error => {
            // Si algo falla, mostramos un mensaje de error bonito en rojo
            contenedor.innerHTML = `
                <div class="card" style="border-left: 5px solid red;">
                    <h3>Error</h3>
                    <p>No se pudo cargar la vista: ${archivo}</p>
                </div>`;
        });
}
