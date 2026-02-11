<nav class="d-md-block sidebar sticky-top border-bottom border-md-end">
    <div class="position-sticky">
        <h3 class="px-3 mb-4 d-none d-md-block">Skill-net <i class="bi bi-wifi"></i></h3>
        <ul class="nav flex-row flex-md-column justify-content-around w-100">
            <!--
            <li class="nav-item">
                <a class="nav-link text-dark p-2">
                    <span class="d-md-none d-inline ms-2">Skill-net <i class="bi bi-wifi"></i></span>
                </a>
            </li>
            -->
            <li class="nav-item">
                <a class="nav-link text-dark p-2" href="index.php">
                    <i class="bi bi-house-door-fill"></i>
                    <span class="d-none d-md-inline ms-2">Inicio</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark p-2" href="buscar.php">
                    <i class="bi bi-search"></i>
                    <span class="d-none d-md-inline ms-2">Explorar</span>
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
                <a class="nav-link text-dark p-2" href="mensajes.php">
                    <i class="bi bi-chat-dots"></i>
                    <span class="d-none d-md-inline ms-2">Mensajes</span>
                    <?php if ($no_leidos > 0): ?>
                        <span class="badge rounded-pill bg-danger">
                            <?php echo $no_leidos; ?>
                        </span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-dark p-2" href="perfil.php">
                    <i class="bi bi-person-circle"></i>
                    <span class="d-none d-md-inline ms-2">Perfil</span>
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger p-2" href="phpscripts/logout.php">
                    <i class="bi bi-box-arrow-right"></i>
                    <span class="d-none d-md-inline ms-2">Cerrar sesión</span>
                </a>
            </li>
        </ul>
    </div>
</nav>