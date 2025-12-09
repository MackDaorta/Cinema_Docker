<?php

namespace App\Controllers;

use App\Models\SalaModel; 

class AdminSalaController {
    protected $salaModel;
    private const UPLOAD_DIR = __DIR__ . '/../../uploads/salas/'; 

    public function __construct(\PDO $pdo) {
        $this->salaModel = new SalaModel($pdo);
    }
    private function handleFileUpload(array $fileData, string $imagenActual): string {
        $imagen = $imagenActual;

        if (isset($fileData['imagen']) && $fileData['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($fileData['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('sala_') . '.' . $ext;
            $destino = self::UPLOAD_DIR . $nuevoNombre;
            
            if (!is_dir(self::UPLOAD_DIR)) {
                mkdir(self::UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($fileData['imagen']['tmp_name'], $destino)) {
                $imagen = $nuevoNombre;
            } else {
                throw new \Exception("Error al subir el archivo de imagen.");
            }
        }
        return $imagen;
    }

    private function handleGet(array $getParams): array {
        if (isset($getParams['id'])) {
            $sala = $this->salaModel->obtenerPorId($getParams['id']);
            return ['success' => true, 'sala' => $sala];
        } else {
            $salas = $this->salaModel->obtenerTodos();
            return ['success' => true, 'salas' => $salas];
        }
    }

    private function handlePost(array $postData, array $fileData): array {
        $id = $postData['id'] ?? null;
        $nombre = trim($postData['nombre'] ?? '');
        $descripcion = $postData['descripcion'] ?? '';
        
        $imagenActual = $postData['imagen_actual'] ?? '';
        
        if (empty($nombre)) {
            throw new \Exception("El nombre es obligatorio.");
        }
        
        $imagenNombre = $this->handleFileUpload($fileData, $imagenActual);

        if ($id) {
            $this->salaModel->actualizar($id, $nombre, $descripcion, $imagenNombre);
        } else {
            $this->salaModel->crear($nombre, $descripcion, $imagenNombre);
            http_response_code(201); // Creado
        }

        return ['success' => true];
    }

    private function handleDelete(array $input): array {
        $id = $input['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID requerido.");
        }

        if (!$this->salaModel->eliminar($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Sala no encontrada para eliminar.'];
        }

        return ['success' => true];
    }

    public function handleRequest(string $method, array $input, array $getParams, array $postData, array $fileData) {
        try {
            switch ($method) {
                case 'GET':
                    return $this->handleGet($getParams);
                case 'DELETE':
                    return $this->handleDelete($input);
                case 'POST':
                    return $this->handlePost($postData, $fileData);
                default:
                    http_response_code(405);
                    return ['success' => false, 'error' => 'Method not permitido.'];
            }
        } catch (\Exception $e) {
            if(!headers_sent()) http_response_code(400); 
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}