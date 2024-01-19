<?php
// Archivo de conexión a la base de datos
require_once 'conexion.php';

// Verifica si se proporciona un idUsuario
if (isset($_GET['idUsuario'])) {
    $idUsuario = $_GET['idUsuario'];

    // Consulta para obtener los datos del usuario
    $query = "SELECT id_usuario, nombre_user, apellido FROM usuarios WHERE id_usuario = :idUsuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    
    try {
        $stmt->execute();

        // Obtiene los datos del usuario
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        // Devuelve los datos en formato JSON
        header('Content-Type: application/json');
        echo json_encode($userData);
    } catch (PDOException $e) {
        // Manejo de errores, puedes personalizar esto según tus necesidades
        echo json_encode(array('error' => 'Error al obtener datos del usuario'));
    }
} else {
    // Manejo de errores, puedes personalizar esto según tus necesidades
    echo json_encode(array('error' => 'No se proporcionó un idUsuario'));
}
?>
