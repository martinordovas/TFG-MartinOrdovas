<?php
include 'phpscripts/dbconn.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$busqueda = isset($_GET['q']) ? $_GET['q'] : '';
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Buscar Usuarios - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include("phpscripts/navbar.php"); ?>
            <main class="main-content col-md-9 ms-sm-auto col-lg-10">
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <h2 class="mb-4">Explorar Talentos</h2>

                            <form action="buscar.php" method="GET" class="mb-5">
                                <div class="input-group input-group-lg shadow-sm">
                                    <input type="text" name="q" class="form-control"
                                        placeholder="Busca por nombre o habilidad (ej: Java, Logo, Martín...)"
                                        value="<?php echo htmlspecialchars($busqueda); ?>">
                                    <button class="btn btn-primary" type="submit">
                                        <i class="bi bi-search"></i> Buscar
                                    </button>
                                </div>
                            </form>

                            <div class="resultados">
                                <?php
                                if (!empty($busqueda)) {
                                    $termino = "%$busqueda%";
                                    $stmt = $conn->prepare("SELECT id, nombre, habilidades, avatar, bio FROM usuarios WHERE nombre LIKE ? OR habilidades LIKE ?");
                                    $stmt->bind_param("ss", $termino, $termino);
                                    $stmt->execute();
                                    $res = $stmt->get_result();

                                    if ($res->num_rows > 0) {
                                        echo "<p class='text-muted'>Se han encontrado {$res->num_rows} usuarios:</p>";
                                        while ($user = $res->fetch_assoc()) {
                                            $foto = (!empty($user['avatar']) && file_exists("img/" . $user['avatar'])) ? $user['avatar'] : "user.png";
                                            ?>
                                            <div class="card mb-3 shadow-sm border-0">
                                                <div class="card-body">
                                                    <div class="d-flex align-items-center">
                                                        <img src="img/<?php echo $foto; ?>"
                                                            class="rounded-circle me-3 img-perfil-crop" width="70" height="70">
                                                        <div class="flex-grow-1">
                                                            <h5 class="mb-1">
                                                                <a href="perfil.php?id=<?php echo $user['id']; ?>"
                                                                    class="text-decoration-none text-dark fw-bold">
                                                                    <?php echo htmlspecialchars($user['nombre']); ?>
                                                                </a>
                                                            </h5>
                                                            <div class="mb-2">
                                                                <?php
                                                                $tags = explode(",", $user['habilidades']);
                                                                foreach ($tags as $tag) {
                                                                    if (!empty(trim($tag))) {
                                                                        echo '<span class="badge bg-light text-primary border me-1">' . htmlspecialchars(trim($tag)) . '</span>';
                                                                    }
                                                                }
                                                                ?>
                                                            </div>
                                                            <p class="text-muted small mb-0">
                                                                <?php echo htmlspecialchars(substr($user['bio'] ?? '', 0, 100)) . '...'; ?>
                                                            </p>
                                                        </div>
                                                        <a href="perfil.php?id=<?php echo $user['id']; ?>"
                                                            class="btn btn-outline-primary btn-sm">Ver Perfil</a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                        }
                                    } else {
                                        echo '<div class="alert alert-warning">No se encontraron usuarios con esos criterios.</div>';
                                    }
                                    $stmt->close();
                                } else {
                                    echo '<p class="text-center text-muted mt-5">Introduce un término para empezar a buscar colaboradores.</p>';
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>