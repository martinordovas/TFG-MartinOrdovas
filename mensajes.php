<?php include 'phpscripts/mensajesLogic.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mis Mensajes - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/message.css">
</head>

<body>
    <main class="main-content">
        <div class="container mt-4">
            <div class="row">
                <?php include("phpscripts/navbar.php"); ?>
                <div class="col-md-4">
                    <div class="chat-list">
                        <div class="card-header fw-bold chat-title">Mis Chats</div>
                        <div class="list-group chat-name p-2">
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
                                    <button type="submit" name="enviar_msg" class="btn-send-msg"><i
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