
CREATE TABLE Sala (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre VARCHAR(10) UNIQUE NOT NULL, -- unique=True
    descripcion TEXT,
    imagen VARCHAR(100) NOT NULL
) ENGINE=InnoDB;


CREATE TABLE Genero (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre VARCHAR(50) UNIQUE NOT NULL, -- unique=True
    descripcion TEXT
) ENGINE=InnoDB;


CREATE TABLE Anuncio (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    imagen VARCHAR(100) NOT NULL,
    tipo VARCHAR(50) NOT NULL,
    link VARCHAR(255),
    vigencia DATE,
    CHECK (tipo IN ('SLIDER', 'PROMOCION'))
) ENGINE=InnoDB;

CREATE TABLE Producto (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    descripcion TEXT,
    precio DECIMAL(5, 2) NOT NULL,
    imagen VARCHAR(100) NOT NULL,
    categoria VARCHAR(50) NOT NULL DEFAULT 'OTRO',
    disponible BOOLEAN NOT NULL DEFAULT TRUE, -- BooleanField
    CHECK (categoria IN ('COMBO', 'POPCORN', 'BEBIDA', 'SNACK', 'COLECCIONABLES'))
) ENGINE=InnoDB;


CREATE TABLE Pelicula (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre VARCHAR(100) NOT NULL,
    sinopsis TEXT,
    imagen VARCHAR(100) NOT NULL,
    restriccion VARCHAR(30) NOT NULL,
    duracion_minutos INT UNSIGNED NOT NULL, -- PositiveIntegerField
    fecha_estreno DATE NOT NULL,
    CHECK (restriccion IN ('APT', '+14', '+18'))
) ENGINE=InnoDB;


CREATE TABLE Pelicula_salas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pelicula_id CHAR(36) NOT NULL,
    sala_id CHAR(36) NOT NULL,
    UNIQUE (pelicula_id, sala_id),
    FOREIGN KEY (pelicula_id) REFERENCES Pelicula(id) ON DELETE CASCADE,
    FOREIGN KEY (sala_id) REFERENCES Sala(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE Pelicula_generos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pelicula_id CHAR(36) NOT NULL,
    genero_id CHAR(36) NOT NULL,
    UNIQUE (pelicula_id, genero_id),
    FOREIGN KEY (pelicula_id) REFERENCES Pelicula(id) ON DELETE CASCADE,
    FOREIGN KEY (genero_id) REFERENCES Genero(id) ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS Usuario (
    id CHAR(36) PRIMARY KEY NOT NULL,
    nombre_usuario VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE,
    contrasena VARCHAR(255) NOT NULL,
    es_admin BOOLEAN NOT NULL DEFAULT FALSE 
) ENGINE=InnoDB;

INSERT INTO Usuario (id, nombre_usuario, email, contrasena, es_admin) 
VALUES ('1', 'admin', 'admin@cinemark.com', '123', TRUE);