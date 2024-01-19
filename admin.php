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
require_once './inc/conexion.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RICK DECKARD - USUARIOS</title>
    <link rel="shortcut icon" href="./img/LOGORICK.png" type="image/x-icon">
    <link rel="stylesheet" href="./css/home.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="shortcut icon" href="./img/LOGORICK.png" type="image/x-icon">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                <a href="./mostra.php"><button class="atrasboton"><img class="atrasimg" src="./img/atras.png" alt=""></button></a>
                <a href="./inc/salir.php"><button class="logoutboton"><img class="logoutimg" src="./img/LOGOUT.png" alt=""></button></a>
            </div>
        </div>
    </nav>
    <div class="container mt-3">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: white;">Historial de Usuarios</h2>
            <button class="btn btn-success" onclick="mostrarFormulario()">Añadir Usuario</button>
        </div>
        <table class="table table-bordered table-striped">
            <thead style="background-color: black;">
                <tr style="color: white">
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Apellido</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody style="background-color: white;">
                <?php
                try {
                    // Consulta para obtener usuarios desde la base de datos
                    $query = "SELECT id_usuario, nombre_user, apellido FROM usuarios";
                    $stmt = $conn->prepare($query);
                    $stmt->execute();

                    // Procesa los resultados
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
            </tbody>
        </table>
        <!-- Script para manejar el formulario -->
        <script>
            var READY_STATE_COMPLETE = 4;

            function mostrarFormulario() {
                // Crea un formulario básico con campos para nombre, apellido y contraseña
                let formHtml = `
                    <form id="formularioUsuario">
                        <label for="nombre">Nombre:</label>
                        <input type="text" id="nombre" name="nombre" required>

                        </br><label for="apellido">Apellido:</label>
                        <input type="text" id="apellido" name="apellido" required>

                        </br><label for="password">Contraseña:</label>
                        <input type="password" id="password" name="password" required>

                        </br><button class="btn btn-success" type="button" onclick="agregarUsuario()">Guardar</button>
                    </form>
                `;

                // Muestra el formulario usando SweetAlert2
                Swal.fire({
                    title: 'Añadir Usuario',
                    html: formHtml,
                    showCancelButton: true,
                    showConfirmButton: false
                });
            }

            function agregarUsuario() {
                // Obtén los valores del formulario
                let nombre = document.getElementById('nombre').value;
                let apellido = document.getElementById('apellido').value;
                let password = document.getElementById('password').value;

                // Realiza la lógica para agregar el usuario (puedes hacerlo con AJAX o recargar la página)
                // ...
                EnviarSolicitud(nombre, apellido, password);

                // Cierra el SweetAlert2
                Swal.close();
            }

            function EnviarSolicitud(nombre, apellido, password) {
                var agregarAmigo = 'agregarAmigo';
                var formDataSolicitud = new FormData();
                formDataSolicitud.append('nombre', nombre);
                formDataSolicitud.append('apellido', apellido);
                formDataSolicitud.append('password', password);
                let http_request_crear_solicitud = new XMLHttpRequest();

                // Abre una conexión con el servidor para enviar la solicitud
                http_request_crear_solicitud.open('POST', './inc/añadir.php');

                http_request_crear_solicitud.onreadystatechange = function() {
                    if (http_request_crear_solicitud.readyState == READY_STATE_COMPLETE) {
                        console.log('readystateok');
                        if (http_request_crear_solicitud.status == 200) {
                            console.log('readystateok2');
                            Swal.fire({
                                title: 'Solicitud enviada!',
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });
                            
                            // Recargar la tabla después de agregar el usuario
                            cargarTablaUsuarios();
                        }
                    } else {
                        // Mensaje error solicitud no enviada
                        Swal.fire({
                            icon: 'error',
                            title: 'Algo ha salido mal',
                            text: 'Solicitud no enviada, inténtelo de nuevo más tarde..',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
                http_request_crear_solicitud.send(formDataSolicitud);
            }

            function EnviarSolicitudEliminar(idUsuario) {
                var eliminarUsuario = 'eliminarUsuario';
                var formDataEliminar = new FormData();
                formDataEliminar.append('idUsuario', idUsuario);
                let http_request_eliminar_usuario = new XMLHttpRequest();

                // Abre una conexión con el servidor para enviar la solicitud
                http_request_eliminar_usuario.open('POST', './inc/eliminar.php');

                http_request_eliminar_usuario.onreadystatechange = function() {
                    if (http_request_eliminar_usuario.readyState == READY_STATE_COMPLETE) {
                        console.log('readystateok');
                        if (http_request_eliminar_usuario.status == 200 && http_request_eliminar_usuario.responseText == 'ok') {
                            console.log('readystateok2');
                            Swal.fire({
                                title: 'Usuario eliminado!',
                                icon: "success",
                                showConfirmButton: false,
                                timer: 1500
                            });

                            // Recargar la tabla después de eliminar el usuario
                            cargarTablaUsuarios();
                        }
                    } else {
                        // Mensaje de error si la solicitud no se envía correctamente
                        Swal.fire({
                            icon: 'error',
                            title: 'Algo ha salido mal',
                            text: 'Solicitud no enviada, inténtelo de nuevo más tarde..',
                            showConfirmButton: false,
                            timer: 1500
                        });
                    }
                }
                http_request_eliminar_usuario.send(formDataEliminar);
            }

            // Función para cargar la tabla de usuarios
            function cargarTablaUsuarios() {
                var ajax = new XMLHttpRequest();
                ajax.onreadystatechange = function() {
                    if (ajax.readyState == 4 && ajax.status == 200) {
                        // Actualizar el contenido de la tabla con la respuesta AJAX
                        document.getElementsByTagName("tbody")[0].innerHTML = ajax.responseText;
                    }
                };
                ajax.open("GET", "./inc/cargar_tabla_usuarios.php", true);
                ajax.send();
            }

            function editarUsuario(idUsuario) {
        // Realiza una solicitud AJAX para obtener los datos del usuario por su id
        fetch(`./inc/obtener_usuario.php?idUsuario=${idUsuario}`)
            .then(response => response.json())
            .then(userData => mostrarFormularioEdicion(userData))
            .catch(error => console.error('Error al obtener datos del usuario:', error));
    }

    function mostrarFormularioEdicion(userData) {
        // Crea un formulario con campos prellenados
        let formHtml = `
            <form id="formularioEdicion">
                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" value="${userData.nombre_user}" required>

                </br><label for="apellido">Apellido:</label>
                <input type="text" id="apellido" name="apellido" value="${userData.apellido}" required>

                </br><label for="nuevaPassword">Nueva Contraseña:</label>
                <input type="password" id="nuevaPassword" name="nuevaPassword" required>

                </br><label for="confirmarPassword">Confirmar Contraseña:</label>
                <input type="password" id="confirmarPassword" name="confirmarPassword" required>

                </br><button class="btn btn-primary" type="button" onclick="guardarEdicionUsuario(${userData.id_usuario});">Guardar</button>
            </form>
        `;

        // Muestra el formulario usando SweetAlert2
        Swal.fire({
            title: 'Editar Usuario',
            html: formHtml,
            showCancelButton: true,
            showConfirmButton: false
        });
    }

    function guardarEdicionUsuario(idUsuario) {
    // Obtén los valores del formulario
    let nombre = document.getElementById('nombre').value;
    let apellido = document.getElementById('apellido').value;
    let nuevaPassword = document.getElementById('nuevaPassword').value;
    let confirmarPassword = document.getElementById('confirmarPassword').value;

    // Verifica que las contraseñas sean iguales
    if (nuevaPassword !== confirmarPassword) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Las contraseñas no coinciden',
            showConfirmButton: false,
            timer: 1500
        });
        return;
    }

    // Realiza la lógica para guardar la edición del usuario mediante una petición AJAX
    var http_request_editar_usuario = new XMLHttpRequest();
    var url_editar_usuario = './inc/editarusuario.php';
    var params_editar_usuario = 'idUsuario=' + idUsuario + '&nombre=' + nombre + '&apellido=' + apellido + '&nuevaPassword=' + nuevaPassword;

    http_request_editar_usuario.open('POST', url_editar_usuario, true);
    http_request_editar_usuario.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http_request_editar_usuario.onreadystatechange = function () {
        if (http_request_editar_usuario.readyState == READY_STATE_COMPLETE) {
            if (http_request_editar_usuario.status == 200) {
                // if (http_request_editar_usuario.responseText == 'ok') {
                    // Cierra el SweetAlert2
                    Swal.close();
                    // Muestra un mensaje de éxito
                    Swal.fire({
                        title: 'Usuario editado!',
                        icon: "success",
                        showConfirmButton: false,
                        timer: 1500
                    });
                    // Recargar la tabla después de editar el usuario
                    cargarTablaUsuarios();
                // }
                
            } else {
                // Manejo de errores, puedes personalizar esto según tus necesidades
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error al editar el usuario',
                    showConfirmButton: false,
                    timer: 1500
                });
            }
        }
    };

    http_request_editar_usuario.send(params_editar_usuario);
}

    
        </script>
    </div>
</body>

</html>
