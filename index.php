<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$mi_id = $_SESSION['user_id'];
// Detectamos qué pestaña quiere ver el usuario (por defecto 'recientes')
$vista = isset($_GET['view']) ? $_GET['view'] : 'recientes';

// PREPARAMOS LA CONSULTA SEGÚN LA VISTA
if ($vista === 'siguiendo') {
    // Añadimos u.habilidades a la selección
    $sql = "SELECT p.*, u.nombre, u.avatar, u.habilidades 
            FROM publicaciones p
            JOIN usuarios u ON p.id_usuario = u.id
            JOIN seguidores s ON u.id = s.id_seguido
            WHERE s.id_seguidor = ?
            ORDER BY p.fecha_publicacion DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $mi_id);
} else {
    // Añadimos u.habilidades a la selección
    $sql = "SELECT p.*, u.nombre, u.avatar, u.habilidades 
            FROM publicaciones p
            JOIN usuarios u ON p.id_usuario = u.id
            ORDER BY p.fecha_publicacion DESC";
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$res_posts = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
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
                                    <div class="">
                                        <div class="d-flex align-items-center mb-3">
                                            <a href="perfil.php?id=<?php echo $post['id_usuario']; ?>" class="">
                                                <?php
                                                // Definimos la foto: si hay nombre en DB y el archivo existe físicamente
                                                $fotoPath = "img/user.png"; // Por defecto
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
                                                            echo '<span class="me-1 px-3">' . htmlspecialchars(trim($tag)) . '</span>';
                                                        }
                                                    }
                                                    ?>
                                                </div>
                                                <small class="">
                                                    <?php echo date("d M, H:i", strtotime($post['fecha_publicacion'])); ?>
                                                </small>
                                            </div>
                                        </div>
                                        <p class="fs-5">
                                            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endwhile; ?>
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
                        <button type="submit" class="">
                            <i class="bi bi-send-fill"></i>
                        </button>
                    </form>
                </div>
            </main>
        </div>
    </div>
</body>

</html>