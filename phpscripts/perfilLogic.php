<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$perfil_id = isset($_GET['id']) ? intval($_GET['id']) : $_SESSION['user_id'];
$mi_id = $_SESSION['user_id'];

// --- LÓGICA DE VALORACIÓN (NUEVA) ---
if (isset($_POST['votar'])) {
    $puntos = intval($_POST['puntuacion']);
    $comentario = trim($_POST['comentario'] ?? ''); // Limpiamos espacios vacíos

    $stmt_v = $conn->prepare("INSERT INTO valoraciones (id_valorador, id_valorado, puntuacion, comentario) 
                             VALUES (?, ?, ?, ?) 
                             ON DUPLICATE KEY UPDATE puntuacion = ?, comentario = ?");
    
    // CORRECCIÓN AQUÍ: iiisis
    $stmt_v->bind_param("iiisis", $mi_id, $perfil_id, $puntos, $comentario, $puntos, $comentario);
    
    if ($stmt_v->execute()) {
        header("Location: perfil.php?id=" . $perfil_id . "&success=1");
    } else {
        // Esto te ayudará a ver si la base de datos escupe algún error
        die("Error en la DB: " . $stmt_v->error);
    }
    exit();
}

// --- LÓGICA DE SEGUIR ---
if (isset($_POST['accion_follow'])) {
    if ($_POST['accion_follow'] == 'seguir') {
        $sql = "INSERT IGNORE INTO seguidores (id_seguidor, id_seguido) VALUES (?, ?)";
    } else {
        $sql = "DELETE FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?";
    }
    $stmt_f = $conn->prepare($sql);
    $stmt_f->bind_param("ii", $mi_id, $perfil_id);
    $stmt_f->execute();
    header("Location: perfil.php?id=" . $perfil_id);
    exit();
}
$stmt_check = $conn->prepare("SELECT id FROM seguidores WHERE id_seguidor = ? AND id_seguido = ?");
$stmt_check->bind_param("ii", $mi_id, $perfil_id);
$stmt_check->execute();
$seguido = $stmt_check->get_result()->num_rows > 0;

// 3. Obtener datos de reputación
$stmt_rep = $conn->prepare("SELECT AVG(puntuacion) as promedio, COUNT(*) as total FROM valoraciones WHERE id_valorado = ?");
$stmt_rep->bind_param("i", $perfil_id);
$stmt_rep->execute();
$stats = $stmt_rep->get_result()->fetch_assoc();

// 4. Traemos los datos del usuario
$stmt = $conn->prepare("SELECT nombre, email, habilidades, bio, avatar FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $perfil_id);
$stmt->execute();
$user_perfil = $stmt->get_result()->fetch_assoc();

$stmt_comm = $conn->prepare("SELECT v.*, u.nombre, u.avatar FROM valoraciones v JOIN usuarios u ON v.id_valorador = u.id WHERE v.id_valorado = ? ORDER BY v.id DESC");
$stmt_comm->bind_param("i", $perfil_id);
$stmt_comm->execute();
$res_comm = $stmt_comm->get_result();

if (!empty($user_perfil['avatar']) && file_exists("img/" . $user_perfil['avatar'])) {
    $fotoPerfil = $user_perfil['avatar'];
} else {
    $fotoPerfil = "user.png";
}
?>