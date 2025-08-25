<?php

class TagModel {
    private string $archivo = TAG_DATA_FILE;

    public function cargar(): array {
        if (!file_exists($this->archivo)) return [];

        $datos = json_decode(file_get_contents($this->archivo), true) ?? [];
        return array_map(fn($tag) => (object) $tag, $datos);
    }

    public function guardar(array $etiquetas): void {
        file_put_contents($this->archivo, json_encode($etiquetas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public function obtenerPorId(string $id): ?object {
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

