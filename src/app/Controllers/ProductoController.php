<?php


namespace App\Controllers;

use App\Models\ProductoModel; 

class ProductoController {
    protected $productoModel;

    public function __construct(\PDO $pdo) {
        $this->productoModel = new ProductoModel($pdo);
    }


    public function obtenerYAgruparProductos(): array {
        $productos = $this->productoModel->obtenerDisponibles();

        $agrupados = [];
        foreach ($productos as $prod) {
            $categoria = $prod->categoria; 
            
            if (!isset($agrupados[$categoria])) {
                $agrupados[$categoria] = [];
            }
            $agrupados[$categoria][] = $prod;
        }
        
        return $agrupados;
    }
}