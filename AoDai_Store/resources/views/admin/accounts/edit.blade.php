@extends('layouts.admin')

@section('title', 'Chỉnh sửa tài khoản: ' . $account->TenDangNhap)

@section('content')
<div class="w-full max-w-4xl mx-auto space-y-4">
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
        <div>
             <h2 class="text-xl font-bold text-red-900">
                <i class="fas fa-edit mr-2"></i>
                Chỉnh sửa tài khoản
            </h2>

            <p class="text-xs text-stone-500 mt-1">
                Cập nhật thông tin tài khoản trong hệ thống.
            </p>
        </div>
        <a href="{{ route('admin.accounts.index') }}"
           class="inline-flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl border border-red-200 bg-white text-red-900 text-xs font-bold hover:bg-red-50 transition">
            <i class="fas fa-arrow-left"></i> Quay lại danh sách
        </a>
    </div>

    @if($errors->any())
        <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-800 text-xs" role="alert">
            <p class="font-bold mb-2"><i class="fas fa-exclamation-circle mr-1"></i> Vui lòng kiểm tra thông tin:</p>
            <ul class="list-disc pl-5 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-red-100 shadow-sm overflow-hidden">
        <div class="px-5 sm:px-7 py-5 bg-red-50 border-b border-red-100">
            <h2 class="font-bold text-red-900 text-sm uppercase tracking-wide">Thông tin tài khoản</h2>
            <p class="text-xs text-stone-500 mt-1">Mã khách hàng #{{ $account->MaTaiKhoan }}</p>
        </div>

        <form id="account-edit-form" action="{{ route('admin.accounts.update', $account->MaTaiKhoan) }}" method="POST" class="p-5 sm:p-7 space-y-5">
            @csrf

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="TenDangNhap" class="block text-xs font-bold text-stone-700 mb-2">Tên đăng nhập</label>
                    <input id="TenDangNhap" type="text" value="{{ $account->TenDangNhap }}" disabled
                           class="w-full rounded-xl border border-stone-200 bg-stone-100 text-stone-500 px-3 py-2.5 text-sm cursor-not-allowed">
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">Quyền tài khoản</label>
                    <div class="min-h-[42px] rounded-xl border border-stone-200 bg-stone-100 px-3 py-2.5 flex items-center justify-between gap-3">
                        <span class="text-sm text-stone-600 font-semibold">{{ $account->Role === 'Admin' ? 'Quản trị viên' : 'Khách hàng' }}</span>
                        <span class="text-[10px] px-2.5 py-1 rounded-full font-bold {{ $account->Role === 'Admin' ? 'bg-red-100 text-red-800' : 'bg-green-100 text-green-700' }}">{{ $account->Role }}</span>
                    </div>
                </div>
                <div>
                    <label for="HoTen" class="block text-xs font-bold text-stone-700 mb-2">Họ và tên <span class="text-red-600">*</span></label>
                    <input id="HoTen" name="HoTen" type="text" required maxlength="255" value="{{ old('HoTen', $account->HoTen) }}"
                           class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-red-700 focus:ring-red-700 @error('HoTen') border-red-500 @enderror">
                    @error('HoTen') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="Email" class="block text-xs font-bold text-stone-700 mb-2">Email <span class="text-red-600">*</span></label>
                    <input id="Email" name="Email" type="email" required maxlength="255" value="{{ old('Email', $account->Email) }}"
                           class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-red-700 focus:ring-red-700 @error('Email') border-red-500 @enderror">
                    @error('Email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label for="SDT" class="block text-xs font-bold text-stone-700 mb-2">Số điện thoại <span class="text-red-600">*</span></label>
                    <input id="SDT" name="SDT" type="tel" required maxlength="10" inputmode="numeric" value="{{ old('SDT', $account->SDT) }}"
                           class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-red-700 focus:ring-red-700 @error('SDT') border-red-500 @enderror">
                    @error('SDT') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-xs font-bold text-stone-700 mb-2">Trạng thái tài khoản</label>
                    <div class="min-h-[42px] rounded-xl border border-stone-200 bg-stone-100 px-3 py-2.5 flex items-center">
                        @if($account->TrangThai)
                            <span class="text-xs font-bold text-green-700"><i class="fas fa-check-circle mr-1"></i> Đang hoạt động</span>
                        @else
                            <span class="text-xs font-bold text-red-700"><i class="fas fa-lock mr-1"></i> Đã khóa</span>
                        @endif
                    </div>
                    <p class="text-[11px] text-stone-400 mt-1">Khóa/mở khóa tại danh sách tài khoản.</p>
                </div>
            </div>

            <div>
                <label for="DiaChi" class="block text-xs font-bold text-stone-700 mb-2">Địa chỉ <span class="text-red-600">*</span></label>
                <textarea id="DiaChi" name="DiaChi" rows="3" required maxlength="500"
                          class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-red-700 focus:ring-red-700 @error('DiaChi') border-red-500 @enderror">{{ old('DiaChi', $account->DiaChi) }}</textarea>
                @error('DiaChi') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="MatKhau" class="block text-xs font-bold text-stone-700 mb-2">Mật khẩu mới <span class="font-normal text-stone-400">(không bắt buộc)</span></label>
                <input id="MatKhau" name="MatKhau" type="password" minlength="6" maxlength="32" autocomplete="new-password" placeholder="Để trống nếu không đổi mật khẩu"
                       class="w-full rounded-xl border border-stone-200 bg-stone-50 px-3 py-2.5 text-sm focus:border-red-700 focus:ring-red-700 @error('MatKhau') border-red-500 @enderror">
                @error('MatKhau') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>

            <div class="flex flex-col-reverse sm:flex-row sm:justify-end gap-3 border-t border-stone-100 pt-5">
                <a href="{{ route('admin.accounts.index') }}" class="inline-flex items-center justify-center px-6 py-3 rounded-xl border border-stone-200 bg-stone-100 text-stone-600 text-xs font-bold hover:bg-stone-200 transition">Hủy</a>
                <button type="submit" class="inline-flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-red-800 text-white text-xs font-bold hover:bg-red-900 transition shadow-sm">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </div>
        </form>
    </div>

    <div class="flex items-start gap-3 rounded-xl border border-red-100 bg-red-50 px-4 py-3 text-xs text-red-900">
        <i class="fas fa-info-circle mt-0.5"></i>
        <p>Lưu ý: Thay đổi thông tin có thể ảnh hưởng đến việc sử dụng tài khoản. Quyền và trạng thái không thể chỉnh sửa tại trang này.</p>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.getElementById('account-edit-form').addEventListener('submit', function (event) {
        if (!this.checkValidity()) return;
        event.preventDefault();
        const form = this;
        Swal.fire({
            title: 'Xác nhận cập nhật',
            text: 'Cậu có chắc muốn lưu các thay đổi cho tài khoản này?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Lưu thay đổi',
            cancelButtonText: 'Hủy bỏ',
            confirmButtonColor: '#991b1b',
            cancelButtonColor: '#78716c',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) HTMLFormElement.prototype.submit.call(form);
        });
    });
</script>
@endsection
