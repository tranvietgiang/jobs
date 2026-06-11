<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_role']) || !isset($_SESSION['user_id'])) {
    header('Location: /qlluong/views/error/404.php');
    exit;
}

require_once __DIR__ . '/../../controllers/SalaryController.php';

$isAdmin = (int) $_SESSION['user_role'] === 0;

if ($isAdmin) {
    $salary = (new SalaryController())->getSalaryAdmin();
} else {
    $salary = (new SalaryController())->getSalary();
}

// $salary = (new SalaryController())->getSalary();
$latestSalaries = $salary ?? [];
$selectedEmployee = '';
$employeeOptions = [];

if ($isAdmin) {
    foreach ($latestSalaries as $s) {
        $employeeName = $s['name'] ?? '';
        if ($employeeName !== '') {
            $employeeOptions[$employeeName] = $employeeName;
        }
    }

    ksort($employeeOptions, SORT_NATURAL | SORT_FLAG_CASE);
    $selectedEmployee = trim($_GET['employee_name'] ?? '');

    if ($selectedEmployee !== '') {
        $latestSalaries = array_values(array_filter(
            $latestSalaries,
            fn($s) => ($s['name'] ?? '') === $selectedEmployee
        ));
    }
}

$salaryCount = count($latestSalaries);
$totalSalary = 0;
$unpaidSalary = 0;
$personalTotals = [];
$personalRowCounts = [];

if (!empty($latestSalaries)) {
    foreach ($latestSalaries as $s) {
        $employeeName = $s['name'] ?? '';
        $personalRowCounts[$employeeName] = ($personalRowCounts[$employeeName] ?? 0) + 1;

        if ((int) ($s['status'] ?? 0) === 1) {
            $totalSalary += (float) ($s['total_salary'] ?? 0);
        } else {
            $salaryTotal = (float) ($s['total_salary'] ?? 0);
            $unpaidSalary += $salaryTotal;
            $personalTotals[$employeeName] = ($personalTotals[$employeeName] ?? 0) + $salaryTotal;
        }
    }
}

$userCount = isset($_SESSION['user_id']) ? 1 : 0;

?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home - Payroll</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <header class="border-b border-slate-200 bg-white">
        <div class="mx-auto flex max-w-6xl items-center justify-between px-6 py-4">
            <div class="flex items-center gap-3">
                <span class="grid h-10 w-10 place-items-center rounded-lg bg-teal-700 font-bold text-white">QL</span>
                <div>
                    <p class="text-xs text-slate-500">Payroll management</p>
                    <h1 class="text-lg font-bold text-slate-900">Dashboard</h1>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <span class="text-sm text-slate-600">Hi, <?php echo htmlspecialchars($_SESSION['user_name'], ENT_QUOTES, 'UTF-8'); ?></span>
                <a class="rounded-md bg-slate-900 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-700" href="/qlluong/views/auth/logout.php">Logout</a>
            </div>
        </div>
    </header>

    <main class="mx-auto max-w-6xl px-6 py-8">
        <section class="mb-8">
            <h2 class="text-2xl font-bold text-slate-950">Trang chủ</h2>
            <p class="mt-1 text-sm text-slate-500">Tổng quan về người dùng và hồ sơ lương</p>
        </section>

        <?php if ($_SESSION['user_role'] == 1) : ?>
            <div>
                <a href="/qlluong/views/salary/insert.php">
                    <button class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-600">Thêm hồ sơ lương mới</button>
                </a>
            </div>
        <?php endif; ?>

        <?php if (!$isAdmin): ?>
        <div class="mb-8 flex items-center gap-4 mt-2">
            <input type="text" placeholder="Tìm kiếm hồ sơ lương..." class="flex-1 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm focus:border-teal-500 focus:ring-teal-500">
            <button class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-600">Tìm kiếm</button>
        </div>

        <?php endif; ?>

        <?php if ($isAdmin): ?>
            <form method="get" class="mb-4 flex items-center gap-4 mt-2">
                <select name="employee_name" class="flex-1 rounded-md border border-slate-300 bg-white px-4 py-2 text-sm focus:border-teal-500 focus:ring-teal-500" onchange="this.form.submit()">
                    <option value="">Tat ca nhan vien</option>
                    <?php foreach ($employeeOptions as $employeeName): ?>
                        <option value="<?php echo htmlspecialchars($employeeName, ENT_QUOTES, 'UTF-8'); ?>" <?php echo $selectedEmployee === $employeeName ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($employeeName, ENT_QUOTES, 'UTF-8'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-600">Loc</button>
                <?php if ($selectedEmployee !== ''): ?>
                    <a class="rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300" href="/qlluong/views/salary/home.php">Bo loc</a>
                <?php endif; ?>
            </form>
        <?php endif; ?>

        <?php if ($_SESSION['user_role'] != 1): ?>
            <section class="grid gap-4 md:grid-cols-4">
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Người dùng</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950"><?php echo number_format($userCount); ?></p>
                </article>
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">Hồ sơ lương</p>
                    <p class="mt-3 text-3xl font-bold text-slate-950"><?php echo number_format($salaryCount); ?></p>
                </article>
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">✓ Đã thanh toán</p>
                    <p class="mt-3 text-3xl font-bold text-green-700"><?php echo number_format($totalSalary, 0); ?> VND</p>
                </article>
                <article class="rounded-lg border border-slate-200 bg-white p-5 shadow-sm">
                    <p class="text-sm font-medium text-slate-500">⏳ Chưa thanh toán</p>
                    <p class="mt-3 text-3xl font-bold text-yellow-700"><?php echo number_format($unpaidSalary, 0); ?> VND</p>
                </article>
            </section>
        <?php endif; ?>
        <section class="mt-8 rounded-lg border border-slate-200 bg-white shadow-sm">
            <div class="border-b border-slate-200 px-5 py-4">
                <h3 class="font-bold text-slate-950">Mức lương mới nhất</h3>
            </div>

            <div class="px-5 py-4 bg-blue-50 border-b border-blue-100">
                <p class="text-sm font-semibold text-blue-900 mb-3">📋 Công thức tính lương:</p>
                <div class="text-xs text-blue-800 space-y-3">
                    <p><strong>Tổng Lương = (Lương/Giờ × Số Giờ) + Ăn + Xăng + Nước</strong></p>
                    <p class="font-semibold">Quy tắc tính tiền Ăn:</p>
                    <table class="w-full border border-blue-300 text-xs mb-3">
                        <tr class="bg-blue-100">
                            <th class="border border-blue-300 px-3 py-2 text-left">Số giờ làm việc</th>
                            <th class="border border-blue-300 px-3 py-2 text-left">Tiền Ăn</th>
                        </tr>
                        <tr>
                            <td class="border border-blue-300 px-3 py-2">≤ 4 tiếng</td>
                            <td class="border border-blue-300 px-3 py-2">1× (<?php echo number_format($_SESSION['EAT'] ?? 0, 0); ?> VND)</td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td class="border border-blue-300 px-3 py-2">4 &lt; giờ ≤ 8 tiếng</td>
                            <td class="border border-blue-300 px-3 py-2">2× (<?php echo number_format(($_SESSION['EAT'] ?? 0) * 2, 0); ?> VND)</td>
                        </tr>
                        <tr>
                            <td class="border border-blue-300 px-3 py-2">&gt; 8 tiếng</td>
                            <td class="border border-blue-300 px-3 py-2">3× (<?php echo number_format(($_SESSION['EAT'] ?? 0) * 3, 0); ?> VND)</td>
                        </tr>
                    </table>
                    <p class="font-semibold">Chi phí khác:</p>
                    <table class="w-full border border-blue-300 text-xs mb-3">
                        <tr class="bg-blue-100">
                            <th class="border border-blue-300 px-3 py-2 text-left">Loại</th>
                            <th class="border border-blue-300 px-3 py-2 text-left">Số tiền</th>
                        </tr>
                        <tr>
                            <td class="border border-blue-300 px-3 py-2">Xăng</td>
                            <td class="border border-blue-300 px-3 py-2"><?php echo number_format($_SESSION['PETROL'] ?? 0, 0); ?> VND</td>
                        </tr>
                        <tr class="bg-blue-50">
                            <td class="border border-blue-300 px-3 py-2">Nước uống</td>
                            <td class="border border-blue-300 px-3 py-2"><?php echo number_format($_SESSION['DRINK'] ?? 0, 0); ?> VND</td>
                        </tr>
                    </table>
                    <p class="font-semibold bg-yellow-100 border border-yellow-300 p-2 rounded">
                        📌 Ví dụ: Lương 35,000/giờ, làm 6 giờ → (35,000 × 6) + (<?php echo number_format(($_SESSION['EAT'] ?? 0) * 2, 0); ?>) + <?php echo number_format($_SESSION['PETROL'] ?? 0, 0); ?> + <?php echo number_format($_SESSION['DRINK'] ?? 0, 0); ?> = <?php echo number_format((50000 * 6) + (($_SESSION['EAT'] ?? 0) * 2) + ($_SESSION['PETROL'] ?? 0) + ($_SESSION['DRINK'] ?? 0), 0); ?> VND
                    </p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-100 text-slate-600">
                        <tr>
                            <th class="px-5 py-3 font-semibold">STT</th>
                            <?php if ($_SESSION['user_role'] == 0): ?>
                                <th class="px-5 py-3 font-semibold">Tên nhân viên</th>
                            <?php endif; ?>
                            <th class="px-5 py-3 font-semibold">Ngày làm việc</th>
                            <th class="px-5 py-3 text-right font-semibold">Lương/Giờ</th>
                            <th class="px-5 py-3 text-right font-semibold">Giờ</th>
                            <th class="px-5 py-3 text-right font-semibold">Tổng cộng</th>
                            <th class="px-5 py-3 text-right font-semibold">Tổng cá nhân</th>
                            <th class="px-5 py-3 text-right font-semibold">Trạng thái</th>
                            <?php if ($_SESSION['user_role'] == 0): ?>
                                <th class="px-5 py-3 font-semibold">Hành động</th>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        <?php if (empty($latestSalaries)): ?>
                            <tr>
                                <td class="px-5 py-5 text-center text-slate-500" colspan="<?= ($_SESSION['user_role'] == 0) ? 9 : 7 ?>">Chưa có thông tin mức lương</td>
                            </tr>
                        <?php endif; ?>

                        <?php $previousName = null; ?>
                        <?php foreach ($latestSalaries as $index => $salary): ?>
                            <?php
                            $currentName = $salary['name'] ?? '';
                            $isFirstRowOfEmployee = $currentName !== $previousName;
                            $nextName = $latestSalaries[$index + 1]['name'] ?? null;
                            $isLastRowOfEmployee = $nextName !== $currentName;
                            $groupBorderClass = $isAdmin ? 'border-b-[8px] border-yellow-300' : '';
                            $rowBorderClass = ($isAdmin && $isLastRowOfEmployee) ? $groupBorderClass : '';
                            $previousName = $currentName;
                            ?>
                            <tr>
                                <td class="px-5 py-4 font-medium text-slate-900 <?php echo $rowBorderClass; ?>"><?php echo $index + 1; ?></td>
                                <?php if ($isAdmin && $isFirstRowOfEmployee): ?>
                                    <td rowspan="<?php echo (int) ($personalRowCounts[$currentName] ?? 1); ?>" class="px-5 py-4 align-middle font-semibold text-slate-800 bg-slate-50 <?php echo $groupBorderClass; ?>"><?php echo htmlspecialchars($salary['name'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <?php endif; ?>
                                <td class="px-5 py-4 text-slate-600 <?php echo $rowBorderClass; ?>"><?php echo htmlspecialchars($salary['working_date'], ENT_QUOTES, 'UTF-8'); ?></td>
                                <td class="px-5 py-4 text-right text-slate-600 <?php echo $rowBorderClass; ?>"><?php echo number_format((float) $salary['price'], 0); ?></td>
                                <td class="px-5 py-4 text-right text-slate-600 <?php echo $rowBorderClass; ?>"><?php echo number_format((int) $salary['hours']); ?></td>
                                <td class="px-5 py-4 text-right font-semibold text-slate-900 <?php echo $rowBorderClass; ?>"><?php echo number_format((float) $salary['total'], 0); ?> VND</td>
                                <?php if ($isFirstRowOfEmployee): ?>
                                    <td rowspan="<?php echo (int) ($personalRowCounts[$currentName] ?? 1); ?>" class="px-5 py-4 align-middle text-right font-bold text-teal-700 bg-teal-50 <?php echo $groupBorderClass; ?>"><?php echo number_format($personalTotals[$currentName] ?? 0, 0); ?> VND</td>
                                <?php endif; ?>
                                <td class="px-5 py-4 text-right <?php echo $rowBorderClass; ?>">
                                    <?php if ((int) $salary['status'] === 1): ?>
                                        <span class="inline-block px-3 py-1 rounded-full bg-green-100 text-green-800 text-xs font-semibold">✓ Đã thanh toán</span>
                                    <?php else: ?>
                                        <?php if ($isAdmin): ?>
                                            <form method="post" action="/qlluong/views/salary/update.php" class="inline">
                                                <input type="hidden" name="salary_id" value="<?php echo (int) $salary['id']; ?>">
                                                <button type="submit" class="inline-flex items-center gap-2 rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-800 hover:bg-yellow-200">⏳ Chưa thanh toán</button>
                                            </form>
                                        <?php else: ?>
                                            <span class="inline-block px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs font-semibold">⏳ Chưa thanh toán</span>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                                <?php if ($isAdmin): ?>
                                    <td class="px-5 py-4 text-slate-600 <?php echo $rowBorderClass; ?>">
                                        <?php if ((int) $salary['status'] === 0): ?>
                                            <form method="post" action="/qlluong/views/salary/update.php">
                                                <input type="hidden" name="salary_id" value="<?php echo (int) $salary['id']; ?>">
                                                <button type="submit" class="rounded-md px-4 py-2 bg-green-500 hover:bg-green-700 text-white text-sm font-medium">thanh toán</button>
                                            </form>
                                        <?php endif; ?>
                                    </td>
                                <?php endif; ?>
                            </tr>
                        <?php endforeach; ?>

                        <?php if (!empty($latestSalaries)): ?>
                            <tr class="bg-slate-50 font-bold">

                                <td class="px-5 py-4 text-slate-900"
                                    colspan="<?= ($_SESSION['user_role'] == 0) ? 8 : 6 ?>">
                                    Tổng chưa thanh toán
                                </td>
                                <td class="px-5 py-4 text-right text-yellow-700"><?php echo number_format($unpaidSalary, 0); ?> VND</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
    </main>
</body>

</html>
