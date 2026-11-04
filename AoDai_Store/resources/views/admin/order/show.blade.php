@extends('layouts.admin')

@section('title', 'Chi tiết đơn hàng #' . $order->MaHoaDon)

@section('content')
<div class="space-y-6">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <a href="{{ route('orders.index') }}" class="text-xs font-bold text-stone-400 hover:text-stone-900 transition-all uppercase tracking-widest flex items-center">
                <i class="fas fa-arrow-left mr-2"></i> Quay lại danh sách
            </a>
            <h2 class="text-2xl font-black text-stone-800 mt-2 tracking-tight">Chi tiết đơn hàng #{{ $order->MaHoaDon }}</h2>
        </div>
        <div class="flex space-x-3">
            <button onclick="window.print()" class="px-5 py-2.5 bg-white border border-stone-200 rounded-xl text-xs font-bold uppercase tracking-widest text-stone-600 hover:bg-stone-50 transition-all">
                <i class="fas fa-print mr-2"></i> In hóa đơn
            </button>
            <button onclick="updateOrderStatus('{{ $order->MaHoaDon }}', '{{ $order->TrangThai }}')" 
                class="px-5 py-2.5 bg-stone-900 text-white rounded-xl text-xs font-bold uppercase tracking-widest hover:bg-red-800 transition-all shadow-lg shadow-stone-200">
                <i class="fas fa-edit mr-2"></i> Cập nhật trạng thái
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-[2rem] shadow-sm border border-stone-100 overflow-hidden">
                <div class="px-8 py-6 border-b border-stone-50 flex justify-between items-center">
                    <h3 class="font-bold text-stone-800 tracking-tight">Danh sách sản phẩm</h3>
                    <span class="text-[10px] font-bold text-stone-400 uppercase tracking-widest">{{ $orderDetails->count() }} mặt hàng</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="text-[10px] uppercase tracking-widest text-stone-400 font-bold border-b border-stone-50">
                                <th class="px-8 py-4">Sản phẩm</th>
                                <th class="px-4 py-4 text-center">Size</th>
                                <th class="px-4 py-4 text-center">Số lượng</th>
                                <th class="px-4 py-4 text-right">Đơn giá</th>
                                <th class="px-8 py-4 text-right text-stone-800">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-50">
                            @foreach($orderDetails as $item)
                            <tr class="group hover:bg-stone-50/50 transition-all">
                                <td class="px-8 py-5">
                                    <div class="flex items-center">
                                        <div class="w-12 h-12 bg-stone-100 rounded-xl overflow-hidden mr-4 border border-stone-100">
                                            <img src="{{ asset('img/products/' . $item->product->HinhAnh) }}" class="w-full h-full object-cover">
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-stone-800">{{ $item->product->TenSanPham }}</p>
                                            <p class="text-[10px] text-stone-400">ID: #SP-{{ $item->MaSanPham }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-5 text-center font-mono text-xs font-bold text-stone-500">
                                    {{ $item->MaSize }}
                                </td>
                                <td class="px-4 py-5 text-center font-bold text-stone-800">
                                    {{ $item->SoLuong }}
                                </td>
                                <td class="px-4 py-5 text-right text-xs text-stone-500">
                                    {{ number_format($item->DonGia, 0, ',', '.') }}đ
                                </td>
                                <td class="px-8 py-5 text-right font-bold text-red-800">
                                    {{ number_format($item->ThanhTien, 0, ',', '.') }}đ
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="bg-stone-50/50 p-8 border-t border-stone-100">
                    <div class="flex flex-col items-end space-y-2">
                        <div class="flex justify-between w-64 text-sm text-stone-500">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($order->TongTien + $order->GiamGia*1000, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between w-64 text-sm text-green-600 font-medium">
                            <span>Giảm giá:</span>
                            <span>-{{ number_format($order->GiamGia*1000, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="flex justify-between w-64 pt-4 border-t border-stone-200">
                            <span class="text-lg font-black text-stone-800 uppercase tracking-tighter">Tổng cộng:</span>
                            <span class="text-xl font-black text-red-800">{{ number_format($order->TongTien, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="space-y-6">
            <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-stone-100">
                <h3 class="font-bold text-stone-800 mb-6 flex items-center">
                    <i class="fas fa-user-circle mr-3 text-stone-400"></i> Khách hàng
                </h3>
                <div class="space-y-4">
                    <div class="flex items-start">
                        <div class="w-8 text-stone-300"><i class="fas fa-user text-xs mt-1"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-stone-400 tracking-widest">Người nhận</p>
                            <p class="text-sm font-bold text-stone-800">{{ $order->TenNguoiNhan }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 text-stone-300"><i class="fas fa-phone text-xs mt-1"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-stone-400 tracking-widest">Điện thoại</p>
                            <p class="text-sm font-bold text-stone-800">{{ $order->SDTNguoiNhan }}</p>
                        </div>
                    </div>
                    <div class="flex items-start">
                        <div class="w-8 text-stone-300"><i class="fas fa-map-marker-alt text-xs mt-1"></i></div>
                        <div>
                            <p class="text-[10px] uppercase font-bold text-stone-400 tracking-widest">Địa chỉ giao hàng</p>
                            <p class="text-sm font-medium text-stone-600 leading-relaxed">{{ $order->DiaChiGiaoHang }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-stone-900 rounded-[2rem] p-8 shadow-xl text-white">
                <h3 class="font-bold mb-6 flex items-center text-stone-400">
                    <i class="fas fa-credit-card mr-3"></i> Thanh toán
                </h3>
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-stone-500 tracking-widest mb-2">Phương thức</p>
                        <span class="px-3 py-1 bg-white/10 rounded-lg text-[10px] font-bold uppercase tracking-wider">
                            {{ $order->PhuongThucThanhToan }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-stone-500 tracking-widest mb-2">Trạng thái phí</p>
                        @if($order->NgayThanhToan)
                            <p class="text-sm font-bold text-green-400 flex items-center">
                                <i class="fas fa-check-circle mr-2"></i> Đã thanh toán
                            </p>
                            <p class="text-[10px] text-stone-500 mt-1 italic">Vào lúc: {{ date('H:i d/m/Y', strtotime($order->NgayThanhToan)) }}</p>
                        @else
                            <p class="text-sm font-bold text-amber-400 flex items-center">
                                <i class="fas fa-clock mr-2"></i> Chờ thanh toán
                            </p>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
 @endsection