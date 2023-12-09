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
                case 'login':
                    userLogin($data);
                    break;
                case 'registerUser':
                    userRegister($data);
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
        if ($action) {
            switch ($action) {
                case 'getSession':
                    getSession();
                    break;
                case 'logout':
                    session_start();
                    session_destroy();
                    break;
                case 'isSuperAdmin':
                    session_start();
                    $isAdmin = isset($_SESSION['user']['type_rol']) && $_SESSION['user']['type_rol'] === 'SuperAdmin';
                    echo json_encode(['isAdmin' => $isAdmin]);
                    break;
                case 'getUsers':
                    
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

    function getSession(){
        session_start(); 
        if(isset($_SESSION['user'])){
            echo json_encode(['success' => true, 'user' => $_SESSION['user'] ]);
        }else{
            echo json_encode(['success' => false, 'message' => 'No hay sesion' ]);
        }
    }
    function userLogin($data){
        try {
            // Establecer la conexión a la base de datos
            $conn = Conexion::conexionDB();

            // Consultar el usuario con el DNI proporcionado
            $stmt = $conn->prepare("SELECT u.*, r.type_rol FROM users u
                                    INNER JOIN roles r ON r.id_rol = u.id_rol WHERE dni = :dni");
            $stmt->bindParam(':dni', $data->dni);
            $stmt->execute();

            // Verificar si el usuario existe
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);

                // Verificar la contraseña
                if (password_verify($data->password, $user['password'])) {
                    // La contraseña es correcta, puedes hacer lo que necesites aquí (por ejemplo, establecer sesiones).
                    $user_login = [
                        'dni' => $user['dni'],
                        'name'=> $user['name'],
                        'lastname' => $user['lastname'],
                        'type_rol' => $user['type_rol']
                    ];
                    session_start();
                    $_SESSION['user'] = $user_login;
                    echo json_encode(['success'=>true,'message' => 'Login exitoso','user'=>$user_login]);
                } else {
                    echo json_encode(['success'=>false, 'error' => 'Credenciales Incorrectas']);
                }
            } else {
                echo json_encode(['error' => 'Usuario no encontrado']);
            }
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error de PDO: ' . $e->getMessage()]);
        } finally {
            // Cerrar la conexión
            $user = null;
            $conn = null;
        }
    }

    function userRegister($data){
    
        try {
            // Establecer la conexión a la base de datos
            $conn = Conexion::conexionDB();

            // Verificar si el usuario ya existe
            $stmt = $conn->prepare("SELECT * FROM users WHERE dni = :dni");
            $stmt->bindParam(':dni', $data->dni);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                echo json_encode(['success'=>false,'error' => 'El usuario con este dni ya existe']);
                return;
            }

            // Hash de la contraseña
            $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
            $id_rol = 2;
            // Insertar nuevo usuario
            $stmt = $conn->prepare("INSERT INTO users (dni, name, lastname, password, id_rol) VALUES (:dni, :name, :lastname, :password, :id_rol)");
            $stmt->bindParam(':dni', $data->dni);
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':lastname', $data->lastname);
            $stmt->bindParam(':password', $hashedPassword);
            $stmt->bindParam(':id_rol', $id_rol);
            $stmt->execute();

            echo json_encode(['success'=>true,'message' => 'Registro exitoso']);
        } catch (PDOException $e) {
            echo json_encode(['error' => 'Error de PDO: ' . $e->getMessage()]);
        } finally {
            // Cerrar la conexión
            $stmt = null;
            $conn = null;
        }
    }
