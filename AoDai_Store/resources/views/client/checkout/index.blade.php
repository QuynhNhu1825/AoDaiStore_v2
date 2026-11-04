
@extends('layouts.client')

@section('title', 'Xác nhận đơn hàng')

@section('content')
<div class="bg-stone-50 py-12">
    <section class="max-w-6xl mx-auto px-4 min-h-screen">

        <div class="mb-10">
            <h2 class="serif text-3xl font-bold text-stone-800">
                Xác nhận đơn hàng
            </h2>
            <p class="text-stone-500 text-sm mt-2">
                Kiểm tra thông tin giao hàng và sản phẩm trước khi đặt hàng.
            </p>
        </div>

        @if ($errors->any())
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl">
                <strong class="font-bold">Vui lòng kiểm tra lại thông tin!</strong>
                <ul class="list-disc ml-5 mt-2 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error'))
            <div class="mb-6 bg-red-100 border border-red-300 text-red-700 px-5 py-4 rounded-xl">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('order.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8">

                {{-- THÔNG TIN GIAO HÀNG --}}
                <div class="lg:col-span-8 space-y-6">

                    <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8">

                        <div class="flex items-center gap-3 mb-8 pb-4 border-b border-stone-100">
                            <span class="w-8 h-8 bg-red-800 text-white rounded-full flex items-center justify-center text-xs">1</span>
                            <h3 class="font-bold text-stone-800 uppercase text-sm tracking-wider">
                                Thông tin người nhận
                            </h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Họ và tên người nhận *
                                </label>
                                <input
                                    type="text"
                                    name="HoTenNguoiNhan"
                                    value="{{ old('HoTenNguoiNhan', $info->HoTen ?? '') }}"
                                    required
                                    maxlength="100"
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700"
                                    placeholder="Nhập họ tên người nhận">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Số điện thoại *
                                </label>
                                <input
                                    type="tel"
                                    name="SDTNguoiNhan"
                                    value="{{ old('SDTNguoiNhan', $info->SoDienThoai ?? '') }}"
                                    required
                                    maxlength="15"
                                    pattern="[0-9]{10,11}"
                                    title="Số điện thoại gồm 10 hoặc 11 chữ số"
                                    oninput="this.value = this.value.replace(/[^0-9]/g, '')"
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700"
                                    placeholder="Nhập số điện thoại">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Số nhà, tên đường *
                                </label>
                                <input
                                    type="text"
                                    id="so_nha"
                                    value="{{ old('so_nha', $addressData['soNha'] ?? '') }}"
                                    required
                                    maxlength="200"
                                    placeholder="Ví dụ: 123 Lê Lợi"
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Phường / Xã *
                                </label>
                                <input
                                    type="text"
                                    id="phuong_xa"
                                    value="{{ old('phuong_xa', $addressData['phuongXa'] ?? '') }}"
                                    required
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700">
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Quận / Huyện *
                                </label>
                                <input
                                    type="text"
                                    id="quan_huyen"
                                    value="{{ old('quan_huyen', $addressData['quanHuyen'] ?? '') }}"
                                    required
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700">
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Tỉnh / Thành phố *
                                </label>
                                <input
                                    type="text"
                                    id="tinh_thanh"
                                    value="{{ old('tinh_thanh', $addressData['tinhThanh'] ?? '') }}"
                                    required
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700"
                                    placeholder="Nhập tỉnh hoặc thành phố">
                            </div>

                            {{-- Ghép các thành phần địa chỉ thành một trường DiaChiNhan --}}
                            <input type="hidden" name="DiaChiNhan" id="DiaChiNhan">

                            <div class="md:col-span-2">
                                <label class="block text-xs font-bold text-stone-600 mb-2">
                                    Ghi chú đơn hàng
                                </label>
                                <textarea
                                    name="GhiChu"
                                    rows="3"
                                    maxlength="500"
                                    placeholder="Ví dụ: Giao giờ hành chính, gọi trước khi đến..."
                                    class="w-full bg-stone-50 border border-stone-200 rounded-xl px-4 py-3 focus:bg-white focus:border-red-800 outline-none text-stone-700 resize-none">{{ old('GhiChu') }}</textarea>
                            </div>

                        </div>
                    </div>

            {{-- PHƯƠNG THỨC THANH TOÁN --}}
            <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-8">

                <div class="flex items-center gap-3 mb-6 pb-4 border-b border-stone-100">
                    <span class="w-8 h-8 bg-red-800 text-white rounded-full flex items-center justify-center text-xs">
                        2
                    </span>

                    <h3 class="font-bold text-stone-800 uppercase text-sm tracking-wider">
                        Phương thức thanh toán
                    </h3>
                </div>

                <div class="space-y-4">

                    <label class="flex items-center gap-3 p-4 border border-stone-200 rounded-xl cursor-pointer hover:border-red-800 transition">
                        <input
                            type="radio"
                            name="PhuongThucThanhToan"
                            value="cod"
                            {{ old('PhuongThucThanhToan', 'cod') == 'cod' ? 'checked' : '' }}
                            required
                            class="text-red-800 focus:ring-red-800">

                        <div>
                            <p class="font-semibold text-stone-800">
                                Thanh toán khi nhận hàng (COD)
                            </p>
                            <p class="text-xs text-stone-500 mt-1">
                                Thanh toán trực tiếp khi nhận được sản phẩm.
                            </p>
                        </div>
                    </label>

                    <label class="flex items-center gap-3 p-4 border border-stone-200 rounded-xl cursor-pointer hover:border-red-800 transition">
                        <input
                            type="radio"
                            name="PhuongThucThanhToan"
                            value="vnpay"
                            {{ old('PhuongThucThanhToan') == 'vnpay' ? 'checked' : '' }}
                            class="text-red-800 focus:ring-red-800">

                        <div>
                            <p class="font-semibold text-stone-800">
                                Thanh toán trực tuyến qua VNPay
                            </p>
                            <p class="text-xs text-stone-500 mt-1">
                                Thanh toán nhanh chóng và an toàn qua VNPay.
                            </p>
                        </div>
                    </label>

                </div>
            </div>

            {{-- ĐÓNG CỘT THÔNG TIN GIAO HÀNG --}}
            </div>

            
                {{-- TÓM TẮT ĐƠN HÀNG --}}
                <div class="lg:col-span-4">
                    <div class="bg-white rounded-2xl shadow-md border border-stone-100 p-6 sticky top-8">

                        <h3 class="font-bold text-stone-800 uppercase text-xs tracking-widest mb-6">
                            Đơn hàng của bạn
                        </h3>

                        <div class="space-y-4 mb-6 max-h-96 overflow-y-auto pr-2">

                            @foreach($cartItems as $item)
                                @php
                                    $detail = $item->productDetail;
                                    $product = $detail?->product;
                                    $color = $detail?->color;
                                    $size = $detail?->size;
                                    $quantity = (int) $item->SoLuong;
                                    $price = (float) ($detail?->Gia ?? 0);
                                @endphp

                                @if($detail && $product)
                                    <div class="flex gap-3 items-start">
                                        <div class="w-16 h-20 rounded-lg overflow-hidden flex-shrink-0 bg-stone-100">
                                            <img
                                                src="{{ asset('img/products/' . $product->HinhAnh) }}"
                                                alt="{{ $product->TenSanPham }}"
                                                class="w-full h-full object-cover"
                                               onerror="this.onerror=null; this.src='{{ asset('images/no-image.png') }}';">
                                        </div>

                                        <div class="flex-1 min-w-0">
                                            <p class="text-xs font-bold text-stone-800">
                                                {{ $product->TenSanPham }}
                                            </p>

                                            <p class="text-[11px] text-stone-500 mt-1">
                                                Màu: {{ $color->TenMau ?? 'Chưa xác định' }}
                                            </p>

                                            <p class="text-[11px] text-stone-500">
                                                Size: {{ $size->TenSize ?? 'Chưa xác định' }}
                                            </p>

                                            <p class="text-[11px] text-stone-500">
                                                Số lượng: {{ $quantity }}
                                            </p>

                                            <p class="text-xs font-bold text-red-800 mt-1">
                                                {{ number_format($price * $quantity, 0, ',', '.') }}đ
                                            </p>
                                        </div>
                                    </div>
                                @endif
                            @endforeach

                        </div>

                        @php
                            $shippingFee = 0;
                            $grandTotal = $totalPrice + $shippingFee;
                        @endphp

                        <div class="space-y-4 pt-5 border-t border-stone-100">

                            <div class="flex justify-between text-sm text-stone-500">
                                <span>Tạm tính</span>
                                <span>{{ number_format($totalPrice, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="flex justify-between text-sm text-stone-500">
                                <span>Phí vận chuyển</span>
                                <span>{{ number_format($shippingFee, 0, ',', '.') }}đ</span>
                            </div>

                            <div class="flex justify-between items-center pt-4 border-t border-stone-200">
                                <span class="text-sm font-bold text-stone-800 uppercase">
                                    Tổng thanh toán
                                </span>
                                <span class="text-xl font-bold text-red-800">
                                    {{ number_format($grandTotal, 0, ',', '.') }}đ
                                </span>
                            </div>

                        </div>

                        <button
                            type="submit"
                            class="w-full bg-red-800 hover:bg-red-900 text-white rounded-xl py-4 mt-8 font-bold text-sm uppercase tracking-widest shadow-lg transition-all hover:-translate-y-1">
                            Xác nhận đặt hàng
                        </button>

                        <a
                            href="{{ route('cart.index') }}"
                            class="block text-center text-xs text-stone-500 hover:text-red-800 mt-5">
                            ← Quay lại giỏ hàng
                        </a>

                    </div>
                </div>

            </div>
        </form>
    </section>
</div>

<script>
document.querySelector('form[action="{{ route('order.store') }}"]')
    .addEventListener('submit', function (event) {
        const parts = [
            document.getElementById('so_nha').value.trim(),
            document.getElementById('phuong_xa').value.trim(),
            document.getElementById('quan_huyen').value.trim(),
            document.getElementById('tinh_thanh').value.trim()
        ];

        if (parts.some(part => !part)) {
            event.preventDefault();
            alert('Vui lòng nhập đầy đủ địa chỉ nhận hàng.');
            return;
        }

        document.getElementById('DiaChiNhan').value = parts.join(', ');
    });
</script>
@endsection
