<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user_id'];
$contacto_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($contacto_id) {
    $update_leido = $conn->prepare("UPDATE mensajes SET leido = 1 WHERE id_emisor = ? AND id_receptor = ?");
    $update_leido->bind_param("ii", $contacto_id, $mi_id);
    $update_leido->execute();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar_msg'])) {
    $msg = trim($_POST['mensaje']);
    $receptor = intval($_POST['receptor_id']);

    if (!empty($msg)) {
        $ins = $conn->prepare("INSERT INTO mensajes (id_emisor, id_receptor, mensaje) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $mi_id, $receptor, $msg);
        $ins->execute();
        header("Location: mensajes.php?id=" . $receptor);
        exit();
    }
}

$sql_contactos = "SELECT DISTINCT u.id, u.nombre, u.avatar 
                  FROM usuarios u 
                  JOIN mensajes m ON (u.id = m.id_emisor OR u.id = m.id_receptor) 
                  WHERE (m.id_emisor = ? OR m.id_receptor = ?) AND u.id != ?
                  ORDER BY u.nombre ASC";
$stmt_c = $conn->prepare($sql_contactos);
$stmt_c->bind_param("iii", $mi_id, $mi_id, $mi_id);
$stmt_c->execute();
$contactos = $stmt_c->get_result();
?>