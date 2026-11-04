<!-- LOGIN MODAL -->
<div id="loginModal"
     class="fixed inset-0 z-50 hidden items-center justify-center bg-black/40">

    <!-- blobs -->
    <div class="absolute -top-24 -left-24 w-[40vw] h-[40vw] bg-[#f1e4d5] blur-[80px] rounded-full opacity-60"></div>
    <div class="absolute -bottom-24 -right-24 w-[40vw] h-[40vw] bg-[#e8ded2] blur-[80px] rounded-full opacity-60"></div>

    <!-- modal box -->
    <div class="relative w-full max-w-md bg-white/85 backdrop-blur-xl rounded-3xl shadow-2xl p-8">

        <!-- close -->
        <button onclick="closeLoginModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-black text-xl">
            ✕
        </button>

        <!-- header -->
        <div class="text-center mb-6">
            @if (session('success'))
                <div id="successAlert"
                    class="mb-4 rounded-xl bg-green-100 text-green-700 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>

                <script>
                    setTimeout(() => {
                        document.getElementById('successAlert')?.remove();
                    }, 5000);
                </script>
            @endif
            @if ($errors->any())
                <div id="errorAlert" class="mb-4 rounded-xl bg-red-100 text-red-700 px-4 py-3 text-sm">
                    {{ $errors->first() }}
                </div>
                <script>
                    setTimeout(() => {
                        document.getElementById('errorAlert')?.remove();
                    }, 5000);
                </script>
            @endif
            <h2 class="text-xl font-bold text-[#5d534a]">ĐĂNG NHẬP</h2>
            <p class="text-sm text-gray-500">Chào mừng bạn đã quay trở lại</p>
        </div>

        <!-- form -->
        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <input type="text"
                   name="TenDangNhap"
                   value="{{ old('TenDangNhap') }}"
                   placeholder="Tên đăng nhập"
                   class="w-full h-12 px-4 rounded-xl bg-[#fcfaf8] focus:outline-none focus:ring-2 focus:ring-red-900/30">

                <div class="relative">
                    <input
                        type="password"
                        name="MatKhau"
                        placeholder="Mật khẩu"
                        class="password-input w-full rounded-lg border px-4 py-3 pr-12
                            focus:outline-none focus:ring-2 focus:ring-stone-400"
                        required
                    >

                    <button
                        type="button"
                        class="toggle-password absolute inset-y-0 right-3 flex items-center text-gray-500"
                    >
                        👁
                    </button>
                </div>

            <button type="submit"
                    class="w-full h-12 bg-red-900 text-white rounded-xl font-bold hover:bg-red-800 transition">
                ĐĂNG NHẬP
            </button>
        </form>

        <p class="text-center text-sm text-gray-500 mt-6">
            Chưa có tài khoản?
            <a href="/register" class="font-bold text-red-900">Đăng ký</a>
        </p>
    </div>
</div>
