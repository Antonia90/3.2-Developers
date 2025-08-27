<?php

declare(strict_types=1);


class UserModel
{
    private $dataFile;

    public function __construct()
    {
        $this->dataFile = USER_DATA_FILE;

        if (!file_exists($this->dataFile)) {
            file_put_contents($this->dataFile, json_encode([]));
        }
    }

    public function getAll()
    {
        $data = file_get_contents($this->dataFile);
        $users = json_decode($data, true);

        return is_array($users) ? $users : [];
    }

    private function saveUsers(array $users): bool
    {
        return file_put_contents($this->dataFile, json_encode($users, JSON_PRETTY_PRINT)) !== false;
    }

    public function create(string $fullname, string $username, string $email, string $password)
    {
        $users = $this->getAll();

        foreach ($users as $user) {
            if (strcasecmp($user['email'], $email) == 0 || strcasecmp($user['username'], $username) == 0) {
                return false;
            }
        }

        $newUser = [
            'id' => uniqid(),
            'fullname' => $fullname,
            'username' => $username,
            'email' => $email,
            'password' => password_hash($password, PASSWORD_DEFAULT)
        ];

        $users[] = $newUser;
        if ($this->saveUsers($users)) {
            return $newUser;
        } else {
            return false;
        }
    }

    public function findById($id)
    {
        foreach ($this->getAll() as $user) {
            if ($user['id'] === $id) {
                return $user;
            }
        }
        return null;
    }

    public function verifyLogin($emailOrUsername, $password)
    {
        foreach ($this->getAll() as $user) {
            if (
                (strcasecmp($user['email'], $emailOrUsername) == 0 || strcasecmp($user['username'], $emailOrUsername) == 0)
                && password_verify($password, $user['password'])
            ) {
                return $user;
            }
        }
        return false;
    }

    public function usernameExists($username): bool
    {
        $users = $this->getAll();
        foreach ($users as $user) {
            if ($user['username'] === $username) {
                return true;
            }
        }
        return false;
    }

    public function updateById(string $id, array $data): bool
    {
        $users = $this->getAll();
        $updated = false;

        foreach ($users as &$user) {
            if ($user['id'] === $id) {
                if (isset($data['fullname'])) {
                    $user['fullname'] = $data['fullname'];
                }
                if (isset($data['username'])) {
                    $user['username'] = $data['username'];
                }
                if (isset($data['email'])) {
                    $user['email'] = $data['email'];
                }
                // Por el momento no se puede actualizar contraseña

                $updated = true;
                break;
            }
        }
        if ($updated) {
            return $this->saveUsers($users);
        }
        return false;
    }

    public function deleteById($id): bool
    {

        $user = $this->findById($id);
        if (!$user) {
            return false;
        }

        $users = $this->getAll();
        $newUsers = [];
        foreach ($users as $u) {
            if ($u['id'] !== $id) {
                $newUsers[] = $u;
            }
        }
        return $this->saveUsers($newUsers);
    }
}
