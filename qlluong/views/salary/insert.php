<?php
if (!isset($error)) {
    require_once __DIR__ . '/../../controllers/SalaryController.php';
    (new SalaryController())->create();
    exit;
}
?>
<!doctype html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thêm Lương</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <main class="grid min-h-screen place-items-center p-6">
        <section class="w-full max-w-[460px] rounded-lg border border-slate-200 bg-white p-8 shadow-sm">
            <div class="mb-6">
                <h1 class="text-2xl font-bold text-slate-900">Thêm lương mới</h1>
            </div>

            <?php if (!empty($error)): ?>
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form id="salary-form" method="post">
                <div class="mb-4">
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="working_date">Ngày làm việc</label>
                    <input class="min-h-11 w-full rounded-md border border-slate-300 px-3 py-2 text-[15px] outline-none focus:border-teal-700 focus:ring-4 focus:ring-teal-700/15" type="date" id="working_date" name="working_date" required>
                </div>


                <div class="mb-5">
                    <label class="mb-2 block text-sm font-bold text-slate-700" for="hours">Số giờ (vd 1 = 1 tiếng)</label>
                    <input class="min-h-11 w-full rounded-md border border-slate-300 px-3 py-2 text-[15px] outline-none focus:border-teal-700 focus:ring-4 focus:ring-teal-700/15" type="number" id="hours" name="hours" min="1" placeholder="Số giờ làm việc" required>
                </div>


                <button id="submit-button" class="min-h-[46px] w-full rounded-md bg-teal-700 text-[15px] font-bold text-white hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-700/20" type="submit">Gửi</button>
            </form>

            <a class="mt-5 block text-center text-sm font-semibold text-teal-700 hover:text-teal-800" href="/qlluong/views/salary/home.php">Quay về trang chủ</a>
        </section>
    </main>

</body>

</html>