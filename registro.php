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
    <title>RICK DECKARD - HISTORIAL</title>
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
    <script src="./js/popup.js" defer></script>
</head>

<body>
    <nav class="navbar navbar-light bg-lights position-top">
        <div class="container">
            <div>
                <a class="navbar-brand " href="./home.php">
                    <img src="./img/LOGORICK _Blanco.png" alt="" width="100" height="90">
                </a>
            </div>
            <div class="saludo">
                <b style="color:white">¡Bienvenido al portal, <?php echo $_SESSION['user']; ?>!</b>
            </div>
            <div>
                <a href="./mostra.php"><button class="atrasboton"><img class="atrasimg" src="./img/atras.png"
                            alt=""></button></a>
                <a href="./inc/salir.php"><button class="logoutboton"><img class="logoutimg" src="./img/LOGOUT.png"
                            alt=""></button></a>
            </div>
        </div>
    </nav>
    <div class="container mt-5">
        <h2 class="mb-4" style="color: white;">Historial de Ocupaciones</h2>

        <form method="get" action="">

            <!-- FILTRO PARA USUARIOS FILTRO PARA USUARIOS FILTRO PARA USUARIOS FILTRO PARA USUARIOS FILTRO PARA USUARIOS FILTRO PARA USUARIOS -->
            <select name="usuario" id="usuario">
                <option value="">Todos los Usuarios</option>
                <!-- Opciones de usuarios aquí -->
                <?php
                try {
                    require_once('./inc/conexion.php');
                    $sqlUser = "SELECT nombre_user FROM usuarios;";
                    $stmtUser = $conn->prepare($sqlUser);
                    $stmtUser->execute();
                    $resultUser = $stmtUser->fetchAll(PDO::FETCH_ASSOC);
                    foreach ($resultUser as $user) {
                        $nom_user = $user['nombre_user'];
                        echo "<option value=\"$nom_user\"";
                        if (isset($_GET['usuario']) && $_GET['usuario'] == $nom_user) {
                            echo " selected";
                        }
                        echo ">$nom_user</option>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>
            <!-- FILTRO PARA SALAS FILTRO PARA SALAS FILTRO PARA SALAS FILTRO PARA SALAS FILTRO PARA SALAS FILTRO PARA SALAS FILTRO PARA SALAS -->
            <select name="sala" id="sala">
                <option value="">Todas las Salas</option>

                <!-- Opciones de salas aquí -->
                <?php
                try {
                    $sqlSalas = "SELECT nombre_sala FROM salas;";
                    $stmtSalas = $conn->prepare($sqlSalas);
                    $stmtSalas->execute();
                    $resultSalas = $stmtSalas->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($resultSalas as $sala) {
                        $nom_sala = $sala['nombre_sala'];
                        echo "<option value=\"$nom_sala\"";
                        if (isset($_GET['sala']) && $_GET['sala'] == $nom_sala) {
                            echo " selected";
                        }
                        echo ">$nom_sala</option>";
                    }
                } catch (PDOException $e) {
                    echo "Error: " . $e->getMessage();
                }
                ?>
            </select>

            <!-- FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS FILTRO PARA MESAS -->
            <select name="mesas" id="mesas">
                <option value="">Todas las Mesas</option>
                <!-- Opciones de usuarios aquí -->
                <?php
                try {
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

            <!-- FILTRO PARA EL NÚMERO DE REGISTROS -->
            <select name="numero_filtro" id="numero_filtro">
                <option value="0">Todos los registros</option>
                <option value="20" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 20) ? 'selected' : ''; ?>>
                    20 registro</option>
                <option value="30" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 30) ? 'selected' : ''; ?>>
                    30 registros</option>
                <option value="40" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 40) ? 'selected' : ''; ?>>
                    40 registros</option>
                <option value="50" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 50) ? 'selected' : ''; ?>>
                    50 registros</option>
                <option value="60" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 60) ? 'selected' : ''; ?>>
                    60 registros</option>
                <option value="70" <?php echo (isset($_GET['numero_filtro']) && $_GET['numero_filtro'] == 70) ? 'selected' : ''; ?>>
                    70 registros</option>
            </select>

            <!-- FILTRO PARA EL ESTADO DE LA SILLA -->
<select name="estado_silla" id="estado_silla">
    <option value="">Todos los Estados</option>
    <option value="libre" <?php echo (isset($_GET['estado_silla']) && $_GET['estado_silla'] == 'libre') ? 'selected' : ''; ?>>Libre</option>
    <option value="ocupado" <?php echo (isset($_GET['estado_silla']) && $_GET['estado_silla'] == 'ocupado') ? 'selected' : ''; ?>>Ocupado</option>
    <option value="mantenimiento" <?php echo (isset($_GET['estado_silla']) && $_GET['estado_silla'] == 'mantenimiento') ? 'selected' : ''; ?>>Mantenimiento</option>
</select>

            <button type="submit">Filtrar</button>
            <button type="submit">
                <a style="text-decoration: none; color: black;" href="./registro.php">Borrar Flitros</a>
            </button>

        </form>

        <?php
        try {
            $numFiltro = isset($_GET['numero_filtro']) ? intval($_GET['numero_filtro']) : 0;

            $sql = "SELECT u.nombre_user, s.nombre_sala, m.numero_mesa, m.estado, o.fecha_inicio, o.fecha_fin,
                TIMEDIFF(o.fecha_fin, o.fecha_inicio) AS duracion_ocupacion
                FROM ocupaciones o 
                INNER JOIN usuarios u ON o.id_usuario = u.id_usuario 
                INNER JOIN mesas m ON o.id_mesa = m.id_mesa 
                INNER JOIN salas s ON s.id_sala = m.id_sala";

            // FILTRO DE SALA POR $_GET
            if (isset($_GET['sala']) && !empty($_GET['sala'])) {
                $salaFilter = $conn->quote($_GET['sala']);
                // Le añadimos a esta fila el filtro WHERE 
                $sql .= " WHERE s.nombre_sala = $salaFilter";
            }

            // FILTRO DE USUARIO POR $_GET
            if (isset($_GET['usuario']) && !empty($_GET['usuario'])) {
                $usuarioFilter = $conn->quote($_GET['usuario']);
                $sql .= (isset($_GET['sala']) && !empty($_GET['sala'])) ? " AND" : " WHERE";
                $sql .= " u.nombre_user = $usuarioFilter";
            }

            // FILTRO DE MESA POR $_GET
            if (isset($_GET['mesas']) && !empty($_GET['mesas'])) {
                $mesaFilter = $conn->quote($_GET['mesas']);
                $sql .= (isset($_GET['mesas']) && !empty($_GET['mesas'])) ? " AND" : " WHERE";
                $sql .= " m.numero_mesa = $mesaFilter";
            }

            // FILTRO DE ESTADO DE LA SILLA POR $_GET
if (isset($_GET['estado_silla']) && !empty($_GET['estado_silla'])) {
    $estadoSillaFilter = $conn->quote($_GET['estado_silla']);
    $sql .= (isset($_GET['sala']) || isset($_GET['usuario']) || isset($_GET['mesas'])) ? " AND" : " WHERE";
    $sql .= " m.estado = $estadoSillaFilter";
}

            // FILTRO NÚMERO REGISTROS
            if ($numFiltro > 0) {
                $sql .= " LIMIT $numFiltro";
            }

            // ACABAMOS EL CÓDIGO (para que se puedan mezclar los filtros, por ejemplo, sala y usuario)
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
                echo '<th>Nombre Usuario</th>';
                echo '<th>Sala</th>';
                echo '<th>Número de Mesa</th>';
                echo '<th>Estado:</th>';
                echo '<th>Fecha Inicio</th>';
                echo '<th>Fecha Fin</th>';
                echo '<th>Duración</th>';
                echo '</tr>';
                echo '</thead>';
                echo '<tbody>';

                // Imprimir resultados
                foreach ($result as $row) {
                    echo '<tr>';
                    echo "<td>{$row['nombre_user']}</td>";
                    echo "<td>{$row['nombre_sala']}</td>";
                    echo "<td>{$row['numero_mesa']}</td>";
                    echo "<td>{$row['estado']}</td>";
                    echo "<td>{$row['fecha_inicio']}</td>";
                    echo "<td>{$row['fecha_fin']}</td>";
                    echo "<td>{$row['duracion_ocupacion']}</td>";
                    echo '</tr>';
                }

                echo '</tbody>';
                echo '</table>';
                echo '</div>';
            } else {
                echo '<a  href="./registro.php">';
                echo '<img src="./img/LOGORICK _Blanco.png" alt="" style="width: 50%; display: block; margin: auto;"><br>';
                echo '</a>';
                echo "<div style='color: white; display: flex; justify-content: center;'>";
                echo "<b style='font-size: 20px;' >¡Oops! Parece que las hamburguesas se han comido los resultados. ¡Intenta con otra combinación!</b>";
                echo "</div>";
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
