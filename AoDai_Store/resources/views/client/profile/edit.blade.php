@extends('layouts.client')

@section('title', 'Cập nhật thông tin tài khoản')

@section('content')

<div class="max-w-3xl mx-auto py-10 px-4">

    {{-- TIÊU ĐỀ --}}
    <div class="mb-7">
        <h2 class="text-2xl font-bold text-gray-800">
            Cập nhật thông tin tài khoản
        </h2>

        <p class="text-gray-500 mt-1">
            Chỉnh sửa thông tin cá nhân và địa chỉ giao hàng.
        </p>
    </div>

    @if(empty($user->SDT) || empty($user->DiaChi))
        <div class="mb-5 p-4 bg-amber-50
                    border border-amber-200 rounded-xl
                    text-amber-800">

            <p class="font-semibold">
                <i class="fa-solid fa-circle-info mr-2"></i>
                Thông tin tài khoản chưa đầy đủ
            </p>

            <p class="text-sm mt-1">
                Vui lòng bổ sung số điện thoại và địa chỉ
                để thuận tiện cho quá trình giao hàng.
            </p>
        </div>
    @endif

    {{-- FORM --}}
    <div class="bg-white rounded-2xl shadow-md
                border border-gray-100 overflow-hidden">

        <div class="bg-gradient-to-r from-red-900 to-red-800
                    px-6 py-5">

            <h3 class="text-white font-bold text-lg">
                <i class="fa-solid fa-user-pen mr-2"></i>
                Thông tin khách hàng
            </h3>

            <p class="text-red-100 text-sm mt-1">
                Các trường có dấu * là bắt buộc.
            </p>
        </div>

        <form action="{{ route('profile.update') }}"
              method="POST"
              class="p-6 sm:p-8">

            @csrf
            @method('PUT')

            <div class="space-y-5">

                {{-- HỌ TÊN --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">
                        Họ và tên <span class="text-red-600">*</span>
                    </label>

                    <input type="text"
                           name="HoTen"
                           value="{{ old('HoTen', $user->HoTen) }}"
                           required
                           maxlength="255"
                           class="w-full border border-gray-300
                                  rounded-xl px-4 py-3
                                  focus:outline-none
                                  focus:border-red-800">

                    @error('HoTen')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- EMAIL --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">
                        Email <span class="text-red-600">*</span>
                    </label>

                    <input type="email"
                           name="Email"
                           value="{{ old('Email', $user->Email) }}"
                           required
                           maxlength="255"
                           class="w-full border border-gray-300
                                  rounded-xl px-4 py-3
                                  focus:outline-none
                                  focus:border-red-800">

                    @error('Email')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- SỐ ĐIỆN THOẠI --}}
                <div>
                    <label class="block font-semibold text-gray-700 mb-2">
                        Số điện thoại <span class="text-red-600">*</span>
                    </label>

                    <input type="tel"
                           name="SDT"
                           value="{{ old('SDT', $user->SDT) }}"
                           required
                           maxlength="10"
                           pattern="(03|05|07|08|09)[0-9]{8}"
                           oninput="this.value=this.value.replace(/[^0-9]/g,'')"
                           placeholder="Nhập số điện thoại"
                           class="w-full border border-gray-300
                                  rounded-xl px-4 py-3
                                  focus:outline-none
                                  focus:border-red-800">

                    @error('SDT')
                        <p class="text-red-600 text-sm mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- ĐỊA CHỈ --}}
                <div class="pt-5 border-t border-gray-200">

                    <h3 class="text-lg font-bold text-red-900 mb-2">
                        <i class="fa-solid fa-location-dot mr-2"></i>
                        Địa chỉ giao hàng mặc định
                    </h3>

                    <p class="text-sm text-gray-500 mb-5">
                        Địa chỉ này sẽ tự động hiển thị khi đặt hàng.
                    </p>

                    <div class="space-y-5">

                        {{-- SỐ NHÀ --}}
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">
                                Số nhà, tên đường
                                <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                   name="so_nha"
                                   value="{{ old('so_nha', $addressData['soNha']) }}"
                                   required
                                   maxlength="200"
                                   placeholder="Ví dụ: 123 Lê Lợi"
                                   class="w-full border border-gray-300
                                          rounded-xl px-4 py-3
                                          focus:outline-none
                                          focus:border-red-800">

                            @error('so_nha')
                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- PHƯỜNG XÃ --}}
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">
                                Phường / Xã
                                <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                   name="phuong_xa"
                                   value="{{ old('phuong_xa', $addressData['phuongXa']) }}"
                                   required
                                   maxlength="150"
                                   placeholder="Ví dụ: Phường Bến Thành"
                                   class="w-full border border-gray-300
                                          rounded-xl px-4 py-3
                                          focus:outline-none
                                          focus:border-red-800">

                            @error('phuong_xa')
                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                        {{-- TỈNH THÀNH --}}
                        <div>
                            <label class="block font-semibold text-gray-700 mb-2">
                                Tỉnh / Thành phố
                                <span class="text-red-600">*</span>
                            </label>

                            <input type="text"
                                   name="tinh_thanh"
                                   value="{{ old('tinh_thanh', $addressData['tinhThanh']) }}"
                                   required
                                   maxlength="150"
                                   placeholder="Ví dụ: TP. Hồ Chí Minh"
                                   class="w-full border border-gray-300
                                          rounded-xl px-4 py-3
                                          focus:outline-none
                                          focus:border-red-800">

                            @error('tinh_thanh')
                                <p class="text-red-600 text-sm mt-1">
                                    {{ $message }}
                                </p>
                            @enderror
                        </div>

                    </div>
                </div>

            </div>

            {{-- NÚT THAO TÁC --}}
            <div class="flex flex-col-reverse sm:flex-row
                        justify-end gap-3 mt-8 pt-6
                        border-t border-gray-200">

                <a href="{{ route('profile.index') }}"
                   class="text-center px-6 py-3
                          border border-gray-300 rounded-xl
                          text-gray-700 font-semibold
                          hover:bg-gray-100 transition">
                    Hủy
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-red-800
                               hover:bg-red-900 text-white
                               font-bold rounded-xl
                               transition shadow-md">

                    <i class="fa-solid fa-floppy-disk mr-2"></i>
                    Lưu thay đổi
                </button>

            </div>

        </form>
    </div>

</div>

@endsection
