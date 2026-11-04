
@extends('layouts.client')

@section('title', 'Giới thiệu - Áo Dài Heritage')

@section('content')

<style>
    .heritage-about,
    .heritage-about * {
        font-family: Arial, Helvetica, sans-serif !important;
        letter-spacing: normal;
        word-spacing: normal;
    }

    .heritage-about h1,
    .heritage-about h2,
    .heritage-about h3 {
        font-style: normal;
        text-transform: none;
    }

    .heritage-about p {
        line-height: 1.9;
    }

    .heritage-title {
        color: #7f1d1d;
        font-weight: 700;
    }

    .heritage-line {
        width: 75px;
        height: 3px;
        background-color: #991b1b;
        margin: 20px auto;
    }

    .heritage-card {
        transition: transform 0.3s ease,
                    box-shadow 0.3s ease;
    }

    .heritage-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.08);
    }
</style>

<div class="heritage-about bg-stone-50 min-h-screen">

    {{-- ================= BANNER GIỚI THIỆU ================= --}}
    <section class="bg-gradient-to-r from-red-950 via-red-900 to-red-950
                    text-white py-20 text-center">

        <div class="max-w-4xl mx-auto px-4">

            <img
                src="{{ asset('img/logo/logo.jpg') }}"
                alt="Áo Dài Heritage"
                class="w-28 h-28 object-cover rounded-full
                       mx-auto mb-7 border-4 border-white shadow-lg"
            >

            <h1 class="text-3xl md:text-5xl font-bold mb-6">
                ÁO DÀI HERITAGE
            </h1>

            <div class="w-24 h-1 bg-amber-200 mx-auto mb-6"></div>

            <p class="text-lg md:text-xl text-red-50 leading-9">
                Gìn giữ nét đẹp truyền thống –
                Tôn vinh vẻ đẹp người phụ nữ Việt Nam
            </p>

            <p class="mt-5 text-red-100 max-w-3xl mx-auto">
                Nơi vẻ đẹp truyền thống hòa quyện cùng phong cách
                hiện đại, mang đến những thiết kế áo dài
                thanh lịch, tinh tế và đầy bản sắc Việt.
            </p>

        </div>
    </section>


    {{-- ================= CÂU CHUYỆN THƯƠNG HIỆU ================= --}}
    <section class="max-w-6xl mx-auto px-4 py-20">

        <div class="grid grid-cols-1 md:grid-cols-2
                    gap-12 items-center">

            <div>

                <h2 class="text-3xl heritage-title mb-6">
                    Câu chuyện của chúng tôi
                </h2>

                <div class="w-20 h-1 bg-red-800 mb-7"></div>

                <p class="text-stone-600 mb-5 text-justify">
                    Áo dài từ lâu đã trở thành biểu tượng của
                    vẻ đẹp duyên dáng, thanh lịch và là một phần
                    không thể thiếu trong văn hóa Việt Nam.
                    Trải qua nhiều thế hệ, tà áo dài vẫn giữ
                    được nét đẹp riêng, gắn liền với những
                    khoảnh khắc ý nghĩa trong cuộc sống.
                </p>

                <p class="text-stone-600 mb-5 text-justify">
                    Xuất phát từ tình yêu dành cho trang phục
                    truyền thống, Áo Dài Heritage được xây dựng
                    với mong muốn gìn giữ và lan tỏa vẻ đẹp
                    của tà áo dài Việt Nam đến với nhiều
                    khách hàng hơn.
                </p>

                <p class="text-stone-600 text-justify">
                    Chúng tôi hướng đến việc mang lại những
                    mẫu áo dài kết hợp hài hòa giữa giá trị
                    truyền thống và xu hướng thời trang hiện đại.
                    Mỗi thiết kế đều chú trọng sự tinh tế,
                    tính thẩm mỹ và sự thoải mái, giúp người
                    mặc tự tin thể hiện phong cách riêng.
                </p>

            </div>


            {{-- GIÁ TRỊ CỐT LÕI --}}
            <div class="bg-white rounded-2xl shadow-lg
                        border border-stone-100 p-10">

                <h3 class="text-2xl font-bold text-red-900
                           text-center mb-4">
                    Giá trị cốt lõi
                </h3>

                <div class="heritage-line"></div>

                <div class="space-y-6 mt-8">

                    <div class="flex items-start gap-4">
                        <span class="text-red-800 text-xl">✓</span>
                        <div>
                            <h4 class="font-bold text-stone-800 mb-1">
                                Gìn giữ giá trị truyền thống
                            </h4>
                            <p class="text-stone-600 text-sm">
                                Trân trọng và lan tỏa nét đẹp
                                văn hóa Việt qua từng tà áo dài.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-red-800 text-xl">✓</span>
                        <div>
                            <h4 class="font-bold text-stone-800 mb-1">
                                Chú trọng chất lượng
                            </h4>
                            <p class="text-stone-600 text-sm">
                                Quan tâm đến chất liệu, kiểu dáng
                                và sự thoải mái khi sử dụng.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-red-800 text-xl">✓</span>
                        <div>
                            <h4 class="font-bold text-stone-800 mb-1">
                                Tinh tế trong thiết kế
                            </h4>
                            <p class="text-stone-600 text-sm">
                                Kết hợp hài hòa giữa nét đẹp
                                cổ điển và phong cách hiện đại.
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4">
                        <span class="text-red-800 text-xl">✓</span>
                        <div>
                            <h4 class="font-bold text-stone-800 mb-1">
                                Tận tâm với khách hàng
                            </h4>
                            <p class="text-stone-600 text-sm">
                                Hướng đến trải nghiệm mua sắm
                                thuận tiện, thân thiện và đáng tin cậy.
                            </p>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </section>


    {{-- ================= SỨ MỆNH VÀ TẦM NHÌN ================= --}}
    <section class="bg-white py-20">

        <div class="max-w-6xl mx-auto px-4">

            <div class="text-center mb-12">
                <h2 class="text-3xl heritage-title">
                    Sứ mệnh và tầm nhìn
                </h2>

                <div class="heritage-line"></div>

                <p class="text-stone-600 max-w-3xl mx-auto">
                    Áo Dài Heritage không chỉ hướng đến
                    việc cung cấp sản phẩm thời trang
                    mà còn mong muốn góp phần gìn giữ
                    bản sắc văn hóa Việt Nam.
                </p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                {{-- SỨ MỆNH --}}
                <div class="heritage-card bg-stone-50
                            rounded-2xl p-9 border border-stone-200">

                    <h3 class="text-2xl font-bold text-red-900 mb-5">
                        Sứ mệnh
                    </h3>

                    <p class="text-stone-600 text-justify">
                        Mang vẻ đẹp của áo dài Việt Nam đến gần
                        hơn với khách hàng thông qua những
                        thiết kế thanh lịch, đa dạng và phù hợp
                        với nhiều nhu cầu sử dụng.
                    </p>

                    <p class="text-stone-600 mt-4 text-justify">
                        Đồng thời, xây dựng một không gian
                        mua sắm trực tuyến thuận tiện,
                        giúp khách hàng dễ dàng tìm kiếm,
                        lựa chọn và sở hữu những mẫu áo dài
                        yêu thích.
                    </p>
                </div>

                {{-- TẦM NHÌN --}}
                <div class="heritage-card bg-stone-50
                            rounded-2xl p-9 border border-stone-200">

                    <h3 class="text-2xl font-bold text-red-900 mb-5">
                        Tầm nhìn
                    </h3>

                    <p class="text-stone-600 text-justify">
                        Trở thành địa chỉ mua sắm áo dài
                        trực tuyến được khách hàng tin tưởng,
                        nơi hội tụ những thiết kế mang đậm
                        nét đẹp truyền thống kết hợp cùng
                        xu hướng thời trang hiện đại.
                    </p>

                    <p class="text-stone-600 mt-4 text-justify">
                        Áo Dài Heritage mong muốn góp phần
                        quảng bá hình ảnh tà áo dài Việt Nam,
                        khuyến khích thế hệ trẻ trân trọng
                        và tiếp nối những giá trị văn hóa
                        tốt đẹp của dân tộc.
                    </p>
                </div>

            </div>
        </div>
    </section>


    {{-- ================= SẢN PHẨM ================= --}}
    <section class="bg-stone-50 py-20">

        <div class="max-w-6xl mx-auto px-4">

            <div class="text-center mb-12">

                <h2 class="text-3xl heritage-title">
                    Sản phẩm của chúng tôi
                </h2>

                <div class="heritage-line"></div>

                <p class="text-stone-600 max-w-3xl mx-auto">
                    Áo Dài Heritage mang đến nhiều phong cách
                    áo dài khác nhau, đáp ứng nhu cầu mặc đẹp
                    trong các dịp lễ hội, sự kiện quan trọng
                    và cuộc sống thường ngày.
                </p>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

                {{-- ÁO DÀI TRUYỀN THỐNG --}}
                <div class="heritage-card bg-white rounded-xl
                            p-8 text-center shadow-sm">

                    <h3 class="text-xl font-bold text-red-900 mb-4">
                        Áo dài truyền thống
                    </h3>

                    <p class="text-stone-600 text-sm">
                        Những thiết kế mang nét đẹp
                        cổ điển, kín đáo và duyên dáng,
                        phù hợp với các dịp lễ,
                        sự kiện và những khoảnh khắc
                        trang trọng.
                    </p>
                </div>

                {{-- ÁO DÀI CÁCH TÂN --}}
                <div class="heritage-card bg-white rounded-xl
                            p-8 text-center shadow-sm">

                    <h3 class="text-xl font-bold text-red-900 mb-4">
                        Áo dài cách tân
                    </h3>

                    <p class="text-stone-600 text-sm">
                        Sự kết hợp giữa nét đẹp truyền thống
                        và phong cách hiện đại, mang đến
                        sự trẻ trung, năng động và
                        thoải mái cho người mặc.
                    </p>
                </div>

                {{-- ÁO DÀI DỊP ĐẶC BIỆT --}}
                <div class="heritage-card bg-white rounded-xl
                            p-8 text-center shadow-sm">

                    <h3 class="text-xl font-bold text-red-900 mb-4">
                        Áo dài dịp đặc biệt
                    </h3>

                    <p class="text-stone-600 text-sm">
                        Những mẫu áo dài thanh lịch
                        dành cho ngày Tết, lễ cưới,
                        lễ tốt nghiệp, chụp ảnh
                        và các sự kiện đáng nhớ.
                    </p>
                </div>

            </div>

        </div>
    </section>


    {{-- ================= LÝ DO LỰA CHỌN ================= --}}
    <section class="bg-white py-20">

        <div class="max-w-6xl mx-auto px-4">

            <div class="text-center mb-12">

                <h2 class="text-3xl heritage-title">
                    Vì sao lựa chọn Áo Dài Heritage?
                </h2>

                <div class="heritage-line"></div>

            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">

                <div class="flex gap-5 p-6 bg-stone-50 rounded-xl">
                    <div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Mẫu mã đa dạng
                        </h3>
                        <p class="text-stone-600 text-sm">
                            Nhiều kiểu dáng, màu sắc và kích thước,
                            phù hợp với sở thích và phong cách
                            của từng khách hàng.
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 p-6 bg-stone-50 rounded-xl">
                    <div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Chú trọng chất liệu
                        </h3>
                        <p class="text-stone-600 text-sm">
                            Cung cấp thông tin về chất liệu,
                            kiểu dáng và đặc điểm sản phẩm
                            để khách hàng dễ dàng lựa chọn.
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 p-6 bg-stone-50 rounded-xl">
                    <div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Mua sắm thuận tiện
                        </h3>
                        <p class="text-stone-600 text-sm">
                            Dễ dàng xem sản phẩm, lựa chọn
                            màu sắc, kích thước, thêm vào
                            giỏ hàng và đặt mua trực tuyến.
                        </p>
                    </div>
                </div>

                <div class="flex gap-5 p-6 bg-stone-50 rounded-xl">
                    <div>
                        <h3 class="font-bold text-stone-800 mb-2">
                            Trải nghiệm tận tâm
                        </h3>
                        <p class="text-stone-600 text-sm">
                            Hướng đến việc hỗ trợ khách hàng
                            trong quá trình tìm hiểu sản phẩm,
                            mua sắm và theo dõi đơn hàng.
                        </p>
                    </div>
                </div>

            </div>
        </div>
    </section>


    {{-- ================= CAM KẾT ================= --}}
    <section class="bg-stone-100 py-20">

        <div class="max-w-4xl mx-auto px-4 text-center">

            <h2 class="text-3xl heritage-title mb-6">
                Cam kết của chúng tôi
            </h2>

            <div class="heritage-line"></div>

            <p class="text-stone-600 mb-5">
                Áo Dài Heritage luôn hướng đến việc
                mang lại trải nghiệm mua sắm trực tuyến
                thuận tiện, minh bạch và thân thiện.
            </p>

            <p class="text-stone-600 mb-5">
                Chúng tôi chú trọng cung cấp thông tin
                sản phẩm rõ ràng, hình ảnh trực quan,
                giá bán minh bạch và hỗ trợ khách hàng
                trong quá trình lựa chọn sản phẩm.
            </p>

            <p class="text-stone-600">
                Sự hài lòng và tin tưởng của khách hàng
                chính là động lực để Áo Dài Heritage
                không ngừng hoàn thiện chất lượng
                sản phẩm và dịch vụ.
            </p>

        </div>
    </section>


    {{-- ================= KHÁM PHÁ SẢN PHẨM ================= --}}
    <section class="bg-red-950 text-white py-20">

        <div class="max-w-4xl mx-auto px-4 text-center">

            <h2 class="text-3xl font-bold mb-6">
                Cùng Áo Dài Heritage tôn vinh vẻ đẹp Việt
            </h2>

            <p class="text-red-100 mb-9">
                Mỗi tà áo dài là một câu chuyện về
                sự duyên dáng, thanh lịch và niềm tự hào
                văn hóa Việt Nam. Hãy khám phá và lựa chọn
                thiết kế phù hợp với phong cách của bạn.
            </p>

            <a
                href="{{ url('/') }}#product-section"
                class="inline-block bg-white text-red-900
                       font-bold px-10 py-4 rounded-lg
                       hover:bg-red-100 transition
                       shadow-lg"
            >
                Khám phá sản phẩm
            </a>

        </div>
    </section>

</div>

@endsection
