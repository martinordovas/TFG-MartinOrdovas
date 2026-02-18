<?php
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user_id'];
$vista = isset($_GET['view']) ? $_GET['view'] : 'recientes';

if ($vista === 'siguiendo') {
    $sql = "SELECT p.*, u.nombre, u.avatar, u.habilidades 
            FROM publicaciones p
            JOIN usuarios u ON p.id_usuario = u.id
            JOIN seguidores s ON u.id = s.id_seguido
            WHERE s.id_seguidor = ?
            ORDER BY p.fecha_publicacion DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mi_id);
} else {
    $sql = "SELECT p.*, u.nombre, u.avatar, u.habilidades 
            FROM publicaciones p
            JOIN usuarios u ON p.id_usuario = u.id
            ORDER BY p.fecha_publicacion DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$res_posts = $stmt->get_result();
?>