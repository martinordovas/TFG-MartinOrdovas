<?php include 'phpscripts/dbconn.php'; ?>
<?php
if (isset($_POST['registrar'])) {
    $nombre = $_POST['nombre'];
    $email = $_POST['email'];
    $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $habs = $_POST['habilidades'];

    $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, habilidades) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nombre, $email, $pass, $habs);

    if ($stmt->execute()) {
        echo "<script>alert('Usuario creado con éxito'); window.location='login.php';</script>";
    } else {
        $error = "Error al registrar: " . $stmt->error;
    }
    $stmt->close();
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/register.css">
</head>

<body class="login-page">
    <div class="container-fluid p-0">
        <div class="row g-0">
            <video autoplay muted loop playsinline class="bg-video">
                <source src="video/video.mp4" type="video/mp4">
            </video>

            <div class="col-md-6 d-none d-md-flex title-side">
                <div class="title-video">
                    <p class="title">Skill-net <i class="bi bi-wifi"></i></p>
                    <p class="sub-title">Únete a la red de talento.</p>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center form-login">
                <div class="login-container">
                    <h3 class="title-form mb-2">Crea tu cuenta</h3>
                    <p class="sub-title-form mb-4">Forma parte de la comunidad.</p>

                    <form action="" method="POST">
                        <div class="mb-3">
                            <label class="form-label">Nombre completo</label>
                            <input type="text" name="nombre" class="form-control custom-input" 
                                placeholder="Tu nombre" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control custom-input" 
                                placeholder="nombre@ejemplo.com" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Contraseña</label>
                            <input type="password" name="password" class="form-control custom-input" 
                                placeholder="••••••••" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Habilidades</label>
                            <input type="text" name="habilidades" class="form-control custom-input" 
                                placeholder="Ej: PHP, Diseño, Python">
                        </div>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger py-2" style="font-size: 0.85rem;"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <button type="submit" name="registrar" class="button-form w-100">Registrarse</button>
                    </form>

                    <p class="mt-4 text-center">
                        ¿Ya tienes cuenta? <a href="login.php" class="link-register">Inicia sesión</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>