<?php
include 'dbconn.php';
if (isset($_SESSION['user_id']) && !empty($_POST['contenido'])) {
    
    $id_usuario = $_SESSION['user_id'];
    $contenido = $_POST['contenido'];

    $stmt = $conn->prepare("INSERT INTO publicaciones (id_usuario, contenido) VALUES (?, ?)");
    $stmt->bind_param("is", $id_usuario, $contenido);

    if ($stmt->execute()) {
        header("Location: ../index.php?success=1");
    } else {
        header("Location: ../index.php?error=1");
    }
    $stmt->close();
} else {
    header("Location: ../index.php");
}
?>