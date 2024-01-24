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

// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        require 'conexion.php';

        // Obtener datos del formulario
        $id_sala = $_POST['id_sala'];
        $id_mesa = $_POST['id_mesa_reserva'];
        $estado_mesa = $_POST['estado_mesa'];
        $nombre_reserva = $_POST['nombre_reserva'];
        $hora_reserva = $_POST['hora_reserva'];
        $hora_fin_reserva = $_POST['hora_fin_reserva'];
        $dia_reserva = $_POST['dia_reserva'];

        // Verificar si hay conflictos de reserva para la mesa, día y período de tiempo
        $sql_conflict = "SELECT nombre_reserva, hora_reserva, dia_reserva FROM reservas WHERE id_mesa = :id_mesa AND dia_reserva = :dia_reserva
                         AND ((hora_reserva <= :hora_reserva AND hora_fin_reserva >= :hora_reserva)
                         OR (hora_reserva <= :hora_fin_reserva AND hora_fin_reserva >= :hora_fin_reserva)
                         OR (:hora_reserva <= hora_reserva AND :hora_fin_reserva >= hora_reserva)
                         OR (:hora_reserva <= hora_fin_reserva AND :hora_fin_reserva >= hora_fin_reserva))";
        $stmt_conflict = $conn->prepare($sql_conflict);
        $stmt_conflict->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
        $stmt_conflict->bindParam(':dia_reserva', $dia_reserva, PDO::PARAM_STR);
        $stmt_conflict->bindParam(':hora_reserva', $hora_reserva, PDO::PARAM_STR);
        $stmt_conflict->bindParam(':hora_fin_reserva', $hora_fin_reserva, PDO::PARAM_STR);
        $stmt_conflict->execute();

        if ($stmt_conflict->rowCount() > 0) {
            // Hay conflictos de reserva, mostrar los detalles de la reserva existente
            $conflict_data = $stmt_conflict->fetch(PDO::FETCH_ASSOC);
            $conflict_nombre = $conflict_data['nombre_reserva'];
            $conflict_hora_reserva = $conflict_data['hora_reserva'];
            $conflict_dia_reserva = $conflict_data['dia_reserva'];

            header("Location: ../mesas.php?id=" . $id_sala . "&error=1&msg=" . urlencode("Esta mesa ya ha sido reservada el día $conflict_dia_reserva por $conflict_nombre entre las $conflict_hora_reserva y $hora_fin_reserva."));
            exit;

        } else {
            // No hay conflictos, proceder con la inserción
            $sql_insert = "INSERT INTO reservas SET id_mesa = :id_mesa, nombre_reserva = :nombre_reserva, hora_reserva = :hora_reserva, hora_fin_reserva = :hora_fin_reserva, dia_reserva = :dia_reserva";
            $stmt_insert = $conn->prepare($sql_insert);
            $stmt_insert->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt_insert->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
            $stmt_insert->bindParam(':hora_reserva', $hora_reserva, PDO::PARAM_STR);
            $stmt_insert->bindParam(':hora_fin_reserva', $hora_fin_reserva, PDO::PARAM_STR);
            $stmt_insert->bindParam(':dia_reserva', $dia_reserva, PDO::PARAM_STR);
            $stmt_insert->execute();

            // Redirigir a la página de origen después de procesar la reserva
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
        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
