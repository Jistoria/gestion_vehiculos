<?php
include_once('conexion/db.php');

// Establecer la conexión a la base de datos
$conn = Conexion::conexionDB();

try {
    // Preparar la consulta SQL
    $query = "SELECT * FROM spot_status";

    // Ejecutar la consulta
    $result = $conn->query($query);

    // Verificar si la consulta se ejecutó correctamente
    if ($result) {
        // Obtener los resultados como un array asociativo
        $spotStatusList = $result->fetchAll(PDO::FETCH_ASSOC);

        // Mostrar o utilizar los resultados según tus necesidades
        echo json_encode(['spot_status'=>$spotStatusList]);
    } else {
        echo "Error al ejecutar la consulta.";
    }
} catch (PDOException $e) {
    echo "Error de PDO: " . $e->getMessage();
}

// Cerrar el cursor
$result=null;
$spotStatusList = null;
// Cerrar la conexión
$conn = null;
?>