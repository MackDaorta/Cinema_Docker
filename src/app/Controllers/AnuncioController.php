<?php

namespace App\Controllers;

use App\Models\AnuncioModel;

class AnuncioController {
    protected $anuncioModel;

    public function __construct(\PDO $pdo) {
        $this->anuncioModel = new AnuncioModel($pdo);
    }

    
    public function obtenerAnunciosPublicos(): array {
        $sliders = $this->anuncioModel->obtenerSliders();
        $promociones = $this->anuncioModel->obtenerPromociones();
        
        return [
            'sliders' => $sliders,
            'promociones' => $promociones
        ];
    }
}