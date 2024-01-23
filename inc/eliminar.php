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
include("conexion.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Asegúrate de que el idUsuario se haya enviado
    if (isset($_POST['idUsuario'])) {
        $idUsuario = $_POST['idUsuario'];

        try {
            // Realiza la lógica para eliminar el usuario
            $stmt = $conn->prepare("DELETE FROM usuarios WHERE id_usuario = :idUsuario");
            $stmt->bindParam(':idUsuario', $idUsuario);
            $stmt->execute();

            // Si llegamos hasta aquí sin errores, podemos enviar 'ok' como respuesta
            echo 'ok';
        } catch (PDOException $e) {
            // En caso de error, puedes devolver un mensaje de error
            echo 'Error: ' . $e->getMessage();
        }
    } else {
        // Si idUsuario no está presente en la solicitud, devolvemos un mensaje de error
        echo 'Error: El ID del usuario no se proporcionó correctamente.';
    }
} else {
    // Si la solicitud no es de tipo POST, devolvemos un mensaje de error
    echo 'Error: Solicitud no válida.';
}
?>
