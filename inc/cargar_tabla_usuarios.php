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

try {
    // Consulta para obtener usuarios desde la base de datos
    $query = "SELECT id_usuario, nombre_user, apellido FROM usuarios";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Genera el contenido de la tabla
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>{$row['id_usuario']}</td>";
        echo "<td>{$row['nombre_user']}</td>";
        echo "<td>{$row['apellido']}</td>";
        echo "<td>
                <a href='#' class='btn btn-primary btn-sm' onclick='editarUsuario({$row['id_usuario']});'>Editar</a>
                <a href='#' class='btn btn-danger btn-sm' onclick='EnviarSolicitudEliminar({$row['id_usuario']});'>Eliminar</a>
              </td>";
        echo "</tr>";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
