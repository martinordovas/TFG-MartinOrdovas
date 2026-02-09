<?php
include 'phpscripts/dbconn.php';
// Si no hay sesión, mandamos al login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkillSwap - Feed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-md-3 col-lg-2 d-md-block sidebar">
                <div class="position-sticky">
                    <h3 class="px-3 mb-4 text-primary">SkillSwap</h3>
                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link active text-dark" href="index.php">
                                <i class="bi bi-house-door-fill me-2"></i> Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="buscar.php">
                                <i class="bi bi-search me-2"></i> Explorar
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="mensajes.php">
                                <i class="bi bi-chat-dots me-2"></i> Mensajes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link text-dark" href="perfil.php">
                                <i class="bi bi-person-circle me-2"></i> Perfil
                            </a>
                        </li>
                        <hr>
                        <li class="nav-item">
                            <a class="nav-link text-danger" href="phpscripts/logout.php">
                                <i class="bi bi-box-arrow-right me-2"></i> Salir
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 main-content">
                <div class="row justify-content-center">
                    <div class="col-md-8">

                        <div class="card mb-4 shadow-sm">
                            <div class="card-body">
                                <form action="phpscripts/publicar.php" method="POST">
                                    <textarea name="contenido" class="form-control mb-2" rows="3"
                                        placeholder="¿Qué necesitas o qué ofreces hoy?"></textarea>
                                    <div class="text-end">
                                        <button type="submit" class="btn btn-primary btn-sm">Publicar</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <h5 class="mb-3">Publicaciones recientes</h5>

                        <?php
                        // Consulta mejorada para incluir el ID del usuario
                        $query = "SELECT p.contenido, p.fecha_publicacion, u.id AS usuario_id, u.nombre, u.habilidades, u.avatar 
            FROM publicaciones p 
            JOIN usuarios u ON p.id_usuario = u.id 
            ORDER BY p.fecha_publicacion DESC";

                        $res = mysqli_query($conn, $query);

                        while ($post = mysqli_fetch_assoc($res)) {
                            // Lógica para imagen por defecto
                            $fotoPerfil = (!empty($post['avatar']) && file_exists("img/" . $post['avatar']))
                                ? $post['avatar']
                                : "user.png";
                            ?>
                            <div class="card mb-3 shadow-sm">
                                <div class="card-body">
                                    <div class="d-flex align-items-center mb-2">
                                        <a href="perfil.php?id=<?php echo $post['usuario_id']; ?>">
                                            <img src="assets/img/<?php echo $fotoPerfil; ?>" class="rounded-circle me-2"
                                                width="45" height="45" alt="Perfil">
                                        </a>
                                        <div>
                                            <h6 class="mb-0">
                                                <a href="perfil.php?id=<?php echo $post['usuario_id']; ?>"
                                                    class="text-decoration-none text-dark fw-bold">
                                                    <?php echo htmlspecialchars($post['nombre']); ?>
                                                </a>
                                            </h6>
                                            <small class="text-muted">
                                                <?php echo date("d/m/Y H:i", strtotime($post['fecha_publicacion'])); ?>
                                            </small>
                                        </div>
                                    </div>
                                    <p class="card-text">
                                        <?php echo htmlspecialchars($post['contenido']); ?>
                                    </p>
                                </div>
                            </div>
                            <?php
                        }
                        ?>

                    </div>
                </div>
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>