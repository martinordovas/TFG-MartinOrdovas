<?php include 'phpscripts/perfilLogic.php'; ?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($user_perfil['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include("phpscripts/navbar.php"); ?>
            <main class="main-content">
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-10 col-lg-8">

                            <div class="card usr-search mb-4">
                                <div class="card-body p-4 text-center">
                                    <img src="img/<?php echo $fotoPerfil; ?>"
                                        class="rounded-circle mb-3 img-perfil-crop img-perfil-grande mx-auto">

                                    <h2 class="fw-bold text-dark mb-1">
                                        <?php echo htmlspecialchars($user_perfil['nombre']); ?>
                                    </h2>

                                    <div class="mb-3">
                                        <?php
                                        $rating = round($stats['promedio'] ?? 0);
                                        for ($i = 1; $i <= 5; $i++)
                                            echo ($i <= $rating) ? '<i class="bi bi-star-fill text-warning"></i>' : '<i class="bi bi-star text-muted"></i>';
                                        ?>
                                        <span class="ms-2 text-black-50">(<?php echo $stats['total']; ?>
                                            valoraciones)</span>
                                    </div>

                                    <div class="mb-4">
                                        <?php
                                        $tags = explode(",", $user_perfil['habilidades'] ?? '');
                                        foreach ($tags as $tag)
                                            if (!empty(trim($tag)))
                                                echo '<span class="tag-pill me-1 p-2 px-3 d-inline-block mb-1">' . htmlspecialchars(trim($tag)) . '</span>';
                                        ?>
                                    </div>
                                    <?php if ($perfil_id != $mi_id): ?>
                                        <form action="" method="POST" class="mb-4">
                                            <button type="submit" name="accion_follow"
                                                value="<?php echo ($seguido ? 'unfollow' : 'seguir'); ?>"
                                                class="btn btn-sm <?php echo ($seguido ? 'btn-outline-danger' : 'btn-outline-primary'); ?> rounded-pill px-3">
                                                <i
                                                    class="bi <?php echo ($seguido ? 'bi-person-x' : 'bi-person-plus'); ?> me-1"></i>
                                                <?php echo ($seguido ? 'Dejar de seguir' : 'Seguir usuario'); ?>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                    <div class="d-flex gap-2 justify-content-center">
                                        <?php if ($perfil_id == $mi_id): ?>
                                            <a href="editar_perfil.php"
                                                class="btn-usr-search px-4 text-decoration-none">Editar Perfil</a>
                                        <?php else: ?>
                                            <a href="mensajes.php?id=<?php echo $perfil_id; ?>"
                                                class="btn-usr-search px-4 text-decoration-none">Mensajear</a>
                                            <button class="btn-usr-search px-4" data-bs-toggle="modal"
                                                data-bs-target="#modalVotar">Valorar</button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                            <div class="card usr-search mb-4">
                                <div class="card-body p-4">
                                    <h5 class="fw-bold mb-3"><i
                                            class="bi bi-info-circle me-2 text-primary"></i>Biografía</h5>
                                    <p class="text-dark mb-0">
                                        <?php echo nl2br(htmlspecialchars($user_perfil['bio'] ?? 'Sin biografía disponible.')); ?>
                                    </p>
                                </div>
                            </div>
                            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-star-half me-2"></i>Opiniones de la
                                comunidad</h5>
                            <div class="row mb-4">
                                <?php if ($res_comm->num_rows > 0): ?>
                                    <?php while ($com = $res_comm->fetch_assoc()): ?>
                                        <div class="col-12 mb-2">
                                            <div class="card usr-search border-0">
                                                <div class="card-body p-3">
                                                    <div class="d-flex align-items-center mb-2">
                                                        <img src="img/<?php echo (!empty($com['avatar']) && file_exists('img/' . $com['avatar'])) ? $com['avatar'] : 'user.png'; ?>"
                                                            class="rounded-circle me-2 img-perfil-crop"
                                                            style="width: 30px; height: 30px;">
                                                        <span class="fw-bold small text-dark me-2">
                                                            <?php echo htmlspecialchars($com['nombre']); ?>
                                                        </span>
                                                        <div>
                                                            <?php for ($i = 1; $i <= 5; $i++)
                                                                echo ($i <= $com['puntuacion']) ? '<i class="bi bi-star-fill text-warning small"></i>' : '<i class="bi bi-star text-muted small"></i>'; ?>
                                                        </div>
                                                    </div>
                                                    <?php if (!empty($com['comentario'])): ?>
                                                        <p class="mb-0 text-dark small italic" style="font-style: italic;">
                                                            "
                                                            <?php echo htmlspecialchars($com['comentario']); ?>"
                                                        </p>
                                                    <?php else: ?>
                                                        <p class="mb-0 text-muted small">Sin comentario escrito.</p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <div class="col-12">
                                        <div class="alert alert-light text-center border-0 usr-search">Aún no hay opiniones
                                            de este usuario.</div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <h5 class="fw-bold mb-3 text-dark"><i class="bi bi-chat-left-text me-2"></i>Actividad</h5>
                            <?php
                            $stmt_posts = $conn->prepare("SELECT contenido, fecha_publicacion FROM publicaciones WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
                            $stmt_posts->bind_param("i", $perfil_id);
                            $stmt_posts->execute();
                            $res_posts = $stmt_posts->get_result();

                            if ($res_posts->num_rows > 0) {
                                while ($post = $res_posts->fetch_assoc()) { ?>
                                    <div class="card usr-search mb-3">
                                        <div class="card-body p-3">
                                            <small class="text-black-50 d-block mb-2">
                                                <i
                                                    class="bi bi-calendar3 me-1"></i><?php echo date("d M, Y H:i", strtotime($post['fecha_publicacion'])); ?>
                                            </small>
                                            <p class="mb-0 text-dark"><?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                                            </p>
                                        </div>
                                    </div>
                                <?php }
                            } else {
                                echo '<div class="alert alert-light text-center border-0 usr-search">Sin publicaciones.</div>';
                            } ?>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>

    <div class="modal fade" id="modalVotar" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg" style="border-radius: 20px;">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold">Valorar a <?php echo htmlspecialchars($user_perfil['nombre']); ?>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="perfil.php?id=<?php echo $perfil_id; ?>" method="POST">
                    <div class="modal-body">
                        <label class="form-label d-block text-center mb-3">¿Qué puntuación le das?</label>
                        <div class="d-flex justify-content-center gap-3 mb-4">
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <input type="radio" class="btn-check" name="puntuacion" id="p<?php echo $i; ?>"
                                    value="<?php echo $i; ?>" required>
                                <label class="btn btn-outline-warning rounded-circle"
                                    for="p<?php echo $i; ?>"><?php echo $i; ?></label>
                            <?php endfor; ?>
                        </div>
                        <textarea name="comentario" class="form-control" rows="3"
                            placeholder="Opcional: Deja un comentario sobre tu experiencia colaborando..."></textarea>
                    </div>
                    <div class="modal-footer border-0">
                        <button type="submit" name="votar" class="btn btn-primary w-100 rounded-pill">Enviar
                            Valoración</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>