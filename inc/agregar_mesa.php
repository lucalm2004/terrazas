<?php
// Conexión a la base de datos
require_once 'conexion.php';

// Verifica que la solicitud sea mediante POST
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $numeroMesa = $_POST['numeroMesa'];
    $sillas = $_POST['sillas'];
    $idSala = $_POST['idSala'];

    // Verifica si el número de mesa ya existe
    $queryVerificar = "SELECT COUNT(*) AS count FROM mesas WHERE numero_mesa = :numeroMesa";
    $stmtVerificar = $conn->prepare($queryVerificar);
    $stmtVerificar->bindParam(':numeroMesa', $numeroMesa);
    $stmtVerificar->execute();
    $rowCount = $stmtVerificar->fetch(PDO::FETCH_ASSOC)['count'];

    if ($rowCount > 0) {
        echo 'repetido';
        exit;
    }

    // Verifica que el número de sillas no exceda el límite
    if ($sillas > 10) {
        echo 'max_sillas_excedido';
        exit;
    }

    // Inserta la mesa en la base de datos
    $queryInsertar = "INSERT INTO mesas (numero_mesa, sillas, id_sala) VALUES (:numeroMesa, :sillas, :idSala)";
    $stmtInsertar = $conn->prepare($queryInsertar);
    $stmtInsertar->bindParam(':numeroMesa', $numeroMesa);
    $stmtInsertar->bindParam(':sillas', $sillas);
    $stmtInsertar->bindParam(':idSala', $idSala);

    if ($stmtInsertar->execute()) {
        echo 'ok';
    } else {
        echo 'error';
    }
} else {
    // Si la solicitud no es mediante POST
    echo 'error';
}
?>
