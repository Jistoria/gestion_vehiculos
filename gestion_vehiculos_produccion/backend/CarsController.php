<?php
require ('conexion/db.php');
require ('conexion/headers.php');
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $data = json_decode(file_get_contents("php://input"));
    $action = isset($_GET['post']) ? $_GET['post'] : null;

    if ($action) {
        // Escoger la acción basada en la variable 'action'
        switch ($action) {
            
            default:
                echo json_encode('Acción no válida');
                break;
        }
    } else {
        echo json_encode('Acción no especificada');
    }
} else if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $action = isset($_GET['get']) ? $_GET['get'] : null;
    $data = json_decode(file_get_contents("php://input"));
    if ($action) {
        switch ($action) {
            case 'getCars':
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                getCars($search);
                break;
            default:
                echo json_encode('Acción no válida');
                break;
        }
    } else {
        echo json_encode('Acción no especificada');
    }
}else if($_SERVER['REQUEST_METHOD'] === 'PUT'){
    $data = json_decode(file_get_contents("php://input"));
    $action = isset($_GET['put']) ? $_GET['put'] : null;
    switch ($action) {
        case 'updateStylist':
            
            break;
        }
} else {
    echo json_encode('Método de solicitud no válido');
}

function getCars($search = null) {
    $query = "SELECT c.*
                FROM cars c
                LEFT JOIN parking_spots ps ON c.license_plate = ps.license_plate";
    
    $query = $search ? $query . " WHERE license_plate LIKE :search AND ps.license_plate IS NULL" : $query." WHERE ps.license_plate IS NULL";

    try {
        $conn = Conexion::conexionDB(); // Obtén la conexión directamente

        $stmt = $conn->prepare($query);

        if ($search) {
            $searchParam = '%' . $search . '%';
            $stmt->bindParam(':search', $searchParam, PDO::PARAM_STR);
        }

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cierra la conexión directamente
        $conn = null;
        
        echo json_encode(['success'=>true, 'cars'=>$results]);
        $results = null;
        exit;
    } catch (PDOException $exp) {
        echo "Error en la consulta: " . $exp->getMessage();
        return false;
    }
}
