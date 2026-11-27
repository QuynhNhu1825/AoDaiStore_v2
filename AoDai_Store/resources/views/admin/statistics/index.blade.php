@extends('layouts.admin')
@section('title', 'Thống kê')
@section('content')
<div class="w-full min-w-0 space-y-5">

    <div class="grid grid-cols-2 xl:grid-cols-4 gap-3">
        <x-admin.stat-card label="Doanh thu" :value="$tongDoanhThu" unit="đ" icon="fas fa-wallet" />
        <x-admin.stat-card label="Tổng đơn hàng" :value="$tongDonHang" icon="fas fa-receipt" color="green" />
        <x-admin.stat-card label="Đã giao" :value="$donDaGiao" icon="fas fa-circle-check" color="green" />
        <x-admin.stat-card label="Sản phẩm đã bán" :value="$soSanPhamDaBan" icon="fas fa-box" color="amber" />
    </div>
    
    <form method="GET" action="{{ route('statistics.index') }}" class="bg-white border border-red-100 shadow-sm rounded-2xl p-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-[1fr_1fr_auto_auto] gap-3 items-end">
            <div><label for="tu_ngay" class="block text-xs font-semibold text-stone-600 mb-2">Từ ngày</label><input id="tu_ngay" name="tu_ngay" type="date" value="{{ $tuNgay }}" class="w-full min-w-0 rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-xs focus:border-red-700 focus:ring-0"></div>
            <div><label for="den_ngay" class="block text-xs font-semibold text-stone-600 mb-2">Đến ngày</label><input id="den_ngay" name="den_ngay" type="date" value="{{ $denNgay }}" class="w-full min-w-0 rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-xs focus:border-red-700 focus:ring-0"></div>
            <button type="submit" class="rounded-xl bg-red-800 hover:bg-red-900 text-white font-semibold text-xs px-5 py-2.5"><i class="fas fa-filter mr-1"></i> Lọc</button>
            <a href="{{ route('statistics.index') }}" class="rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-600 text-center font-semibold text-xs px-5 py-2.5"><i class="fas fa-redo mr-1"></i>
                    Đặt lại</a>
        </div>
    </form>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <section class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 min-w-0">
            <h3 class="font-bold text-sm text-red-900 mb-1">Doanh thu theo thời gian</h3>
            <p class="text-[11px] text-stone-500 mb-4">Tiền hàng và phí vận chuyển của đơn đã giao và đã thanh toán, tính theo ngày đặt</p>
            @if($doanhThuTheoNgay->isNotEmpty())
                <div class="relative h-64"><canvas id="revenueChart" aria-label="Biểu đồ doanh thu theo ngày đặt" role="img"></canvas></div>
            @else
                <p class="py-8 text-center text-xs text-stone-500">Chưa có đơn nào vừa đã giao vừa đã thanh toán trong khoảng thời gian này.</p>
            @endif
        </section>
        <section class="bg-white rounded-2xl border border-red-100 shadow-sm p-5 min-w-0">
            <h3 class="font-bold text-sm text-red-900 mb-1">Đơn hàng theo trạng thái</h3>
            <p class="text-[11px] text-stone-500 mb-4">Số lượng đơn hàng trong khoảng thời gian lọc</p>
            @if($tongDonHang > 0)
                <div class="relative h-64"><canvas id="statusChart" aria-label="Biểu đồ đơn hàng theo trạng thái" role="img"></canvas></div>
            @else
                <p class="py-8 text-center text-xs text-stone-500">Chưa có đơn hàng trong khoảng thời gian này.</p>
            @endif
        </section>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
        <section class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden min-w-0">
            <div class="px-5 py-4 border-b border-red-100 bg-red-50"><h3 class="font-bold text-sm text-red-900">Số lượng đơn hàng theo trạng thái</h3></div>
            <div class="p-4 space-y-2">
                @foreach($donTheoTrangThai as $item)
                    <div class="flex justify-between items-center gap-3 border-b border-stone-100 last:border-0 py-2 text-xs"><span class="text-stone-600">{{ $item['label'] }}</span><strong class="text-red-900">{{ number_format($item['count'],0,',','.') }} đơn</strong></div>
                @endforeach
            </div>
        </section>
        <section class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden min-w-0">
            <div class="px-5 py-4 border-b border-red-100 bg-red-50"><h3 class="font-bold text-sm text-red-900">Top 5 sản phẩm bán chạy</h3></div>
            <div class="p-4 space-y-2">
                @forelse($sanPhamBanChay as $index => $item)
                    <div class="flex items-center gap-3 border-b border-stone-100 last:border-0 py-2 text-xs">
                        <span class="w-7 h-7 rounded-lg bg-red-50 text-red-800 flex items-center justify-center font-bold shrink-0">{{ $index + 1 }}</span>
                        <span class="flex-1 min-w-0 text-stone-700 break-words">{{ $item->TenSanPham }}</span>
                        <span class="font-bold text-red-900 whitespace-nowrap">{{ number_format($item->so_luong_ban,0,',','.') }} đã bán</span>
                    </div>
                @empty
                    <p class="text-xs text-stone-400 text-center py-8">Chưa có sản phẩm được bán trong khoảng thời gian này.</p>
                @endforelse
            </div>
        </section>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.8/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', async function () {
    if (typeof Chart === 'undefined') return;
    await document.fonts.ready;
    Chart.defaults.font.family = getComputedStyle(document.body).fontFamily;
    const revenueChart = document.getElementById('revenueChart');
    if (revenueChart) {
        const dates = @json($doanhThuTheoNgay->pluck('ngay'));
        const revenue = @json($doanhThuTheoNgay->pluck('tong_tien'));
        new Chart(revenueChart, {
            type: 'line', data: {labels: dates.map(date => date ?? 'Không rõ ngày đặt'), datasets:[{label:'Doanh thu (đ)',data:revenue,borderColor:'#831c30',backgroundColor:'rgba(131,28,48,.08)',fill:true,tension:.3,pointRadius:3}]},
            options:{responsive:true,maintainAspectRatio:false,plugins:{legend:{display:false},tooltip:{callbacks:{label:ctx=>new Intl.NumberFormat('vi-VN').format(ctx.parsed.y)+' đ'}}},scales:{y:{beginAtZero:true,ticks:{callback:v=>new Intl.NumberFormat('vi-VN',{notation:'compact'}).format(v)}}}}
        });
    }

    const statusChart = document.getElementById('statusChart');
    if (statusChart) {
        const statuses = @json(collect($donTheoTrangThai)->pluck('label'));
        const counts = @json(collect($donTheoTrangThai)->pluck('count'));
        new Chart(statusChart, {
            type:'doughnut',data:{labels:statuses,datasets:[{data:counts,backgroundColor:['#f59e0b','#3b82f6','#8b5cf6','#16a34a','#a8a29e','#475569'],borderWidth:2,borderColor:'#fff'}]},
            options:{responsive:true,maintainAspectRatio:false,cutout:'65%',plugins:{legend:{position:'bottom',labels:{boxWidth:10,font:{size:10}}}}}
        });
    }
});
</script>
@endsection
