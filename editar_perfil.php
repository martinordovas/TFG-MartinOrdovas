<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// 1. Obtener datos actuales para rellenar el formulario
$stmt = $conn->prepare("SELECT nombre, habilidades, bio, avatar FROM usuarios WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$datos = $stmt->get_result()->fetch_assoc();

// 2. Lógica de actualización al enviar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nombre = $_POST['nombre'];
    $bio = $_POST['bio'];
    $habilidades = $_POST['habilidades'];
    $nombre_foto = $datos['avatar']; // Por defecto dejamos la que hay

    // Gestión de la subida de imagen
    if (!empty($_FILES['foto']['name'])) {
        $extension = pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION);
        $nuevo_nombre = "perfil_" . $user_id . "_" . time() . "." . $extension;
        $ruta_destino = "img/" . $nuevo_nombre;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $ruta_destino)) {
            $nombre_foto = $nuevo_nombre;
            // Opcional: podrías borrar la foto vieja aquí si no es 'user.png'
        }
    }
    // Actualizar base de datos
    $update = $conn->prepare("UPDATE usuarios SET nombre = ?, bio = ?, habilidades = ?, avatar = ? WHERE id = ?");
    $update->bind_param("ssssi", $nombre, $bio, $habilidades, $nombre_foto, $user_id);

    if ($update->execute()) {
        header("Location: perfil.php?success=updated");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h3>Editar mi perfil</h3>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control"
                                    value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Habilidades (separadas por comas)</label>
                                <input type="text" name="habilidades" class="form-control"
                                    value="<?php echo htmlspecialchars($datos['habilidades'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Biografía</label>
                                <textarea name="bio" class="form-control"
                                    rows="4"><?php echo htmlspecialchars($datos['bio'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto de perfil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Si no seleccionas nada, se mantendrá la actual.</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="perfil.php" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>