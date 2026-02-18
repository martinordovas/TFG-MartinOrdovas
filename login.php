<?php include 'phpscripts/dbconn.php'; ?>
<?php
if (isset($_POST['entrar'])) {
    $email = $_POST['email'];
    $pass = $_POST['password'];

    $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($user = $result->fetch_assoc()) {
        if (password_verify($pass, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['nombre'] = $user['nombre'];
            header("Location: index.php");
            exit();
        } else {
            $error = "Contraseña incorrecta.";
        }
    } else {
        $error = "El usuario no existe.";
    }
    $stmt->close();
}
?>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio sesión - Red Social Habilidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/login.css">
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
                    <p class="sub-title">Conecta con el talento.</p>
                </div>
            </div>

            <div class="col-md-6 d-flex align-items-center justify-content-center form-login">
                <div class="login-container">
                    <h3 class="title-form mb-2">Bienvenid@ a Skill-net</h3>
                    <p class="sub-title-form mb-4">Ingresa tus credenciales para acceder.</p>

                    <form action="" method="POST">
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

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger py-2" style="font-size: 0.85rem;"><?php echo $error; ?></div>
                        <?php endif; ?>

                        <button type="submit" name="entrar"
                            class="button-form w-100">Iniciar sesión</button>
                    </form>

                    <p class="mt-4 text-center">
                        ¿Aún no tienes cuenta? <a href="registrarse.php" class="link-register">Únete!</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>