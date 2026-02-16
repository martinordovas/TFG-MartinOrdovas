<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user_id'];
// Usuario con el que estamos hablando
$contacto_id = isset($_GET['id']) ? intval($_GET['id']) : null;
if ($contacto_id) {
    // Marcamos como leídos los mensajes que me envió este contacto
    $update_leido = $conn->prepare("UPDATE mensajes SET leido = 1 WHERE id_emisor = ? AND id_receptor = ?");
    $update_leido->bind_param("ii", $contacto_id, $mi_id);
    $update_leido->execute();
}

// 1. Lógica para ENVIAR mensaje
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['enviar_msg'])) {
    $msg = trim($_POST['mensaje']);
    $receptor = intval($_POST['receptor_id']);

    if (!empty($msg)) {
        $ins = $conn->prepare("INSERT INTO mensajes (id_emisor, id_receptor, mensaje) VALUES (?, ?, ?)");
        $ins->bind_param("iis", $mi_id, $receptor, $msg);
        $ins->execute();
        // Recargamos para ver el mensaje nuevo
        header("Location: mensajes.php?id=" . $receptor);
        exit();
    }
}

// 2. Obtener lista de personas con las que he hablado (Bandeja de entrada)
// Esta consulta es un poco "pro", agrupa los mensajes para sacar los contactos únicos
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