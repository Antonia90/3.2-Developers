<?php
<<<<<<< HEAD
   
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
        public ?int $tagId = null; // nueva propiedad
       
        const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

        public function __construct( array $data = []) {
            $this->idTask = $data["idTask"];
            $this->descriptionTask = $data["descriptionTask"];
            $this->userTask = $data["userTask"];
            $this->dateTask = new DateTime($data["dateTask"]);
            $this->taskTipe = TaskTipe::from($data["taskTipe"]);
            $this->taskStatus = TaskStatus::from($data["taskStatus"]);
            $this->tagId = $data["tagId"] ?? null;

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
                    "taskStatus" => $task->taskStatus->value,
                    "tagId" => $task->tagId // añadido para conectarlo con etiquetas
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
                    if (isset($newData['tagId'])) {
                        $task->tagId = $newData['tagId'];
                    }

=======
require_once __DIR__ . "/TagModel.php";
require_once __DIR__ . '/../enums/taskEnums.php';
require_once __DIR__ . '/../../config/constants.php';

class TaskModel {
    public int $idTask;
    public string $descriptionTask;
    public string $userTask;
    public DateTime $dateTask;
    public TaskTipe $taskTipe;
    public TaskStatus $taskStatus;
    public ?string $tagId = null; 
    public ?stdClass $tag = null;

    const FILE_PATH = __DIR__ . "/../../lib/data/tasks.json";

  public function __construct(array $data = []) {
    $this->idTask = $data["idTask"];
    $this->descriptionTask = $data["descriptionTask"];
    $this->userTask = $data["userTask"];
    $this->dateTask = new DateTime($data["dateTask"]);

    $this->taskTipe = array_key_exists("taskTipe", $data) ? TaskTipe::from($data["taskTipe"]) : TaskTipe::FORMACION;
    $this->taskStatus = array_key_exists("taskStatus", $data) ? TaskStatus::from($data["taskStatus"]) : TaskStatus::PENDIENTE;

    $this->tagId = array_key_exists("tagId", $data) && $data["tagId"] !== '' ? $data["tagId"] : null;
}


    public function saveData(): void {
        $tasks = self::accesAllData();
        $tasks[] = $this;
        self::saveAllData($tasks);
    }

    public static function saveAllData(array $tasks): void {
        $data = array_map(function ($task) {
            return [
                "idTask" => $task->idTask,
                "descriptionTask" => $task->descriptionTask,
                "userTask" => $task->userTask,
                "dateTask" => $task->dateTask->format('Y-m-d'),
                "taskTipe" => $task->taskTipe->value,
                "taskStatus" => $task->taskStatus->value,
                "tagId" => $task->tagId
            ];
        }, $tasks);

        file_put_contents(self::FILE_PATH, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    public static function accesAllData(): array {
        if (!file_exists(self::FILE_PATH)) {
            return [];
        }

        $json = file_get_contents(self::FILE_PATH);
        $data = json_decode($json, true) ?? [];
        return array_map(fn($task) => new self($task), $data);
    }

    public static function deleteById(int $id): void {
        $tasks = self::accesAllData();
        $tasks = array_filter($tasks, fn($task) => $task->idTask !== $id);
        self::saveAllData(array_values($tasks));
    }

    public static function updateById(int $id, array $newData): void {
        $tasks = self::accesAllData();

        foreach ($tasks as &$task) {
            if ($task->idTask === $id) {
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
>>>>>>> 75b1eb1a03486903d514bfd62c1f040471fec0e2
                }
                if (array_key_exists('tagId', $newData)) {
                    $task->tagId = $newData['tagId'] !== '' ? $newData['tagId'] : null;
                }
            }

                self::saveAllData($tasks);
        }

<<<<<<< HEAD
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
=======
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
        if (!$id || !is_numeric($id)) {
            return null;
        }
        return (int)$id;
    }

   public static function compareUser(): array {
    $idUser = $_SESSION["user"]["username"] ?? null;
    $userTask = [];
    $tasks = self::getAllWithTags(); //  CAMBIO: usa las tareas que ya traen el objeto `tag`

    foreach ($tasks as $task) {
        if ($idUser === $task->userTask) {
            $userTask[] = $task;
        }
    }
    return $userTask;
}


    public static function getAllWithTags(): array {
        $tasks = self::accesAllData();
        $tags = TagModel::accesAllData();

        $tagMap = [];
        foreach ($tags as $tag) {
            $tagMap[$tag->id] = $tag;
        }

        foreach ($tasks as $task) {
            $task->tag = $task->tagId && isset($tagMap[$task->tagId]) ? $tagMap[$task->tagId] : null;
        }

        return $tasks;
    }

    public static function taskStatusFilter(?TaskStatus $status = null): array {
        $userTasks = self::compareUser();
        $userTaskStatus = [];

        foreach ($userTasks as $userTask) {
            if ($status === null || $userTask->taskStatus === $status) {
                $userTaskStatus[] = $userTask;
>>>>>>> 75b1eb1a03486903d514bfd62c1f040471fec0e2
            }
            return $userTask;
        }
    }
<<<<<<< HEAD
?>
=======

    public static function taskTipeFilter(?TaskTipe $tipe = null): array {
        $userTasks = self::compareUser();
        $userTaskTipe = [];

        foreach ($userTasks as $userTask) {
            if ($tipe === null || $userTask->taskTipe === $tipe) {
                $userTaskTipe[] = $userTask;
            }
        }
        return $userTaskTipe;
    }
}
>>>>>>> 75b1eb1a03486903d514bfd62c1f040471fec0e2
