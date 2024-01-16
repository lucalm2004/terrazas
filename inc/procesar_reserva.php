<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        require 'conexion.php';
        $id_sala = $_POST['idSala'];
        echo $id_sala;
        // Obtener datos del formulario
        $id_mesa = $_POST['id_mesa_reserva'];
        echo $id_mesa;
        $estado_mesa = $_POST['estado_mesa'];
echo $estado_mesa;
        // Actualizar el estado de la mesa a "reservado"
        $sql = "UPDATE mesas SET estado = :estado WHERE id_mesa = :id_mesa";
        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':estado', $estado_mesa, PDO::PARAM_STR);
        $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt->execute();

        // Puedes agregar más lógica aquí, como registrar la reserva en otra tabla, etc.

        // Redirigir a la página de origen o a donde desees después de procesar la reserva
        if ($id_sala >= 1 && $id_sala <= 3) {
            header("Location: ../mesas.php?id=" . $id_sala);
        } elseif ($id_sala >= 4 && $id_sala <= 5) {
            header("Location: ../mesas.php?id=" . $id_sala);
        } elseif ($id_sala >= 6 && $id_sala <= 9) {
            header("Location: ../mostrar.php?id=Privada");
        } else {
            echo "Error: ID de sala desconocido.";
        }
                exit;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
