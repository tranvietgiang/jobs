<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Db.php';

class User extends Db
{
    public int $id;
    public string $name;
    public string $password;
    public int $role;


    public function login(string $name, string $password): bool
    {
        $sql = self::$connection->prepare("SELECT * FROM `users` WHERE `name` = ?");
        $sql->bind_param("s", $name);
        $sql->execute();

        $result = $sql->get_result()->fetch_assoc();

        if ($result && password_verify($password, $result['password'])) {
            $this->id = (int) $result['id'];
            $this->name = $result['name'];
            $this->password = $result['password'];
            $this->role = (int) $result['role'];

            return true;
        }

        return false;
    }

    public function exists(string $name): bool
    {
        $sql = self::$connection->prepare("SELECT `id` FROM `users` WHERE `name` = ? LIMIT 1");
        $sql->bind_param("s", $name);
        $sql->execute();

        return (bool) $sql->get_result()->fetch_assoc();
    }

    public function register(string $name, string $password, int $role = 1): bool
    {
        if ($this->exists($name)) {
            return false;
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $sql = self::$connection->prepare("INSERT INTO `users` (`name`, `password`, `role`) VALUES (?, ?, ?)");
        $sql->bind_param("ssi", $name, $hashedPassword, $role);

        if (!$sql->execute()) {
            return false;
        }

        $this->id = (int) self::$connection->insert_id;
        $this->name = $name;
        $this->password = $hashedPassword;
        $this->role = $role;

        return true;
    }
}
