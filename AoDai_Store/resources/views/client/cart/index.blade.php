@extends('layouts.client')

@section('title', 'Giỏ hàng của bạn')

@section('content')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<section class="max-w-7xl mx-auto py-16 px-4 min-h-screen">

    {{-- ==================== TIÊU ĐỀ ==================== --}}
    <div class="text-center mb-12">
        <h2 class="serif text-3xl font-bold text-stone-800 uppercase tracking-widest">
            Túi hàng của bạn
        </h2>
        <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
    </div>

    {{-- QUAY LẠI --}}
        <div class="pt-6 border-t border-stone-200">
            <a href="{{ route('home') }}" class="text-stone-600 hover:text-red-800 transition flex items-center gap-2 font-medium">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                    Quay lại cửa hàng
            </a>
        </div>

    {{-- ==================== THÔNG BÁO ==================== --}}
    @if(session('error') || isset($message))
        <div class="bg-red-100 text-red-700 px-4 py-2 rounded mb-4 text-center">
            ⚠️ {{ session('error') ?? 'Vui lòng' }}
            <a href="{{ route('login') }}" class="underline font-bold">
                đăng nhập
            </a>
            để tiến hành thanh toán hoặc đồng bộ giỏ hàng.
        </div>
    @endif


    {{-- ==================== CONTAINER CHỨA GIỎ HÀNG ==================== --}}
    {{-- Đặt id="cart-container" để JS có thể ghi đè giao diện nếu là khách vãng lai --}}
    <div id="cart-container">
        
        @if($cartItems->count() > 0)

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

                {{-- ==================== DANH SÁCH SẢN PHẨM ==================== --}}
                <div class="lg:col-span-2 space-y-8">

                    {{-- Xóa sạch giỏ hàng --}}
                    <div class="flex justify-end mb-4">
                        <form action="{{ route('cart.clear') }}" method="POST" id="formClearCart">
                            @csrf
                            @method('DELETE')
                            <button type="button" onclick="confirmClearCart()" class="flex items-center gap-2 text-sm text-stone-400 hover:text-red-700 transition duration-300 group">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                                <span class="border-b border-transparent group-hover:border-red-700 uppercase tracking-wider font-medium">
                                    Xóa sạch giỏ hàng
                                </span>
                            </button>
                        </form>
                    </div>
                    
                    {{-- CHỌN TẤT CẢ --}}
                    <div class="flex items-center justify-between bg-white border border-stone-200 p-4 rounded-lg mb-5">

                    <label class="flex items-center gap-3 cursor-pointer">
                    <input type="checkbox" id="select-all" class="w-5 h-5 accent-red-800 cursor-pointer">

                    <span class="font-semibold text-stone-700">
                        Chọn tất cả
                    </span>
                    </label>

                    <span id="selected-count" class="text-sm text-stone-500">
                       Đã chọn 0 sản phẩm
                    </span>
                    </div>

                    
{{-- ==================== TỪNG SẢN PHẨM ==================== --}}
@foreach($cartItems as $item)
    @php
        $detail = $item->productDetail;
        $sanPham = $detail?->product;
    @endphp

    @if($detail && $sanPham)

        <div
            class="cart-item flex flex-col md:flex-row
                   bg-white shadow-sm border border-stone-100
                   p-6 group hover:shadow-md transition
                   rounded-lg gap-4"

            data-id="{{ $item->MaGioHang }}"
            data-price="{{ $detail->Gia ?? 0 }}"
            data-quantity="{{ $item->SoLuong }}"
        >

            {{-- CHECKBOX CHỌN SẢN PHẨM --}}
            <div class="flex items-start md:pt-16">
                <input
                    type="checkbox"
                    name="selected_items[]"
                    value="{{ $item->MaGioHang }}"
                    class="cart-checkbox w-5 h-5
                           accent-red-800 cursor-pointer"
                >
            </div>

            {{-- HÌNH ẢNH --}}
            <div class="w-full md:w-32 h-44 overflow-hidden
                        mb-4 md:mb-0 bg-stone-100 shrink-0">

                @if($sanPham->HinhAnh)
                    <img
                        src="{{ asset('img/products/' . $sanPham->HinhAnh) }}"
                        class="w-full h-full object-cover"
                        alt="{{ $sanPham->TenSanPham }}"
                    >
                @else
                    <div class="w-full h-full flex items-center
                                justify-center text-stone-400 text-sm">
                        Chưa có hình
                    </div>
                @endif

            </div>

            {{-- THÔNG TIN --}}
            <div class="md:ml-4 flex-1 flex flex-col justify-between">

                <div>
                    <div class="flex justify-between items-start gap-4">

                        <h3 class="serif text-xl font-semibold
                                   text-stone-700 uppercase">
                            {{ $sanPham->TenSanPham }}
                        </h3>

                        {{-- XÓA SẢN PHẨM --}}
                        <form
                            action="{{ route('cart.remove', $item->MaGioHang) }}"
                            method="POST"
                            class="form-remove-item"
                        >
                            @csrf
                            @method('DELETE')

                            <button
                                type="button"
                                onclick="confirmRemoveItem(this)"
                                class="text-stone-400 hover:text-red-700 transition"
                                title="Xóa sản phẩm"
                            >
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-5 w-5"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </form>

                    </div>

                    {{-- THUỘC TÍNH --}}
                    <div class="mt-3 grid grid-cols-2 gap-y-1
                                text-sm text-stone-500 italic">

                        <p>
                            Màu sắc:
                            <span class="text-stone-700">
                                {{ $detail->color?->TenMau ?? 'N/A' }}
                            </span>
                        </p>

                        <p>
                            Kích cỡ:
                            <span class="font-bold text-red-800">
                                {{ $detail->size?->TenSize ?? 'N/A' }}
                            </span>
                        </p>

                    </div>
                </div>

                {{-- SỐ LƯỢNG + GIÁ --}}
                <div class="flex justify-between items-center mt-6 gap-3">

                    <div class="flex items-center border
                                border-stone-300 rounded">

                        <button
                            type="button"
                            class="btn-update px-3 py-1
                                   hover:bg-stone-100
                                   border-r border-stone-300
                                   text-stone-600"
                            data-id="{{ $item->MaGioHang }}"
                            data-type="decrease"
                        >
                            -
                        </button>

                        <input
                            type="text"
                            id="qty-{{ $item->MaGioHang }}"
                            value="{{ $item->SoLuong }}"
                            class="w-12 text-center border-none
                                   focus:ring-0 text-stone-700 font-semibold"
                            readonly
                        >

                        <button
                            type="button"
                            class="btn-update px-3 py-1
                                   hover:bg-stone-100
                                   border-l border-stone-300
                                   text-stone-600"
                            data-id="{{ $item->MaGioHang }}"
                            data-type="increase"
                        >
                            +
                        </button>

                    </div>

                    <div class="text-right">

                        <p class="text-red-800 font-bold text-xl">
                            {{ number_format(
                                ($detail->Gia ?? 0) * $item->SoLuong,
                                0, ',', '.'
                            ) }}đ
                        </p>

                    </div>

                </div>
            </div>
        </div>
    @endif
@endforeach

                    {{-- PHÂN TRANG --}}
                    <div class="mt-8 flex justify-center">
                        {{ $cartItems->links() }}
                    </div>

                </div>

                {{-- ==================== TÓM TẮT ĐƠN HÀNG ==================== --}}
                <div class="lg:col-span-1">
                    <div class="bg-stone-50 p-8 border border-stone-200 sticky top-24 shadow-sm">
                        <h3 class="serif text-xl font-bold text-stone-800 mb-6 uppercase tracking-widest border-b border-stone-300 pb-4">
                            Tóm tắt đơn hàng
                        </h3>

                        
<div class="space-y-4 text-stone-600">

    <div class="flex justify-between">
        <span>
            Tạm tính (<span id="summary-quantity">0</span> món)
        </span>
        <span id="summary-subtotal">0đ</span>
    </div>

    <div class="flex justify-between">
        <span>Phí vận chuyển</span>
        <span class="text-green-700 font-medium">
            Miễn phí
        </span>
    </div>

    <div class="border-t border-stone-300 pt-4
                flex justify-between text-stone-900
                font-bold text-lg uppercase">

        <span>Tổng thanh toán</span>

        <span id="summary-total" class="text-red-800">
            0đ
        </span>
    </div>

</div>

{{-- NÚT ĐẶT HÀNG --}}
<button
    type="button"
    id="btn-checkout"
    onclick="checkoutSelected()"
    disabled
    class="block w-full text-center bg-red-800
           hover:bg-red-900 text-white font-bold
           py-4 mt-8 transition uppercase
           tracking-widest shadow-lg
           disabled:opacity-50 disabled:cursor-not-allowed"
>
    Vui lòng chọn sản phẩm
</button>


                        {{-- Thông tin --}}
                        <div class="mt-8 space-y-4">
                            <div class="flex items-center gap-3 text-xs text-stone-500 italic">
                                <svg class="w-5 h-5 text-stone-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z"/>
                                </svg>
                                <span>Thanh toán an toàn qua cổng SSL bảo mật</span>
                            </div>
                            <div class="flex items-center gap-3 text-xs text-stone-500 italic">
                                <svg class="w-5 h-5 text-stone-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M11 3a1 1 0 10-2 0v1a1 1 0 102 0V3zM5.88 6.76L4.46 5.34a1 1 0 10-1.42 1.42l1.42 1.42a1 1 0 001.42-1.42zM13.17 6.76a1 1 0 011.42-1.42l1.42 1.42a1 1 0 01-1.42 1.42l-1.42-1.42zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zM3 11a1 1 0 100-2H2a1 1 0 100 2h1z"/>
                                </svg>
                                <span>Hỗ trợ đổi trả trong vòng 7 ngày</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        @else
            {{-- ==================== GIỎ HÀNG TRỐNG ==================== --}}
            <div class="text-center py-24 bg-white border border-stone-100 shadow-sm">
                <svg class="mx-auto h-16 w-16 text-stone-200 mb-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                <p class="text-stone-500 italic mb-8 text-xl">Giỏ hàng của bạn hiện đang trống.</p>
                <a href="{{ route('home') }}" class="inline-block bg-stone-800 text-white px-10 py-4 hover:bg-stone-900 transition uppercase tracking-widest font-bold">
                    Tiếp tục mua sắm
                </a>
            </div>
        @endif

    </div>

</section>


{{-- ==================== JQUERY VÀ JAVASCRIPT ==================== --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    // ==================== TOAST (DÙNG CHUNG) ====================
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 2000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer);
            toast.addEventListener('mouseleave', Swal.resumeTimer);
        }
    });

    // ==================== XỬ LÝ CHO NGƯỜI ĐÃ ĐĂNG NHẬP ====================
    function confirmClearCart() {
        Swal.fire({
            title: 'Xóa toàn bộ giỏ hàng?',
            text: 'Bạn sẽ không thể hoàn tác hành động này!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            confirmButtonText: 'Đồng ý, xóa hết!',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) document.getElementById('formClearCart').submit();
        });
    }

    function confirmRemoveItem(button) {
        Swal.fire({
            title: 'Xóa sản phẩm này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) button.closest('form').submit();
        });
    }

    $(document).ready(function () {
        // Cập nhật số lượng qua Ajax (Backend)
        $(document).on('click', '.btn-update', function (e) {
            e.preventDefault();
            let id = $(this).data('id');
            let type = $(this).data('type');
            let inputField = $('#qty-' + id);

            $.ajax({
                url: "{{ route('update.quantity') }}",
                method: "POST",
                data: { _token: "{{ csrf_token() }}", id: id, type: type },
                success: function (response) {
                    if (response.success) {
                        inputField.val(response.new_qty);
                        Toast.fire({
                            icon: 'success',
                            title: 'Đã cập nhật số lượng'
                        }).then(() => location.reload());
                    }
                },
                error: function (xhr) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Rất tiếc...',
                        text: 'Có lỗi xảy ra khi cập nhật số lượng!',
                        confirmButtonColor: '#991b1b'
                    });
                }
            });
        });
    });


    // ==================== XỬ LÝ RIÊNG CHO KHÁCH VÃNG LAI (LOCAL STORAGE) ====================
    const isGuest = {{ Auth::check() ? 'false' : 'true' }};
    const imgBaseUrl = "{{ asset('img/products') }}/";
    const checkoutUrl = "{{ route('checkout.home') }}";
    const homeUrl = "{{ route('home') }}";

    if (isGuest) {
        let guestCart = JSON.parse(localStorage.getItem('guest_cart')) || [];
        
        if (guestCart.length > 0) {
            renderGuestCart(guestCart);
        } 
        // Nếu không có sản phẩm trong LocalStorage, giao diện mặc định trống của Blade sẽ được giữ nguyên
    }

    
function renderGuestCart(cart) {
    let itemsHtml = '';

    // Mỗi biến thể có một mã chọn riêng
    function guestItemKey(item) {
        return `guest:${item.productId}:${item.colorId}:${item.sizeId}`;
    }

    // Đọc danh sách đã chọn của khách vãng lai
    let selectedIds = [];

    try {
        const saved = JSON.parse(
            sessionStorage.getItem('heritage_guest_selected_cart') || '[]'
        );
        selectedIds = Array.isArray(saved) ? saved : [];
    } catch (e) {
        selectedIds = [];
    }

    cart.forEach((item, index) => {
        const itemTotal = Number(item.price) * Number(item.SoLuong);
        const key = guestItemKey(item);

        itemsHtml += `
            <div
                class="cart-item flex flex-col md:flex-row gap-4
                       bg-white shadow-sm border border-stone-100
                       p-6 rounded-lg hover:shadow-md transition"
                data-id="${key}"
                data-price="${item.price}"
                data-quantity="${item.SoLuong}"
            >
                <!-- CHECKBOX -->
                <div class="flex items-start md:pt-16">
                    <input
                        type="checkbox"
                        class="cart-checkbox w-5 h-5 accent-red-800 cursor-pointer"
                        value="${key}"
                        ${selectedIds.includes(key) ? 'checked' : ''}
                    >
                </div>

                <!-- HÌNH ẢNH -->
                <div class="w-full md:w-32 h-44 overflow-hidden
                            mb-4 md:mb-0 bg-stone-100 shrink-0">
                    <img
                        src="${imgBaseUrl}${item.productImage}"
                        class="w-full h-full object-cover"
                        alt=""
                    >
                </div>

                <!-- THÔNG TIN -->
                <div class="md:ml-4 flex-1 flex flex-col justify-between">
                    <div>
                        <div class="flex justify-between items-start gap-4">
                            <h3 class="serif text-xl font-semibold
                                       text-stone-700 uppercase">
                                ${item.productName}
                            </h3>

                            <button
                                type="button"
                                onclick="guestRemoveItem(${index})"
                                class="text-stone-400 hover:text-red-700 transition"
                                title="Xóa sản phẩm"
                            >
                                ✕
                            </button>
                        </div>

                        <div class="mt-3 grid grid-cols-2 gap-y-1
                                    text-sm text-stone-500 italic">
                            <p>
                                Màu sắc:
                                <span class="text-stone-700">
                                    ${item.colorName}
                                </span>
                            </p>
                            <p>
                                Kích cỡ:
                                <span class="font-bold text-red-800">
                                    ${item.sizeName}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- SỐ LƯỢNG + GIÁ -->
                    <div class="flex justify-between items-center mt-6 gap-3">
                        <div class="flex items-center border
                                    border-stone-300 rounded">
                            <button
                                type="button"
                                onclick="guestUpdateQty(${index}, -1)"
                                class="px-3 py-1 hover:bg-stone-100
                                       border-r border-stone-300"
                            >-</button>

                            <input
                                type="text"
                                value="${item.SoLuong}"
                                class="w-12 text-center border-none
                                       focus:ring-0 font-semibold"
                                readonly
                            >

                            <button
                                type="button"
                                onclick="guestUpdateQty(${index}, 1)"
                                class="px-3 py-1 hover:bg-stone-100
                                       border-l border-stone-300"
                            >+</button>
                        </div>

                        <p class="text-red-800 font-bold text-xl">
                            ${formatCartMoney(itemTotal)}
                        </p>
                    </div>
                </div>
            </div>
        `;
    });

    document.getElementById('cart-container').innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">

            <!-- DANH SÁCH SẢN PHẨM -->
            <div class="lg:col-span-2 space-y-6">

                <div class="flex justify-end">
                    <button
                        type="button"
                        onclick="guestClearCart()"
                        class="text-sm text-stone-400 hover:text-red-700
                               uppercase tracking-wider font-medium"
                    >
                        Xóa sạch giỏ hàng
                    </button>
                </div>

                <!-- CHỌN TẤT CẢ -->
                <div class="flex items-center justify-between
                            bg-white border border-stone-200
                            p-4 rounded-lg">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input
                            type="checkbox"
                            id="select-all"
                            class="w-5 h-5 accent-red-800 cursor-pointer"
                        >
                        <span class="font-semibold text-stone-700">
                            Chọn tất cả
                        </span>
                    </label>

                    <span id="selected-count" class="text-sm text-stone-500">
                        Đã chọn 0 sản phẩm
                    </span>
                </div>

                ${itemsHtml}
            </div>

            <!-- TÓM TẮT ĐƠN HÀNG -->
            <div class="lg:col-span-1">
                <div class="bg-stone-50 p-8 border border-stone-200
                            sticky top-24 shadow-sm">

                    <h3 class="serif text-xl font-bold text-stone-800
                               mb-6 uppercase tracking-widest
                               border-b border-stone-300 pb-4">
                        Tóm tắt đơn hàng
                    </h3>

                    <div class="space-y-4 text-stone-600">
                        <div class="flex justify-between">
                            <span>
                                Tạm tính
                                (<span id="summary-quantity">0</span> món)
                            </span>
                            <span id="summary-subtotal">0đ</span>
                        </div>

                        <div class="flex justify-between">
                            <span>Phí vận chuyển</span>
                            <span class="text-green-700 font-medium">
                                Miễn phí
                            </span>
                        </div>

                        <div class="border-t border-stone-300 pt-4
                                    flex justify-between font-bold text-lg">
                            <span>Tổng thanh toán</span>
                            <span id="summary-total" class="text-red-800">
                                0đ
                            </span>
                        </div>
                    </div>

                    <button
                        type="button"
                        id="btn-checkout"
                        onclick="checkoutSelected()"
                        disabled
                        class="block w-full text-center bg-red-800
                               hover:bg-red-900 text-white font-bold
                               py-4 mt-8 uppercase tracking-widest
                               disabled:opacity-50
                               disabled:cursor-not-allowed"
                    >
                        Vui lòng chọn sản phẩm
                    </button>
                </div>
            </div>
        </div>
    `;

    updateCartSummary();
}

    // Các hàm phụ trợ cho khách vãng lai
    function guestUpdateQty(index, step) {
        let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
        if (cart[index]) {
            cart[index].SoLuong += step;
            if (cart[index].SoLuong < 1) cart[index].SoLuong = 1;
            localStorage.setItem('guest_cart', JSON.stringify(cart));
            
            Toast.fire({ icon: 'success', title: 'Đã cập nhật số lượng' });
            updateGuestCartBadge();
            renderGuestCart(cart);
        }
    }

    function guestRemoveItem(index) {
        Swal.fire({
            title: 'Xóa sản phẩm này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                let cart = JSON.parse(localStorage.getItem('guest_cart')) || [];
                cart.splice(index, 1);
                localStorage.setItem('guest_cart', JSON.stringify(cart));
                
                if (cart.length === 0) {
                    location.reload(); // Reload để hiện giao diện trống mặc định
                } else {
                    renderGuestCart(cart);
                }
            }
        });
    }

    function guestClearCart() {
        Swal.fire({
            title: 'Xóa toàn bộ giỏ hàng?',
            text: 'Bạn sẽ không thể hoàn tác hành động này!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            confirmButtonText: 'Đồng ý, xóa hết!',
            cancelButtonText: 'Hủy bỏ'
        }).then((result) => {
            if (result.isConfirmed) {
                localStorage.removeItem('guest_cart');
                location.reload();
            }
        });
    }

    
// ==================== CHỌN SẢN PHẨM GIỎ HÀNG ====================

const CART_SELECTION_KEY = isGuest
    ? 'heritage_guest_selected_cart'
    : 'heritage_selected_cart';

function getCartSelection() {
    try {
        const saved = JSON.parse(
            sessionStorage.getItem(CART_SELECTION_KEY) || '[]'
        );

        return Array.isArray(saved) ? saved.map(String) : [];
    } catch (error) {
        return [];
    }
}

// Lưu danh sách sản phẩm đã chọn
function saveCartSelection() {
    const selectedIds = Array.from(
        document.querySelectorAll('.cart-checkbox:checked')
    ).map(checkbox => checkbox.value);

    sessionStorage.setItem(
        CART_SELECTION_KEY,
        JSON.stringify(selectedIds)
    );
}

// Định dạng tiền Việt Nam
function formatCartMoney(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

// Tính lại tổng tiền
function updateCartSummary() {
    const cartItems = document.querySelectorAll('.cart-item');

    let selectedCount = 0;
    let totalQuantity = 0;
    let totalPrice = 0;

    cartItems.forEach(item => {
        const checkbox = item.querySelector('.cart-checkbox');

        if (!checkbox) return;

        if (checkbox.checked) {
            selectedCount++;

            const price = Number(item.dataset.price) || 0;
            const quantity = Number(item.dataset.quantity) || 0;

            totalQuantity += quantity;
            totalPrice += price * quantity;

            item.classList.add('border-red-800', 'bg-red-50');
        } else {
            item.classList.remove('border-red-800', 'bg-red-50');
        }
    });

    const selectedCountElement =
        document.getElementById('selected-count');

    const summaryQuantity =
        document.getElementById('summary-quantity');

    const summarySubtotal =
        document.getElementById('summary-subtotal');

    const summaryTotal =
        document.getElementById('summary-total');

    const checkoutButton =
        document.getElementById('btn-checkout');

    if (selectedCountElement) {
        selectedCountElement.textContent =
            `Đã chọn ${selectedCount} sản phẩm`;
    }

    if (summaryQuantity) {
        summaryQuantity.textContent = totalQuantity;
    }

    if (summarySubtotal) {
        summarySubtotal.textContent = formatCartMoney(totalPrice);
    }

    if (summaryTotal) {
        summaryTotal.textContent = formatCartMoney(totalPrice);
    }

    if (checkoutButton) {
        checkoutButton.disabled = selectedCount === 0;

        checkoutButton.textContent = selectedCount > 0
            ? `Đặt hàng (${selectedCount} sản phẩm)`
            : 'Vui lòng chọn sản phẩm';
    }

    // Đồng bộ checkbox Chọn tất cả
    const selectAll = document.getElementById('select-all');

    if (selectAll) {
        selectAll.checked =
            cartItems.length > 0 &&
            selectedCount === cartItems.length;

        selectAll.indeterminate =
            selectedCount > 0 &&
            selectedCount < cartItems.length;
    }
}

// Xử lý khi chọn từng sản phẩm hoặc chọn tất cả
document.addEventListener('change', function(event) {

    if (event.target.classList.contains('cart-checkbox')) {
        saveCartSelection();
        updateCartSummary();
    }

    if (event.target.id === 'select-all') {
        document.querySelectorAll('.cart-checkbox')
            .forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });

        saveCartSelection();
        updateCartSummary();
    }
});

// Khôi phục lựa chọn khi tải lại trang
document.addEventListener('DOMContentLoaded', function() {
    const savedIds = getCartSelection();

    document.querySelectorAll('.cart-checkbox')
        .forEach(checkbox => {
            checkbox.checked = savedIds.includes(checkbox.value);
        });

    updateCartSummary();
});

// Kiểm tra khi nhấn đặt hàng
function checkoutSelected() {
    const selectedIds = Array.from(
        document.querySelectorAll('.cart-checkbox:checked')
    ).map(checkbox => checkbox.value);

    if (selectedIds.length === 0) {
        Swal.fire({
            icon: 'warning',
            title: 'Chưa chọn sản phẩm',
            text: 'Vui lòng chọn ít nhất một sản phẩm để đặt hàng.',
            confirmButtonColor: '#991b1b'
        });
        return;
    }

    // Tạo form gửi danh sách MaGioHang lên Laravel
    const form = document.createElement('form');

    form.method = 'POST';
    form.action = "{{ route('checkout.selected') }}";

    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = "{{ csrf_token() }}";

    form.appendChild(csrf);

    selectedIds.forEach(id => {
        const input = document.createElement('input');

        input.type = 'hidden';
        input.name = 'selected_ids[]';
        input.value = id;

        form.appendChild(input);
    });

    document.body.appendChild(form);
    form.submit();
}
</script>

@endsection