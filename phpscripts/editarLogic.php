<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nombre, habilidades, bio, avatar FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $bio = $_POST['bio'];
    $habilidades = $_POST['habilidades'];
    $nombre_foto = $datos['avatar'];

    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nuevo_nombre = "perfil_" . $user_id . "_" . time() . "." . $extension;
        $ruta_destino = "img/" . $nuevo_nombre;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $nombre_foto = $nuevo_nombre;
        }
    }
    $update = $conn->prepare("UPDATE usuarios SET nombre = ?, bio = ?, habilidades = ?, avatar = ? WHERE id = ?");
    $update->bind_param("ssssi", $nombre, $bio, $habilidades, $nombre_foto, $user_id);

    if ($update->execute()) {
        header("Location: perfil.php?success=updated");
        exit();
    }
}