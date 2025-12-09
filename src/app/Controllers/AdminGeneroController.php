<?php


namespace App\Controllers;

use App\Models\GeneroModel; 

class AdminGeneroController {
    protected $generoModel;

    public function __construct(\PDO $pdo) {
        $this->generoModel = new GeneroModel($pdo);
    }

    private function handleGet(array $getParams): array {
        if (isset($getParams['id'])) {
            $genero = $this->generoModel->obtenerPorId($getParams['id']);
            if (!$genero) {
                 http_response_code(404);
                 return ['success' => false, 'message' => 'Género no encontrado'];
            }
            return ['success' => true, 'genero' => $genero];
        } else {
            $generos = $this->generoModel->obtenerTodos();
            return ['success' => true, 'generos' => $generos];
        }
    }

    private function handlePost(array $postData): array {
        $id = $postData['id'] ?? null;
        $nombre = trim($postData['nombre'] ?? '');
        $descripcion = $postData['descripcion'] ?? '';

        if (empty($nombre)) {
            throw new \Exception("El nombre es obligatorio");
        }

        if ($id) {
            
            $this->generoModel->actualizar($id, $nombre, $descripcion);
        } else {
            
            $this->generoModel->crear($nombre, $descripcion);
            http_response_code(201); // Creado
        }

        return ['success' => true];
    }

    private function handleDelete(array $input): array {
        $id = $input['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID requerido");
        }

        if (!$this->generoModel->eliminar($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Género no encontrado para eliminar.'];
        }

        return ['success' => true];
    }

    public function handleRequest(string $method, array $input, array $getParams, array $postData) {
        try {
            switch ($method) {
                case 'GET':
                    return $this->handleGet($getParams);
                case 'DELETE':
                    return $this->handleDelete($input);
                case 'POST':
                    return $this->handlePost($postData);
                default:
                    http_response_code(405);
                    return ['success' => false, 'error' => 'Method not permitido.'];
            }
        } catch (\Exception $e) {
            if(!headers_sent()) http_response_code(400); // 400 Bad Request
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}