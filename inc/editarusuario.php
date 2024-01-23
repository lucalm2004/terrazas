<?php
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: ./index.php");
    exit;
} else if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ./index.php");
    exit;
}
// Archivo de conexión a la base de datos
require_once 'conexion.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Obtener los datos del formulario
    $idUsuario = $_POST['idUsuario'];
    $nombre = $_POST['nombre'];
    $apellido = $_POST['apellido'];
    $nuevaPassword = $_POST['nuevaPassword'];

    // Puedes agregar la lógica de validación y hash de la contraseña aquí
    $hashedPassword = password_hash($nuevaPassword, PASSWORD_DEFAULT);

    try {
        // Preparar la consulta para actualizar el usuario
        $query = "UPDATE usuarios SET nombre_user = ?, apellido = ?, contrasena = ? WHERE id_usuario = ?";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(1, $nombre);
        $stmt->bindParam(2, $apellido);
        $stmt->bindParam(3, $hashedPassword);
        $stmt->bindParam(4, $idUsuario);

        // Ejecutar la consulta
        $stmt->execute();

        // Devolver respuesta de éxito
        echo 'ok';
    } catch (PDOException $e) {
        // Devolver mensaje de error en caso de fallo
        echo 'error';
    }
} else {
    // Si la solicitud no es POST, devolver error
    echo 'error';
}
?>
