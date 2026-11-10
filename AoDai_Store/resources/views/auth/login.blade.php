
<!-- LOGIN MODAL -->
<div id="loginModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">

    <!-- HIỆU ỨNG NỀN -->
    <div class="absolute -top-24 -left-24 w-[40vw] h-[40vw]
                bg-[#f1e4d5] blur-[80px] rounded-full opacity-60
                pointer-events-none"></div>

    <div class="absolute -bottom-24 -right-24 w-[40vw] h-[40vw]
                bg-[#e8ded2] blur-[80px] rounded-full opacity-60
                pointer-events-none"></div>

    <!-- MODAL BOX -->
    <div class="relative w-full max-w-md mx-4
                bg-white/85 backdrop-blur-xl
                rounded-3xl shadow-2xl p-8">

        <!-- NÚT ĐÓNG -->
        <button type="button"
                onclick="closeLoginModal()"
                class="absolute top-4 right-4
                       text-gray-400 hover:text-black text-xl">
            ✕
        </button>

        <!-- HEADER -->
        <div class="text-center mb-6">

            <!-- THÔNG BÁO THÀNH CÔNG -->
            @if(session('success'))
                <div id="loginSuccessAlert"
                     class="mb-4 rounded-xl bg-green-100
                            text-green-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <!-- THÔNG BÁO LỖI -->
            @if($errors->any())
                <div id="loginErrorAlert"
                     class="mb-4 rounded-xl bg-red-100
                            text-red-700 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
            @endif

            <h2 class="text-xl font-bold text-[#5d534a]">
                ĐĂNG NHẬP
            </h2>

            <p class="text-sm text-gray-500">
                Chào mừng bạn đã quay trở lại
            </p>
        </div>

        <!-- FORM ĐĂNG NHẬP -->
        <form method="POST"
              action="{{ route('login') }}"
              class="space-y-4">

            @csrf

            <!-- TÊN ĐĂNG NHẬP -->
            <input
                type="text"
                name="TenDangNhap"
                value="{{ old('TenDangNhap') }}"
                placeholder="Tên đăng nhập"
                autocomplete="username"
                required
                class="w-full h-12 px-4 rounded-xl
                       bg-[#fcfaf8] border border-gray-200
                       focus:outline-none
                       focus:ring-2 focus:ring-red-900/30"
            >

            <!-- MẬT KHẨU -->
            <div class="relative">

                <input
                    id="loginPassword"
                    type="password"
                    name="MatKhau"
                    placeholder="Mật khẩu"
                    autocomplete="current-password"
                    required
                    class="w-full h-12 pl-4 pr-12 rounded-xl
                           bg-[#fcfaf8] border border-gray-200
                           focus:outline-none
                           focus:ring-2 focus:ring-red-900/30"
                >

                <!-- NÚT HIỆN / ẨN MẬT KHẨU -->
                <button
                    type="button"
                    id="toggleLoginPassword"
                    class="absolute inset-y-0 right-4
                           flex items-center justify-center
                           text-gray-500 hover:text-red-900
                           transition"
                    aria-label="Hiện mật khẩu"
                    aria-controls="loginPassword"
                    aria-pressed="false">

                    <i class="fas fa-eye"></i>

                </button>
            </div>

            <!-- NÚT ĐĂNG NHẬP -->
            <button
                type="submit"
                class="w-full h-12 bg-red-900
                       text-white rounded-xl font-bold
                       hover:bg-red-800 transition">

                ĐĂNG NHẬP

            </button>
        </form>

        <!-- ĐĂNG KÝ -->
        <p class="text-center text-sm text-gray-500 mt-6">
            Chưa có tài khoản?

            <a href="{{ route('register') }}"
               class="font-bold text-red-900 hover:underline">
                Đăng ký
            </a>
        </p>
    </div>
</div>

<!-- JAVASCRIPT -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    // HIỆN / ẨN MẬT KHẨU
    const passwordInput = document.getElementById('loginPassword');
    const toggleButton = document.getElementById('toggleLoginPassword');

    if (passwordInput && toggleButton) {
        toggleButton.addEventListener('click', function () {

            const icon = this.querySelector('i');
            const showPassword = passwordInput.type === 'password';

            passwordInput.type = showPassword ? 'text' : 'password';

            icon.classList.toggle('fa-eye', !showPassword);
            icon.classList.toggle('fa-eye-slash', showPassword);

            this.setAttribute(
                'aria-label',
                showPassword ? 'Ẩn mật khẩu' : 'Hiện mật khẩu'
            );

            this.setAttribute('aria-pressed', String(showPassword));
        });
    }

    // THÔNG BÁO TỰ ẨN SAU 5 GIÂY
    ['loginSuccessAlert', 'loginErrorAlert'].forEach(function (id) {

        const alert = document.getElementById(id);

        if (!alert) return;

        setTimeout(function () {
            alert.style.transition = 'opacity 0.3s';
            alert.style.opacity = '0';

            setTimeout(function () {
                alert.remove();
            }, 300);
        }, 5000);
    });

});
</script>
