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
    <title>RICK DECKARD - MESAS</title>
    <link rel="shortcut icon" href="./img/LOGORICK.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/mesas.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <!-- Enlace a SweetAlert -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Enlace a tu archivo popup.js -->
    <script src="./js/popup.js" defer></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"></script>
</head>

<body>
    <nav class="navbar navbar-light bg-lights position-top">
        <div class="container">
            <div>
                <a class="navbar-brand " href="./home.php">
                    <img src="./img/LOGORICK _Blanco.png" alt="" width="100" height="90">
                    <a href="./registro.php"><button class="atrasboton"><img class="atrasimg" src="./img/libro.png" alt=""></button></a>

                </a>
            </div>
            <div class="saludo">
                <b>¡Bienvenido al portal, <?php echo $_SESSION['user']; ?>!</b>
            </div>
            <div>

                <a href="./home.php"><button class="atrasboton"><img class="atrasimg" src="./img/atras.png" alt=""></button></a>
                <a href="./inc/salir.php"><button class="logoutboton"><img class="logoutimg" src="./img/LOGOUT.png" alt=""></button></a>
            </div>
        </div>
    </nav>

    <?php
    if (!isset($_GET['id'])) {
        header("Location: ./home.php");
        exit;
    } else {
        try {
            require './inc/conexion.php';

            $id = trim($_GET['id']);
            $sql = "SELECT * FROM mesas WHERE id_sala = :id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        echo '<div class="centrado">';
        $i = 0;
        foreach ($res as $mesa) {
            if ($i % 3 == 0 || $i == 0) {
                echo '<div class="image-grid">';
            }
            echo '<a><div class="image-item">';
            if ($mesa['estado'] == "ocupada" || $mesa['estado'] == "reservado") {
                if ($mesa['estado'] == "mantenimiento") {
                    echo '<img class="filtro2" src="./img/mesas.png" alt="Imagen 1">';
                } else {
                    echo '<img class="filtro" src="./img/mesas.png" alt="Imagen 1">';
                }
                echo '<div class="image-text"><h2> Mesa' . $mesa['numero_mesa'] . '</h2>';
                echo '<p class="diss">' . $mesa['estado'] . '</p>';
                $clase = 'class ="btn2 danger  btn-block" value="Desocupar" ';
            } else {
                if ($mesa['estado'] == "mantenimiento") {
                    echo '<img class="filtro2" src="./img/mesas.png" alt="Imagen 1">';
                } else {
                    echo '<img class="" src="./img/mesas.png" alt="Imagen 1">';
                }
                echo '<div class="image-text"><h2> Mesa' . $mesa['numero_mesa'] . '</h2>';
                echo '<p><b>Estado: </b>' . $mesa['estado'] . '</p>';
                if ($_SESSION['user'] == 'mantenimiento') {
                    echo '<form action="sillas.php?id=' . $mesa['numero_mesa'] . '">';
                    echo '<b>Sillas: </b><input style="margin-bottom:5%" type="text" value="' . $mesa['sillas'] . '"></input>';
                    echo '</form>';
                    if ($mesa['estado'] == 'mantenimiento') {
                        $clase = 'class ="btn2 success btn-block" value="Arreglado/Mantenimiento" ';
                    } else {
                        $clase = 'class ="btn2 danger btn-block" value="Mantenimiento/Arreglado" ';
                    }
                } elseif ($mesa['estado'] !== 'mantenimiento') {
                    echo '<form method="POST" action="./inc/sillas.php?id=' . $mesa['numero_mesa'] . '">';
                    echo "<input type='hidden' name='id_sala' value=" . $mesa['id_sala'] . ">";
                    echo "<input type='hidden' name='id_mesa' value=" . $mesa['id_mesa'] . ">";

                    echo "<input type='hidden' name='sillas' value=" . $mesa['sillas'] . ">";
                    echo '<div class="d-flex align-items-center justify-content-between">';
                    echo '<p><b>Sillas: </b>' . $mesa['sillas'] . '</p>';
                    echo '<div>';
                    echo '<span>';
                    echo '<button style="margin-bottom: 30%;" type="submit" name="add_silla" class="btn btn-success">+</button>';
                    echo '  ';
                    echo '<button style="margin-bottom: 30%" type="submit" name="remove_silla" class="btn btn-danger">-</button>';
                    echo '</span>';
                    echo '</div>';

                    echo '</div>';
                    echo '</form>';

                    $clase = 'class ="btn2 success btn-block" value="Ocupar" ';
                } else {
                    $clase = 'class ="btn btn-secondary btn-block" value="Mantenimiento" disabled';
                }
            }

            if ($mesa['estado'] == "reservado") {
                echo "<form action='./inc/procesar_reserva.php' method='post'>";
                echo "<input type='hidden' name='id_sala' value=" . $mesa['id_sala'] . ">";
                echo "<input type='hidden' name='id_mesa' value=" . $mesa['id_mesa'] . ">";
                echo "<input type='hidden' name='numero_mesa' value=" . $mesa['numero_mesa'] . ">";
                echo '<p> <b>Nombre de la reserva: </b>' . $mesa['nombre_reserva'] . '</p>';
                echo '<p><b>Hora de la Reserva: </b>' . $mesa['hora_reserva'] . '</p>';
                echo '<p><b>Hora fin Reserva: </b>' . $mesa['hora_fin_reserva'] . '</p>';
                echo '<p><b>Dia de la reserva: </b>' . $mesa['dia_reserva'] . '</p>';

                echo "<input type='hidden' name='desreserva' value='desreserva'>";
            } else {
                echo "<form method='POST' action='./inc/procesar.php'>";
                
                echo "<input type='hidden' name='id_sala' value=" . $mesa['id_sala'] . ">";
                echo "<input type='hidden' name='id_mesa' value=" . $mesa['id_mesa'] . ">";
                echo "<input type='hidden' name='numero_mesa' value=" . $mesa['numero_mesa'] . ">";
            }


            if ($mesa['estado'] == "reservado" && $_SESSION['user'] !== 'mantenimiento') {
                if ($mesa['estado'] !== "mantenimiento") {
                    echo "<input class='btn2 danger btn-block' value='Desreservar' type='submit'>";
                }
            } elseif ($_SESSION['user'] !== 'mantenimiento') {
                echo "<input " . $clase . " type='submit'>";
                if ($mesa['estado'] !== "mantenimiento") {
                    echo "<input class = 'btn2 danger btn-block' name='estadoSilla' value='Mantenimiento' type='submit'>";
                }

                if ($mesa['estado'] !== "mantenimiento") {
                    echo "<input class='btn2 success btn-block' value='Reservar' type='button' onclick='mostrarReservaModal(\"{$mesa['id_mesa']}\", \"{$mesa['numero_mesa']}\")'>";
                }
            } else {
                echo "<input " . $clase . " name='estadoSilla' type='submit'>";
            }
            echo "</form>";
            echo '</div></div></a>';

            if ($i == 2) {
                echo '</div>';
            }
            $i++;
        }
    }
    ?>

    <!-- Modal de Reserva -->
    <div class="modal fade" id="reservaModal" tabindex="-1" aria-labelledby="reservaModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="reservaModalLabel">Realizar Reserva</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Agrega un elemento para mostrar el número/nombre de la mesa -->
                    <div id="infoMesa" class="mb-3"></div>

                    <!-- Formulario de reserva -->
                    <form id="reservaForm" method="post" action="./inc/procesar_reserva.php" onsubmit="return validarReserva()">
                        <!-- Otros campos del formulario -->

                        <!-- Campo oculto para almacenar el id de la mesa -->
                        <input type="hidden" id="id_mesa_reserva" name="id_mesa_reserva">

                        <!-- Campo oculto para el estado de la mesa -->
                        <input type="hidden" name="estado_mesa" value="reservado">

                        <!-- Nombre -->
                        <label for="nombre_reserva">Nombre:</label>
                        <input type="text" id="nombre_reserva" name="nombre_reserva" oninput="validarNombre()" required>
                        <p id="nombreError" style="color: red;"></p>

                        <!-- Hora -->
                        <label for="hora_reserva">Hora:</label>
                        <input type="time" id="hora_reserva" name="hora_reserva" oninput="validarHora()" required>
                        <p id="horaError" style="color: red;"></p>

                        <!-- Hora de fin -->
                        <label for="hora_fin_reserva">Hora de fin:</label>
                        <input type="time" id="hora_fin_reserva" name="hora_fin_reserva" oninput="validarHoraFin()" required>
                        <p id="errorFechaFin" style="color: red;"></p>

                        <!-- Día -->
                        <label for="dia_reserva">Día:</label>
                        <input type="date" id="dia_reserva" name="dia_reserva" oninput="validarDia()" required>
                        <p id="diaError" style="color: red;"></p>

                        <!-- ID Sala -->
                        <input type="hidden" name="id_sala" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">

                        <button type="submit" class="btn btn-primary">Enviar Reserva</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script src="./js/mesas.js"></script>



</body>

</html>