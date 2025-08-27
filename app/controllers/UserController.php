<?php
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../models/UserModel.php';
require_once __DIR__ . '/../models/TaskModel.php';

class UserController extends ApplicationController
{
    public function indexAction() {}
    public function signupAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $_SESSION['username'] = $_POST['username']; // para que no se pierda. cambio nombre de variable de sesion 'nameUser'
            $email = trim($_POST['email'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $errors = [];

            if ($fullname === '' || strlen($fullname) < 3) $errors[] = "Nombre completo inválido.";
            if ($username === '' || strlen($username) < 3) $errors[] = "Usuario inválido.";
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido.";
            if ($password === '' || strlen($password) < 6) $errors[] = "Contraseña demasiado corta.";


            if (!empty($errors)) {
                $this->view->errors = $errors;
                $this->view->render('user/signup.phtml');
                exit;
            }

            $model = new UserModel();
            $created = $model->create($fullname, $username, $email, $password);

            if (!$created) {
                $this->view->errors = ["El usuario o email ya existe."];
                $this->view->render('user/signup.phtml');
                exit;
            }
            echo "<p style='color:green;'>Usuario creado correctamente</p>";
            $_SESSION['user'] = $created;
            header('Location: ' . BASE_URL . '/userView');
            exit;
        }
    }


    public function loginAction()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $input = trim($_POST['emailOrUsername'] ?? '');
            $password = trim($_POST['password'] ?? '');

            $model = new UserModel();
            $user = $model->verifyLogin($input, $password);

            if ($user) {
                $_SESSION['user'] = $user;
                header('Location: ' . BASE_URL . '/userView');
                exit;
            } else {
                $this->view->error = "Datos inválidos.";
            }
        }
    }

    public function userViewAction()
    {

        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->view->user = $_SESSION['user'];
        $tasks = TaskModel::getAllWithTags(); // ✅ Esto sí trae etiquetas
        $this->view->tasks = $tasks;

        $statusFilter = $_GET['status'] ?? null; //traemos el get de la pestaña
        $tipeFilter   = $_GET['tipe'] ?? null;


        $allUserTasks = TaskModel::compareUser(); //hay que ponerlo aqui para el flujo de programa
        $filteredTasks = [];

        foreach ($allUserTasks as $task) {

            if ($statusFilter && $task->taskStatus->value !== $statusFilter) {
                continue;
            }

            if ($tipeFilter && $task->taskTipe->value !== $tipeFilter) {
                continue;
            }

            $filteredTasks[] = $task; // si ha pasado los dos filtros anteriores se añade al array
        }

        $this->view->tasks = $filteredTasks; //se mostrara el array
        if (count($filteredTasks) === 0) {
            $this->view->message = "No hay tareas que coincidan con los filtros.";
        }
    }


    public function deleteAction()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $model = new UserModel();
            $deleted = $model->deleteById($user['id']);

            if ($deleted) {
                session_destroy();
                header('Location: ' . BASE_URL . '/?deleted=1');
                exit;
            } else {
                $this->view->error = 'No se pudo eliminar el usuario. Intente de nuevo.';
            }
        }

        $this->view->user = $user;
    }

    public function updateAction()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $model = new UserModel();
        $user = $_SESSION['user'];

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $fullname = trim($_POST['fullname'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $email    = trim($_POST['email'] ?? '');

            $errors = [];

            if ($fullname === '' || strlen($fullname) < 3) $errors[] = "Nombre completo inválido.";
            if ($username === '' || strlen($username) < 3) $errors[] = "Usuario inválido.";
            if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Email inválido.";


            $allUsers = $model->getAll();
            foreach ($allUsers as $otherUser) {
                if ($otherUser['id'] !== $user['id']) {
                    if (strcasecmp($otherUser['username'], $username) === 0) {
                        $errors[] = "El nombre de usuario ya está en uso.";
                    }
                    if (strcasecmp($otherUser['email'], $email) === 0) {
                        $errors[] = "El email ya está en uso.";
                    }
                }
            }

            if (!empty($errors)) {
                $this->view->errors = $errors;
                $this->view->user = ['fullname' => $fullname, 'username' => $username, 'email' => $email];
                return;
            }

            $updated = $model->updateById($user['id'], [
                'fullname' => $fullname,
                'username' => $username,
                'email'    => $email
            ]);

            if ($updated) {
                $_SESSION['user']['fullname'] = $fullname;
                $_SESSION['user']['username'] = $username;
                $_SESSION['user']['email'] = $email;

                header('Location: ' . BASE_URL . '/userView');
                exit;
            } else {
                $this->view->error = "No se pudo actualizar el usuario.";
                $this->view->user = ['fullname' => $fullname, 'username' => $username, 'email' => $email];
                return;
            }
        } else {
            $this->view->user = $user;
        }
    }

    public function logoutAction()
    {
        session_destroy();
        session_start();
        header('Location: ' . BASE_URL . '/logoutSuccess');
        exit;
    }

    public function logoutSuccessAction() {}
}
