<?php
// Arrancamos la sesión para poder usar las variables de sesión
session_start();

// Si no hay usuario logueado, lo mandamos directo al login
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php"); // redirige a la página de login
    exit(); // no queremos que siga ejecutando nada más
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Finanza</title>
    <!-- Link a nuestro CSS para darle estilo al dashboard -->
    <link rel="stylesheet" href="../public/css/dashboard.css">
</head>
<body>
    <div class="wrapper">
        <!-- Sidebar / Menú lateral -->
        <nav id="sidebar">
            <div class="sidebar-header">
                <h3>SISTEMA MVC</h3>
                <!-- Mostramos el nombre del usuario que está en sesión -->
                <small>En línea: <?php echo $_SESSION['usuario_nombre']; ?></small>
            </div>

            <ul class="list-unstyled components">
                <!-- Etiqueta del primer menú -->
                <li class="menu-label">MENU UNO</li>
                <li><a href="#" onclick="cargarContenido('opcion1.php')">Opcion 1</a></li>
                <li><a href="#" onclick="cargarContenido('opcion2.php')">Opcion 2</a></li>
                <li><a href="#" onclick="cargarContenido('opcion3.php')">Opcion 3</a></li>

                <!-- Etiqueta del segundo menú -->
                <li class="menu-label">MENU DOS</li>
                <li><a href="#" onclick="cargarContenido('opcion_uno_dos.php')">Opcion Uno</a></li>
                <li><a href="#" onclick="cargarContenido('opcion_dos_dos.php')">Opcion Dos</a></li>
                <li><a href="#" onclick="cargarContenido('opcion_tres_dos.php')">Opcion Tres</a></li>
            </ul>

            <!-- Pie del sidebar con el link de logout -->
            <div class="sidebar-footer">
                <a href="logout.php" class="logout-link">Cerrar Sesión</a>
            </div>
        </nav>

        <!-- Contenido principal -->
        <div id="content">
            <!-- Barra superior -->
            <header class="top-bar">
                <!-- Botón para colapsar el sidebar en pantallas pequeñas -->
                <button type="button" id="sidebarCollapse" class="btn-menu">☰</button>
                <h2>Panel de Control</h2>
            </header>
            
            <!-- Aquí se cargará todo el contenido dinámico según la opción del menú -->
            <main class="main-body" id="contenedor-principal">
                <div class="card">
                    <!-- Mensaje de bienvenida -->
                    <h3>Bienvenido, <?php echo $_SESSION['usuario_nombre']; ?></h3>
                    <p>Seleccione una opción del menú lateral para cargar las OPCIONES.</p>
                </div>
            </main>
        </div>
    </div>

    <!-- JS que controla el sidebar y carga de contenido -->
    <script src="../public/js/dashboard.js"></script>
</body>
</html>
