<?php include 'phpscripts/dbconn.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Inicio sesión - Red Social Habilidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center">Login</h2>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <p class="mt-3 text-center">¿No tienes cuenta? <a href="registrarse.php">Regístrate</a></p>
                            <button type="submit" name="entrar" class="btn btn-success w-100">Entrar</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['entrar'])) {
        $email = $_POST['email'];
        $pass = $_POST['password'];

        // 1. Preparamos la consulta para buscar al usuario por email
        $stmt = $conn->prepare("SELECT id, nombre, password FROM usuarios WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        // 2. Obtenemos el resultado
        $result = $stmt->get_result();

        if ($user = $result->fetch_assoc()) {
            // 3. Verificamos la contraseña encriptada
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
</body>

</html>