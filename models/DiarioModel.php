<?php
// Modelo del Diario, aqui va todo lo que interactua con la base de datos

class diarioModel
{

    private $db;

    // Guardamos la conexion para usarla en todo el modelo
    public function __construct($conexion)
    {
        $this->db = $conexion;
    }

    // Devuelve un asiento contable completo por medio de la fecha, tipo documento y numero
    public function getAsientoPorNumero($doc)
    {
        $sql = "SELECT cuenta, detalle, fecha, tipodocumento, numero, auxiliar, origen, valor, asiento
                FROM CONT02
                WHERE asiento = :doc
                ORDER BY cuenta, origen ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':doc' => $doc]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Trae los tipos de documentos (combobox / select)
    public function getTiposDocumentos()
    {
        $sql = "SELECT IDTIPO, DESCRIPCION FROM TIPDOC ORDER BY IDTIPO ASC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Busca las cuentas contables (auxiliares) que no sean control
    public function getCuentas($termino = '')
    {
        $sql = "SELECT Cuenta, Descripcion FROM CONT01
                WHERE (Cuenta LIKE :q OR Descripcion LIKE :q)
                AND ESTATUS = 1
                AND Control = 'N'
                ORDER BY Cuenta ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':q' => "%$termino%"]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Devuelve auxiliares segun el modulo seleccionado
    public function getAuxiliares($modulo)
    {
        if ($modulo === 'CXC') {
            $sql = "SELECT IDCLIENTE AS ID, NOMBRECLIENTE AS NOMBRE FROM CLIENTES ORDER BY NOMBRECLIENTE ASC";
        } elseif ($modulo === 'CXP') {
            $sql = "SELECT IDSUPLIDOR AS ID, NOMBRESUPLIDOR AS NOMBRE FROM SUPLIDOR ORDER BY NOMBRESUPLIDOR ASC";
        } else {
            return [];
        }

        return $this->db->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function guardarAsiento($data)
    {
        try {
            // Iniciamos transaccion
            $this->db->beginTransaction();

            // Fecha completa yyyyMMdd
            $fechaStr = $data['fecha'];

            // Tomamos el periodo yyyyMM
            $periodo = substr($fechaStr, 0, 6);

            // Formateamos el numero de documento
            $numdocu = str_pad($data['numero'], 8, '0', STR_PAD_LEFT);

            // Creamos el ID unico del asiento
            $asiento = $fechaStr . $data['tipdoc'] . $numdocu;

            // Preparamos el SQL de inserción en detalle (CONT02)
            $sql02 = "INSERT INTO CONT02 (cuenta, detalle, fecha, tipoDocumento, numero,
                      auxiliar, origen, valor, clienteBeneficiario, asiento)
                      VALUES (:cta, :det, :fec, :tip, :num, :aux, :ori, :val, :cli, :doc)";

            $stmt02 = $this->db->prepare($sql02);

            // Recorremos cada linea del asiento en el detalle
            foreach ($data['detalles'] as $fila) {
                $valor = floatval($fila['valor']);

                // Determinamos debito y credito para la actualizacion de saldos
                $debito = ($fila['origen'] === 'D') ? $valor : 0;
                $credito = ($fila['origen'] === 'C') ? $valor : 0;

                // Ejecutamos la inserción del detalle
                $stmt02->execute([
                    ':cta' => $fila['cuenta'],
                    ':det' => strtoupper($data['concepto']),
                    ':fec' => $fechaStr,
                    ':tip' => $data['tipdoc'],
                    ':num' => $numdocu,
                    ':aux' => $fila['auxiliar'] ?? '', // Manejo de nulos si no hay auxiliar
                    ':ori' => $fila['origen'],
                    ':val' => $valor,
                    ':cli' => $fila['auxiliar'] ?? '', // Asumiendo que clienteBeneficiario es el auxiliar
                    ':doc' => $asiento
                ]);

                // Actualizamos los saldos de forma recursiva (Jerarquía de cuentas)
                $this->actualizarSaldosConJerarquia(
                    $fila['cuenta'],
                    $debito,
                    $credito,
                    $periodo
                );
            }

            // Si todo sale bien confirmamos cambios
            $this->db->commit();
            return ['success' => true];

        } catch (Exception $e) {
            // Si algo falla, deshacemos todo
            $this->db->rollBack();
            return [
                'success' => false,
                'message' => "Error en DB: " . $e->getMessage()
            ];
        }
    }

    public function actualizarAsiento($data)
    {
        try {
            // 1. Obtener el asiento original para reversarlo
            $asientoOriginal = $this->getAsientoPorNumero($data['numero']); // Ojo: getAsientoPorNumero busca por 'asiento' ID, pero aqui pasamos numero?
            // El controller pasa $data['numero']. 
            // getAsientoPorNumero usa WHERE asiento = :doc.
            // Si el 'numero' que pasa el front es el 'Documento' (ID compuesto) o solo el correlativo?
            // En guardarAsiento: $asiento = $fechaStr . $data['tipdoc'] . $numdocu;
            // El controller en line 108 obtiene 'num' y llama getAsientoPorNumero($num).
            // Asumiremos que $data['numero'] es el ID del asiento o el numero de documento.
            // PERO en guardarAsiento, $data['numero'] es solo el correlativo (ej 1).

            // Re-analizando getAsientoPorNumero: WHERE asiento = :doc.
            // Probablemente el frontend debe enviar el ID completo del asiento para editarlo, 
            // O si envia los componentes (fecha, tipo, num) los reconstruimos.

            // Asumimos que para actualizar, necesitamos borrar el previo.
            // PERO si cambio la fecha o tipo, el ID cambia.
            // Vamos a simplificar: reversamos basado en el ID re-calculado con los datos ORIGINALES?
            // El array $data trae lo NUEVO.
            // Deberíamos tener el ID original si cambió.
            // Por simplicidad y bug-fix, reconstruimos el ID asumiendo que el ID (Fecha+Tipo+Num) no cambia, solo el contenido.
            // O buscamos por el numero si es unico? No es unico.

            // Estrategia segura: Reconstruir ID con datos actuales (si no cambiaron fecha/tipo/num).
            $fechaStr = $data['fecha'];
            $numdocu = str_pad($data['numero'], 8, '0', STR_PAD_LEFT);
            $idAsiento = $fechaStr . $data['tipdoc'] . $numdocu;

            // Iniciamos transacción
            $this->db->beginTransaction();

            // 1. Buscar lineas viejas
            $stmt = $this->db->prepare("SELECT cuenta, origen, valor FROM CONT02 WHERE asiento = :id");
            $stmt->execute([':id' => $idAsiento]);
            $lineasViejas = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // 2. Reversar saldos (Restar lo que sumamos)
            $periodo = substr($fechaStr, 0, 6);
            foreach ($lineasViejas as $linea) {
                $debito = ($linea['origen'] === 'D') ? $linea['valor'] : 0;
                $credito = ($linea['origen'] === 'C') ? $linea['valor'] : 0;

                // Pasamos valores NEGATIVOS para restar
                $this->actualizarSaldosConJerarquia($linea['cuenta'], -$debito, -$credito, $periodo);
            }

            // 3. Borrar detalle viejo
            $delParams = [':id' => $idAsiento];
            $this->db->prepare("DELETE FROM CONT02 WHERE asiento = :id")->execute($delParams);

            // 4. Insertar nuevo detalle (copiado de guardarAsiento logic)
            // Preparamos el SQL de inserción en detalle (CONT02)
            $sql02 = "INSERT INTO CONT02 (cuenta, detalle, fecha, tipoDocumento, numero,
                      auxiliar, origen, valor, clienteBeneficiario, asiento)
                      VALUES (:cta, :det, :fec, :tip, :num, :aux, :ori, :val, :cli, :doc)";
            $stmt02 = $this->db->prepare($sql02);

            foreach ($data['detalles'] as $fila) {
                $valor = floatval($fila['valor']);
                $debito = ($fila['origen'] === 'D') ? $valor : 0;
                $credito = ($fila['origen'] === 'C') ? $valor : 0;

                $stmt02->execute([
                    ':cta' => $fila['cuenta'],
                    ':det' => strtoupper($data['concepto']),
                    ':fec' => $fechaStr,
                    ':tip' => $data['tipdoc'],
                    ':num' => $numdocu,
                    ':aux' => $fila['auxiliar'] ?? '',
                    ':ori' => $fila['origen'],
                    ':val' => $valor,
                    ':cli' => $fila['auxiliar'] ?? '',
                    ':doc' => $idAsiento
                ]);

                // Sumar nuevos saldos
                $this->actualizarSaldosConJerarquia($fila['cuenta'], $debito, $credito, $periodo);
            }

            $this->db->commit();
            return ['success' => true];

        } catch (Exception $e) {
            $this->db->rollBack();
            return ['success' => false, 'message' => "Error al actualizar: " . $e->getMessage()];
        }
    }

    // Función recursiva/iterativa para actualizar saldos subiendo niveles
    public function actualizarSaldosConJerarquia($cuenta, $deb, $cre, $periodo)
    {
        $ctaActual = $cuenta;

        // Mientras exista una cuenta (no vacia)
        while (!empty($ctaActual)) {

            // 1. Actualizamos los saldos acumulados en la tabla maestra (CONT01)
            // Nota: Se asume que balanceactual se afecta por (Debito - Credito)
            $sql01 = "UPDATE CONT01 SET
                      debito = debito + :deb,
                      credito = credito + :cre,
                      balanceactual = balanceactual + (:deb - :cre)
                      WHERE cuenta = :cta";

            $stmt01 = $this->db->prepare($sql01);
            $stmt01->execute([':deb' => $deb, ':cre' => $cre, ':cta' => $ctaActual]);

            // 2. Insertamos o Actualizamos el historico mensual (CONT04)
            // Se usa ON DUPLICATE KEY UPDATE para manejar si ya existe el registro del mes
            $sql04 = "INSERT INTO CONT04 (CUENTA, AAAAMM, BCE_ANTERIOR, DEBITO, CREDITO, BCE_ACTUAL)
                      VALUES (:cta, :per, 0, :deb, :cre, (:deb - :cre))
                      ON DUPLICATE KEY UPDATE
                      DEBITO = DEBITO + VALUES(DEBITO),
                      CREDITO = CREDITO + VALUES(CREDITO),
                      BCE_ACTUAL = BCE_ACTUAL + (VALUES(DEBITO) - VALUES(CREDITO))";

            $stmt04 = $this->db->prepare($sql04);
            $stmt04->execute([
                ':cta' => $ctaActual,
                ':per' => $periodo,
                ':deb' => $deb,
                ':cre' => $cre
            ]);

            // Subimos de nivel en la jerarquia de cuentas para la siguiente iteración
            $ctaActual = $this->subirNivelCuenta($ctaActual);
        }
    }

    // Calcula la cuenta padre basada en la longitud de la cuenta actual
    private function subirNivelCuenta($cuenta)
    {
        $len = strlen($cuenta);

        if ($len <= 1)
            return ""; // Estamos en el nivel más alto

        // Lógica de recorte según paridad de longitud (basado en el código original)
        if ($len % 2 == 0) {
            return substr($cuenta, 0, $len - 2);
        } else {
            return substr($cuenta, 0, $len - 1);
        }
    }
}
?>