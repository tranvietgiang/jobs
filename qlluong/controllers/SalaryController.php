<?php
require_once __DIR__ . '/../models/Salary.php';
require_once __DIR__ . '/../constant/GLOBAL_VARIABLES.php';

class SalaryController
{
    public function index() {}

    public function calculate(): void
    {
        header('Content-Type: application/json');


        $price = $_SESSION['PRICE'] ?? 35000;
        $hours = (int) ($_POST['hours'] ?? 0);

        if ($hours <= 0) {
            echo json_encode([
                'success' => false,
                'message' => 'Vui lòng nhập giờ hợp lệ',
            ]);
            return;
        }

        $salary = new Salary();
        $salary->price = $price;
        $salary->hours = $hours;
        $totalSalary = $salary->execute_salary($hours);

        echo json_encode([
            'success' => true,
            'total_salary' => $totalSalary,
            'formatted_total_salary' => number_format($totalSalary, 0) . ' VND',
        ]);
    }

    public function create(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            header('Location: /qlluong/views/auth/login.php');
            exit;
        }

        $error = '';

        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST') {
            $workingDate = $_POST['working_date'] ?? '';
            $hours = (int) ($_POST['hours'] ?? 0);

            if ($workingDate === '') {
                $error = 'Vui lòng nhập ngày làm việc';
            } elseif (!DateTime::createFromFormat('Y-m-d', $workingDate)) {
                $error = 'Vui lòng nhập ngày làm việc hợp lệ';
            } elseif (strtotime($workingDate) > time()) {
                $error = 'Ngày làm việc không được là ngày trong tương lai';
            } elseif ($hours <= 0) {
                $error = 'Vui lòng nhập số giờ hợp lệ';
            } else {
                $salary = new Salary();
                $salary->user_id = (int) $_SESSION['user_id'];
                $salary->working_date = $workingDate;
                $salary->name = $_SESSION['user_name'] ?? '';
                $salary->price = $_SESSION['PRICE'] ?? 35000;
                $salary->hours = $hours;
                $salary->execute_salary($hours);
                $salary->insertSalary();

                header('Location: /qlluong/views/salary/home.php');
                exit;
            }
        }

        require __DIR__ . '/../views/salary/insert.php';
    }

    public function getSalary()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $salary = new Salary();
        $salary->user_id = $_SESSION['user_id'];

        return $salary->getSalary();
    }

    public function getSalaryAdmin()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id'])) {
            return null;
        }

        $salary = new Salary();
        $salary->user_id = $_SESSION['user_id'];

        return $salary->getSalaryAdmin();
    }

    public function updateStatus(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (!isset($_SESSION['user_id']) || (int) $_SESSION['user_role'] !== 0) {
            header('Location: /qlluong/views/error/404.php');
            exit;
        }

        $salaryId = (int) ($_POST['salary_id'] ?? 0);

        if ($salaryId <= 0) {
            header('Location: /qlluong/views/salary/home.php');
            exit;
        }

        $salary = new Salary();
        $salary->id = $salaryId;
        $salary->status = 1;
        $salary->updateStatus();

        header('Location: /qlluong/views/salary/home.php');
        exit;
    }
}
