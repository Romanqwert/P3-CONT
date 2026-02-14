<?php
// controllers/CuentaController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/CuentaModel.php';

class CuentaController
{
    private $model;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new CuentaModel($this->db);
    }

    // Listado (Index)
    public function index()
    {
        $cuentas = $this->model->getAllCuentas();
        // Cargar vista
        require_once 'views/cuentas/index.php';
    }

    // Mostrar formulario (Crear o Editar)
    public function form()
    {
        $cuenta = null;
        if (isset($_GET['id'])) {
            $cuenta = $this->model->getCuentaById($_GET['id']);
        }
        require_once 'views/cuentas/form.php';
    }

    // Guardar (Insertar o Actualizar)
    public function guardar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Validar datos básicos
            $cuenta = $_POST['Cuenta'] ?? '';
            $descripcion = $_POST['Descripcion'] ?? '';

            if (empty($cuenta) || empty($descripcion)) {
                die("Error: Cuenta y Descripción son obligatorios.");
            }

            $data = [
                'Cuenta' => $cuenta,
                'Descripcion' => $descripcion,
                'Tipo' => $_POST['Tipo'] ?? '',
                'Nivel' => $_POST['Nivel'] ?? 1,
                'Padre' => $_POST['Padre'] ?? '',
                'Grupo' => $_POST['Grupo'] ?? '',
                'Control' => $_POST['Control'] ?? 'N',
                'ESTATUS' => $_POST['ESTATUS'] ?? 'A'
            ];

            // Determinar si es update o insert
            // Si viene un 'action_type' o si pudimos verificar existencia.
            // Por simplicidad, si la cuenta ya existe, hacemos update, sino insert? 
            // O usaremos hidden field.

            // Verificamos si existe por ID (PK)
            $existe = $this->model->getCuentaById($cuenta);

            // Sin embargo, si estoy editando, el ID es key. Si cambio el ID, es nuevo?
            // Asumiremos que el ID (Cuenta) no se edita, o si se edita es un lio.
            // En form edit, ID readonly.

            $isUpdate = false;
            // Check hidden field or logic. 
            if (isset($_POST['is_update']) && $_POST['is_update'] == 1) {
                $isUpdate = true;
            }

            if ($isUpdate || ($existe && !empty($existe))) {
                $res = $this->model->updateCuenta($data);
            } else {
                $res = $this->model->addCuenta($data);
            }

            if ($res['success']) {
                header("Location: index.php?module=cuentas&action=index");
                exit;
            } else {
                echo "Error: " . $res['error'];
            }
        }
    }

    // Eliminar
    public function eliminar()
    {
        if (isset($_GET['id'])) {
            // Podríamos pedir confirmación extra o usar POST para seguridad, pero por rapidez GET
            $this->model->deleteCuenta($_GET['id']);
        }
        header("Location: index.php?module=cuentas&action=index");
        exit;
    }
}
