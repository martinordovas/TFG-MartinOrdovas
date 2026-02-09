-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS tfg_social_habilidades;
USE tfg_social_habilidades;

-- Tabla de usuarios (con los campos para tu perfil)
CREATE TABLE IF NOT EXISTS usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    habilidades TEXT, -- Guardaremos aquí los tags (ej: "PHP, Diseño UX, CSS")
    bio TEXT,
    avatar VARCHAR(255) DEFAULT 'default.png',
    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- Tabla de publicaciones (para el feed estilo Twitter que mencionaste)
CREATE TABLE IF NOT EXISTS publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB;

select * from publicaciones;