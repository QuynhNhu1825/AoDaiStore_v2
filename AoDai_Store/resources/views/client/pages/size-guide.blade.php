@extends('layouts.client')

@section('title', 'Hướng dẫn chọn kích cỡ')

@section('content')
<section class="bg-stone-50 min-h-screen py-12 px-4">
    <div class="max-w-5xl mx-auto">

        <div class="text-center mb-10">
            <h1 class="serif text-3xl font-bold uppercase tracking-widest text-stone-800">
                Hướng dẫn chọn kích cỡ
            </h1>
            <div class="w-20 h-1 bg-red-800 mx-auto mt-4"></div>
            <p class="text-stone-500 mt-4">
                Lựa chọn kích cỡ áo dài phù hợp để tôn lên vóc dáng của bạn.
            </p>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-stone-100 p-6 md:p-10 space-y-10">

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-4">
                    1. Bảng kích cỡ tham khảo
                </h2>

                <p class="text-sm text-stone-600 mb-5">
                    Bảng kích cỡ dưới đây mang tính tham khảo.
                    Kích thước thực tế có thể thay đổi tùy theo kiểu dáng và chất liệu.
                </p>

                <div class="overflow-x-auto rounded-xl border border-stone-200">
                    <table class="w-full text-center text-sm">
                        <thead class="bg-red-900 text-white">
                            <tr>
                                <th class="p-4">Size</th>
                                <th class="p-4">Vòng ngực (cm)</th>
                                <th class="p-4">Vòng eo (cm)</th>
                                <th class="p-4">Vòng mông (cm)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-stone-100 text-stone-700">
                            <tr><td class="p-4 font-bold">S</td><td>80–84</td><td>62–66</td><td>86–90</td></tr>
                            <tr class="bg-stone-50"><td class="p-4 font-bold">M</td><td>84–88</td><td>66–70</td><td>90–94</td></tr>
                            <tr><td class="p-4 font-bold">L</td><td>88–92</td><td>70–74</td><td>94–98</td></tr>
                            <tr class="bg-stone-50"><td class="p-4 font-bold">XL</td><td>92–96</td><td>74–78</td><td>98–102</td></tr>
                            <tr><td class="p-4 font-bold">XXL</td><td>96–100</td><td>78–82</td><td>102–106</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <div>
                <h2 class="text-xl font-bold text-red-900 mb-5">
                    2. Cách đo số đo cơ thể
                </h2>

                <div class="grid md:grid-cols-3 gap-5">
                    @foreach([
                        ['Vòng ngực', 'Đo quanh phần đầy đặn nhất của ngực, giữ thước dây nằm ngang.'],
                        ['Vòng eo', 'Đo quanh phần nhỏ nhất của eo, không siết thước quá chặt.'],
                        ['Vòng mông', 'Đo quanh phần rộng nhất của mông, giữ thước dây song song mặt đất.']
                    ] as $item)
                        <div class="bg-stone-50 border border-stone-100 rounded-xl p-5">
                            <h3 class="font-bold text-stone-800 mb-2">
                                {{ $item[0] }}
                            </h3>
                            <p class="text-sm text-stone-600 leading-relaxed">
                                {{ $item[1] }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-red-50 border border-red-100 rounded-xl p-5">
                <h3 class="font-bold text-red-900 mb-2">
                    <i class="fas fa-lightbulb mr-2"></i>
                    Lưu ý khi chọn size: 
                </h3>
                <p class="text-sm text-stone-700 leading-relaxed">
                    Nếu số đo của bạn nằm giữa hai kích cỡ, hãy tham khảo
                    bảng size của sản phẩm cụ thể và ưu tiên kích cỡ
                    mang lại sự thoải mái khi mặc.
                </p>
            </div>

            <div class="text-center">
                <a href="{{ route('products.index') }}"
                   class="inline-flex items-center gap-2 bg-red-900 hover:bg-red-800
                          text-white font-semibold px-7 py-3 rounded-xl transition">
                    Khám phá sản phẩm
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>

        </div>
    </div>
</section>
@endsection
