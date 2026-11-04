<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký tài khoản</title>
    <!-- Nhúng Tailwind CSS thay cho Bootstrap -->
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* CSS thu gọn thanh cuộn cho card */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #e8ded2; border-radius: 10px; }
    </style>
</head>
<body class="relative flex items-center justify-center min-h-screen bg-gradient-to-br from-[#fdfbf9] to-[#f3ece4] overflow-hidden">

    <!-- blobs (Hiệu ứng nền mờ đồng bộ với login) -->
    <div class="absolute -top-[10%] -left-[10%] w-[40vw] h-[40vw] bg-[#f1e4d5] blur-[80px] rounded-full opacity-60 z-0"></div>
    <div class="absolute -bottom-[10%] -right-[10%] w-[40vw] h-[40vw] bg-[#e8ded2] blur-[80px] rounded-full opacity-60 z-0"></div>

    <!-- main card -->
    <div class="relative z-10 w-full max-w-md bg-white/85 backdrop-blur-xl rounded-[2rem] shadow-2xl p-8 max-h-[95vh] overflow-y-auto custom-scrollbar m-4">
        
        <!-- header -->
        <div class="text-center mb-6">
            <div class="inline-flex p-3 rounded-full bg-red-900/10 text-red-900 mb-3">
                <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" fill="currentColor" viewBox="0 0 16 16" class="text-[#800000]">
                    <path d="M1 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H1zm5-6a3 3 0 1 0 0-6 3 3 0 0 0 0 6z"/>
                    <path fill-rule="evenodd" d="M13.5 5a.5.5 0 0 1 .5.5V7h1.5a.5.5 0 0 1 0 1H14v1.5a.5.5 0 0 1-1 0V8h-1.5a.5.5 0 0 1 0-1H13V5.5a.5.5 0 0 1 .5-.5z"/>
                </svg>
            </div>
            <h2 class="text-xl font-bold text-[#5d534a]">ĐĂNG KÝ</h2>
            <p class="text-sm text-gray-500">Hãy đăng ký để trải nghiệm dịch vụ</p>
        </div>

        <!-- Thông báo lỗi (nếu có) -->
        @if ($errors->any())
            <div class="mb-4 rounded-xl bg-red-100 text-red-700 px-4 py-3 text-sm">
                <ul class="list-disc pl-4 mb-0 space-y-1">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- form -->
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Tên đăng nhập -->
            <input type="text" name="TenDangNhap" value="{{ old('TenDangNhap') }}" placeholder="Tên đăng nhập" required
                   class="w-full h-12 px-4 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">

            <!-- Họ tên -->
            <input type="text" name="HoTen" value="{{ old('HoTen') }}" placeholder="Họ tên" required
                   class="w-full h-12 px-4 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">

            <!-- Email -->
            <input type="email" name="Email" value="{{ old('Email') }}" placeholder="Email" required
                   class="w-full h-12 px-4 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">

            <!-- Số điện thoại -->
            <input type="text" name="SDT" value="{{ old('SDT') }}" placeholder="Số điện thoại"
                   class="w-full h-12 px-4 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">

            <!-- Mật khẩu -->
            <div class="relative">
                <input type="password" name="MatKhau" placeholder="Mật khẩu" required
                       class="password-input w-full h-12 pl-4 pr-12 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">
                <button type="button" class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-500 hover:text-black transition">
                    👁
                </button>
            </div>

            <!-- Nhập lại mật khẩu -->
            <div class="relative">
                <input type="password" name="MatKhau_confirmation" placeholder="Nhập lại mật khẩu" required
                       class="password-input w-full h-12 pl-4 pr-12 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">
                <button
                        type="button"
                        class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-500"
                    >
                        👁
                </button>
            </div>

            <button type="submit" class="w-full h-12 mt-2 bg-red-900 text-white rounded-xl font-bold hover:bg-red-800 transition">
                ĐĂNG KÝ NGAY
            </button>
        </form>

        <!-- Footer -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Đã có tài khoản? 
            <a href="/login" class="font-bold text-red-900 hover:underline">Đăng nhập</a>
        </p>

    </div>

    <script>
    document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function() {
            const input = this.parentElement.querySelector('input');
            const eyeIcon = this.querySelector('.eye-icon');
            const eyeSlashIcon = this.querySelector('.eye-slash-icon');
            
            if (input.type === 'password') {
                input.type = 'text';
                eyeIcon.classList.add('hidden');
                eyeSlashIcon.classList.remove('hidden');
            } else {
                input.type = 'password';
                eyeIcon.classList.remove('hidden');
                eyeSlashIcon.classList.add('hidden');
            }
        });
    });
</script>
</body>
</html>