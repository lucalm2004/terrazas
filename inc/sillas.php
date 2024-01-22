<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ./index.php");
    exit;
} elseif (isset($_GET['logout'])) {
    session_destroy();
    header("Location: ./index.php");
    exit;
}
require_once './conexion.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_mesa = $_POST['id_mesa'];
    $id_sala = $_POST['id_sala'];
    $sillas = $_POST['sillas'] + 1;
    $silla = $_POST['sillas'] - 1;



    try {
        // Verificar qué botón se presionó
        if (isset($_POST['add_silla'])) {
            $sql = "UPDATE mesas SET sillas = :sillas WHERE id_mesa = :id_mesa";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt->bindParam(':sillas', $sillas, PDO::PARAM_INT);
        } elseif (isset($_POST['remove_silla'])) {
            $sql = "UPDATE mesas SET sillas = :sillas WHERE id_mesa = :id_mesa";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt->bindParam(':sillas', $silla, PDO::PARAM_INT);
        } elseif(isset($_POST['borrar_mesa'])){
            $sql = "DELETE FROM mesas WHERE id_mesa = :id_mesa";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        }
        // Preparar la consulta
       

        // Ejecutar la consulta
        if ($stmt->execute()) {
            if ($id_sala >= 1 && $id_sala <= 3) {
                header("Location: ../mesas.php?id=" . $id_sala);
            } elseif ($id_sala >= 4 && $id_sala <= 5) {
                header("Location: ../mesas.php?id=" . $id_sala);
            } elseif ($id_sala >= 6 && $id_sala <= 9) {
                header("Location: ../mostrar.php?id=Privada");
            } else {
                echo "Error: ID de sala desconocido.";
            }
            die();
                } else {
                    echo "Error en la operación";
                }
    } catch (PDOException $e) {
        echo "Error en la conexión a la base de datos: " . $e->getMessage();
    }
}
?>
