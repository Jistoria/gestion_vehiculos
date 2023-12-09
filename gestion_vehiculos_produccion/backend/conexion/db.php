<?php
class Conexion {

    public static function conexionDB(){
        // Datos de conexión a la base de datos
        $host = "212.1.208.199";
        $dbname = "u312507976_db56";
        $user = "u312507976_db56"; // Usuario de MySQL
        $password = "5Bg423-2"; // Contraseña de MySQL
        $dsn = "mysql:host=$host;dbname=$dbname";

        try {
            $conn = new PDO($dsn, $user, $password);
            // Habilitar manejo de errores de PDO
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $conn;
        } catch (PDOException $exp) {
            echo("No se pudo conectar a la base de datos: $exp");
        }
    }
}