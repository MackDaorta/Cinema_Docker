<?php

namespace App\Models;

class PeliculaModel {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerOpcionesSalas(): array {
        $stmt = $this->pdo->query("SELECT id, nombre FROM Sala ORDER BY nombre");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerOpcionesGeneros(): array {
        $stmt = $this->pdo->query("SELECT id, nombre FROM Genero ORDER BY nombre");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function obtenerVigentesPublicas(): array {
        $sql_peliculas = "SELECT id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno 
                          FROM Pelicula 
                          ORDER BY fecha_estreno DESC";
        $stmt = $this->pdo->query($sql_peliculas);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }
    public function obtenerRelacionesGeneros(): array {
        $sql_generos = "SELECT pg.pelicula_id, g.nombre AS genero_nombre
                        FROM Pelicula_generos pg
                        JOIN Genero g ON pg.genero_id = g.id";
        return $this->pdo->query($sql_generos)->fetchAll(\PDO::FETCH_OBJ);
    }
    public function obtenerRelacionesSalas(): array {
        $sql_salas = "SELECT ps.pelicula_id, s.nombre AS sala_nombre
                      FROM Pelicula_salas ps
                      JOIN Sala s ON ps.sala_id = s.id";
        return $this->pdo->query($sql_salas)->fetchAll(\PDO::FETCH_OBJ);
    }
    public function enriquecerPeliculas(array $peliculas, array $generosRel, array $salasRel): array {
        foreach ($peliculas as $peli) {
            $peli->generos = [];
            $peli->salas = [];

            foreach ($generosRel as $rel_g) {
                if ($rel_g->pelicula_id === $peli->id) {
                    $peli->generos[] = $rel_g->genero_nombre;
                }
            }

            // Unir Salas
            foreach ($salasRel as $rel_s) {
                if ($rel_s->pelicula_id === $peli->id) {
                    $peli->salas[] = $rel_s->sala_nombre;
                }
            }
        }
        return $peliculas;
    }
    public function obtenerSalasIds(string $peliculaId): array {
        $stmt = $this->pdo->prepare("SELECT sala_id FROM Pelicula_salas WHERE pelicula_id = ?");
        $stmt->execute([$peliculaId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function obtenerGenerosIds(string $peliculaId): array {
        $stmt = $this->pdo->prepare("SELECT genero_id FROM Pelicula_generos WHERE pelicula_id = ?");
        $stmt->execute([$peliculaId]);
        return $stmt->fetchAll(\PDO::FETCH_COLUMN);
    }
    
    public function obtenerPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Pelicula WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }
    
    public function obtenerListadoAdmin(): array {
        $stmt = $this->pdo->query("SELECT id, nombre, fecha_estreno FROM Pelicula ORDER BY fecha_estreno DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    

    public function actualizarPelicula(string $id, array $data): bool {
        $sql = "UPDATE Pelicula SET nombre=?, sinopsis=?, imagen=?, restriccion=?, duracion_minutos=?, fecha_estreno=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $data['nombre'], $data['sinopsis'], $data['imagenNombre'], $data['restriccion'], $data['duracion'], $data['fecha'], $id
        ]);
    }

    public function crearPelicula(string $id, array $data): bool {
        $sql = "INSERT INTO Pelicula (id, nombre, sinopsis, imagen, restriccion, duracion_minutos, fecha_estreno) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            $id, $data['nombre'], $data['sinopsis'], $data['imagenNombre'], $data['restriccion'], $data['duracion'], $data['fecha']
        ]);
    }
    

    public function limpiarRelaciones(string $peliculaId): void {
        $this->pdo->prepare("DELETE FROM Pelicula_salas WHERE pelicula_id = ?")->execute([$peliculaId]);
        $this->pdo->prepare("DELETE FROM Pelicula_generos WHERE pelicula_id = ?")->execute([$peliculaId]);
    }

    public function insertarSalas(string $peliculaId, array $salasIds): void {
        $stmtSala = $this->pdo->prepare("INSERT INTO Pelicula_salas (pelicula_id, sala_id) VALUES (?, ?)");
        foreach ($salasIds as $salaId) {
            $stmtSala->execute([$peliculaId, $salaId]);
        }
    }

    public function insertarGeneros(string $peliculaId, array $generosIds): void {
        $stmtGenero = $this->pdo->prepare("INSERT INTO Pelicula_generos (pelicula_id, genero_id) VALUES (?, ?)");
        foreach ($generosIds as $generoId) {
            $stmtGenero->execute([$peliculaId, $generoId]);
        }
    }
    

    public function eliminarPelicula(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Pelicula WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}