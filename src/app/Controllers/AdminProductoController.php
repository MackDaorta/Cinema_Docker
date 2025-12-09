<?php

namespace App\Controllers;

use App\Models\ProductoModel; 

class AdminProductoController {
    protected $productoModel;
    private const UPLOAD_DIR = __DIR__ . '/../../uploads/productos/'; 

    public function __construct(\PDO $pdo) {
        $this->productoModel = new ProductoModel($pdo);
    }
    
    private function handleFileUpload(array $fileData, string $imagenActual): string {
        $imagen = $imagenActual;

        if (isset($fileData['imagen']) && $fileData['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($fileData['imagen']['name'], PATHINFO_EXTENSION);
            $nombreArchivo = uniqid('prod_') . '.' . $ext;
            $destino = self::UPLOAD_DIR . $nombreArchivo;

            if (!is_dir(self::UPLOAD_DIR)) {
                mkdir(self::UPLOAD_DIR, 0777, true);
            }

            if (move_uploaded_file($fileData['imagen']['tmp_name'], $destino)) {
                $imagen = $nombreArchivo;
            } else {
                throw new \Exception("Error al subir el archivo de imagen.");
            }
        }
        return $imagen;
    }
    
    private function handleGet(array $getParams): array {
        if (isset($getParams['id'])) {
            $producto = $this->productoModel->obtenerPorId($getParams['id']);
            if (!$producto) {
                 http_response_code(404);
                 return ['success' => false, 'message' => 'Producto no encontrado'];
            }
            return ['success' => true, 'producto' => $producto];
        } else {
            $productos = $this->productoModel->obtenerTodosParaAdmin();
            return ['success' => true, 'productos' => $productos];
        }
    }
    
    private function handlePost(array $postData, array $fileData): array {
        $id = $postData['id'] ?? null;

        $imagenActual = $postData['imagen_actual'] ?? '';
        $imagenNombre = $this->handleFileUpload($fileData, $imagenActual);
        
        $data = [
            'nombre' => trim($postData['nombre'] ?? ''),
            'descripcion' => $postData['descripcion'] ?? '',
            'precio' => filter_var($postData['precio'] ?? 0, FILTER_VALIDATE_FLOAT),
            'categoria' => $postData['categoria'] ?? '',
            'disponible' => isset($postData['disponible']) ? 1 : 0,
            'imagen' => $imagenNombre
        ];
        
        if (empty($data['nombre']) || empty($data['categoria']) || $data['precio'] === false) {
            throw new \Exception("Nombre, Categoría y Precio válidos son obligatorios.");
        }

        if ($id) {
            $this->productoModel->actualizar($id, $data);
        } else {
            $this->productoModel->crear($data);
            http_response_code(201); 
        }

        return ['success' => true];
    }
    
    private function handleDelete(array $input): array {
        $id = $input['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID requerido para eliminar.");
        }

        if (!$this->productoModel->eliminar($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Producto no encontrado para eliminar.'];
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