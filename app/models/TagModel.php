<?php
class TagModel
 {
   private $archivo = TAG_DATA_FILE;

    public function cargar()
    {
        if(!file_exists($this->archivo))return[];
        return json_decode(file_get_contents($this->archivo),true)??[];
    }
    public function guardar(array $etiquetas)
    {
        file_put_contents($this->archivo,json_encode($etiquetas,JSON_PRETTY_PRINT)); 
    }
    
    public function obtenerPorId($id)
    {
        foreach ($this->cargar() as $e) {

          if($e['id']===$id)return $e; 
               
        }
        return null;
    }
}
