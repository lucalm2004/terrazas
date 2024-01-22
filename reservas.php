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
?>

<!DOCTYPE html>
<html lang="en">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RICK DECKARD - RESERVAS</title>
    <link rel="shortcut icon" href="./img/LOGORICK.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">
    <style>
        .table-responsive>.table-bordered {
            margin-bottom: 0px !important;
        }
    </style>
    <!-- Enlace a SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Enlace a tu archivo popup.js -->
    <script src="./js/popup.js" defer></script></head>

<body>
    <!-- ... (código del cuerpo) ... -->
    <div class="container mt-5">
    <div>
    <div style="float: left;">
        <h2 class="mb-4" style="color: white;">Información de las Reservas</h2>
    </div>
    <div style="float: right;">
        <a href="./mostrar.php">
            <button class="atrasboton">
                <img class="atrasimg" src="./img/atras.png" alt="">
            </button>
        </a>
    </div>
</div>
<br><br><br>

   
        <form method="get" action="">
            <!-- FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS -->
            <select name="mesas" id="mesas">
                <option value="">Todas las Mesas</option>
                <!-- Opciones de mesas aquí -->
                <?php
                try {
                    require_once('./inc/conexion.php');
                    $sqlMesas = "SELECT numero_mesa FROM mesas;";
                    $stmtMesas = $conn->prepare($sqlMesas);
                    $stmtMesas->execute();
                    $resultMesas = $stmtMesas->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resultMesas as $mesa) {
                        $num_mesa = $mesa['numero_mesa'];
                        echo "<option value=\"$num_mesa\"";
                        if (isset($_GET['mesas']) && $_GET['mesas'] == $num_mesa) {
                            echo " selected";
                        }
                        echo ">$num_mesa</option>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>

            <button type="submit">Filtrar</button>
            <button type="submit">
                <a style="text-decoration: none; color: black;" href="./registro.php">Borrar Filtros</a>
            </button>
        </form>

        <?php
        try {
            require_once('./inc/conexion.php');

            // Construir la consulta SQL
            $sql = "SELECT numero_mesa, nombre_reserva, hora_reserva, hora_fin_reserva, dia_reserva FROM mesas";

            // Agregar filtro por número de mesa si está presente en $_GET
            if (isset($_GET['mesas']) && !empty($_GET['mesas'])) {
                $numMesaFilter = $conn->quote($_GET['mesas']);
                $sql .= " WHERE numero_mesa = $numMesaFilter";
            }

            // Finalizar la consulta SQL
            $sql .= ";";

            $stmt = $conn->prepare($sql);

            if ($stmt === false) {
                die("Error en la consulta: " . $conn->errorInfo());
            }

            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($result) {
                echo '<div class="table-responsive table-wrapper" style="background-color: white;">';
                echo '<table class="table table-bordered">';
                echo '<thead class="thead-dark">';
                echo '<tr>';
                echo '<th>Número de Mesa</th>';
                echo '<th>Nombre de la Reserva</th>';
                echo '<th>Hora de Reserva</th>';
                echo '<th>Hora de Fin de Reserva</th>';
                echo '<th>Día de Reserva</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Imprimir resultados
                foreach ($result as $row) {
                    echo '<tr>';
                    echo "<td>{$row['numero_mesa']}</td>";
                    echo "<td>" . (!empty($row['nombre_reserva']) ? $row['nombre_reserva'] : 'No hay Reservas') . "</td>";
                    echo "<td>" . (!empty($row['hora_reserva']) ? $row['hora_reserva'] : 'No hay Reservas') . "</td>";
                    echo "<td>" . (!empty($row['hora_fin_reserva']) ? $row['hora_fin_reserva'] : 'No hay Reservas') . "</td>";
                    echo "<td>" . (!empty($row['dia_reserva']) ? $row['dia_reserva'] : 'No hay Reservas') . "</td>";
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<p style="color: white;">No hay Reservas.</p>';
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        } finally {
            if (isset($stmt)) {
                $stmt->closeCursor();
            }
        }
        ?>
    </div>
</body>

</html>
