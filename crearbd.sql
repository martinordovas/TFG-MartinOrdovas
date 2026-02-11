-- Crear la base de datos
CREATE DATABASE IF NOT EXISTS skillnet 
CHARACTER SET utf8mb4 
COLLATE utf8mb4_unicode_ci;
drop DATABASE tfg_social_habilidades;
drop DATABASE skillnet;
USE skillnet;
drop Table usuarios;


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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Tabla de publicaciones (para el feed estilo Twitter que mencionaste)
CREATE TABLE IF NOT EXISTS publicaciones (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_usuario INT NOT NULL,
    contenido TEXT NOT NULL,
    fecha_publicacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

select * from publicaciones;
select * from usuarios;
delete from publicaciones;

-- 1. INSERTAR USUARIOS 
-- Nota: La contraseña para todos es '1234' (ya encriptada con BCRYPT)
INSERT INTO usuarios (nombre, email, password, habilidades, bio, avatar) VALUES 
('Jorge Villuendas', 'jorge@tfg.com', '$2y$12$m9ceJoCQEC2afg68H5xhdeC2aAmf1.NO2ZBICJeXznK2DFqYuslmq', 'PHP, MariaDB, Bootstrap', 'Estudiante de DAW trabajando en su TFG. Busco ayuda con el diseño visual.', 'user.png'),
('Elena Diseño', 'elena@art.com', '$2y$12$m9ceJoCQEC2afg68H5xhdeC2aAmf1.NO2ZBICJeXznK2DFqYuslmq', 'UI/UX, Figma, Photoshop', 'Diseñadora gráfica con 5 años de experiencia. Quiero aprender las bases de PHP.', 'user.png'),
('Carlos Marketing', 'carlos@growth.com', '$2y$12$m9ceJoCQEC2afg68H5xhdeC2aAmf1.NO2ZBICJeXznK2DFqYuslmq', 'SEO, Ads, Estrategia', 'Experto en crecimiento digital. Te ayudo con tu marca a cambio de tutorías de Python.', 'user.png'),
('Lucía Data', 'lucia@data.com', '$2y$12$m9ceJoCQEC2afg68H5xhdeC2aAmf1.NO2ZBICJeXznK2DFqYuslmq', 'Python, SQL, Tableau', 'Analista de datos apasionada. Busco aprender desarrollo web frontend.', 'user.png');

UPDATE usuarios 
SET password = '$2y$12$m9ceJoCQEC2afg68H5xhdeC2aAmf1.NO2ZBICJeXznK2DFqYuslmq' 
WHERE id > 1;

-- 2. INSERTAR PUBLICACIONES
-- Asumimos que los IDs generados son 1, 2, 3 y 4 respectivamente.
INSERT INTO publicaciones (id_usuario, contenido, fecha_publicacion) VALUES 
(2, '¡He conseguido que el sistema de login funcione con sentencias preparadas!', NOW() - INTERVAL 1 HOUR),
(3, '¿Alguien sabe cómo conectar un formulario de contacto con una base de datos? Ofrezco clase de Figma.', NOW() - INTERVAL 2 HOUR),
(4, 'Si quieres mejorar el posicionamiento de tu web de SkillSwap, escríbeme. Busco programadores.', NOW() - INTERVAL 3 HOUR),
(5, 'Acabo de subir un dataset sobre tendencias de lenguajes en 2024. ¡Súper interesante!', NOW() - INTERVAL 5 HOUR),
(2, 'Probando el sistema de perfiles dinámicos. Esto de PHP mola bastante.', NOW() - INTERVAL 10 MINUTE),
(3, 'He rediseñado la interfaz del buscador, ¿qué os parece?', NOW() - INTERVAL 1 DAY);

CREATE TABLE IF NOT EXISTS mensajes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_emisor INT NOT NULL,
    id_receptor INT NOT NULL,
    mensaje TEXT NOT NULL,
    fecha_envio TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    leido TINYINT(1) DEFAULT 0,
    FOREIGN KEY (id_emisor) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_receptor) REFERENCES usuarios(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS seguidores (
    id INT AUTO_INCREMENT PRIMARY KEY,
    id_seguidor INT NOT NULL, -- El que da "Seguir"
    id_seguido INT NOT NULL,  -- El que es seguido
    fecha_follow TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (id_seguidor) REFERENCES usuarios(id) ON DELETE CASCADE,
    FOREIGN KEY (id_seguido) REFERENCES usuarios(id) ON DELETE CASCADE,
    UNIQUE(id_seguidor, id_seguido) -- Evita que sigas dos veces a la misma persona
);

select * from mensajes;
select * from seguidores;
delete from mensajes;
show tables;