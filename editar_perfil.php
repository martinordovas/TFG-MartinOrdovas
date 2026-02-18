<?php
include 'phpscripts/editarLogic.php';
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Editar Perfil - SkillSwap</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/edit-form.css">
</head>

<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="edit-container">
                    <div class="">
                        <h3>Editar mi perfil</h3>
                        <form action="" method="POST" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Nombre</label>
                                <input type="text" name="nombre" class="form-control custom-input"
                                    value="<?php echo htmlspecialchars($datos['nombre']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Habilidades (separadas por comas)</label>
                                <input type="text" name="habilidades" class="form-control custom-input"
                                    value="<?php echo htmlspecialchars($datos['habilidades'] ?? ''); ?>">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Biografía</label>
                                <textarea name="bio" class="form-control custom-input"
                                    rows="4"><?php echo htmlspecialchars($datos['bio'] ?? ''); ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Foto de perfil</label>
                                <input type="file" name="foto" class="form-control" accept="image/*">
                                <small class="text-muted">Si no seleccionas nada, se mantendrá la actual.</small>
                            </div>
                            <div class="d-flex justify-content-between">
                                <a href="perfil.php" class="button-cancel">Cancelar</a>
                                <button type="submit" class="button-save">Guardar Cambios</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>

</html>