<?php
require_once __DIR__ . '/../models/TaskModel.php';
require_once __DIR__ . '/../models/TagModel.php';

class TaskController extends ApplicationController {

    // Recolección de datos desde create.phtml
public function createAction() {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $id = rand(1000, 9999);
         $tagId = $_POST["tagId"] ?? null;
        $tagId = $tagId === "" ? null : $tagId;

        $dataTask = [
            "idTask" => $id,
            "descriptionTask" => $_POST["descriptionTask"],
            "userTask" => $_SESSION["user"]["username"],
            "taskTipe" => $_POST["taskTipe"] ?? null,
            "dateTask" => $_POST["dateTask"],
            "taskStatus" => $_POST["taskStatus"] ?? null,
            "tagId" => $tagId
        ];

        $task = new TaskModel($dataTask);
        $task->saveData();

        header("Location: " . BASE_URL . "/userView");
        exit;
    }

    $this->view->tags = TagModel::accesAllData();
}


    // Mostrar todas las tareas del usuario
    public function indexAction() {
        $userTasks = TaskModel::compareUser(); // usa este si quieres filtrar por usuario
        $this->view->tasks = $userTasks;
    }

    public function taskViewAction() {
        $id = TaskModel::getTaskId();
        if ($id === null) {
            echo "ID no válido";
            return;
        }

        $task = TaskModel::findById((int)$id);
        if (!$task) {
            echo "Tarea no encontrada";
            return;
        }

        $this->view->task = $task;
    }

    public function deleteAction() {
        $id = TaskModel::getTaskId();
        if ($id === null) {
            echo "ID no válido";
            return;
        }

        TaskModel::deleteById((int)$id);
        header("Location: " . BASE_URL . "/userView");
        exit;
    }

    public function editAction() {
        $id = TaskModel::getTaskId();
        if ($id === null) {
            echo "ID no válido";
            return;
        }

        $task = TaskModel::findById((int)$id);
        if (!$task) {
            echo "Tarea no encontrada";
            return;
        }

        $this->view->task = $task;
        $this->view->tags = TagModel::accesAllData(); // <-- importante para ver los tags en la edición
    }

public function updateAction() {
    $id = TaskModel::getTaskId();
    if ($id === null) {
        echo "ID no válido";
        return;
    }

    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $tagId = $_POST["tagId"] ?? null;
        $tagId = $tagId === "" ? null : $tagId;

        $newData = [
            "descriptionTask" => $_POST["descriptionTask"],
            "userTask" => $_POST["userTask"],
            "taskTipe" => $_POST["taskTipe"] ?? null,
            "dateTask" => $_POST["dateTask"],
            "taskStatus" => $_POST["taskStatus"] ?? null,
            "tagId" => $tagId
        ];

        TaskModel::updateById((int)$id, $newData);
        header("Location: " . BASE_URL . "/userView");
        exit;
    }
}

}
?>
