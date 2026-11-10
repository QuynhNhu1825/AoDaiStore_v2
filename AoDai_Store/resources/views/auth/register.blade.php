
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Đăng ký tài khoản | Áo Dài Heritage</title>

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">

    <!-- Font Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #e8ded2;
            border-radius: 10px;
        }

        .password-input::-ms-reveal {
            display: none;
        }
    </style>
</head>

<body class="relative flex items-center justify-center
             min-h-screen bg-gradient-to-br
             from-[#fdfbf9] to-[#f3ece4] overflow-x-hidden">

    <!-- HIỆU ỨNG NỀN -->
    <div class="fixed -top-[10%] -left-[10%]
                w-[40vw] h-[40vw] bg-[#f1e4d5]
                blur-[80px] rounded-full opacity-60
                pointer-events-none">
    </div>

    <div class="fixed -bottom-[10%] -right-[10%]
                w-[40vw] h-[40vw] bg-[#e8ded2]
                blur-[80px] rounded-full opacity-60
                pointer-events-none">
    </div>

    <!-- FORM ĐĂNG KÝ -->
    <div class="relative z-10 w-full max-w-md
                bg-white/90 backdrop-blur-xl
                rounded-[2rem] shadow-2xl
                p-6 sm:p-8 my-6 mx-4
                max-h-[95vh] overflow-y-auto
                custom-scrollbar">

        <!-- HEADER -->
        <div class="text-center mb-6">

            <div class="inline-flex items-center justify-center
                        w-14 h-14 rounded-full
                        bg-red-900/10 text-red-900 mb-3">

                <i class="fas fa-user-plus text-2xl"></i>

            </div>

            <h2 class="text-xl font-bold text-red-900">
                ĐĂNG KÝ
            </h2>

            <p class="text-sm text-gray-500 mt-1">
                Hãy đăng ký để trải nghiệm dịch vụ
            </p>
        </div>

        <!-- THÔNG BÁO THÀNH CÔNG -->
        @if(session('success'))
            <div id="successAlert"
                 role="status"
                 class="mb-4 rounded-xl bg-green-100
                        text-green-700 px-4 py-3 text-sm">

                <i class="fas fa-circle-check mr-1"></i>
                {{ session('success') }}

            </div>
        @endif

        <!-- THÔNG BÁO LỖI -->
        @if($errors->any())
            <div id="errorAlert"
                 role="alert"
                 class="mb-4 rounded-xl bg-red-100
                        text-red-700 px-4 py-3 text-sm">

                <div class="flex items-center gap-2 mb-2">
                    <i class="fas fa-circle-exclamation"></i>
                    <span class="font-semibold">
                        Đăng ký chưa thành công
                    </span>
                </div>

                <ul class="list-disc pl-5 space-y-1">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <!-- FORM -->
        <form method="POST"
              action="{{ route('register') }}"
              class="space-y-4">

            @csrf

            <!-- TÊN ĐĂNG NHẬP -->
            <div>
                <label for="TenDangNhap"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Tên đăng nhập
                </label>

                <input
                    id="TenDangNhap"
                    type="text"
                    name="TenDangNhap"
                    value="{{ old('TenDangNhap') }}"
                    placeholder="Nhập tên đăng nhập"
                    autocomplete="username"
                    maxlength="50"
                    required
                    class="w-full h-12 px-4 rounded-xl
                           bg-[#fcfaf8] border
                           border-gray-200
                           focus:outline-none
                           focus:border-red-800
                           focus:ring-2 focus:ring-red-900/20
                           transition"
                >
            </div>

            <!-- HỌ TÊN -->
            <div>
                <label for="HoTen"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Họ và tên
                </label>

                <input
                    id="HoTen"
                    type="text"
                    name="HoTen"
                    value="{{ old('HoTen') }}"
                    placeholder="Nhập họ và tên"
                    autocomplete="name"
                    required
                    class="w-full h-12 px-4 rounded-xl
                           bg-[#fcfaf8] border
                           border-gray-200
                           focus:outline-none
                           focus:border-red-800
                           focus:ring-2 focus:ring-red-900/20
                           transition"
                >
            </div>

            <!-- EMAIL -->
            <div>
                <label for="Email"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Email
                </label>

                <input
                    id="Email"
                    type="email"
                    name="Email"
                    value="{{ old('Email') }}"
                    placeholder="Nhập địa chỉ email"
                    autocomplete="email"
                    required
                    class="w-full h-12 px-4 rounded-xl
                           bg-[#fcfaf8] border
                           border-gray-200
                           focus:outline-none
                           focus:border-red-800
                           focus:ring-2 focus:ring-red-900/20
                           transition"
                >
            </div>

            <!-- SỐ ĐIỆN THOẠI -->
            <div>
                <label for="SDT"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Số điện thoại
                </label>

                <input
                    id="SDT"
                    type="tel"
                    name="SDT"
                    value="{{ old('SDT') }}"
                    placeholder="Nhập số điện thoại"
                    autocomplete="tel"
                    inputmode="numeric"
                    maxlength="10"
                    class="w-full h-12 px-4 rounded-xl
                           bg-[#fcfaf8] border
                           border-gray-200
                           focus:outline-none
                           focus:border-red-800
                           focus:ring-2 focus:ring-red-900/20
                           transition"
                >
            </div>

            <!-- MẬT KHẨU -->
            <div>
                <label for="MatKhau"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Mật khẩu
                </label>

                <div class="relative">

                    <input
                        id="MatKhau"
                        type="password"
                        name="MatKhau"
                        placeholder="Nhập mật khẩu"
                        autocomplete="new-password"
                        minlength="6"
                        maxlength="32"
                        required
                        class="password-input w-full h-12
                               pl-4 pr-12 rounded-xl
                               bg-[#fcfaf8] border
                               border-gray-200
                               focus:outline-none
                               focus:border-red-800
                               focus:ring-2 focus:ring-red-900/20
                               transition"
                    >

                    <button
                        type="button"
                        class="toggle-password absolute
                               inset-y-0 right-4
                               flex items-center justify-center
                               text-gray-500 hover:text-red-900
                               transition"
                        aria-label="Hiện mật khẩu"
                        aria-pressed="false"
                        aria-controls="MatKhau">

                        <i class="fas fa-eye"></i>

                    </button>

                </div>
            </div>

            <!-- XÁC NHẬN MẬT KHẨU -->
            <div>
                <label for="MatKhau_confirmation"
                       class="block text-xs font-semibold
                              text-gray-600 mb-2">
                    Nhập lại mật khẩu
                </label>

                <div class="relative">

                    <input
                        id="MatKhau_confirmation"
                        type="password"
                        name="MatKhau_confirmation"
                        placeholder="Nhập lại mật khẩu"
                        autocomplete="new-password"
                        minlength="6"
                        maxlength="32"
                        required
                        class="password-input w-full h-12
                               pl-4 pr-12 rounded-xl
                               bg-[#fcfaf8] border
                               border-gray-200
                               focus:outline-none
                               focus:border-red-800
                               focus:ring-2 focus:ring-red-900/20
                               transition"
                    >

                    <button
                        type="button"
                        class="toggle-password absolute
                               inset-y-0 right-4
                               flex items-center justify-center
                               text-gray-500 hover:text-red-900
                               transition"
                        aria-label="Hiện mật khẩu"
                        aria-pressed="false"
                        aria-controls="MatKhau_confirmation">

                        <i class="fas fa-eye"></i>

                    </button>

                </div>
            </div>

            <!-- NÚT ĐĂNG KÝ -->
            <button
                type="submit"
                class="w-full h-12 mt-2 bg-red-900
                       text-white rounded-xl
                       font-bold text-sm
                       hover:bg-red-800
                       active:scale-[0.99]
                       transition">

                <i class="fas fa-user-plus mr-2"></i>
                ĐĂNG KÝ NGAY

            </button>

        </form>

        <!-- FOOTER -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Đã có tài khoản?

            <a href="{{ route('login') }}"
               class="font-bold text-red-900
                      hover:underline">
                Đăng nhập
            </a>
        </p>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {

            // HIỆN / ẨN MẬT KHẨU
            document.querySelectorAll('.toggle-password')
                .forEach(function (button) {

                    button.addEventListener('click', function () {

                        const inputId =
                            this.getAttribute('aria-controls');

                        const input =
                            document.getElementById(inputId);

                        const icon = this.querySelector('i');

                        if (!input || !icon) return;

                        const isHidden =
                            input.type === 'password';

                        input.type = isHidden
                            ? 'text'
                            : 'password';

                        icon.classList.toggle(
                            'fa-eye',
                            !isHidden
                        );

                        icon.classList.toggle(
                            'fa-eye-slash',
                            isHidden
                        );

                        this.setAttribute(
                            'aria-label',
                            isHidden
                                ? 'Ẩn mật khẩu'
                                : 'Hiện mật khẩu'
                        );

                        this.setAttribute(
                            'aria-pressed',
                            String(isHidden)
                        );
                    });
                });

            // THÔNG BÁO TỰ ẨN SAU 5 GIÂY
            ['successAlert', 'errorAlert'].forEach(function (id) {
                const alert = document.getElementById(id);

                if (!alert) return;

                setTimeout(function () {
                    alert.style.transition = 'opacity 0.4s';
                    alert.style.opacity = '0';

                    setTimeout(function () {
                        alert.remove();
                    }, 400);

                }, 5000);
            });

        });
    </script>

</body>
</html>
