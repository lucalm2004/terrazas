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

include_once('./conexion.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if($_POST['estadoSilla'] == 'Arreglado/Mantenimiento' ||$_POST['estadoSilla'] == 'Mantenimiento/Arreglado' ){
        if (isset($_POST['numero_mesa'])) {
            try {
                $id_sala = $_POST['id_sala'];
                $numero_mesa = $_POST['numero_mesa'];
    
                $sql = "SELECT numero_mesa, estado FROM mesas WHERE numero_mesa = :numero_mesa";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if (count($res) > 0) {
                    $estado = $res[0]['estado'];
    
                    if ($estado == 'libre') {
                        $conn->beginTransaction();
    
                        $L_update_sql = "UPDATE mesas SET estado = 'mantenimiento' WHERE numero_mesa = :numero_mesa";
                        $L_update_stmt = $conn->prepare($L_update_sql);
                        $L_update_stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                        $L_update_stmt->execute();
    
                        $sql_insert = "INSERT INTO ocupaciones (id_usuario, id_mesa) VALUES (:id_usuario, :id_mesa)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bindParam(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt_insert->bindParam(':id_mesa', $res[0]['id_mesa'], PDO::PARAM_INT);
                        $stmt_insert->execute();
    
                        $conn->commit();
    
                        // Redirect based on the id_sala
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
                    } elseif ($estado == 'mantenimiento') {
                        $conn->beginTransaction();
    
                        $O_update_sql = "UPDATE mesas SET estado = 'libre' WHERE numero_mesa = :numero_mesa";
                        $O_update_stmt = $conn->prepare($O_update_sql);
                        $O_update_stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                        $O_update_stmt->execute();
    
                        $sql_update = "UPDATE ocupaciones SET fecha_fin = :fecha_fin WHERE id_usuario = :id_usuario AND id_mesa = :id_mesa AND fecha_fin IS NULL";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bindParam(':fecha_fin', $fecha_actual);
                        $stmt_update->bindParam(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt_update->bindParam(':id_mesa', $res[0]['id_mesa'], PDO::PARAM_INT);
                        $stmt_update->execute();
    
                        $conn->commit();
    
                        // Redirect based on the id_sala
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
                    }
                } else {
                    echo "Error: La mesa con número $numero_mesa no existe en la BD";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                $conn->rollBack();
                die();
            } finally {
                if (isset($L_update_stmt)) {
                    $L_update_stmt->closeCursor();
                }
                if (isset($O_update_stmt)) {
                    $O_update_stmt->closeCursor();
                }
            }
        } else {
            echo "Error: No se ha enviado el número de mesa.";
        }
        }else{
        if (isset($_POST['numero_mesa'])) {
            try {
                $id_sala = $_POST['id_sala'];
                $numero_mesa = $_POST['numero_mesa'];
    
                $sql = "SELECT numero_mesa, estado FROM mesas WHERE numero_mesa = :numero_mesa";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                $stmt->execute();
                $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
                if (count($res) > 0) {
                    $estado = $res[0]['estado'];
    
                    if ($estado == 'libre') {
                        $conn->beginTransaction();
    
                        $L_update_sql = "UPDATE mesas SET estado = 'ocupada' WHERE numero_mesa = :numero_mesa";
                        $L_update_stmt = $conn->prepare($L_update_sql);
                        $L_update_stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                        $L_update_stmt->execute();
    
                        $sql_insert = "INSERT INTO ocupaciones (id_usuario, id_mesa) VALUES (:id_usuario, :id_mesa)";
                        $stmt_insert = $conn->prepare($sql_insert);
                        $stmt_insert->bindParam(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt_insert->bindParam(':id_mesa', $res[0]['id_mesa'], PDO::PARAM_INT);
                        $stmt_insert->execute();
    
                        $conn->commit();
    
                        // Redirect based on the id_sala
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
                    } elseif ($estado == 'ocupada') {
                        $conn->beginTransaction();
    
                        $O_update_sql = "UPDATE mesas SET estado = 'libre' WHERE numero_mesa = :numero_mesa";
                        $O_update_stmt = $conn->prepare($O_update_sql);
                        $O_update_stmt->bindParam(':numero_mesa', $numero_mesa, PDO::PARAM_INT);
                        $O_update_stmt->execute();
    
                        $sql_update = "UPDATE ocupaciones SET fecha_fin = :fecha_fin WHERE id_usuario = :id_usuario AND id_mesa = :id_mesa AND fecha_fin IS NULL";
                        $stmt_update = $conn->prepare($sql_update);
                        $stmt_update->bindParam(':fecha_fin', $fecha_actual);
                        $stmt_update->bindParam(':id_usuario', $_SESSION['id'], PDO::PARAM_INT);
                        $stmt_update->bindParam(':id_mesa', $res[0]['id_mesa'], PDO::PARAM_INT);
                        $stmt_update->execute();
    
                        $conn->commit();
    
                        // Redirect based on the id_sala
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
                    }
                } else {
                    echo "Error: La mesa con número $numero_mesa no existe en la BD";
                }
            } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
                $conn->rollBack();
                die();
            } finally {
                if (isset($L_update_stmt)) {
                    $L_update_stmt->closeCursor();
                }
                if (isset($O_update_stmt)) {
                    $O_update_stmt->closeCursor();
                }
            }
        } else {
            echo "Error: No se ha enviado el número de mesa.";
        }
    } 
    }

   else {
    echo "Error: Método de solicitud no válido.";
}
?>
