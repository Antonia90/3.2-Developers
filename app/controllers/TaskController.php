<?php
    require_once __DIR__ . '/../models/TaskModel.php';
    class TaskController extends ApplicationController {


        //recoleccion de datos de create.phtml
        public function createAction(){
            if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $id = rand(1000, 9999);
            $dataTask = [
                "idTask" => $id,
                "descriptionTask" => $_POST["descriptionTask"],
                "userTask" => $_SESSION["user"] ["username"],
                "taskTipe" => $_POST["taskTipe"],
                "dateTask" => $_POST["dateTask"],
                "taskStatus" => $_POST["taskStatus"]
            ];
            //creamos el objeto de tarea con el constructor(modelo).
            $task= new TaskModel($dataTask);

            //Guarda la tarea en el json, ver metodo saveData() en el modelo.
            $task->saveData();
            
            header("Location: " . BASE_URL . "/userView"); //redirecciona a listado de tareas
            exit;
            }

        }

        public function indexAction() { //mostrara todas las tareas
            $userTasks = TaskModel::compareUser();
            $this->view->tasks = $userTasks;
        }

        public function taskViewAction() {

            $id = TaskModel::getTaskId();
            if ($id === null) {
                echo "ID no valido";
                return;
            }

            // Buscar la tarea por ID
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
                echo "ID no valido";
                return;
            }

            TaskModel::deleteById((int)$id);
            header("Location: " . BASE_URL . "/userView");
            exit;
        }

        public function editAction() {
            $id = TaskModel::getTaskId();
            if ($id === null) {
                echo "ID no valido";
                return;
            }

            $task = TaskModel::findById((int)$id);
                if (!$task) {
                echo "Tarea no encontrada";
                return;
                }

            $this->view->task = $task;
        }   

        public function updateAction() {
            $id = TaskModel::getTaskId();
            if ($id === null) {
                echo "ID no valido";
                return;
            }


            if ($_SERVER["REQUEST_METHOD"] === "POST") { //si se ha enviado el formulario correctamente entra.
                $newData = [
                    "descriptionTask" => $_POST["descriptionTask"],
                    "userTask" => $_POST["userTask"],
                    "taskTipe" => $_POST["taskTipe"],
                    "dateTask" => $_POST["dateTask"],
                    "taskStatus" => $_POST["taskStatus"]
                ];

                TaskModel::updateById((int)$id, $newData);
                header("Location: " . BASE_URL . "/userView");
                exit;
            }
        }

    }

?>