<?php

namespace App\Controllers;

use App\Models\AnuncioModel;

class AdminAnuncioController {
    protected $anuncioModel;

    public function __construct(\PDO $pdo) {
        $this->anuncioModel = new AnuncioModel($pdo);
    }
    
    private function handleGet(array $getParams): array {
        if (isset($getParams['id'])) {
            $anuncio = $this->anuncioModel->obtenerPorId($getParams['id']);
            if (!$anuncio) {
                 http_response_code(404);
                 return ['success' => false, 'message' => 'Anuncio no encontrado'];
            }
            return ['success' => true, 'anuncio' => $anuncio];
        } else {
            $anuncios = $this->anuncioModel->obtenerTodos();
            return ['success' => true, 'anuncios' => $anuncios];
        }
    }
    
    private function handlePost(array $postData, array $fileData): array {
        $id = $postData['id'] ?? null;
        
        $imagenNombre = $postData['imagen_actual'] ?? '';
        
        if (isset($fileData['imagen']) && $fileData['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($fileData['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('ads_') . '.' . $ext;
            
            $destino = __DIR__ . '/../../uploads/anuncios/' . $nuevoNombre; 
            
            if (!is_dir(dirname($destino))) {
                mkdir(dirname($destino), 0777, true);
            }

            if (move_uploaded_file($fileData['imagen']['tmp_name'], $destino)) {
                $imagenNombre = $nuevoNombre;
            } else {
                throw new \Exception("Error al subir el archivo de imagen.");
            }
        }
        
        $data = [
            'nombre' => $postData['nombre'] ?? '',
            'tipo' => $postData['tipo'] ?? '',
            'link' => $postData['link'] ?? '',
            'vigencia' => !empty($postData['vigencia']) ? $postData['vigencia'] : null, 
            'imagen' => $imagenNombre
        ];
        
        if (empty($data['nombre']) || empty($data['tipo'])) {
            throw new \Exception("Nombre y Tipo son obligatorios.");
        }

        if ($id) {
            $this->anuncioModel->actualizar($id, $data);
        } else {
            $this->anuncioModel->crear($data);
            http_response_code(201); // Created
        }

        return ['success' => true];
    }
    
    private function handleDelete(array $input): array {
        $id = $input['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID requerido para eliminar.");
        }
        
        $this->anuncioModel->eliminar($id);
        
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
            if(!headers_sent()) http_response_code(400); // 400 Bad Request para errores de validación/lógica
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }
}