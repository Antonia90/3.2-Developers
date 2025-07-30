<?php
class TagController
{
    public function execute($action)
    {
        if (method_exists($this, $action)) {
            $this->$action();
        } else{                       
            throw new Exception("La acción '$action' no existe en TagController");
            
        }
    }

    public function gestionar()
    {
        require_once(ROOT_PATH . '/app/models/TagModel.php');
       



        $model = new TagModel;
        $etiquetas = $model->cargar();

        // Crear etiqueta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_etiqueta'])) {
            $nueva = trim($_POST['nueva_etiqueta']);
            if ($nueva !== '' && !in_array($nueva, array_column($etiquetas, 'nombre'))) {
                $etiquetas[] = ['id' => uniqid(), 'nombre' => $nueva];
                $model->guardar($etiquetas);
            }
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

        // Editar etiqueta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
            foreach ($etiquetas as &$e) {
                if ($e['id'] === $_POST['editar_id']) {
                    $e['nombre'] = trim($_POST['nuevo_nombre']);
                }
            }
            unset($e);
            $model->guardar($etiquetas);
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

        // Eliminar etiqueta
        if (isset($_GET['eliminar'])) {
            $etiquetas = array_filter($etiquetas, fn($e) => $e['id'] !== $_GET['eliminar']);
            $model->guardar(array_values($etiquetas));
            header('Location: ' . $_SERVER['PHP_SELF']);
            exit;
        }

       // Mostrar vista
        include(ROOT_PATH . '/app/views/scripts/tag/TagView.phtml');

    }
}
