<?php

namespace App\Models;

class SalaModel {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Sala WHERE id = ?");
        $stmt->execute([$id]);
        $sala = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $sala ?: null;
    }
    public function obtenerTodasPublicas(): array {
        $stmt = $this->pdo->query("SELECT * FROM Sala ORDER BY nombre ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT * FROM Sala ORDER BY nombre ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function crear(string $nombre, string $descripcion, string $imagenNombre): bool {
        $stmt = $this->pdo->prepare("INSERT INTO Sala (id, nombre, descripcion, imagen) VALUES (UUID(), ?, ?, ?)");
        return $stmt->execute([$nombre, $descripcion, $imagenNombre]);
    }


    public function actualizar(string $id, string $nombre, string $descripcion, string $imagenNombre): bool {
        $stmt = $this->pdo->prepare("UPDATE Sala SET nombre=?, descripcion=?, imagen=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $imagenNombre, $id]);
        return $stmt->rowCount() > 0;
    }


    public function eliminar(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Sala WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}