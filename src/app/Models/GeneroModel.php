<?php

namespace App\Models;

class GeneroModel {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Genero WHERE id = ?");
        $stmt->execute([$id]);
        $genero = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $genero ?: null;
    }

    public function obtenerTodos(): array {
        $stmt = $this->pdo->query("SELECT * FROM Genero ORDER BY nombre ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function crear(string $nombre, string $descripcion): bool {
        $stmt = $this->pdo->prepare("INSERT INTO Genero (id, nombre, descripcion) VALUES (UUID(), ?, ?)");
        return $stmt->execute([$nombre, $descripcion]);
    }


    public function actualizar(string $id, string $nombre, string $descripcion): bool {
        $stmt = $this->pdo->prepare("UPDATE Genero SET nombre=?, descripcion=? WHERE id=?");
        $stmt->execute([$nombre, $descripcion, $id]);
        return $stmt->rowCount() > 0;
    }


    public function eliminar(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Genero WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}