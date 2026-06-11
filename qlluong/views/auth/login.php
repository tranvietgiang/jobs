<?php
if (!isset($error)) {
    require_once __DIR__ . '/../../controllers/UserController.php';
    (new UserController())->login();
    exit;
}
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Payroll</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-slate-50 text-slate-800">
    <main class="grid min-h-screen place-items-center bg-[linear-gradient(135deg,rgba(20,184,166,0.16),rgba(245,158,11,0.14))] p-6">
        <section class="w-full max-w-[420px] rounded-lg border border-slate-200 bg-white p-8 shadow-[0_22px_60px_rgba(15,23,42,0.12)] sm:p-6" aria-labelledby="login-title">
            <div class="mb-7 flex items-center gap-3.5">
                <span class="grid h-12 w-12 place-items-center rounded-lg bg-teal-700 font-bold text-white">QL</span>
                <div>
                    <p class="mb-1 text-[13px] text-slate-500">Payroll management</p>
                    <h1 id="login-title" class="text-[28px] font-bold leading-tight text-slate-900">Sign in</h1>
                </div>
            </div>

            <?php if (!empty($success)): ?>
                <div class="mb-4 rounded-md border border-green-300 bg-green-50 p-3 text-sm text-green-800"><?php echo htmlspecialchars($success, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <?php if (!empty($error)): ?>
                <div class="mb-4 rounded-md border border-red-200 bg-red-50 p-3 text-sm text-red-800"><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></div>
            <?php endif; ?>

            <form method="post">
                <div class="mb-4">
                    <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Username</label>
                    <input class="min-h-11 w-full rounded-md border border-slate-300 px-3 py-2 text-[15px] text-slate-900 outline-none focus:border-teal-700 focus:ring-4 focus:ring-teal-700/15" type="text" id="name" name="name" autocomplete="username" required autofocus>
                </div>

                <div class="mb-5">
                    <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Password</label>
                    <input class="min-h-11 w-full rounded-md border border-slate-300 px-3 py-2 text-[15px] text-slate-900 outline-none focus:border-teal-700 focus:ring-4 focus:ring-teal-700/15" type="password" id="password" name="password" autocomplete="current-password" required>
                </div>

                <button class="min-h-[46px] w-full rounded-md bg-teal-700 text-[15px] font-bold text-white hover:bg-teal-800 focus:outline-none focus:ring-4 focus:ring-teal-700/20" type="submit">Login</button>
            </form>

            <p class="mt-5 text-center text-[13px] text-slate-500">Demo account: admin / 123456</p>
            <p class="mt-2 text-center text-[13px] text-slate-500">
                Chua co tai khoan?
                <a class="font-semibold text-teal-700 hover:text-teal-800" href="/qlluong/views/auth/register.php">Dang ky</a>
            </p>
        </section>
    </main>
</body>

</html>
