
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - Áo Dài Heritage</title>

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <!-- Swiper -->
    <link rel="stylesheet"
          href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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

    {{-- HEADER --}}
    @include('partials.client._header')

    {{-- CONTENT --}}
    <main>
        @yield('content')
    </main>

    {{-- LOGIN POPUP --}}
    @include('auth.login')

    {{-- FOOTER --}}
    @include('partials.client._footer')

    {{-- JS MODAL --}}
    <script>
        function openLoginModal() {
            const modal = document.getElementById('loginModal');

            if (modal) {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            }
        }

        function closeLoginModal() {
            const modal = document.getElementById('loginModal');

            if (modal) {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }
        }

        // Toggle password
        document.addEventListener('click', function (e) {
            if (e.target.closest('.toggle-password')) {
                const button = e.target.closest('.toggle-password');
                const wrapper = button.closest('.relative');
                const input = wrapper.querySelector('.password-input');

                if (input.type === 'password') {
                    input.type = 'text';
                    button.textContent = '👁‍🗨️';
                } else {
                    input.type = 'password';
                    button.textContent = '👁';
                }
            }
        });

        // Tự mở login modal khi có ?login=1
        document.addEventListener('DOMContentLoaded', function () {
            const params = new URLSearchParams(window.location.search);

            if (params.has('login')) {
                openLoginModal();

                window.history.replaceState(
                    {},
                    document.title,
                    window.location.pathname
                );
            }
        });
    </script>

    {{-- AUTO OPEN LOGIN WHEN ERROR --}}
    @if ($errors->has('TenDangNhap') || $errors->has('password'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            openLoginModal();
        });
    </script>
    @endif

    {{-- ĐỒNG BỘ GIỎ HÀNG SAU ĐĂNG NHẬP --}}
    @if(session('merge_cart_pending'))
    <script>
        document.addEventListener('DOMContentLoaded', async function () {

            let cart = [];

            try {
                cart = JSON.parse(localStorage.getItem('cart') || '[]');
            } catch (error) {
            console.error('LocalStorage giỏ hàng không hợp lệ:', error);
            localStorage.removeItem('cart');
        }

            // Không đồng bộ nếu giỏ hàng trống
            if (!Array.isArray(cart) || cart.length === 0) {
                return;
            }

            try {
                const response = await fetch('{{ route("cart.merge") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector(
                            'meta[name="csrf-token"]'
                        ).content
                    },
                    body: JSON.stringify({
                        items: cart
                    })
                });

                const result = await response.json();

                if (response.ok && result.success) {

                    // Chỉ xóa Local Storage khi đồng bộ thành công
                    localStorage.removeItem('cart');

                    console.log('Đồng bộ giỏ hàng thành công!');

                    // Reload để cập nhật số lượng giỏ hàng
                    window.location.reload();

                } else {
                    console.error(
                        'Đồng bộ giỏ hàng thất bại:',
                        result.message
                    );
                }

            } catch (error) {
                console.error('Lỗi đồng bộ giỏ hàng:', error);
            }
        });
    </script>
    @endif

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        updateGuestCartBadge();
    });

    // Viết thành hàm riêng để dễ dàng gọi lại khi cần
    function updateGuestCartBadge() {
        // Kiểm tra xem có phải khách vãng lai không
        const isGuest = {{ Auth::check() ? 'false' : 'true' }};
        const cartBadge = document.getElementById('header-cart-count');

        if (isGuest && cartBadge) {
            let guestCart = JSON.parse(localStorage.getItem('guest_cart')) || [];
            let totalQty = 0;
            
            // Tính tổng số lượng của tất cả sản phẩm trong giỏ
            guestCart.forEach(item => {
                totalQty += parseInt(item.SoLuong);
            });

            // Ghi đè con số lên giao diện
            cartBadge.innerText = totalQty;
        }
    }
</script>
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        // Kiểm tra xem người dùng đã đăng nhập chưa (dựa vào Auth của Laravel)
        const isLoggedIn = {{ Auth::check() ? 'true' : 'false' }};

        if (isLoggedIn) {
            // Lấy giỏ hàng từ localStorage của khách vãng lai
            let guestCart = JSON.parse(localStorage.getItem('guest_cart')) || [];

            if (guestCart.length > 0) {
                // Chuẩn bị dữ liệu gửi lên API mergeCartAfterLogin của bạn
                fetch("{{ route('cart.merge') }}", { // Đảm bảo bạn đã khai báo route này ở web.php
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                    body: JSON.stringify({ items: guestCart })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Bước 5: Xóa giỏ hàng trong localStorage sau khi đồng bộ thành công
                        localStorage.removeItem('guest_cart');
                        
                        // Cập nhật lại số lượng trên Header nếu cần
                        if (typeof updateGuestCartBadge === 'function') {
                            updateGuestCartBadge();
                        }
                    }
                })
                .catch(error => {
                    console.error("Lỗi đồng bộ giỏ hàng:", error);
                });
            }
        }
    });
</script>
</body>
</html>
