<?php
require_once(ROOT_PATH . '/app/models/TagModel.php');

class TagController extends ApplicationController
{
    public function gestionarAction()
    {
        $model = new TagModel;
        $etiquetas = $model->cargar();

        // Crear etiqueta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nueva_etiqueta'])) {
            $nueva = trim($_POST['nueva_etiqueta']);
            if ($nueva !== '' && !in_array($nueva, array_map(fn($e) => $e->nombre, $etiquetas))) {
                $nuevaEtiqueta = new stdClass();
                $nuevaEtiqueta->id = uniqid();
                $nuevaEtiqueta->nombre = $nueva;
                $etiquetas[] = $nuevaEtiqueta;
                $model->guardar($etiquetas);
            }
            header('Location: ' . BASE_URL . '/tags');
            exit;
        }

        // Editar etiqueta
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['editar_id'])) {
            foreach ($etiquetas as $e) {
                if ($e->id === $_POST['editar_id']) {
                    $e->nombre = trim($_POST['nuevo_nombre']);
                }
            }
            $model->guardar($etiquetas);
            header('Location: ' . BASE_URL . '/tags');
            exit;
        }

        // Eliminar etiqueta
        if (isset($_GET['eliminar'])) {
            $etiquetas = array_filter($etiquetas, fn($e) => $e->id !== $_GET['eliminar']);
            $model->guardar(array_values($etiquetas));
            header('Location: ' . BASE_URL . '/tags');
            exit;
        }

        $this->view->etiquetas = $etiquetas;
        $this->view->settings->title = 'Gestión de Etiquetas';
        $this->view->render('tag/TagView.phtml');
        exit;
    }
}
