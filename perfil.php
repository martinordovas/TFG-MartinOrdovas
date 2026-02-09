<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Si hay un ID en la URL, mostramos ese perfil; si no, mostramos el del usuario logueado
$perfil_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id'];

$stmt = $conn->prepare("SELECT nombre, email, habilidades, bio, avatar FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $perfil_id);
$stmt->execute();
$resultado = $stmt->get_result();
$user_perfil = $resultado->fetch_assoc();

if (!$user_perfil) {
    die("Usuario no encontrado.");
}

// Lógica de imagen por defecto
$fotoPerfil = (!empty($user_perfil['avatar']) && file_exists("assets/img/" . $user_perfil['avatar'])) 
              ? $user_perfil['avatar'] 
              : "user.png";
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil de <?php echo htmlspecialchars($user_perfil['nombre']); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <main class="main-content">
        <div class="container mt-4">
            <div class="card shadow-sm">
                <div class="card-body text-center">
                    <img src="assets/img/<?php echo $fotoPerfil; ?>" class="rounded-circle mb-3 border" width="150" height="150">
                    <h2><?php echo htmlspecialchars($user_perfil['nombre']); ?></h2>
                    <p class="text-muted"><?php echo htmlspecialchars($user_perfil['email']); ?></p>
                    
                    <div class="mb-3">
                        <?php
                        $tags = explode(",", $user_perfil['habilidades']);
                        foreach ($tags as $tag) {
                            if (!empty(trim($tag))) {
                                echo '<span class="badge bg-primary me-1">' . htmlspecialchars(trim($tag)) . '</span>';
                            }
                        }
                        ?>
                    </div>
                    
                    <hr>
                    <h5>Biografía</h5>
                    <p><?php echo nl2br(htmlspecialchars($user_perfil['bio'] ?? 'Este usuario aún no tiene biografía.')); ?></p>
                    
                    <?php if ($perfil_id == $_SESSION['user_id']): ?>
                        <a href="editar_perfil.php" class="btn btn-outline-secondary btn-sm">Editar Perfil</a>
                    <?php else: ?>
                        <a href="mensajes.php?receptor=<?php echo $perfil_id; ?>" class="btn btn-primary btn-sm">Contactar / Enviar Mensaje</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </main>
</body>
</html>