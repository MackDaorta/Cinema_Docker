<?php

namespace App\Controllers;

use App\Models\PeliculaModel;

class PeliculaController {
    protected $peliculaModel;

    public function __construct(\PDO $pdo) {
        $this->peliculaModel = new PeliculaModel($pdo);
    }

    public function obtenerPeliculasEnriquecidas(): array {
        $peliculas = $this->peliculaModel->obtenerVigentesPublicas();

        if (empty($peliculas)) {
            return [];
        }

        $generos_relacionados = $this->peliculaModel->obtenerRelacionesGeneros();
        $salas_relacionadas = $this->peliculaModel->obtenerRelacionesSalas();

        return $this->peliculaModel->enriquecerPeliculas($peliculas, $generos_relacionados, $salas_relacionadas);
    }
}