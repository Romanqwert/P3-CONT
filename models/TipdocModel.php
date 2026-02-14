<?php
// models/TipdocModel.php

class TipdocModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAll()
    {
        // Solo traemos ID y Descripcion para la lista, o todo?
        $sql = "SELECT * FROM TIPDOC ORDER BY IDTIPO ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById($id)
    {
        $sql = "SELECT * FROM TIPDOC WHERE IDTIPO = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function create($data)
    {
        try {
            // TODO: Completar con las 13 columnas solicitadas.
            // Por ahora solo IDTIPO y DESCRIPCION.

            $sql = "INSERT INTO TIPDOC (IDTIPO, DESCRIPCION) VALUES (:id, :desc)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $data['IDTIPO'],
                ':desc' => $data['DESCRIPCION']
            ]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function update($id, $data)
    {
        try {
            // TODO: Completar update de columnas
            $sql = "UPDATE TIPDOC SET DESCRIPCION = :desc WHERE IDTIPO = :id";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':id' => $id,
                ':desc' => $data['DESCRIPCION']
            ]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function delete($id)
    {
        try {
            $sql = "DELETE FROM TIPDOC WHERE IDTIPO = :id";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':id' => $id]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }
}
