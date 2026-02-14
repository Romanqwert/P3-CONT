<?php
// Traemos la conexión a la base de datos
require_once __DIR__ . '/../config/database.php';

// Decimos que esto va a devolver JSON, que no se nos olvide
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Agarramos el usuario y la contraseña que mandó el formulario, si no vienen, les ponemos ''
    $user = $_POST['usuario'] ?? '';
    $pass = $_POST['password'] ?? '';

    try {
        // realizamos la conexion a la base de datos
        $db = Database::connect();

        // preparamos la llamada al procedimiento almacenado que alida el usuario
        // CORREGIDO: faltaba cerrar paréntesis en la consulta
        $stmt = $db->prepare("CALL sp_ValidarUsuario(:u, :p)");

        // le decimos al query quien es quien
        $stmt->bindParam(':u', $user);
        $stmt->bindParam(':p', $pass);

        // ejecutamos el store procedure
        $stmt->execute();

        // Agarramos el resultado como un arreglo asociativo
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            echo json_encode(['success' => true]); // todo esta bien
        } else {
            echo json_encode(['success' => false]); // usuario y pass no son correcto
        }
    } catch (Exception $e) {
        // Si algo falla (conexión, query, lo que sea) mandamos error
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}