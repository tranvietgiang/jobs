<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <title>404 - Page Not Found</title>
    <!-- Tailwind CSS + Google Fonts for better typography -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,300;14..32,400;14..32,600;14..32,700&display=swap" rel="stylesheet">
    <style>
        * {
            font-family: 'Inter', system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
        }

        body {
            background: linear-gradient(145deg, #f9fafc 0%, #f1f5f9 100%);
        }

        /* subtle floating animation for the 404 number */
        @keyframes float {
            0% {
                transform: translateY(0px);
            }

            50% {
                transform: translateY(-12px);
            }

            100% {
                transform: translateY(0px);
            }
        }

        .floating-number {
            animation: float 4s ease-in-out infinite;
        }

        /* custom hover transitions for buttons */
        .btn-transition {
            transition: all 0.2s cubic-bezier(0.23, 1, 0.32, 1);
        }

        .btn-transition:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 20px -12px rgba(0, 0, 0, 0.2);
        }
    </style>
</head>

<body class="antialiased">
    <div class="min-h-screen flex items-center justify-center px-6 py-12 md:py-24">
        <div class="max-w-2xl w-full text-center">
            <!-- Main 404 illustration area -->
            <div class="relative mb-8 md:mb-12">
                <!-- large background circle -->
                <div class="absolute inset-0 flex items-center justify-center -z-0">
                    <div class="w-64 h-64 md:w-80 md:h-80 bg-gradient-to-br from-red-100 to-amber-100 rounded-full opacity-60 blur-2xl"></div>
                </div>
                <!-- floating 404 number -->
                <div class="floating-number relative z-10">
                    <h1 class="text-[110px] md:text-[180px] font-black tracking-tighter text-transparent bg-clip-text bg-gradient-to-r from-red-500 via-orange-500 to-amber-600 drop-shadow-lg">
                        404
                    </h1>
                </div>
            </div>

            <!-- Error message -->
            <div class="space-y-4 mb-10">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-800 tracking-tight">
                    Trang không tồn tại
                </h2>
                <p class="text-lg md:text-xl text-slate-500 max-w-lg mx-auto leading-relaxed">
                    Có vẻ như bạn đã đi lạc vào vùng đất chưa được khám phá.
                    Đường dẫn này không có thật hoặc đã bị di chuyển.
                </p>
                <!-- subtle original message reminder (optional) -->
                <p class="text-sm text-slate-400 italic">"The page you are looking for does not exist."</p>
            </div>

            <!-- Three action buttons (ba = three in Vietnamese) -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 sm:gap-5 mt-6">
                <!-- Button 1: Trở về trang chủ -->
                <a href="/qlluong/views/auth/login.php" class="btn-transition inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-gradient-to-r from-slate-800 to-slate-900 hover:from-slate-700 hover:to-slate-800 text-white font-semibold rounded-2xl shadow-lg shadow-slate-200 transition-all duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Trang chủ
                </a>

                <!-- Button 2: Liên hệ hỗ trợ -->
                <a href="/contact" class="btn-transition inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-white border border-slate-200 hover:border-red-200 hover:bg-red-50 text-slate-700 hover:text-red-600 font-semibold rounded-2xl shadow-sm transition-all duration-200 group">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-all group-hover:scale-105" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M18.364 5.636L16.95 7.05M12 3v2m-6.364 2.636L7.05 7.05M21 12h-2M3 12H1m3.364 6.364L5.05 16.95M16.95 16.95L18.364 18.364M12 21v-2m-3.05-7.05a3 3 0 116.1 0 3 3 0 01-6.1 0z" />
                    </svg>
                    Hỗ trợ
                </a>

                <!-- Button 3: Tìm kiếm nội dung -->
                <button onclick="alert('🔍 Tính năng tìm kiếm sẽ sớm ra mắt! Quay lại trang chủ để khám phá.')" class="btn-transition inline-flex items-center justify-center gap-2 px-6 py-3.5 bg-amber-50 border border-amber-200 hover:bg-amber-100 text-amber-800 font-semibold rounded-2xl transition-all duration-200 group cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition-all group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                    </svg>
                    Tìm kiếm
                </button>
            </div>

            <!-- helpful suggestion with three example paths (ba gợi ý) -->
            <div class="mt-12 pt-6 border-t border-slate-200/60 max-w-md mx-auto">
                <p class="text-xs text-slate-400 uppercase tracking-wider mb-3 font-semibold">✨ Thử những đường dẫn sau ✨</p>
                <div class="flex flex-wrap justify-center gap-x-5 gap-y-2 text-sm font-mono text-slate-500">
                    <span class="bg-slate-100 px-3 py-1 rounded-full">/trang-chu</span>
                    <span class="bg-slate-100 px-3 py-1 rounded-full">/san-pham</span>
                    <span class="bg-slate-100 px-3 py-1 rounded-full">/lien-he</span>
                </div>
            </div>

            <!-- tiny decorative footer -->
            <div class="mt-12 text-center text-xs text-slate-400">
                <span>⚡ 404 — Không tìm thấy nội dung · Hãy kiểm tra lại URL</span>
            </div>
        </div>
    </div>
</body>

</html>