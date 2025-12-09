<?php

namespace App\Controllers;

use App\Models\SalaModel;

class SalaController {
    protected $salaModel;

    public function __construct(\PDO $pdo) {
        $this->salaModel = new SalaModel($pdo);
    }

    
    public function obtenerSalas(): array {
        return $this->salaModel->obtenerTodasPublicas();
    }
}