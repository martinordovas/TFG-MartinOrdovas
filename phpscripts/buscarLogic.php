<?php
if (!empty($busqueda)) {
    $termino = "%$busqueda%";
    $sql = "SELECT u.id, u.nombre, u.habilidades, u.avatar, u.bio, 
                                                AVG(v.puntuacion) as promedio, 
                                                COUNT(v.id) as total_votos 
                                            FROM usuarios u 
                                            LEFT JOIN valoraciones v ON u.id = v.id_valorado 
                                            WHERE u.nombre LIKE ? OR u.habilidades LIKE ? 
                                            GROUP BY u.id";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $termino, $termino);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        echo "<p class='text-black mb-4'>Se han encontrado {$res->num_rows} usuarios:</p>";
        while ($user = $res->fetch_assoc()) {
            $foto = (!empty($user['avatar']) && file_exists("img/" . $user['avatar'])) ? $user['avatar'] : "user.png";
            ?>
            <div class="card usr-search mb-3">
                <div class="card-body p-4">
                    <div class="d-flex align-items-center">
                        <img src="img/<?php echo $foto; ?>" class="rounded-circle me-3 img-perfil-crop" width="70" height="70">
                        <div class="flex-grow-1">
                            <h5 class="mb-1">
                                <a href="perfil.php?id=<?php echo $user['id']; ?>" class="text-decoration-none card-text fw-bold">
                                    <?php echo htmlspecialchars($user['nombre']); ?>
                                </a>
                            </h5>
                            <div class="mb-2">
                                <?php
                                $promedio = round($user['promedio'] ?? 0);
                                $votos = $user['total_votos'];

                                for ($i = 1; $i <= 5; $i++) {
                                    if ($i <= $promedio) {
                                        echo '<i class="bi bi-star-fill text-warning me-1"></i>';
                                    } else {
                                        echo '<i class="bi bi-star text-black-50 me-1"></i>';
                                    }
                                }
                                ?>
                                <span class="small text-black-50">(<?php echo $votos; ?>
                                    valoraciones)</span>
                            </div>
                            <p class="text-black-50 small mb-0">
                                <?php echo htmlspecialchars(substr($user['bio'] ?? '', 0, 100)) . '...'; ?>
                            </p>
                        </div>
                        <a href="perfil.php?id=<?php echo $user['id']; ?>" class="btn-usr-search">Ver Perfil</a>
                    </div>
                </div>
            </div>
            <?php
        }
    } else {
        echo '<div class="alert alert-warning bg-transparent text-warning border-warning">No se encontraron usuarios con esos criterios.</div>';
    }
    $stmt->close();
} else {
    echo '<div class="text-center mt-5 text-black-50">
                                            <i class="bi bi-person-plus-fill display-1 d-block mb-3"></i>
                                            <p>Introduce un nombre o habilidad para empezar.</p>
                                          </div>';
}
?>