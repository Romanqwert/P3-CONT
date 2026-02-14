<?php
// models/CuentaModel.php

class CuentaModel
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllCuentas()
    {
        $sql = "SELECT * FROM CONT01 ORDER BY Cuenta ASC";
        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getCuentaById($cuenta)
    {
        $sql = "SELECT * FROM CONT01 WHERE Cuenta = :cuenta";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([':cuenta' => $cuenta]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addCuenta($data)
    {
        try {
            $sql = "INSERT INTO CONT01 (Cuenta, Descripcion, Tipo, Nivel, Padre, Grupo, Control, ESTATUS, debito, credito, balanceactual) 
                    VALUES (:cuenta, :desc, :tipo, :nivel, :padre, :grupo, :control, :estatus, 0, 0, 0)";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cuenta' => $data['Cuenta'],
                ':desc' => $data['Descripcion'],
                ':tipo' => $data['Tipo'],
                ':nivel' => $data['Nivel'],
                ':padre' => $data['Padre'],
                ':grupo' => $data['Grupo'],
                ':control' => $data['Control'],
                ':estatus' => $data['ESTATUS'] ?? 'A'
            ]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function updateCuenta($data)
    {
        try {
            $sql = "UPDATE CONT01 SET 
                    Descripcion = :desc,
                    Tipo = :tipo,
                    Nivel = :nivel,
                    Padre = :padre,
                    Grupo = :grupo,
                    Control = :control,
                    ESTATUS = :estatus
                    WHERE Cuenta = :cuenta";

            $stmt = $this->db->prepare($sql);
            $stmt->execute([
                ':cuenta' => $data['Cuenta'],
                ':desc' => $data['Descripcion'],
                ':tipo' => $data['Tipo'],
                ':nivel' => $data['Nivel'],
                ':padre' => $data['Padre'],
                ':grupo' => $data['Grupo'],
                ':control' => $data['Control'],
                ':estatus' => $data['ESTATUS']
            ]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    public function deleteCuenta($cuenta)
    {
        try {
            $sql = "DELETE FROM CONT01 WHERE Cuenta = :cuenta";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([':cuenta' => $cuenta]);
            return ['success' => true];
        } catch (Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}
