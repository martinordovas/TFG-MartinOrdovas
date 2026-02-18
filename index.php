<?php
include 'phpscripts/dbconn.php';
include 'phpscripts/indexLogic.php';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio - Skill-net</title>
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
                <div class="container mt-4" style="max-width: 700px;">
                    <h5 class="mb-3">Publicaciones recientes</h5>
                    <div class="feed">
                        <?php if ($res_posts->num_rows > 0): ?>
                            <?php while ($post = $res_posts->fetch_assoc()): ?>
                                <div class="mb-4">
                                    <div class="post-design pb-0">
                                        <div class="d-flex align-items-center mb-3 usr-info">
                                            <a href="perfil.php?id=<?php echo $post['id_usuario']; ?>" class="">
                                                <?php
                                                $fotoPath = "img/user.png";
                                                if (!empty($post['avatar']) && file_exists("img/" . $post['avatar'])) {
                                                    $fotoPath = "img/" . $post['avatar'];
                                                }
                                                ?>
                                                <img src="<?php echo $fotoPath; ?>" class="rounded-circle me-3 fotofeed"
                                                    width="45" height="45">
                                            </a>
                                            <div>
                                                <h6 class="mb-0 fw-bold">
                                                    <a href="perfil.php?id=<?php echo $post['id_usuario']; ?>"
                                                        class="text-decoration-none text-dark">
                                                        <?php echo htmlspecialchars($post['nombre']); ?>
                                                    </a>
                                                </h6>
                                                <div class="mb-1">
                                                    <?php
                                                    $tags = explode(",", $post['habilidades'] ?? '');
                                                    foreach ($tags as $tag) {
                                                        if (!empty(trim($tag))) {
                                                            echo '<span class="me-2 tag-pill px-2">' . htmlspecialchars(trim($tag)) . '</span>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <small class="">
                                                    <?php echo date("d M, H:i", strtotime($post['fecha_publicacion'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <p class="fs-6 usr-post">
                                            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                            <div class="finalpadding"></div>
                        <?php else: ?>
                            <div class="text-center p-5 bg-white rounded shadow-sm">
                                <i class="bi bi-emoji-frown display-1"></i>
                                <p class="mt-3 text-muted">No hay publicaciones aquí todavía. ¡Sigue a alguien para empezar!
                                </p>
                                <a href="buscar.php" class="">Explorar usuarios</a>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
                <div class="publish-box shadow-lg">
                    <div class="view-tabs d-flex justify-content-center gap-2 mb-2">
                        <a href="index.php?view=recientes"
                            class="tab-item <?php echo ($vista === 'recientes') ? 'active' : ''; ?>">
                            Explorar
                        </a>
                        <a href="index.php?view=siguiendo"
                            class="tab-item <?php echo ($vista === 'siguiendo') ? 'active' : ''; ?>">
                            Siguiendo
                        </a>
                    </div>

                    <form action="phpscripts/publicar.php" method="POST" class="d-flex align-items-center gap-2">
                        <textarea name="contenido" class="form-control" rows="1" placeholder="¿Qué necesitas hoy?"
                            style="resize: none;"></textarea>
                        <button type="submit" class="btn-send-msg">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>