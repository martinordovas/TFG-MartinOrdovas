<?php include 'phpscripts/dbconn.php'; ?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro - Red Social Habilidades</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow">
                    <div class="card-body">
                        <h2 class="text-center">Crea tu cuenta</h2>
                        <form action="" method="POST">
                            <div class="mb-3">
                                <label>Nombre completo</label>
                                <input type="text" name="nombre" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Contraseña</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Tus habilidades (separadas por comas)</label>
                                <input type="text" name="habilidades" class="form-control"
                                    placeholder="Ej: PHP, UX, Python">
                            </div>
                            <button type="submit" name="registrar" class="btn btn-primary w-100">Registrarse</button>
                        </form>
                        <p class="mt-3 text-center">¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php
    if (isset($_POST['registrar'])) {
        $nombre = $_POST['nombre'];
        $email = $_POST['email'];
        $pass = password_hash($_POST['password'], PASSWORD_BCRYPT);
        $habs = $_POST['habilidades'];

        // 1. Preparamos la sentencia
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, email, password, habilidades) VALUES (?, ?, ?, ?)");

        // 2. Vinculamos los parámetros (s = string)
        $stmt->bind_param("ssss", $nombre, $email, $pass, $habs);

        // 3. Ejecutamos
        if ($stmt->execute()) {
            echo "<script>alert('Usuario creado con éxito'); window.location='login.php';</script>";
        } else {
            echo "Error al registrar: " . $stmt->error;
        }

        $stmt->close();
    }
    ?>
</body>

</html>