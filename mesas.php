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
                    <?php
                    if($_SESSION['id'] == 4){
                        echo "<a href='./admin.php'><button class='atrasboton'><img class='atrasimg' src='./img/admin.png' alt=''></button></a>";
                    }else{
                        echo "<a href='./registro.php'><button class='atrasboton'><img class='atrasimg' src='./img/libro.png' alt=''></button></a>";

                    }
                    ?>

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
<!-- Muestra las mesas -->
    <?php
    if (!isset($_GET['id'])) {
        header("Location: ./home.php");
        exit;
    } else {
        try {
            require './inc/conexion.php';

            $id = trim($_GET['id']);
            $sql = "SELECT m.*, COUNT(r.id_mesa) AS total_reservas
            FROM mesas m
            LEFT JOIN reservas r ON m.id_mesa = r.id_mesa
            WHERE m.id_sala = :id
            GROUP BY m.id_mesa";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_STR);
            $stmt->execute();
            $res = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        echo '<div class="centrado">';

        if($_SESSION['id'] == 4){
           echo "<a style='position: absolute; margin-left: 50%; margin-bottom: 27%;'>
            <button class='atrasboton' onclick='mostrarFormularioAgregarMesa()'>
                <img class='atrasimg' src='./img/plus.png' alt=''>
            </button>
        </a>";
        
        }
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
                if($_SESSION['id'] !== 4 ){
                    $clase = 'class ="btn2 danger  btn-block" value="Desocupar" ';

                }else{
                    $clase = 'class ="btn2 danger  btn-block" value="Desocupar" hidden';

                }
            } else {
                if ($mesa['estado'] == "mantenimiento") {
                    echo '<img class="filtro2" src="./img/mesas.png" alt="Imagen 1">';
                } else {
                    echo '<img class="" src="./img/mesas.png" alt="Imagen 1">';
                }
                echo '<div class="image-text"><h2> Mesa: ' . $mesa['numero_mesa'] . '</h2>';
                echo '<p><b>Estado: </b>' . $mesa['estado'] . '</p>';
            
                if ($_SESSION['user'] == 'mantenimiento' ) {
                    echo '<form method="POST" action="./inc/sillasrotas.php?id=' . $mesa['numero_mesa'] . '">';
                     echo "<input type='hidden' name='id_sala' value=" . $mesa['id_sala'] . ">";
                    echo "<input type='hidden' name='id_mesa' value=" . $mesa['id_mesa'] . ">";
                    echo "<input type='hidden' name='sillas' value=" . $mesa['sillas'] . ">";
                    echo "<input type='hidden' name='sillas_mantenimiento' value=" . $mesa['sillas_mantenimiento'] . ">";
                    echo '<div class="d-flex align-items-center justify-content-between">';
                    echo '<p><b>Sillas rotas: </b>' . $mesa['sillas_mantenimiento'] . '</p>';
                    echo '<div>';
                    echo '<span>';
                    if($mesa['sillas'] !== 0){
                    echo '<button style="margin-bottom: 30%;" type="submit" name="add_silla" class="btn btn-success">+</button>';
                    }
                    echo '  ';
                    if($mesa['sillas_mantenimiento'] !== 0){
                        echo '<button style="margin-bottom: 30%" type="submit" name="remove_silla" class="btn btn-danger">-</button>';

                    }
                    echo '</span>';
                    echo '</div>';

                    echo '</div>';
                    echo '</form>';
                    if($mesa['sillas'] == 0){
                        echo '<p style="text-align: center;"><b>Todas las sillas estan rotas</b></p>';
                        }
                        if($_SESSION['id'] == 4 ){
                            $clase = 'hidden';

                        }elseif ($mesa['estado'] == 'mantenimiento') {
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
                    echo '<p><b>Total Reservas: </b>' . $mesa['total_reservas'] . '</p>';

                    echo '<span>';
                    if($_SESSION['id'] == 4 ){

                    echo '<button style="margin-bottom: 30%;" type="submit" name="add_silla" class="btn btn-success">+</button>';
                    echo '  ';
                    if($mesa['sillas'] !== 0){
                    echo '<button style="margin-bottom: 30%" type="submit" name="remove_silla" class="btn btn-danger">-</button>';
                    }
                    echo '  ';
                    echo '<button style="margin-bottom: 30%;" type="submit" name="borrar_mesa" class="btn btn-outline-danger">🗑️</button>';
                }
                    echo '</span>';
                    echo '</div>';

                    echo '</div>';
                    echo '</form>';
                    if($_SESSION['id'] == 4 ){
                        $clase = 'hidden';

                    }else{
                        $clase = 'class ="btn2 success btn-block" value="Ocupar" ';

                    }
                } else {
                    if($_SESSION['id'] == 4 ){
                        $clase = 'hidden';

                    }else{
                    $clase = 'class ="btn btn-secondary btn-block" value="Mantenimiento" disabled';
                    }
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
                    if($_SESSION['id'] !== 4 ){
                        echo "<input class='btn2 danger btn-block' value='Desreservar' type='submit'>";

                    }
                }
            } elseif ($_SESSION['user'] !== 'mantenimiento') {
                echo "<input " . $clase . " type='submit'>";
                if ($mesa['estado'] !== "mantenimiento") {
                    if($_SESSION['id'] !== 4 ){
                    echo "<input class = 'btn2 danger btn-block' name='estadoSilla' value='Mantenimiento' type='submit'>";
                    }
                }

                if ($mesa['estado'] !== "mantenimiento") {
                    if($_SESSION['id'] !== 4 ){
                    echo "<input class='btn2 success btn-block' value='Reservar' type='button' onclick='mostrarReservaModal(\"{$mesa['id_mesa']}\", \"{$mesa['numero_mesa']}\")'>";
                    }
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
    <!-- Dentro de la sección head de tu HTML -->
<script>
   function mostrarFormularioAgregarMesa() {
    // Crea un formulario básico con campos para número de mesa, sillas e id_sala
    let formHtml = `
        <form id="formularioAgregarMesa">
            <label for="numeroMesa">Número de Mesa:</label>
            <input type="text" id="numeroMesa" name="numeroMesa" required>

            <br><br><label for="sillas">Número de Sillas:</label>
            <input type="text" id="sillas" name="sillas" required>

            <br><br><label for="idSala">ID de Sala:</label>
            <select id="idSala" name="idSala" required>
                <option value="1">Terraza 1</option>
                <option value="2">Terraza 2</option>
                <option value="3">Terraza 3</option>
            </select>

            <br><br><button class="btn btn-success" type="button" onclick="agregarMesa()">Guardar</button>
        </form>
    `;

    // Muestra el formulario usando SweetAlert2
    Swal.fire({
        title: 'Agregar Mesa',
        html: formHtml,
        showCancelButton: true,
        showConfirmButton: false
    });
}


    function agregarMesa() {
    // Obtén los valores del formulario
    let numeroMesa = document.getElementById('numeroMesa').value;
    let sillas = document.getElementById('sillas').value;
    let idSala = document.getElementById('idSala').value;

    // Realiza la lógica para agregar la mesa (puedes hacerlo con AJAX o recargar la página)
    agregarMesaAjax(numeroMesa, sillas, idSala);

    // Cierra el SweetAlert2
    Swal.close();
}

const READY_STATE_COMPLETE = 4;

    function agregarMesaAjax(numeroMesa, sillas, idSala) {
    var agregarMesa = 'agregarMesa';
    var formDataMesa = new FormData();
    formDataMesa.append('numeroMesa', numeroMesa);
    formDataMesa.append('sillas', sillas);
    formDataMesa.append('idSala', idSala);
    let http_request_agregar_mesa = new XMLHttpRequest();

    // Abre una conexión con el servidor para enviar la solicitud
    http_request_agregar_mesa.open('POST', './inc/agregar_mesa.php');

    http_request_agregar_mesa.onreadystatechange = function() {
        if (http_request_agregar_mesa.readyState == READY_STATE_COMPLETE) {
            console.log('readystateok');
            if (http_request_agregar_mesa.status == 200 && http_request_agregar_mesa.responseText == 'ok') {
                console.log('readystateok2');
                Swal.fire({
                    title: 'Mesa agregada!',
                    icon: "success",
                    showConfirmButton: false,
                    timer: 1500
                });

                
                // Puedes agregar aquí la lógica para recargar o actualizar la tabla de mesas
                window.location.reload();
            } else if (http_request_agregar_mesa.responseText == 'repetido') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'El número de mesa ya existe. Por favor, elige otro número.',
                    showConfirmButton: false,
                    timer: 3000
                });
            } else if (http_request_agregar_mesa.responseText == 'max_sillas_excedido') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Número máximo de sillas por mesa (10) excedido.',
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        }
    };
    http_request_agregar_mesa.send(formDataMesa);
}


const urlParams = new URLSearchParams(window.location.search);
    const errorParam = urlParams.get('error');
    const errorMsg = urlParams.get('msg');

    if (errorParam && errorMsg) {
        // Muestra SweetAlert con el mensaje de error
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: decodeURIComponent(errorMsg),
        });
    }
</script>




</body>

</html>