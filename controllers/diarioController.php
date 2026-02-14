<?php
// Traemos el modelo que se encargará de hablar con la tabla de DB
require_once __DIR__ . '/../models/DiarioModel.php';

class diarioController
{

    private $model;

    // Cuando se instancia el controlador le pasamos la conexión a la BD
    public function __construct($db)
    {
        $this->model = new diarioModel($db);
    }

    public function guardarAction()
    {
        // Indicamos que la respuesta será en formato JSON
        header('Content-Type: application/json');

        // Leemos lo que llega del body (fetch/axios/ajax)
        $input = file_get_contents('php://input');

        // Convertimos el JSON en un array asociativo PHP
        $data = json_decode($input, true);

        // Validación: si no hay datos o no vienen detalles, salimos de la función
        if (!$data || empty($data['detalles'])) {
            echo json_encode([
                'success' => false,
                'message' => 'Datos incompletos'
            ]);
            return;
        }

        // Validamos la partida doble antes de tocar la base de datos
        $totalDebito = 0;
        $totalCredito = 0;

        // Recorremos cada línea del asiento (detalle de la vista)
        foreach ($data['detalles'] as $fila) {
            if ($fila['origen'] === 'D') {
                $totalDebito += floatval($fila['valor']);
            } else {
                $totalCredito += floatval($fila['valor']);
            }
        }

        // Si los totales no cuadran, no se guardará la data en la tabla
        if (round($totalDebito, 2) != round($totalCredito, 2)) {
            echo json_encode([
                'success' => false,
                'message' => 'El asiento no cuadra'
            ]);
            return;
        }

        try {
            // Verificamos si el asiento ya existe
            $existe = $this->model->getAsientoPorNumero($data['numero']);

            if (!empty($existe)) {
                // Si existe, realizamos una reversa (ajuste) y luego lo actualizamos
                $resultado = $this->model->actualizarAsiento($data);
            } else {
                // Si no existe, inserta un registro nuevo en la tabla
                $resultado = $this->model->guardarAsiento($data);
            }

            // Devolvemos el resultado al frontend
            echo json_encode($resultado);

        } catch (Exception $e) {
            // Si algo falla, devolvemos un error controlado
            echo json_encode([
                'success' => false,
                'message' => $e->getMessage()
            ]);
        }
    }

    // Métodos auxiliares

    // Listar las cuentas contables (con búsqueda)
    public function listarCuentas()
    {
        $q = $_GET['q'] ?? ''; // Texto de búsqueda
        echo json_encode($this->model->getCuentas($q));
    }

    // Listar tipos de documento
    public function listarTipDoc()
    {
        echo json_encode($this->model->getTiposDocumentos());
    }

    // Listar los auxiliares (con búsqueda por módulo)
    public function listarAuxiliar()
    {
        $mod = $_GET['modulo'] ?? ''; // Texto de búsqueda
        echo json_encode($this->model->getAuxiliares($mod));
    }

    // Obtener un asiento específico (Búsqueda por número)
    // Ejemplo formato: DOCUMENTO 20260131 ED 0000012
    public function obtenerAsiento()
    {
        $num = $_GET['num'] ?? ''; // Número de asiento
        echo json_encode($this->model->getAsientoPorNumero($num));
    }
}
?>