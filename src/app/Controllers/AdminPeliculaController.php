<?php

namespace App\Controllers;

use App\Models\PeliculaModel;

class AdminPeliculaController {
    protected $peliculaModel;
    protected $pdo;
    private const UPLOAD_DIR = __DIR__ . '/../../uploads/peliculas/'; 

    public function __construct(\PDO $pdo) {
        $this->pdo = $pdo; 
        $this->peliculaModel = new PeliculaModel($pdo);
    }
    
    private function generateUuidV4(): string {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff), mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
    }
    
    private function handleFileUpload(array $fileData, string $imagenActual): string {
        $imagen = $imagenActual;

        if (isset($fileData['imagen']) && $fileData['imagen']['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($fileData['imagen']['name'], PATHINFO_EXTENSION);
            $nuevoNombre = uniqid('peli_') . '.' . $ext;
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
        if (isset($getParams['action']) && $getParams['action'] === 'options') {
            $salas = $this->peliculaModel->obtenerOpcionesSalas();
            $generos = $this->peliculaModel->obtenerOpcionesGeneros();
            return ['success' => true, 'salas' => $salas, 'generos' => $generos];
        }
        
        if (isset($getParams['id'])) {
            $id = $getParams['id'];
            $pelicula = $this->peliculaModel->obtenerPorId($id);
            $salasIds = $this->peliculaModel->obtenerSalasIds($id);
            $generosIds = $this->peliculaModel->obtenerGenerosIds($id);

            return [
                'success' => true, 
                'pelicula' => $pelicula,
                'salas_ids' => $salasIds,
                'generos_ids' => $generosIds
            ];
        } 
        
        
        return ['success' => true, 'peliculas' => $this->peliculaModel->obtenerListadoAdmin()];
    }

    private function handlePost(array $postData, array $fileData): array {
        $id = $postData['id'] ?? null; 
        
        $data = [
            'nombre' => trim($postData['nombre'] ?? ''),
            'sinopsis' => $postData['sinopsis'] ?? '',
            'duracion' => filter_var($postData['duracion_minutos'] ?? 0, FILTER_VALIDATE_INT),
            'fecha' => $postData['fecha_estreno'] ?? '',
            'restriccion' => $postData['restriccion'] ?? '',
        ];
        
        if (empty($data['nombre']) || empty($data['fecha']) || $data['duracion'] === false) {
             throw new \Exception("Datos de película incompletos o inválidos.");
        }
        
        $imagenActual = $postData['imagen_actual'] ?? '';
        $data['imagenNombre'] = $this->handleFileUpload($fileData, $imagenActual);

        $salasSeleccionadas = $postData['salas'] ?? []; 
        $generosSeleccionados = $postData['generos'] ?? [];
        
        $this->pdo->beginTransaction();

        try {
            if ($id) {
                
                $this->peliculaModel->actualizarPelicula($id, $data);
                
                $this->peliculaModel->limpiarRelaciones($id);
            } else {
                $id = $this->generateUuidV4();
                $this->peliculaModel->crearPelicula($id, $data);
            }

            $this->peliculaModel->insertarSalas($id, $salasSeleccionadas);
            $this->peliculaModel->insertarGeneros($id, $generosSeleccionados);

            $this->pdo->commit();
            http_response_code($postData['id'] ? 200 : 201);
            return ['success' => true, 'message' => 'Película guardada'];

        } catch (\Exception $e) {
            if ($this->pdo->inTransaction()) $this->pdo->rollBack();
            throw $e; 
        }
    }

    private function handleDelete(array $input): array {
        $id = $input['id'] ?? null;
        if (!$id) {
            throw new \Exception("ID requerido");
        }
        
     
        if (!$this->peliculaModel->eliminarPelicula($id)) {
            http_response_code(404);
            return ['success' => false, 'message' => 'Película no encontrada para eliminar.'];
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