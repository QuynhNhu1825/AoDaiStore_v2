<footer class="bg-stone-900 text-stone-300 pt-10 pb-5">

    <div class="max-w-7xl mx-auto px-4 sm:px-6">

        {{-- 4 CỘT CHÍNH --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4
                    gap-8 lg:gap-10 pb-8">

            {{-- THƯƠNG HIỆU --}}
            <div>
                <div class="flex items-center gap-3 mb-4">
                    <img
                        src="{{ asset('img/logo/logo.jpg') }}"
                        alt="Logo Áo Dài Heritage"
                        class="w-12 h-12 object-contain rounded-lg"
                    >

                    <h3 class="serif text-lg text-white tracking-wide">
                        ÁO DÀI HERITAGE
                    </h3>
                </div>

                <p class="text-sm leading-6 text-stone-400">
                    Sứ mệnh của chúng tôi là gìn giữ và tôn vinh
                    nét đẹp vĩnh cửu của chiếc Áo dài Việt Nam
                    thông qua những thiết kế tinh xảo và
                    chất liệu cao cấp.
                </p>
            </div>

            {{-- KHÁM PHÁ --}}
            <div>
                <h4 class="font-bold text-white uppercase
                           tracking-wider text-sm mb-4">
                    Khám phá
                </h4>

                <ul class="space-y-3 text-sm text-stone-400">
                    <li>
                        <a href="{{ url('/') }}"
                           class="hover:text-white transition">
                            Trang chủ
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/products') }}"
                           class="hover:text-white transition">
                            Tất cả sản phẩm
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/products/category') }}"
                           class="hover:text-white transition">
                            Bộ sưu tập áo dài
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/gioi-thieu') }}"
                           class="hover:text-white transition">
                            Giới thiệu
                        </a>
                    </li>
                </ul>
            </div>

            {{-- HỖ TRỢ --}}
            <div>
                <h4 class="font-bold text-white uppercase
                           tracking-wider text-sm mb-4">
                    Hỗ trợ khách hàng
                </h4>

                <ul class="space-y-3 text-sm text-stone-400">
                    <li>
                        <a href="{{ route('pages.size-guide') }}"
                           class="hover:text-white transition">
                            Hướng dẫn chọn kích cỡ (Size)
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pages.shopping-guide') }}"
                           class="hover:text-white transition">
                            Hướng dẫn mua hàng
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pages.shipping-policy') }}"
                           class="hover:text-white transition">
                            Chính sách vận chuyển
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pages.return-policy') }}"
                           class="hover:text-white transition">
                            Chính sách đổi trả
                        </a>
                    </li>

                    <li>
                        <a href="{{ route('pages.payment-methods') }}"
                           class="hover:text-white transition">
                            Các phương thức thanh toán
                        </a>
                    </li>
                </ul>
            </div>

            {{-- KẾT NỐI --}}
            <div>
                <h4 class="font-bold text-white uppercase
                           tracking-wider text-sm mb-4">
                    Kết nối với chúng tôi
                </h4>

                <p class="text-sm leading-6 text-stone-400">
                    Theo dõi Áo Dài Heritage để cập nhật
                    những bộ sưu tập mới và xu hướng áo dài.
                </p>

                <div class="flex items-center gap-3 mt-4">

                    
{{-- FACEBOOK --}}
<a href="https://www.facebook.com/"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Facebook"
   class="w-11 h-11 rounded-full
          flex items-center justify-center
          overflow-hidden
          hover:scale-110 transition-transform">

    <img src="{{ asset('img/logo/facebook.png') }}"
         alt="Facebook"
         class="w-full h-full object-contain rounded-full">
</a>


                    
{{-- INSTAGRAM --}}
<a href="https://www.instagram.com/"
   target="_blank"
   rel="noopener noreferrer"
   aria-label="Instagram"
   class="w-11 h-11 rounded-full
          flex items-center justify-center
          overflow-hidden
          hover:scale-110 transition-transform">

    <img
        src="{{ asset('img/logo/instagram.png') }}"
        alt="Instagram"
        class="w-full h-full object-contain rounded-full"
    >
</a>


                </div>
            </div>

        </div>

        {{-- THANH THÔNG TIN CỬA HÀNG --}}
        <div class="border-t border-stone-800 py-6">

            <div class="grid grid-cols-1 sm:grid-cols-2
                        lg:grid-cols-4 gap-5 lg:gap-8">

                {{-- ĐỊA CHỈ --}}
                <div class="min-w-0">
                    <p class="text-xs text-stone-500 mb-1">
                        Địa chỉ cửa hàng
                    </p>

                    <p class="text-sm text-stone-200 leading-6">
                        123 Lê Lợi, Phường Bến Thành, TP. HCM
                    </p>
                </div>

                {{-- HOTLINE --}}
                <div class="min-w-0">
                    <p class="text-xs text-stone-500 mb-1">
                        Hotline
                    </p>

                    <a href="tel:19006333"
                       class="text-sm text-stone-200
                              hover:text-red-400 transition">
                        1900 6333
                    </a>
                </div>

                {{-- ZALO --}}
                <div class="min-w-0">
                    <p class="text-xs text-stone-500 mb-1">
                        Zalo hỗ trợ
                    </p>

                    <a href="https://zalo.me/0345310901"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="text-sm text-stone-200
                              hover:text-red-400 transition">
                        0345 310 901
                    </a>
                </div>

                {{-- EMAIL --}}
                <div class="min-w-0">
                    <p class="text-xs text-stone-500 mb-1">
                        Email
                    </p>

                    <a href="mailto:aodaiheritage@gmail.com"
                       class="text-sm text-stone-200
                              hover:text-red-400 transition
                              break-words">
                        aodaiheritage@gmail.com
                    </a>
                </div>

            </div>
        </div>

        {{-- BẢN QUYỀN --}}
        <div class="border-t border-stone-800 pt-5
                    flex flex-col sm:flex-row
                    justify-between items-center gap-3">

            <p class="text-xs text-stone-500 text-center">
                &copy; {{ date('Y') }} Áo Dài Heritage.
                All rights reserved.
            </p>

            <p class="text-xs text-stone-500 text-center">
                Gìn giữ nét đẹp truyền thống Việt Nam
            </p>

        </div>

    </div>

</footer>
