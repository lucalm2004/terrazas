<?php
// session_start();
// if (!isset($_SESSION['id'])) {
//     header("Location: ./index.php");
//     exit;
// } else if (isset($_GET['logout'])) {
//     session_destroy();
//     header("Location: ./index.php");
//     exit;
// }


if (!filter_has_var(INPUT_POST, 'inicio')) {
    header('Location: ../index.php');
    exit();
} else {
    include_once("./conexion.php");

    $user = $_POST["user"];
    $password = $_POST["password"];

    if (empty($user) || empty($password)) {
        header("Location: ../index.php?empty");
        exit();
    } else {
        try{

            $query = "SELECT id_usuario, nombre_user, contrasena FROM usuarios WHERE nombre_user = :nombre_user";
            $stmt = $conn->prepare($query);
            $stmt->bindParam(':nombre_user', $user);
            $stmt->execute();
            $resultadoConsulta = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $stmt->closeCursor();

            foreach ($resultadoConsulta as $fila) {
                $id_usuario = $fila['id_usuario'];
                $nombre_user = $fila['nombre_user']; 
                $contrasena = $fila['contrasena'];
                }

                if (password_verify($password, $contrasena)) {
                    session_start();
                    $_SESSION["id"] = $id_usuario;
                    $_SESSION["user"] = $nombre_user;
                    header("Location: ../home.php?username=$nombre_user");
                    exit();
                        } else {
                            header("Location: ../index.php?error");
                            exit();
                        }
        }catch(PDOException $e){
            echo "Error in the database connection" . $e->getMessage();
                $conn = null;
                die();
        }
    }
}
?>
