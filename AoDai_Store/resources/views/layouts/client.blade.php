<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Áo Dài Heritage') - Áo Dài Heritage</title>

    {{-- ==================== TAILWIND ==================== --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- ==================== CSS ==================== --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    {{-- ==================== SWIPER ==================== --}}
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">

    {{-- ==================== SWEETALERT2 ==================== --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- ==================== GOOGLE FONTS ==================== --}}
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display&family=Roboto:wght@300;400;700&display=swap');

        body {
            font-family: 'Roboto', sans-serif;
        }

        .serif {
            font-family: 'Playfair Display', serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-stone-800">

    {{-- ==================== HEADER ==================== --}}
    @include('partials.client._header')

    {{-- ==================== NỘI DUNG ==================== --}}
    <main>
        @yield('content')
    </main>

    {{-- ==================== LOGIN POPUP ==================== --}}
    @include('auth.login')

    {{-- ==================== FOOTER ==================== --}}
    @include('partials.client._footer')


    {{-- ========================================================
        XỬ LÝ MODAL ĐĂNG NHẬP
    ======================================================== --}}
    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');

            if (!modal) return;

            modal.classList.remove('hidden');
            modal.classList.add('flex');
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');

            if (!modal) return;

            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }

        // Hiển thị / ẩn mật khẩu
        document.addEventListener('click', function (event) {

            const button = event.target.closest('.toggle-password');

            if (!button) return;

            const wrapper = button.closest('.relative');

            if (!wrapper) return;

            const input = wrapper.querySelector('.password-input');

            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                button.textContent = '👁‍🗨️';
            } else {
                input.type = 'password';
                button.textContent = '👁';
            }
        });

        // Tự mở modal khi URL có ?login=1
        document.addEventListener('DOMContentLoaded', function () {

            const url = new URL(window.location.href);

            if (url.searchParams.has('login')) {

                openLoginModal();

                url.searchParams.delete('login');

                window.history.replaceState(
                    {},
                    document.title,
                    url.pathname + url.search + url.hash
                );
            }
        });
    </script>


    {{-- ========================================================
        TỰ MỞ MODAL KHI ĐĂNG NHẬP THẤT BẠI
    ======================================================== --}}
    @if(
        $errors->has('TenDangNhap') ||
        $errors->has('MatKhau') ||
        $errors->has('password')
    )
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                openLoginModal();
            });
        </script>
    @endif


    {{-- ========================================================
        CẬP NHẬT BADGE GIỎ HÀNG KHÁCH VÃNG LAI
    ======================================================== --}}
    <script>
        const heritageIsGuest = @json(!Auth::check());

        function updateGuestCartBadge() {

            if (!heritageIsGuest) return;

            const cartBadge = document.getElementById('header-cart-count');

            if (!cartBadge) return;

            let guestCart = [];

            try {
                const saved = JSON.parse(
                    localStorage.getItem('guest_cart') || '[]'
                );

                guestCart = Array.isArray(saved) ? saved : [];

            } catch (error) {
                console.error(
                    'Dữ liệu giỏ hàng khách không hợp lệ:',
                    error
                );
                return;
            }

            const totalQuantity = guestCart.reduce(function (total, item) {

                const quantity = Number(item.SoLuong) || 0;

                return total + Math.max(0, quantity);

            }, 0);

            cartBadge.textContent = totalQuantity;
        }

        document.addEventListener('DOMContentLoaded', function () {
            updateGuestCartBadge();
        });
    </script>


    {{-- ========================================================
        ĐỒNG BỘ GIỎ HÀNG SAU ĐĂNG NHẬP

        Chỉ chạy khi:
        - Người dùng đã đăng nhập
        - Laravel session có merge_cart_pending
        - LocalStorage có guest_cart

        Không đồng bộ ở mọi lần tải trang.
    ======================================================== --}}
    @if(Auth::check() && session('merge_cart_pending'))
        <script>
            document.addEventListener('DOMContentLoaded', async function () {

                const storageKey = 'guest_cart';
                const guestSelectedKey = 'heritage_guest_selected_cart';
                const mergeLockKey = 'heritage_cart_merging';

                let guestCart = [];

                try {

                    const savedCart = JSON.parse(
                        localStorage.getItem(storageKey) || '[]'
                    );

                    guestCart = Array.isArray(savedCart)
                        ? savedCart
                        : [];

                } catch (error) {

                    console.error(
                        'Không thể đọc giỏ hàng khách:',
                        error
                    );

                    return;
                }

                // Không có sản phẩm để đồng bộ
                if (guestCart.length === 0) {
                    return;
                }

                // Tránh gửi yêu cầu trùng trong cùng tab
                if (sessionStorage.getItem(mergeLockKey) === '1') {
                    return;
                }

                sessionStorage.setItem(mergeLockKey, '1');

                try {

                    const response = await fetch(
                        "{{ route('cart.merge') }}",
                        {
                            method: 'POST',

                            credentials: 'same-origin',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',

                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]'
                                ).content
                            },

                            body: JSON.stringify({
                                items: guestCart
                            })
                        }
                    );

                    const result = await response.json();

                    if (!response.ok || !result.success) {

                        throw new Error(
                            result.message ||
                            'Không thể đồng bộ giỏ hàng.'
                        );
                    }

                    // Server đã đồng bộ thành công
                    // Mới xóa giỏ hàng khách ở trình duyệt
                    localStorage.removeItem(storageKey);

                    sessionStorage.removeItem(guestSelectedKey);

                    console.log(
                        'Đồng bộ giỏ hàng sau đăng nhập thành công.'
                    );

                    // Tải lại để cập nhật dữ liệu giỏ hàng từ MySQL
                    window.location.reload();

                } catch (error) {

                    console.error(
                        'Lỗi đồng bộ giỏ hàng:',
                        error
                    );

                } finally {

                    sessionStorage.removeItem(mergeLockKey);
                }
            });
        </script>
    @endif


    {{-- ========================================================
        THÔNG BÁO THÀNH CÔNG
    ======================================================== --}}
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                Swal.fire({
                    icon: 'success',
                    title: 'Thành công!',
                    text: @json(session('success')),

                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#991b1b',

                    timer: 3500,
                    timerProgressBar: true
                });
            });
        </script>
    @endif


    {{-- ========================================================
        THÔNG BÁO THẤT BẠI
    ======================================================== --}}
    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                Swal.fire({
                    icon: 'error',
                    title: 'Thao tác thất bại!',
                    text: @json(session('error')),

                    confirmButtonText: 'Đóng',
                    confirmButtonColor: '#991b1b'
                });
            });
        </script>
    @endif

</body>
</html>
