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

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Mis Mensajes - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/message.css">
</head>

<body>
    <?php include("phpscripts/navbar.php"); ?>
    <main class="main-content">
        <div class="container mt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="card shadow-sm">
                        <div class="card-header bg-white fw-bold">Mis Chats</div>
                        <div class="list-group list-group-flush">
                            <?php while ($c = $contactos->fetch_assoc()): ?>
                                <a href="mensajes.php?id=<?php echo $c['id']; ?>"
                                    class="list-group-item list-group-item-action <?php echo ($contacto_id == $c['id']) ? 'active' : ''; ?>">
                                    <?php echo htmlspecialchars($c['nombre']); ?>
                                </a>
                            <?php endwhile; ?>
                            <?php if ($contactos->num_rows == 0)
                                echo "<p class='p-3 text-muted small'>No tienes conversaciones abiertas.</p>"; ?>
                        </div>
                    </div>
                </div>

                <div class="col-md-8">
                    <?php if ($contacto_id):
                        // Obtener nombre del contacto
                        $stmt_u = $conn->prepare("SELECT nombre FROM usuarios WHERE id = ?");
                        $stmt_u->bind_param("i", $contacto_id);
                        $stmt_u->execute();
                        $nombre_contacto = $stmt_u->get_result()->fetch_assoc()['nombre'];

                        // Obtener los mensajes entre los dos
                        $stmt_m = $conn->prepare("SELECT * FROM mensajes WHERE 
                            (id_emisor = ? AND id_receptor = ?) OR (id_emisor = ? AND id_receptor = ?) 
                            ORDER BY fecha_envio ASC");
                        $stmt_m->bind_param("iiii", $mi_id, $contacto_id, $contacto_id, $mi_id);
                        $stmt_m->execute();
                        $chat = $stmt_m->get_result();
                        ?>
                        <div class="card shadow-sm">
                            <div class="card-header bg-primary text-white fw-bold">
                                Chat con <?php echo htmlspecialchars($nombre_contacto); ?>
                            </div>
                            <div class="card-body d-flex flex-column chat-container" id="chatBox">
                                <?php while ($m = $chat->fetch_assoc()): ?>
                                    <div class="<?php echo ($m['id_emisor'] == $mi_id) ? 'msg-mio' : 'msg-otro'; ?>">
                                        <div class="small fw-light" style="font-size: 0.75rem;">
                                            <?php echo date("H:i", strtotime($m['fecha_envio'])); ?>
                                        </div>
                                        <?php echo htmlspecialchars($m['mensaje']); ?>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                            <div class="card-footer bg-white">
                                <form action="" method="POST" class="d-flex">
                                    <input type="hidden" name="receptor_id" value="<?php echo $contacto_id; ?>">
                                    <input type="text" name="mensaje" class="form-control me-2"
                                        placeholder="Escribe un mensaje..." required>
                                    <button type="submit" name="enviar_msg" class="btn btn-primary"><i
                                            class="bi bi-send"></i></button>
                                </form>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="text-center mt-5">
                            <i class="bi bi-chat-dots" style="font-size: 4rem; color: #dee2e6;"></i>
                            <p class="text-muted">Selecciona un chat para empezar a colaborar.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
    <script src="js/message.js"></script>
</body>

</html>