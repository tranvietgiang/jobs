<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../config/Db.php';
class Salary extends Db
{
    public int $id;
    public int $user_id;
    public string $working_date;
    public string $name;
    public int $price;
    public int $hours;
    public float $total_salary;
    public int $status = 0;

    public function getSalary(): ?array
    {
        $sql = self::$connection->prepare(
            "SELECT s.*, s.total_salary AS total, u.name AS user_name
            FROM salaries s
            JOIN users u ON u.id = s.user_id
            WHERE s.user_id = ?
            ORDER BY s.id DESC"
        );

        $sql->bind_param("i", $this->user_id);
        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getSalaryAdmin(): ?array
    {
        $sql = self::$connection->prepare(
            "SELECT s.*, s.total_salary AS total, u.name AS user_name
            FROM salaries s
            JOIN users u ON u.id = s.user_id
            ORDER BY s.name ASC, s.id DESC"
        );


        $sql->execute();

        return $sql->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertSalary(): void
    {
        $sql = self::$connection->prepare(
            "INSERT INTO salaries (
            user_id, 
            working_date,
            name, 
            price, 
            hours, 
            total_salary,
            status) 
            VALUES (?, ?, ?, ?, ?, ?, ?)"
        );
        $sql->bind_param("issiiii", $this->user_id, $this->working_date, $this->name, $this->price, $this->hours, $this->total_salary, $this->status);
        $sql->execute();
    }

    public function updateStatus(): void
    {
        $sql = self::$connection->prepare(
            "UPDATE salaries SET status = ? WHERE id = ?"
        );
        $sql->bind_param("ii", $this->status, $this->id);
        $sql->execute();
    }

    public function execute_salary(int $hours): float
    {
        $eat = $_SESSION['EAT'];

        if ($hours > 8) {
            $eat = $_SESSION['EAT'] * 3;
        } elseif ($hours > 4 && $hours <= 8) {
            $eat = $_SESSION['EAT'] * 2;
        }

        $petrol = $_SESSION['PETROL'];
        $drink = $_SESSION['DRINK'];

        $this->total_salary =
            ($this->price * $hours)
            + $eat
            + $petrol
            + $drink;
        return $this->total_salary;
    }
}
