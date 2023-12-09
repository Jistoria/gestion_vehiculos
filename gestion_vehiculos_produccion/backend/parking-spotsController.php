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
            case 'entryCar':
                $selectedCar = is_array($data->selectedCars) ? $data->selectedCars[0] : $data->selectedCars;
                $selectedSpot = is_array($data->selectedSpots) ? $data->selectedSpots[0] : $data->selectedSpots;
                entryCar($selectedCar, $selectedSpot);
                break;
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
            case 'getPS':
                $search = isset($_GET['search']) ? $_GET['search'] : '';
                getParkingSpots($search);
                break;
            case 'history' :
                getHistoryParking();
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
        case 'releaseCar':
            releaseCar($data);
            break;
        }
} else {
    echo json_encode('Método de solicitud no válido');
}

function getParkingSpots($search = null) {
    $query = "SELECT ps.*, ss.status, c.license_plate
                FROM parking_spots ps
                INNER JOIN spot_status ss ON ss.id_status = ps.id_status
                LEFT JOIN cars c ON c.license_plate = ps.license_plate";
    
    $query = $search ? $query . " WHERE ps.spot_code LIKE :search LIMIT 20" : $query." LIMIT 20";

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
        $stmt = null;
        echo json_encode(['success'=>true, 'parking_spots'=>$results]);
        $results = null;
        exit;
    } catch (PDOException $exp) {
        echo "Error en la consulta: " . $exp->getMessage();
        return false;
    }
}

function entryCar($car, $spot){
    $query = "INSERT INTO parking_history (license_plate, spot_code, occupied_datetime)
                VALUES (:car, :spot, CURRENT_TIMESTAMP)";
    try {
        $conn = Conexion::conexionDB(); // Obtén la conexión directamente

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':car', $car, PDO::PARAM_STR);
        $stmt->bindParam(':spot', $spot, PDO::PARAM_STR);
        $stmt->execute();

        echo json_encode(['success' => true, 'message' => 'Se ha ingresado correctamente el carro']);
    } catch (PDOException $exp) {
        // Manejar la excepción de alguna manera, por ejemplo:
        echo json_encode(['success' => false, 'message' => 'Error al ingresar el carro: ' . $exp->getMessage()]);
    }
}

function releaseCar($spot)
{
    $query = "UPDATE parking_history SET release_datetime = NOW() WHERE spot_code = :spot_code AND release_datetime IS NULL";

    try {
        $conn = Conexion::conexionDB(); // Obtén la conexión directamente

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':spot_code', $spot, PDO::PARAM_STR);
        $stmt->execute();

        $rowCount = $stmt->rowCount();

        if ($rowCount > 0) {
            echo json_encode(['success' => true, 'message' => 'Se ha retirado correctamente el carro']);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se encontró ningún registro para liberar']);
        }
    } catch (PDOException $exp) {
        // Manejar la excepción de alguna manera, por ejemplo:
        echo json_encode(['success' => false, 'message' => 'Error al retirar el carro: ' . $exp->getMessage()]);
    }
}

function getHistoryParking(){
    $query = "SELECT *
                FROM parking_history ";
    try {
        $conn = Conexion::conexionDB(); // Obtén la conexión directamente

        $stmt = $conn->prepare($query);

        $stmt->execute();

        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Cierra la conexión directamente
        $conn = null;
        $stmt = null;
        echo json_encode(['success'=>true, 'history'=>$results]);
        $results = null;
        exit;
    } catch (PDOException $exp) {
        echo "Error en la consulta: " . $exp->getMessage();
        return false;
    }
}