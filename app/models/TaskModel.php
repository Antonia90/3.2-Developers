<?php

    enum TaskStatus : string {
        case PENDIENTE = "pendiente";
        case ACABADA = "acabada";
        case EMPEZADA = "empezada";
    }
    enum TaskTipe : string {
        case REUNION = "reunion";
        case REVISION = "revision";
        case DESARROLLO = "desarrollo";
        case FORMACION = "formacion";
    }

    class TaskModel {
        public int $idTask;
        public string $descriptionTask;
        public string $userTask;
        public DateTime $dateTask;
        public TaskTipe $taskTipe;
        public TaskStatus $taskStatus;
        const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

        public function __construct( array $data = []) {
            $this->idTask = $data["idTask"];
            $this->descriptionTask = $data["descriptionTask"];
            $this->userTask = $data["userTask"];
            $this->dateTask = new DateTime($data["dateTask"]);
            $this->taskTipe = TaskTipe::from($data["taskTipe"]);
            $this->taskStatus = TaskStatus::from($data["taskStatus"]);
        }

        public function saveData(): void {
            $tasks = self::accesAllData(); //carga tareas existentes metodo allData() mas abajo
            $tasks[] = $this;   //agrega esta tarea al array
            self::saveAllData($tasks);  //guarda el array completo en el json.
        }

        public static function accesAllData(): array{
            if (!file_exists(self::FILE_PATH)) {
            return [];
            }

            $json = file_get_contents(self::FILE_PATH); //lee el contenido del json como string
            $data = json_decode($json, true) ?? []; //convierte el string anterior como un array asociativo
                //el ?? [], es por si el decode falla devuelve un un array vacio
            return array_map(fn($item) => new self($item), $data);
            // fn($item) => new self($item) trasnforma un array de arrays en un array de objetos,asi cada tarea sera un objeto.
        }

        public static function saveAllData(array $tasks): void {
            $data = array_map(function ($task) {
                return [
                    "idTask" => $task->idTask,
                    "descriptionTask" => $task->descriptionTask,
                    "userTask" => $task->userTask,
                    "dateTask" => $task->dateTask->format('Y-m-d'),
                    "taskTipe" => $task->taskTipe->value,
                    "taskStatus" => $task->taskStatus->value
                ];
            }, $tasks);

        file_put_contents(self::FILE_PATH, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }

        public static function deleteById(int $id): void {
            $tasks = self::accesAllData();
            $tasks = array_filter($tasks, fn($task) => $task->idTask !== $id);
            self::saveAllData(array_values($tasks)); // reindexamos el array
        }

        public static function updateById(int $id, array $newData): void {
            $tasks = self::accesAllData();

            foreach ($tasks as &$task) {
                if ($task->idTask === $id) {
                    // Actualizar campos si existen en $newData
                    if (isset($newData['descriptionTask'])) {
                        $task->descriptionTask = $newData['descriptionTask'];
                    }
                    if (isset($newData['userTask'])) {
                        $task->userTask = $newData['userTask'];
                    }
                    if (isset($newData['dateTask'])) {
                        $task->dateTask = new DateTime($newData['dateTask']);
                    }
                    if (isset($newData['taskTipe'])) {
                        $task->taskTipe = TaskTipe::from($newData['taskTipe']);
                    }
                    if (isset($newData['taskStatus'])) {
                        $task->taskStatus = TaskStatus::from($newData['taskStatus']);
                    }
                }
            }

                self::saveAllData($tasks);
        }

        public static function findById(int $id): ?self {
            $tasks = self::accesAllData();
            foreach ($tasks as $task) {
                if ($task->idTask === $id) {
                    return $task;
                }
            }
            return null;
        }

        public static function getTaskId(): ?int {
            $id = $_GET['id'] ?? null;

            // Validar que sea numérico
            if (!$id || !is_numeric($id)) {
                return null;
            }
            return (int)$id;
        }
        public static function compareUser() { //nuevo
            $idUser = $_POST["username"];
            $userTask = [];

            $tasks = self::accesAllData();
            foreach ($tasks as $task) {
                if ($idUser === $task["userTask"]) {
                    $userTask[] = $task;
                }
            }
            return $userTask;
        }
    }
?>