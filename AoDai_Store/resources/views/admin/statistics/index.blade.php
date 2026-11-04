@extends('layouts.admin')

@section('title', 'Thống kê')

@section('content')
<div class="container mx-auto px-4">
    <h2 class="text-2xl font-bold mb-6">Thống kê</h2>

    <form method="GET"
          action="{{ route('statistics.index') }}"
          class="bg-white p-4 rounded-xl shadow mb-6">

        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 items-end">

            <div>
                <label class="block font-semibold mb-1">From date</label>
                <input type="date"
                       name="tu_ngay"
                       value="{{ $tuNgay }}"
                       class="w-full border rounded-lg p-2">
            </div>

            <div>
                <label class="block font-semibold mb-1">To date</label>
                <input type="date"
                       name="den_ngay"
                       value="{{ $denNgay }}"
                       class="w-full border rounded-lg p-2">
            </div>

            <div>
                <button type="submit"
                        class="bg-blue-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                    Lọc
                </button>
            </div>

        </div>
    </form>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 mb-1">Tổng doanh thu</p>
            <h3 class="text-3xl font-bold text-green-600">
                {{ number_format($tongDoanhThu) }} đ
            </h3>
        </div>

        <div class="bg-white p-6 rounded-xl shadow">
            <p class="text-gray-500 mb-1">Tổng lượt mua</p>
            <h3 class="text-3xl font-bold text-blue-600">
                {{ $soLuotMua }}
            </h3>
        </div>

    </div>

    <div class="bg-white p-6 rounded-xl shadow mb-8">
        <h3 class="text-xl font-semibold mb-4">
            Doanh thu theo tháng ({{ date('Y') }})
        </h3>

        <table class="table-auto w-full border">
            <thead class="bg-gray-100">
                <tr>
                    <th class="border p-2">Tháng</th>
                    <th class="border p-2">Doanh thu</th>
                </tr>
            </thead>
            <tbody>
                @forelse($doanhThuTheoThang as $item)
                    <tr>
                        <td class="border p-2 text-center">
                            Tháng {{ $item->thang }}
                        </td>
                        <td class="border p-2 text-right">
                            {{ number_format($item->tong_tien) }} đ
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2" class="border p-4 text-center text-gray-500">
                           Không có dữ liệu
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
        <div class="bg-white p-6 rounded-xl shadow">
    <h3 class="text-xl font-semibold mb-4">
        Lượt mua sản phẩm theo tháng ({{ date('Y') }})
    </h3>

    <table class="table-auto w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Tháng</th>
                <th class="border p-2">Sản phẩm</th>
                <th class="border p-2">Số lượt mua</th>
            </tr>
        </thead>
        <tbody>
            @forelse($luotMuaTheoThang as $item)
                <tr>
                    <td class="border p-2 text-center">
                        Tháng {{ $item->thang }}
                    </td>
                    <td class="border p-2">
                        {{ $item->TenSanPham }}
                    </td>
                    <td class="border p-2 text-center font-semibold text-blue-600">
                        {{ $item->so_luot_mua }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="border p-4 text-center text-gray-500">
                        Không có dữ liệu
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

    
        
    
</div>

    </div>

</div>
@endsection
