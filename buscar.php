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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buscar Usuarios - Skill-net</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <link href="https://fonts.cdnfonts.com/css/sf-pro-display" rel="stylesheet">
    <link rel="stylesheet" href="css/fonts.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
    <div class="container-fluid">
        <div class="row">
            <?php include("phpscripts/navbar.php"); ?>
            <main class="main-content">
                <div class="container mt-4">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <h2 class="mb-4 text-white">Explorar Talentos</h2>

                            <form action="buscar.php" method="GET" class="mb-5">
                                <div class="input-group input-group-lg search-box">
                                    <input type="text" name="q" class="form-control"
                                        placeholder="Busca por nombre o habilidad..."
                                        value="<?php echo htmlspecialchars($busqueda); ?>">
                                    <button class="search-btn" type="submit">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </form>

                            <div class="resultados">
                                <?php include 'phpscripts/buscarLogic.php'?>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
    </div>
</body>

</html>