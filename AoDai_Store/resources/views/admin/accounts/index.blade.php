@extends('layouts.admin')

@section('title', 'Danh sách Tài khoản')

@section('content')
<div class="space-y-6">

    {{-- THỐNG KÊ --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white p-4 rounded-lg shadow-sm flex items-center border-l-4 border-emerald-500">
            <div class="p-3 bg-emerald-100 rounded-full text-emerald-600 mr-4">
                <i class="fas fa-users fa-lg"></i>
            </div>
            <div>
                <p class="text-sm text-gray-500 uppercase">Tổng số tài khoản</p>
                <p class="text-xl font-bold text-gray-800">{{ $accounts->total() }}</p>
            </div>
        </div>
    </div>

    {{-- TÌM KIẾM --}}
    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <form action="{{ route('admin.accounts.index') }}" method="GET" class="flex flex-col md:flex-row gap-3">

            <div class="relative flex-1">
                <span class="absolute inset-y-0 left-0 pl-3 flex items-center text-gray-400">
                    <i class="fas fa-search"></i>
                </span>
                <input type="text" name="keyword" value="{{ request('keyword') }}"
                    class="w-full pl-10 pr-4 py-2 border rounded-lg focus:ring-2 focus:ring-emerald-500 outline-none"
                    placeholder="Tìm username, họ tên hoặc email...">
            </div>

            <button class="bg-slate-800 text-white px-6 py-2 rounded-lg hover:bg-slate-700">
                Lọc
            </button>

            @if(request('keyword'))
                <a href="{{ route('admin.accounts.index') }}"
                    class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg flex items-center">
                    Xóa lọc
                </a>
            @endif
        </form>
    </div>

    {{-- BẢNG --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
        <table class="w-full text-left">
            <thead>
                <tr class="bg-gray-50 text-gray-600 text-xs uppercase font-bold">
                    <th class="px-6 py-4">Tài khoản</th>
                    <th class="px-6 py-4">Họ tên</th>
                    <th class="px-6 py-4">Email</th>
                    <th class="px-6 py-4">Quyền</th>
                    <th class="px-6 py-4">Trạng thái</th>
                    <th class="px-6 py-4 text-center">Chức năng</th>
                </tr>
            </thead>

            <tbody class="divide-y">
                @forelse($accounts as $tk)
                    <tr class="hover:bg-emerald-50/40 transition">

                        {{-- USERNAME --}}
                        <td class="px-6 py-4">
                            <p class="font-bold text-gray-800">{{ $tk->TenDangNhap }}</p>
                            <p class="text-xs text-gray-400 font-mono">ID: {{ $tk->MaTaiKhoan }}</p>
                        </td>

                        {{-- HỌ TÊN --}}
                        <td class="px-6 py-4 text-sm text-gray-700">
                            {{ $tk->HoTen ?? '---' }}
                        </td>

                        {{-- EMAIL --}}
                        <td class="px-6 py-4 text-sm text-gray-600">
                            {{ $tk->Email ?? '---' }}
                        </td>

                        {{-- ROLE --}}
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-bold">
                                {{ strtoupper($tk->Role) }}
                            </span>
                        </td>

                        {{-- TRẠNG THÁI --}}
                        <td class="px-6 py-4">
                            @if($tk->TrangThai == 1)
                                <span class="inline-flex px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">
                                    Hoạt động
                                </span>
                            @else
                                <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-bold">
                                    Bị khóa
                                </span>
                            @endif
                        </td>

                        {{-- CHỨC NĂNG --}}
                        <td class="px-6 py-4">
                            <div class="flex justify-center space-x-2">

                                <a href="{{ route('admin.accounts.edit', $tk->MaTaiKhoan) }}"
                                   class="flex items-center px-3 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg hover:bg-blue-200">
                                    <i class="fas fa-edit mr-1"></i> Sửa
                                </a>

                                {{-- KHÓA / MỞ --}}
                                <form action="{{ route('admin.accounts.lock', $tk->MaTaiKhoan) }}"
                                      method="POST">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center px-3 py-2 text-sm 
                                        {{ $tk->TrangThai == 1 
                                            ? 'bg-red-100 text-red-700 hover:bg-red-200' 
                                            : 'bg-green-100 text-green-700 hover:bg-green-200' }}">
                                        <i class="fas {{ $tk->TrangThai == 1 ? 'fa-lock' : 'fa-unlock' }} mr-1"></i>
                                        {{ $tk->TrangThai == 1 ? 'Khóa' : 'Mở' }}
                                    </button>
                                </form>

                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <i class="fas fa-user-slash fa-3x mb-3"></i>
                            <p>Không có tài khoản nào</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- PAGINATION --}}
    <div>
        {{ $accounts->links() }}
    </div>

</div>
@endsection
