<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// 1. PRIMERO definimos quién es el dueño del perfil
$perfil_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];

// 2. DESPUÉS procesamos la lógica de seguir (ahora $perfil_id ya tiene valor)
if (isset($_POST['accion_follow'])) {
    if ($_POST['accion_follow'] == 'seguir') {
        $sql = "INSERT IGNORE INTO seguidores (id_seguidor, id_seguido) VALUES (?, ?)";
    } else {
        $sql = "DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?";
    }
    $stmt_f = $conn->prepare($sql);
    $stmt_f->bind_param("ii", $_SESSION['user_id'], $perfil_id);
    $stmt_f->execute();
    header("Location: perfil.php?id=" . $perfil_id);
    exit();
}

// 3. Comprobamos si ya le sigo para el botón
$stmt_check = $conn->prepare("SELECT id FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?");
$stmt_check->bind_param("ii", $_SESSION['user_id'], $perfil_id);
$stmt_check->execute();
$seguido = $stmt_check->get_result()->num_rows > 0;

// 4. Traemos los datos del usuario
$stmt = $conn->prepare("SELECT nombre, email, habilidades, bio, avatar FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $perfil_id);
$stmt->execute();
$resultado = $stmt->get_result();
$user_perfil = $resultado->fetch_assoc();

if (!$user_perfil) {
    die("Usuario no encontrado.");
}

// Lógica de imagen por defecto
$fotoPerfil = (!empty($user_perfil['avatar']) && file_exists("img/" . $user_perfil['avatar']))
    ? $user_perfil['avatar']
    : "user.png";
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($user_perfil['nombre']); ?> - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <main class="main-content">
        <div class="container mt-4">
            <div class="row justify-content-center">
                <?php include("phpscripts/navbar.php"); ?>
                <div class="col-md-8 col-lg-6">
                    <div class="card shadow-sm mb-4">
                        <div class="card-body text-center p-4">
                            <img src="img/<?php echo $fotoPerfil; ?>"
                                class="rounded-circle mb-3 img-perfil-crop img-perfil-grande mx-auto d-block"
                                alt="Perfil">

                            <h2 class="fw-bold"><?php echo htmlspecialchars($user_perfil['nombre']); ?></h2>
                            <p class=""><?php echo htmlspecialchars($user_perfil['email']); ?></p>

                            <div class="mb-3">
                                <?php
                                $tags = explode(",", $user_perfil['habilidades'] ?? '');
                                foreach ($tags as $tag) {
                                    if (!empty(trim($tag))) {
                                        echo '<span class="badge bg-primary rounded-pill me-1 px-3">' . htmlspecialchars(trim($tag)) . '</span>';
                                    }
                                }
                                ?>
                            </div>

                            <hr>

                            <div class="text-start px-3">
                                <h5 class="fw-bold"><i class="bi bi-info-circle me-2"></i>Sobre mí</h5>
                                <p class="">
                                    <?php echo nl2br(htmlspecialchars($user_perfil['bio'] ?? 'Este usuario aún no tiene biografía.')); ?>
                                </p>
                            </div>

                            <div class="mt-4">
                                <?php if ($perfil_id == $_SESSION['user_id']): ?>
                                    <a href="editar_perfil.php" class="btn btn-outline-secondary w-100">
                                        <i class="bi bi-pencil me-2"></i>Editar Perfil
                                    </a>
                                <?php else: ?>
                                    <a href="mensajes.php?id=<?php echo $perfil_id; ?>" class="btn btn-primary w-100">
                                        <i class="bi bi-chat-fill me-2"></i>Contactar para colaborar
                                    </a>
                                <?php endif; ?>
                            </div>
                            <?php if ($perfil_id != $_SESSION['user_id']): ?>
                                <form action="" method="POST" class="mt-2">
                                    <?php if ($seguido): ?>
                                        <button type="submit" name="accion_follow" value="unfollow"
                                            class="btn btn-primary w-100 mb-2">
                                            <i class="bi bi-person-x me-2"></i>Dejar de seguir
                                        </button>
                                    <?php else: ?>
                                        <button type="submit" name="accion_follow" value="seguir"
                                            class="btn btn-primary w-100 mb-2">
                                            <i class="bi bi-person-plus me-2"></i>Seguir
                                        </button>
                                    <?php endif; ?>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-md-10 col-lg-10">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-chat-left-text me-2"></i>Publicaciones recientes
                    </h5>

                    <?php
                    $stmt_posts = $conn->prepare("SELECT contenido, fecha_publicacion FROM publicaciones WHERE id_usuario = ? ORDER BY fecha_publicacion DESC");
                    $stmt_posts->bind_param("i", $perfil_id);
                    $stmt_posts->execute();
                    $res_posts = $stmt_posts->get_result();

                    if ($res_posts->num_rows > 0) {
                        while ($post = $res_posts->fetch_assoc()) {
                            ?>
                            <div class="card mb-3 shadow-sm border-0">
                                <div class="card-body p-3">
                                    <div class="mb-1">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar3 me-1"></i>
                                            <?php echo date("d/m/Y H:i", strtotime($post['fecha_publicacion'])); ?>
                                        </small>
                                    </div>
                                    <div class="text-start">
                                        <p class="card-text text-dark">
                                            <?php echo nl2br(htmlspecialchars($post['contenido'])); ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    } else {
                        echo '<div class="text-center p-4 bg-white rounded shadow-sm border">
                                <p class="text-muted mb-0">Este usuario aún no ha compartido nada.</p>
                              </div>';
                    }
                    $stmt_posts->close();
                    ?>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>