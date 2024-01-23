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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tipo'])) {
    $targetDirectory = '../img/';
    // echo $_POST['tipo'];
    $targetName = $_POST['tipo'] . '.jpg';
    $targetFile = $targetDirectory . $targetName;
    // basename($_FILES['file']['name']);
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Verificar si el archivo es una imagen
    $check = getimagesize($_FILES['file']['tmp_name']);
    if ($check !== false) {
        // Permitir solo ciertos formatos de imagen (puedes ajustar esto según tus necesidades)
        if ($imageFileType === 'jpg' || $imageFileType === 'jpeg' || $imageFileType === 'png' || $imageFileType === 'gif') {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $targetFile)) {
                echo json_encode(['success' => true, 'filename' => basename($targetFile)]);
            } else {
                echo json_encode(['success' => false, 'error' => 'Error al mover el archivo']);
            }
        } else {
            echo json_encode(['success' => false, 'error' => 'Formato de imagen no permitido']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'El archivo no es una imagen']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Solicitud no válida']);
}
?>
