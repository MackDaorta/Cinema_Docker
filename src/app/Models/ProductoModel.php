<?php

namespace App\Models;

class ProductoModel {
    protected $pdo;

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo;
    }


    public function obtenerPorId(string $id): ?array {
        $stmt = $this->pdo->prepare("SELECT * FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC) ?: null;
    }

    public function obtenerTodosParaAdmin(): array {
        $stmt = $this->pdo->query("SELECT * FROM Producto ORDER BY categoria, nombre");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    public function crear(array $data): bool {
        $sql = "INSERT INTO Producto (id, nombre, descripcion, precio, imagen, categoria, disponible) 
                VALUES (UUID(), ?, ?, ?, ?, ?, ?)";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['precio'] ?? 0.0,
            $data['imagen'] ?? '',
            $data['categoria'] ?? '',
            $data['disponible'] ?? 0
        ]);
    }
    public function obtenerDisponibles(): array {
        $sql = "SELECT * FROM Producto WHERE disponible = 1 ORDER BY categoria ASC, nombre ASC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(\PDO::FETCH_OBJ);
    }


    public function actualizar(string $id, array $data): bool {
        $sql = "UPDATE Producto SET nombre=?, descripcion=?, precio=?, imagen=?, categoria=?, disponible=? WHERE id=?";
        $stmt = $this->pdo->prepare($sql);
        
        return $stmt->execute([
            $data['nombre'],
            $data['descripcion'] ?? '',
            $data['precio'] ?? 0.0,
            $data['imagen'] ?? '',
            $data['categoria'] ?? '',
            $data['disponible'] ?? 0,
            $id
        ]);
    }


    public function eliminar(string $id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM Producto WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount() > 0;
    }
}