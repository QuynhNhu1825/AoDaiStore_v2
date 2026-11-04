
<footer class="bg-stone-900 text-stone-300 pt-16 pb-8">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-12">

            <!-- Thông tin thương hiệu -->
            <div>
                <img src="{{ asset('img/logo/logo.jpg') }}"
                     alt="Logo Áo Dài Heritage"
                     class="w-16 h-auto mb-4">

                <h3 class="serif text-2xl text-white mb-6 tracking-wider">
                    ÁO DÀI HERITAGE
                </h3>

                <p class="text-sm leading-relaxed mb-6">
                    Sứ mệnh của chúng tôi là gìn giữ và tôn vinh nét đẹp
                    vĩnh cửu của chiếc Áo dài Việt Nam thông qua những
                    thiết kế tinh xảo và chất liệu cao cấp.
                </p>
            </div>

            <!-- Khám phá -->
            <div>
                <h4 class="font-bold text-white uppercase tracking-widest text-sm mb-6 border-b border-stone-700 pb-2 inline-block">
                    Khám phá
                </h4>

                <ul class="space-y-4 text-sm">
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
                        <a href="{{ url('/products') }}"
                           class="hover:text-white transition">
                            Bộ sưu tập áo dài
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/products') }}"
                           class="hover:text-white transition">
                            Áo dài truyền thống và cách tân
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Hỗ trợ khách hàng -->
            <div>
                <h4 class="font-bold text-white uppercase tracking-widest text-sm mb-6 border-b border-stone-700 pb-2 inline-block">
                    Hỗ trợ khách hàng
                </h4>

                <ul class="space-y-4 text-sm">
                    <li>
                        <a href="{{ url('/products') }}"
                           class="hover:text-white transition">
                            Hướng dẫn chọn kích cỡ (Size)
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/user-guide') }}"
                           class="hover:text-white transition">
                            Hướng dẫn mua hàng
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/shipping-policy') }}"
                           class="hover:text-white transition">
                            Chính sách vận chuyển
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/return-policy') }}"
                           class="hover:text-white transition">
                            Chính sách đổi trả
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/payment-policy') }}"
                           class="hover:text-white transition">
                            Các phương thức thanh toán
                        </a>
                    </li>
                </ul>
            </div>

            <!-- Bản tin và mạng xã hội -->
            <div>
                <h4 class="font-bold text-white uppercase tracking-widest text-sm mb-6 border-b border-stone-700 pb-2 inline-block">
                    Kết nối với chúng tôi
                </h4>

                <p class="text-sm mb-4">
                    Theo dõi Áo Dài Heritage để cập nhật những bộ sưu tập
                    mới và xu hướng áo dài.
                </p>

                <div class="flex items-center gap-4 mt-6">
                    <a href="https://www.facebook.com/"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Facebook"
                       class="hover:opacity-80 transition-opacity">
                        <img src="{{ asset('img/logo/Facebook-logo.png') }}"
                        alt="Facebook"
                        class="w-12 h-12 object-contain">

                    <a href="https://www.instagram.com/"
                       target="_blank"
                       rel="noopener noreferrer"
                       aria-label="Instagram"
                       class="hover:opacity-80 transition-opacity">
                        <img src="{{ asset('img/logo/ins.png') }}"
                             alt="Instagram"
                             class="w-8 h-8 object-contain">
                    </a>

                </div>
            </div>

        </div>

        <!-- Bản quyền -->
        <div class="border-t border-stone-800 pt-8 flex flex-col md:flex-row justify-between items-center text-xs">
            <p>
                &copy; {{ date('Y') }} Áo Dài Heritage.
                Designed with ❤️ in Vietnam.
            </p>

            <div class="mt-4 md:mt-0 flex space-x-6">
                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5e/Visa_Inc._logo.svg/2560px-Visa_Inc._logo.svg.png"
                     class="h-4 grayscale hover:grayscale-0 transition opacity-50"
                     alt="Visa">

                <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2a/Mastercard-logo.svg/1280px-Mastercard-logo.svg.png"
                     class="h-4 grayscale hover:grayscale-0 transition opacity-50"
                     alt="Mastercard">
            </div>
        </div>
    </div>
</footer>