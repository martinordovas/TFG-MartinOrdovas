<nav class="col-md-3 col-lg-2 d-md-block sidebar">
    <div class="position-sticky">
        <h3 class="px-3 mb-4">Skill-net</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link active text-dark" href="index.php">
                    <i class="bi bi-house-door-fill me-2"></i> Inicio
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="buscar.php">
                    <i class="bi bi-search me-2"></i> Explorar
                </a>
            </li>
            <?php
            // Consulta para contar mensajes no leídos dirigidos a mí
            $stmt_count = $conn->prepare("SELECT COUNT(*) as total FROM mensajes WHERE id_receptor = ? AND leido = 0");
            $stmt_count->bind_param("i", $_SESSION['user_id']);
            $stmt_count->execute();
            $count_res = $stmt_count->get_result()->fetch_assoc();
            $no_leidos = $count_res['total'];
            ?>

            <li class="nav-item">
                <a class="nav-link text-dark d-flex justify-content-between align-items-center" href="mensajes.php">
                    <span><i class="bi bi-chat-dots me-2"></i> Mensajes</span>
                    <?php if ($no_leidos > 0): ?>
                        <span class="badge rounded-pill bg-danger">
                            <?php echo $no_leidos; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark" href="perfil.php">
                    <i class="bi bi-person-circle me-2"></i> Perfil
                </a>
            </li>
            <hr>
            <li class="nav-item">
                <a class="nav-link text-danger" href="phpscripts/logout.php">
                    <i class="bi bi-box-arrow-right me-2"></i> Salir
                </a>
            </li>
        </ul>
    </div>
</nav>