<?php
// Router Principal
// Ejemplo URL: index.php?module=cuentas&action=index

// Configuración básica
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Definir constante de ruta base si es necesario
define('BASE_PATH', __DIR__);

// Obtener módulo y acción
$module = $_GET['module'] ?? 'dashboard';
$action = $_GET['action'] ?? 'index';

// Enrutamiento
switch ($module) {
    case 'login':
        // Si es login, cargamos la vista de login directamente o usamos un controlador si queremos
        // Para simplificar según estructura actual, redirigimos a views/login.php si existe o manejamos aquí
        // La estructura propuesta movía views/login.php (renombrado de indexLogin.php)
        // Por ahora, asumimos que views/login.php es la vista
        if ($action === 'auth') {
            require_once 'controllers/loginController.php';
        } else {
            header("Location: views/login.php");
            exit;
        }
        break;

    case 'dashboard':
        // Redirigir al dashboard
        header("Location: views/dashboard.php");
        exit;
        break;

    case 'cuentas':
        require_once 'controllers/CuentaController.php';
        $controller = new CuentaController();

        switch ($action) {
            case 'index':
                $controller->index();
                break;
            case 'guardar':
                $controller->guardar();
                break;
            case 'form':
                $controller->form();
                break;
            case 'eliminar':
                $controller->eliminar();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'tipdoc':
        require_once 'controllers/TipdocController.php';
        $controller = new TipdocController();

        switch ($action) {
            case 'index': // Vista principal
                $controller->index();
                break;
            case 'listar': // API JSON
                $controller->listar();
                break;
            case 'obtener': // API JSON
                $controller->obtener();
                break;
            case 'guardar': // API JSON
                $controller->guardar();
                break;
            case 'eliminar': // API JSON
                $controller->eliminar();
                break;
            default:
                $controller->index();
                break;
        }
        break;

    case 'diario':
        require_once 'config/database.php';
        require_once 'controllers/diarioController.php';
        $db = Database::connect();
        $controller = new diarioController($db);

        if ($action === 'guardar') {
            $controller->guardarAction();
        } elseif ($action === 'listarCuentas') {
            $controller->listarCuentas();
        } elseif ($action === 'listarTipDoc') {
            $controller->listarTipDoc();
        } elseif ($action === 'listarAuxiliar') {
            $controller->listarAuxiliar();
        } elseif ($action === 'obtenerAsiento') {
            $controller->obtenerAsiento();
        }
        break;

    default:
        // Si no coincide nada, login
        header("Location: views/login.php");
        exit;
        break;
}
