<?php

namespace App\Models;

class AnuncioModel {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Anuncio WHERE id = ?");
        $stmt->execute([$id]);
        $anuncio = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $anuncio ?: null;
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT * FROM Anuncio ORDER BY vigencia DESC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function obtenerSliders(): array {
        $stmt = $this->pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'SLIDER'");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    public function obtenerPromociones(): array {
        $stmt = $this->pdo->query("SELECT nombre, imagen, link FROM Anuncio WHERE tipo = 'PROMOCION' ");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function crear(array $data): bool {
        $sql = "INSERT INTO Anuncio (id, nombre, tipo, link, vigencia, imagen) VALUES (UUID(), ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['link'] ?? '',
            $data['vigencia'] ?? null,
            $data['imagen'] ?? ''
        ]);
    }


    public function actualizar(string $id, array $data): bool {
        $sql = "UPDATE Anuncio SET nombre=?, tipo=?, link=?, vigencia=?, imagen=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $data['nombre'],
            $data['tipo'],
            $data['link'] ?? '',
            $data['vigencia'] ?? null,
            $data['imagen'] ?? '',
            $id
        ]);
    }


    public function eliminar(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Anuncio WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}