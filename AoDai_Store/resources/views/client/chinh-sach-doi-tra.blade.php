
@extends('layouts.client')

@section('title', 'Chính sách đổi trả')

@section('content')

<div class="bg-stone-50 min-h-screen py-12">

    <div class="max-w-5xl mx-auto px-4">

        {{-- TIÊU ĐỀ --}}
        <div class="text-center mb-12">
            <p class="text-red-800 uppercase tracking-[0.25em] text-xs font-semibold mb-3">
                Áo Dài Heritage
            </p>

            <h1 class="text-3xl md:text-4xl font-bold text-stone-800 serif">
                CHÍNH SÁCH ĐỔI TRẢ
            </h1>

            <div class="w-20 h-1 bg-red-800 mx-auto mt-5 mb-5"></div>

            <p class="text-stone-500 text-sm md:text-base max-w-2xl mx-auto leading-relaxed">
                Áo Dài Heritage luôn mong muốn mang đến cho khách hàng
                những sản phẩm chất lượng và trải nghiệm mua sắm tốt nhất.
            </p>
        </div>

        {{-- NỘI DUNG CHÍNH SÁCH --}}
        <div class="bg-white rounded-2xl shadow-sm border border-stone-200 p-6 md:p-12">

            {{-- GIỚI THIỆU --}}
            <div class="mb-10">
                <p class="text-stone-600 leading-8 text-justify">
                    Chúng tôi hiểu rằng việc lựa chọn một chiếc áo dài phù hợp
                    về kiểu dáng, màu sắc và kích thước rất quan trọng.
                    Vì vậy, Áo Dài Heritage xây dựng chính sách đổi trả nhằm
                    bảo vệ quyền lợi khách hàng và đảm bảo sự hài lòng
                    trong quá trình mua sắm.
                </p>
            </div>

            {{-- 1. ĐIỀU KIỆN ĐỔI TRẢ --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        01
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Điều kiện đổi trả
                    </h2>
                </div>

                <p class="text-stone-600 leading-7 mb-4">
                    Khách hàng có thể yêu cầu đổi hoặc trả sản phẩm khi đáp ứng
                    các điều kiện sau:
                </p>

                <ul class="space-y-3 text-stone-600 leading-7">

                    <li class="flex gap-3">
                        <span class="text-red-800 font-bold">✓</span>
                        <span>Sản phẩm còn nguyên tem, nhãn và phụ kiện đi kèm (nếu có).</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-800 font-bold">✓</span>
                        <span>Sản phẩm chưa qua sử dụng, giặt tẩy hoặc chỉnh sửa.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-800 font-bold">✓</span>
                        <span>Sản phẩm không bị hư hỏng do lỗi bảo quản hoặc sử dụng từ phía khách hàng.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-800 font-bold">✓</span>
                        <span>Khách hàng cung cấp được mã đơn hàng hoặc thông tin mua hàng để cửa hàng kiểm tra.</span>
                    </li>

                </ul>
            </section>

            {{-- 2. TRƯỜNG HỢP ĐƯỢC ĐỔI TRẢ --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        02
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Các trường hợp được hỗ trợ đổi trả
                    </h2>
                </div>

                <p class="text-stone-600 leading-7 mb-4">
                    Áo Dài Heritage tiếp nhận yêu cầu đổi trả trong những trường hợp:
                </p>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

                    <div class="bg-stone-50 rounded-xl p-5 border border-stone-100">
                        <div class="text-red-800 text-2xl mb-3">01</div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Sản phẩm bị lỗi
                        </h3>
                        <p class="text-sm text-stone-500 leading-6">
                            Sản phẩm có lỗi kỹ thuật, đường may hoặc hư hỏng
                            phát sinh từ phía cửa hàng.
                        </p>
                    </div>

                    <div class="bg-stone-50 rounded-xl p-5 border border-stone-100">
                        <div class="text-red-800 text-2xl mb-3">02</div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Giao sai sản phẩm
                        </h3>
                        <p class="text-sm text-stone-500 leading-6">
                            Sản phẩm nhận được không đúng mẫu mã, màu sắc
                            hoặc kích thước đã xác nhận trong đơn hàng.
                        </p>
                    </div>

                    <div class="bg-stone-50 rounded-xl p-5 border border-stone-100">
                        <div class="text-red-800 text-2xl mb-3">03</div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Sai sót khi đóng gói
                        </h3>
                        <p class="text-sm text-stone-500 leading-6">
                            Đơn hàng thiếu sản phẩm hoặc có nhầm lẫn
                            trong quá trình đóng gói.
                        </p>
                    </div>

                    <div class="bg-stone-50 rounded-xl p-5 border border-stone-100">
                        <div class="text-red-800 text-2xl mb-3">04</div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Trường hợp khác
                        </h3>
                        <p class="text-sm text-stone-500 leading-6">
                            Các trường hợp đặc biệt sẽ được cửa hàng
                            xem xét và hỗ trợ tùy theo tình trạng thực tế.
                        </p>
                    </div>

                </div>
            </section>

            {{-- 3. TRƯỜNG HỢP KHÔNG ĐƯỢC ĐỔI TRẢ --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        03
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Các trường hợp không áp dụng đổi trả
                    </h2>
                </div>

                <ul class="space-y-3 text-stone-600 leading-7">

                    <li class="flex gap-3">
                        <span class="text-red-700">✕</span>
                        <span>Sản phẩm đã qua sử dụng, giặt tẩy hoặc bị hư hỏng do khách hàng.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-700">✕</span>
                        <span>Sản phẩm đã bị chỉnh sửa, thay đổi thiết kế hoặc kích thước mà không có sự xác nhận của cửa hàng.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-700">✕</span>
                        <span>Sản phẩm không còn đầy đủ phụ kiện, tem nhãn đi kèm do lỗi từ phía khách hàng.</span>
                    </li>

                    <li class="flex gap-3">
                        <span class="text-red-700">✕</span>
                        <span>Khách hàng không cung cấp được thông tin cần thiết để xác minh đơn hàng.</span>
                    </li>

                </ul>
            </section>

            {{-- 4. QUY TRÌNH ĐỔI TRẢ --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        04
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Quy trình đổi trả
                    </h2>
                </div>

                <div class="space-y-5">

                    <div class="flex gap-4">
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-red-50 text-red-800 flex items-center justify-center font-bold">
                            1
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800">
                                Gửi yêu cầu đổi trả
                            </h3>
                            <p class="text-sm text-stone-500 leading-6 mt-1">
                                Khách hàng liên hệ với Áo Dài Heritage và cung cấp
                                mã đơn hàng cùng lý do yêu cầu đổi trả.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-red-50 text-red-800 flex items-center justify-center font-bold">
                            2
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800">
                                Tiếp nhận và kiểm tra
                            </h3>
                            <p class="text-sm text-stone-500 leading-6 mt-1">
                                Cửa hàng tiếp nhận thông tin, kiểm tra tình trạng
                                sản phẩm và xác minh yêu cầu.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-red-50 text-red-800 flex items-center justify-center font-bold">
                            3
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800">
                                Xác nhận phương án xử lý
                            </h3>
                            <p class="text-sm text-stone-500 leading-6 mt-1">
                                Cửa hàng thông báo kết quả và hướng dẫn khách hàng
                                thực hiện các bước tiếp theo.
                            </p>
                        </div>
                    </div>

                    <div class="flex gap-4">
                        <div class="w-10 h-10 flex-shrink-0 rounded-full bg-red-50 text-red-800 flex items-center justify-center font-bold">
                            4
                        </div>
                        <div>
                            <h3 class="font-semibold text-stone-800">
                                Hoàn tất đổi trả
                            </h3>
                            <p class="text-sm text-stone-500 leading-6 mt-1">
                                Hai bên thực hiện đổi hoặc trả sản phẩm theo
                                phương án đã được cửa hàng xác nhận.
                            </p>
                        </div>
                    </div>

                </div>
            </section>

            {{-- 5. CHI PHÍ ĐỔI TRẢ --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        05
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Chi phí đổi trả
                    </h2>
                </div>

                <p class="text-stone-600 leading-7">
                    Chi phí vận chuyển phát sinh trong quá trình đổi trả sẽ được
                    xem xét tùy theo nguyên nhân và tình trạng thực tế.
                    Đối với trường hợp lỗi từ phía cửa hàng hoặc giao sai sản phẩm,
                    khách hàng vui lòng liên hệ để được hướng dẫn hỗ trợ.
                </p>
            </section>

            {{-- 6. LƯU Ý --}}
            <section class="mb-10">

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        06
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Một số lưu ý
                    </h2>
                </div>

                <div class="bg-amber-50 border border-amber-200 rounded-xl p-5">
                    <p class="text-sm text-stone-700 leading-7">
                        Khách hàng nên kiểm tra sản phẩm ngay khi nhận hàng.
                        Nếu phát hiện sản phẩm có vấn đề, vui lòng giữ lại sản phẩm,
                        bao bì và hình ảnh liên quan để cửa hàng có cơ sở hỗ trợ.
                    </p>
                </div>
            </section>

            {{-- 7. LIÊN HỆ --}}
            <section>

                <div class="flex items-center gap-3 mb-5">
                    <span class="w-9 h-9 flex-shrink-0 rounded-full bg-red-800 text-white flex items-center justify-center font-bold">
                        07
                    </span>

                    <h2 class="text-xl font-bold text-stone-800">
                        Thông tin hỗ trợ
                    </h2>
                </div>

                <p class="text-stone-600 leading-7 mb-5">
                    Nếu có bất kỳ thắc mắc nào liên quan đến chính sách đổi trả,
                    khách hàng vui lòng liên hệ với Áo Dài Heritage thông qua
                    các thông tin liên hệ được hiển thị ở cuối trang.
                    Chúng tôi luôn sẵn sàng tiếp nhận và hỗ trợ khách hàng.
                </p>

                <div class="bg-stone-50 border border-stone-200 rounded-xl p-6 text-center">
                    <h3 class="text-xl font-bold text-red-800 serif mb-2">
                        ÁO DÀI HERITAGE
                    </h3>

                    <p class="text-sm text-stone-500 italic">
                        Gìn giữ nét đẹp truyền thống Việt
                    </p>
                </div>

            </section>

        </div>

        {{-- NÚT QUAY LẠI --}}
        <div class="text-center mt-10">

            <a href="{{ url('/') }}"
               class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-900 text-white px-8 py-3 rounded-xl font-semibold text-sm transition duration-300 shadow-md">

                <span>←</span>
                <span>Quay lại trang chủ</span>

            </a>

        </div>

    </div>

</div>

@endsection
