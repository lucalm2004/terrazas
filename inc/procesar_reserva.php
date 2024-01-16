<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: ../index.php");
    exit;
}


// Verificar si se han enviado datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['desreserva'])) {
        // El campo 'desreserva' está seteado
        $desreserva = $_POST['desreserva'];
        // Resto del código para el caso de desreserva
    }
    if(isset($desreserva)){
        try {
            require 'conexion.php';
            $id_sala = $_POST['id_sala'];
            echo $id_sala;
            // Obtener datos del formulario
            $id_mesa = $_POST['id_mesa'];
            echo $id_mesa;
            $estado_mesa = 'libre';
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
    }else{
        try {
            require 'conexion.php';

            // Obtener datos del formulario
            $id_sala = $_POST['id_sala'];
            echo '<p>' . $id_sala .'</p>';
            $id_mesa = $_POST['id_mesa_reserva'];
            $estado_mesa = $_POST['estado_mesa'];
            $nombre_reserva = $_POST['nombre_reserva'];
            $hora_reserva = $_POST['hora_reserva'];
            $hora_fin_reserva = $_POST['hora_fin_reserva'];
            $dia_reserva = $_POST['dia_reserva'];

            // Actualizar el estado de la mesa y agregar datos de reserva
            $sql = "UPDATE mesas SET estado = :estado, nombre_reserva = :nombre_reserva, hora_reserva = :hora_reserva, hora_fin_reserva = :hora_fin_reserva, dia_reserva = :dia_reserva WHERE id_mesa = :id_mesa";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':estado', $estado_mesa, PDO::PARAM_STR);
            $stmt->bindParam(':nombre_reserva', $nombre_reserva, PDO::PARAM_STR);
            $stmt->bindParam(':hora_reserva', $hora_reserva, PDO::PARAM_STR);
            $stmt->bindParam(':hora_fin_reserva', $hora_fin_reserva, PDO::PARAM_STR);
            $stmt->bindParam(':dia_reserva', $dia_reserva, PDO::PARAM_STR);
            $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
            $stmt->execute();

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
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
    
}
?>
