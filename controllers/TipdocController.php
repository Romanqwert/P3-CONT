<?php
// controllers/TipdocController.php

require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../models/TipdocModel.php';

class TipdocController
{
    private $model;
    private $db;

    public function __construct()
    {
        $this->db = Database::connect();
        $this->model = new TipdocModel($this->db);
    }

    public function index()
    {
        // Cargar la vista principal
        // Podríamos precargar datos si quisieramos, pero usaremos AJAX como se pidió
        require_once 'views/tipdoc/index.php';
    }

    public function listar()
    {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAll());
    }

    public function obtener()
    {
        header('Content-Type: application/json');
        $id = $_GET['id'] ?? '';
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }
        $data = $this->model->getById($id);
        echo json_encode($data);
    }

    public function guardar()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);

        if (!$input) {
            echo json_encode(['success' => false, 'message' => 'Datos inválidos']);
            return;
        }

        // Validación básica
        if (empty($input['IDTIPO']) || empty($input['DESCRIPCION'])) {
            echo json_encode(['success' => false, 'message' => 'Campos obligatorios faltantes']);
            return;
        }

        // Determinar si es crear o actualizar
        // El frontend debe decidir o verificamos existencia.
        // Dado que IDTIPO es la PK, si existe, es update? O rechazamos duplicado en create?
        // Asumiremos lógica: Si viene flag 'is_update' o similar.
        // O intentamos insert y si falla por key duplicate... 

        if (!empty($input['is_update'])) {
            $res = $this->model->update($input['IDTIPO'], $input);
        } else {
            // Verificar si ya existe para dar mejor error
            $existe = $this->model->getById($input['IDTIPO']);
            if ($existe) {
                echo json_encode(['success' => false, 'message' => 'El código ya existe']);
                return;
            }
            $res = $this->model->create($input);
        }

        echo json_encode($res);
    }

    public function eliminar()
    {
        header('Content-Type: application/json');
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? '';

        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID no proporcionado']);
            return;
        }

        echo json_encode($this->model->delete($id));
    }
}
