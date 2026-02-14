<?php

class Database
{
    // Método estático para conectarnos sin tener que crear un objeto
    public static function connect(): PDO
    {
        try {
            // Nos conectamos a la base de datos con PDO
            // mysql:host=localhost → dónde está la base
            // dbname=DBFINANZA → qué base usamos
            // charset=utf8 → para que acepte acentos y ñ
            $conn = new PDO("mysql:host=localhost;dbname=DBFINANZA;charset=utf8", "root", "");

            // Esto hace que PDO nos tire errores en forma de excepciones
            // Así los podemos manejar con try/catch
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            // Todo salió bien, devolvemos la conexión
            return $conn;
        } catch (PDOException $e) {
            die(json_encode(['success' => false, 'error' => $e->getMessage()]));
        }
    }
}