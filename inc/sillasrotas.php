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
    $sillas = $_POST['sillas_mantenimiento'] + 1;
    $silla = $_POST['sillas_mantenimiento'] - 1;
    $sillarota = $_POST['sillas'] - 1;
    $sillaarreglada = $_POST['sillas'] + 1;
    // $si = $_POST['sillas'];
    // if($si == 0){
    //     if ($id_sala >= 1 && $id_sala <= 3) {
    //         header("Location: ../mesas.php?id=" . $id_sala);
    //     } elseif ($id_sala >= 4 && $id_sala <= 5) {
    //         header("Location: ../mesas.php?id=" . $id_sala);
    //     } elseif ($id_sala >= 6 && $id_sala <= 9) {
    //         header("Location: ../mostrar.php?id=Privada");
    //     } else {
    //         echo "Error: ID de sala desconocido.";}
    //     die();
    // }else{
       

        try {
            // Verificar qué botón se presionó
            if (isset($_POST['add_silla'])) {
                $sql = "UPDATE mesas SET sillas_mantenimiento = :sillas WHERE id_mesa = :id_mesa";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->bindParam(':sillas', $sillas, PDO::PARAM_INT);
                $stmt->execute();
    
    
                $sql2 = "UPDATE mesas SET sillas = :sillas WHERE id_mesa = :id_mesa";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt2->bindParam(':sillas', $sillarota, PDO::PARAM_INT);
                $stmt2->execute();
            } elseif (isset($_POST['remove_silla'])) {
                $sql = "UPDATE mesas SET sillas_mantenimiento = :sillas WHERE id_mesa = :id_mesa";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt->bindParam(':sillas', $silla, PDO::PARAM_INT);
                $stmt->execute();
    
                $sql2 = "UPDATE mesas SET sillas = :sillas WHERE id_mesa = :id_mesa";
                $stmt2 = $conn->prepare($sql2);
                $stmt2->bindParam(':id_mesa', $id_mesa, PDO::PARAM_INT);
                $stmt2->bindParam(':sillas', $sillaarreglada, PDO::PARAM_INT);
                $stmt2->execute();
    
    
            }
        
            // Ejecutar la consulta
            
                if ($id_sala >= 1 && $id_sala <= 3) {
                    header("Location: ../mesas.php?id=" . $id_sala);
                } elseif ($id_sala >= 4 && $id_sala <= 5) {
                    header("Location: ../mesas.php?id=" . $id_sala);
                } elseif ($id_sala >= 6 && $id_sala <= 9) {
                    header("Location: ../mostrar.php?id=Privada");
                } else {
                    echo "Error: ID de sala desconocido.";}
                die();
                
        } catch (PDOException $e) {
            echo "Error en la conexión a la base de datos: " . $e->getMessage();
        }
    }

    
// }
?>
