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
            <b>¡Bienvenido al portal, <?php echo $_SESSION['user'];?>!</b>
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
            if ($mesa['estado'] == "ocupada" || $mesa['estado'] == "reservado" ) {
                echo '<img class="filtro" src="./img/mesas.png" alt="Imagen 1">';
                echo '<div class="image-text"><h2> Mesa'.$mesa['numero_mesa'].'</h2>';
                echo '<p class="diss">'.$mesa['estado'].'</p>';
                $clase = 'class ="btn2 danger  btn-block" value="Desocupar" ';
            } else {
                echo '<img class="" src="./img/mesas.png" alt="Imagen 1">';
                echo '<div class="image-text"><h2> Mesa'.$mesa['numero_mesa'].'</h2>';
                echo '<p>'.$mesa['estado'].'</p>';
                $clase = 'class ="btn2 success btn-block" value="Ocupar" ';
            }
            if($mesa['estado'] == "reservado"){
                echo "<form action='./inc/procesar_reserva.php' method='post'>";
                echo "<input type='hidden' name='id_sala' value=".$mesa['id_sala'].">";
            echo "<input type='hidden' name='id_mesa' value=".$mesa['id_mesa'].">";
            echo "<input type='hidden' name='numero_mesa' value=".$mesa['numero_mesa'].">";
            echo "<input type='hidden' name='desreserva' value='desreserva'>";
            }else{
                echo "<form method='POST' action='./inc/procesar.php'>";
                echo "<input type='hidden' name='id_sala' value=".$mesa['id_sala'].">";
                echo "<input type='hidden' name='id_mesa' value=".$mesa['id_mesa'].">";
                echo "<input type='hidden' name='numero_mesa' value=".$mesa['numero_mesa'].">";
            }
            
            
            if($mesa['estado'] == "reservado"){
               
                echo "<input class='btn2 danger btn-block' value='Desreservar' type='submit'>";
            }else{
                echo "<input ".$clase." type='submit'>";

                echo "<input class='btn2 success btn-block' value='Reservar' type='button' onclick='mostrarReservaModal(\"{$mesa['id_mesa']}\", \"{$mesa['numero_mesa']}\")'>";
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
    <!-- Modal de Reserva -->
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
                <form id="reservaForm" method="post" action="./inc/procesar_reserva.php">
                    <!-- Otros campos del formulario -->
                    <input type="hidden" name="idSala" id="idSala" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">

                    <!-- Campo oculto para almacenar el id de la mesa -->
                    <input type="hidden" id="id_mesa_reserva" name="id_mesa_reserva">

                    <!-- Campo oculto para el estado de la mesa -->
                    <input type="hidden" name="estado_mesa" value="reservado">

                    <!-- Nuevos campos para la reserva -->
                    <div class="mb-3">
                        <label for="nombre_reserva" class="form-label">Nombre de la Reserva</label>
                        <input type="text" class="form-control" id="nombre_reserva" name="nombre_reserva">
                    </div>

                    <div class="mb-3">
                        <label for="hora_reserva" class="form-label">Hora de Inicio de la Reserva</label>
                        <input type="time" class="form-control" id="hora_reserva" name="hora_reserva">
                    </div>

                    <div class="mb-3">
                        <label for="hora_fin_reserva" class="form-label">Hora de Fin de la Reserva</label>
                        <input type="time" class="form-control" id="hora_fin_reserva" name="hora_fin_reserva">
                    </div>

                    <div class="mb-3">
                        <label for="dia_reserva" class="form-label">Día de la Reserva</label>
                        <input type="date" class="form-control" id="dia_reserva" name="dia_reserva">
                    </div>

                    <button type="submit" class="btn btn-primary">Enviar Reserva</button>
                </form>
            </div>
        </div>
    </div>
</div>



    <script>
   function mostrarReservaModal(idMesa, nombreMesa, idSala) {
    $("#id_mesa_reserva").val(idMesa);
    $("#infoMesa").html("<p>Reservando mesa: " + nombreMesa + "</p>");
    $("#reservaModal").modal("show");
}
</script>


</body>
</html>
