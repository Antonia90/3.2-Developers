<?php
class TagModel {
    private $archivo = __DIR__ . '/../../lib/data/tags.json';

    public function cargar() {
        if (!file_exists($this->archivo)) return [];
        $datos = json_decode(file_get_contents($this->archivo), true) ?? [];
         return array_map(function($tag) {
            return (object) $tag;
        }, $datos);

    }

    public function guardar(array $etiquetas) {
        file_put_contents($this->archivo, json_encode($etiquetas, JSON_PRETTY_PRINT));
    }

    public function obtenerPorId($id) {
        foreach ($this->cargar() as $e) {
          if ($e->id === $id) return $e;  

        }
        return null;
    }


  
    public static function accesAllData(): array {
        $modelo = new self();
        return $modelo->cargar();
    }

    public static function getById($id) {
    $modelo = new self();
        return $modelo->obtenerPorId($id);
    }

}

