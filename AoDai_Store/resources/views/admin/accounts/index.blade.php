@extends('layouts.admin')

@section('title', 'Quản lý tài khoản')

@section('content')
<div class="accounts-page w-full max-w-full min-w-0 space-y-4">
    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
        <x-admin.stat-card label="Tổng tài khoản" :value="$totalAccounts" icon="fas fa-users" color="blue" />
        <x-admin.stat-card label="Đang hoạt động" :value="$activeAccounts" icon="fas fa-user-check" color="green" />
        <x-admin.stat-card label="Bị khóa" :value="$lockedAccounts" icon="fas fa-user-lock" color="slate" />
    </div>

    {{-- TÌM KIẾM --}}
    <div class="bg-white p-4 rounded-2xl shadow-sm border border-stone-100">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="flex flex-col lg:flex-row lg:items-center gap-3">
            <div class="relative w-full lg:flex-1 lg:max-w-md min-w-0">
                <span class="absolute inset-y-0 left-3 flex items-center text-stone-400"><i class="fas fa-search text-[10px]"></i></span>
                <input type="text" name="keyword" value="{{ request('keyword') }}" placeholder="Tìm tên đăng nhập, họ tên hoặc email..."
                    class="w-full pl-9 pr-3 py-2.5 bg-stone-50 border border-stone-200 rounded-xl text-[11px] focus:ring-0 focus:border-red-700 transition-all">
            </div>
            <div class="grid grid-cols-2 sm:flex sm:flex-wrap items-center gap-2 w-full lg:w-auto lg:justify-end">
                <button type="submit" class="w-full sm:w-auto bg-red-800 text-white px-5 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-red-900 transition-all shadow-sm whitespace-nowrap"><i class="fas fa-filter mr-1"></i> Lọc</button>
                <a href="{{ route('admin.accounts.index') }}" class="w-full sm:w-auto inline-flex items-center justify-center bg-stone-100 text-stone-600 px-4 py-2.5 rounded-xl text-[10px] font-bold uppercase tracking-widest hover:bg-stone-200 transition-all whitespace-nowrap"><i class="fas fa-redo mr-1"></i> Đặt lại</a>
            </div>
        </form>
    </div>

    {{-- BẢNG TÀI KHOẢN --}}
    <div class="w-full max-w-full bg-white rounded-xl shadow-sm border border-red-100 overflow-hidden">
        <div class="accounts-table-wrap overflow-x-auto">
            <table class="accounts-table w-full min-w-[900px] table-fixed text-left border-collapse text-xs">
                <thead>
                    <tr class="bg-red-50 text-red-900 text-[10px] uppercase font-bold">
                        <th class="w-[19%] px-3 py-3">Tài khoản</th>
                        <th class="w-[18%] px-3 py-3">Họ tên</th>
                        <th class="w-[23%] px-3 py-3">Email</th>
                        <th class="w-[11%] px-2 py-3 text-center">Quyền</th>
                        <th class="w-[13%] px-2 py-3 text-center">Trạng thái</th>
                        <th class="w-[16%] px-2 py-3 text-center">Thao tác</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-red-50">
                    @forelse($accounts as $tk)
                        <tr class="account-card hover:bg-red-50/40 transition">
                            <td data-label="Tài khoản" class="px-3 py-3 align-middle">
                                <div class="min-w-0"><p class="font-bold text-red-900 text-[11px] break-words">{{ $tk->TenDangNhap }}</p><p class="text-[10px] text-stone-400 mt-1">#{{ $tk->MaTaiKhoan }}</p></div>
                            </td>
                            <td data-label="Họ tên" class="px-3 py-3 align-middle"><span class="text-[11px] text-stone-700 break-words">{{ $tk->HoTen ?? '---' }}</span></td>
                            <td data-label="Email" class="px-3 py-3 align-middle"><span class="text-[11px] text-stone-600 break-all">{{ $tk->Email ?? '---' }}</span></td>
                            <td data-label="Quyền" class="px-2 py-3 text-center align-middle">
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-[10px] font-bold {{ strtolower($tk->Role ?? '') === 'admin' ? 'bg-red-50 text-red-800 border border-red-100' : 'bg-stone-100 text-stone-600 border border-stone-200' }}">{{ strtoupper($tk->Role ?? '---') }}</span>
                            </td>
                            <td data-label="Trạng thái" class="px-2 py-3 text-center align-middle">
                                @if($tk->TrangThai == 1)
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-green-50 text-green-700 border border-green-100 whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-green-600"></span> Hoạt động</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2 py-1 rounded-full text-[10px] font-bold bg-stone-100 text-stone-600 border border-stone-200 whitespace-nowrap"><span class="w-1.5 h-1.5 rounded-full bg-stone-400"></span> Bị khóa</span>
                                @endif
                            </td>
                            <td data-label="Thao tác" class="px-2 py-3 align-middle">
                                @if(strcasecmp((string) $tk->Role, 'Admin') === 0)
                                    <div class="flex justify-center items-center">
                                        <span class="inline-flex items-center gap-1.5 px-3 py-2 rounded-lg bg-stone-100 text-stone-500 border border-stone-200 text-[10px] font-semibold" title="Tài khoản Admin được bảo vệ, không thể sửa hoặc khóa/mở">
                                            <i class="fas fa-shield-alt"></i> Được bảo vệ
                                        </span>
                                    </div>
                                @else
                                <div class="flex justify-center items-center gap-2">
                                    <a href="{{ route('admin.accounts.edit', $tk->MaTaiKhoan) }}" title="Sửa tài khoản" aria-label="Sửa tài khoản {{ $tk->TenDangNhap }}" class="w-8 h-8 flex items-center justify-center rounded-md border border-red-200 text-red-800 hover:bg-red-800 hover:text-white transition"><i class="fas fa-edit text-[11px]"></i></a>
                                    <form action="{{ route('admin.accounts.lock', $tk->MaTaiKhoan) }}" method="POST" class="inline-flex account-lock-form" data-account-name="{{ $tk->TenDangNhap }}" data-action="{{ $tk->TrangThai == 1 ? 'khóa' : 'mở khóa' }}">
                                        @csrf
                                        <button type="submit" title="{{ $tk->TrangThai == 1 ? 'Khóa tài khoản' : 'Mở khóa tài khoản' }}" aria-label="{{ $tk->TrangThai == 1 ? 'Khóa' : 'Mở khóa' }} tài khoản {{ $tk->TenDangNhap }}" class="w-8 h-8 flex items-center justify-center rounded-md border {{ $tk->TrangThai == 1 ? 'border-red-200 text-red-600 hover:bg-red-700' : 'border-green-200 text-green-700 hover:bg-green-700' }} hover:text-white transition"><i class="fas {{ $tk->TrangThai == 1 ? 'fa-lock' : 'fa-unlock' }} text-[11px]"></i></button>
                                    </form>
                                </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr class="account-empty-row"><td colspan="6" class="px-4 py-10 text-center text-stone-400 italic"><i class="fas fa-user-slash text-2xl mb-2"></i><p class="text-xs">Không tìm thấy tài khoản nào.</p></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if(method_exists($accounts, 'links'))
        <div class="admin-pagination p-4 bg-white rounded-xl border border-stone-100">{{ $accounts->appends(request()->query())->links('pagination::tailwind') }}</div>
    @endif
</div>

{{-- THÔNG BÁO VÀ XÁC NHẬN THAO TÁC --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
    // Chỉ xác nhận khóa/mở tài khoản User; tài khoản Admin không có form thao tác.
    document.querySelectorAll('.account-lock-form').forEach(form => {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            if (form.dataset.submitting === 'true') return;

            const action = form.dataset.action;
            const name = form.dataset.accountName;
            const result = await confirmNotification({
                title: action === 'khóa' ? 'Xác nhận khóa tài khoản' : 'Xác nhận mở khóa',
                text: `Bạn có chắc muốn ${action} tài khoản "${name}" không?`,
                icon: 'question',
                confirmButtonText: action === 'khóa' ? 'Đồng ý khóa' : 'Đồng ý mở khóa',
                cancelButtonText: 'Hủy bỏ',
            });

            if (result.isConfirmed) {
                form.dataset.submitting = 'true';
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });
});
</script>

<style>
.accounts-page { min-width: 0; }
@media (max-width: 767px) {
    .accounts-page .accounts-table-wrap { overflow: visible; background: #fafaf9; padding: 10px; }
    .accounts-page .accounts-table { display: block; width: 100%; min-width: 0 !important; table-layout: auto; }
    .accounts-page .accounts-table thead { display: none; }
    .accounts-page .accounts-table tbody { display: grid; gap: 12px; }
    .accounts-page .accounts-table .account-card { display: block; width: 100%; background: white; border: 1px solid #fee2e2; border-radius: 14px; padding: 6px 13px; box-shadow: 0 2px 8px rgba(0,0,0,.035); }
    .accounts-page .accounts-table .account-card td { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; width: 100%; padding: 11px 0 !important; border-bottom: 1px solid #f5f5f4; text-align: right; overflow-wrap: anywhere; }
    .accounts-page .accounts-table .account-card td:last-child { border-bottom: 0; }
    .accounts-page .accounts-table .account-card td::before { content: attr(data-label); flex: 0 0 35%; font-size: 11px; font-weight: 700; color: #78716c; text-align: left; }
    .accounts-page .accounts-table .account-card td > * { min-width: 0; max-width: 63%; }
    .accounts-page .accounts-table .account-card td[data-label="Thao tác"] > div { justify-content: flex-end; }
    .accounts-page .accounts-table .account-card td[data-label="Thao tác"] a,
    .accounts-page .accounts-table .account-card td[data-label="Thao tác"] button { width: 36px; height: 36px; }
    .accounts-page .accounts-table .account-empty-row { display: block; }
    .accounts-page .accounts-table .account-empty-row td { display: block; width: 100%; }
}
</style>
@endsection
