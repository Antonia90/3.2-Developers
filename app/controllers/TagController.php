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
            if ($nueva !== '' && !in_array($nueva, array_column($etiquetas, 'nombre'))) {
                $etiquetas[] = ['id' => uniqid(), 'nombre' => $nueva];
                $model->guardar($etiquetas);
            }
            header('Location: ' . BASE_URL . '/tags');
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
            header('Location: ' . BASE_URL . '/tags');
            exit;
        }

        // Eliminar etiqueta
        if (isset($_GET['eliminar'])) {
            $etiquetas = array_filter($etiquetas, fn($e) => $e['id'] !== $_GET['eliminar']);
            $model->guardar(array_values($etiquetas));
            header('Location: ' . BASE_URL . '/tags');
            exit;
        }
        $this->view->etiquetas = $etiquetas;
        $this->view->settings->title = 'Gestión de Etiquetas';
        $this->view->render('tag/TagView.phtml'); // no incluir layouts manualmente
        exit;
    }
}
