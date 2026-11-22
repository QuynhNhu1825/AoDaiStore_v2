@extends('layouts.client')

@section('title', 'Heritage')

@section('content')
<div id="heritage-home">
    {{-- BANNER: tên class riêng để tránh CSS carousel trong layout ghi đè --}}
    <section class="heritage-hero" aria-label="Bộ sưu tập Áo Dài Heritage">
        <div class="heritage-hero-track">
            <div class="heritage-hero-slide is-active" aria-hidden="false">
                <img src="{{ asset('img/logo/index.png') }}"
                     alt="Bộ sưu tập áo dài Heritage" fetchpriority="high">
                <div class="heritage-hero-content">
                    <h1>Dáng Việt Kiêu Sa</h1>
                    <p>Tôn vinh vẻ đẹp vĩnh cửu của phụ nữ Việt</p>
                    <a href="#product-section">Khám phá ngay</a>
                </div>
            </div>
            <div class="heritage-hero-slide" aria-hidden="true">
                <img src="{{ asset('img/logo/download.jpg') }}" alt="Thông tin cửa hàng Áo Dài Heritage">
            </div>
            <div class="heritage-hero-slide" aria-hidden="true">
                <img src="{{ asset('img/logo/Hương Thời Gian, Sắc Di Sản.png') }}" alt="Hương Thời Gian, Sắc Di Sản">
            </div>
        </div>
        <button type="button" class="heritage-hero-arrow heritage-prev" aria-label="Slide trước">&#10094;</button>
        <button type="button" class="heritage-hero-arrow heritage-next" aria-label="Slide tiếp theo">&#10095;</button>
        <div class="heritage-hero-dots" aria-label="Chọn slide">
            <button type="button" class="is-active" aria-label="Slide 1" aria-current="true"></button>
            <button type="button" aria-label="Slide 2"></button>
            <button type="button" aria-label="Slide 3"></button>
        </div>
    </section>

    {{-- BỘ LỌC --}}
    <section id="product-section" class="relative py-5 sm:py-7">
        <div class="max-w-7xl mx-auto px-4 sm:px-6">
            <form id="filter-form" action="{{ route('home') }}" method="GET"
                  class="max-w-6xl mx-auto bg-[#faf9f6] border border-stone-200 rounded-2xl shadow-sm p-4 sm:p-5">
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3">
                    <div class="relative min-w-0 sm:col-span-2 lg:col-span-1">
                        <input type="search" name="search" value="{{ request('search') }}"
                               placeholder="Tìm kiếm mẫu áo dài..." aria-label="Tìm kiếm sản phẩm"
                               class="w-full h-12 pl-11 pr-4 bg-white border border-stone-200 rounded-xl text-sm text-stone-700 outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/10">
                        <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-stone-400 pointer-events-none"></i>
                    </div>
                    <select name="color" aria-label="Lọc màu sắc" class="filter-select heritage-filter-input">
                        <option value="">Tất cả màu sắc</option>
                        @foreach(($data['colors'] ?? collect()) as $color)
                            <option value="{{ $color->MaMau }}" {{ request('color') == $color->MaMau ? 'selected' : '' }}>{{ $color->TenMau }}</option>
                        @endforeach
                    </select>
                    <select name="category" aria-label="Lọc danh mục" class="filter-select heritage-filter-input">
                        <option value="">Tất cả danh mục</option>
                        @foreach(($data['categories'] ?? collect()) as $cat)
                            <option value="{{ $cat->MaDanhMuc }}" {{ request('category') == $cat->MaDanhMuc ? 'selected' : '' }}>{{ $cat->TenDanhMuc }}</option>
                        @endforeach
                    </select>
                    <select name="sort" aria-label="Sắp xếp sản phẩm" class="filter-select heritage-filter-input">
                        <option value="">Sắp xếp sản phẩm</option>
                        <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Giá: Thấp đến Cao</option>
                        <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Giá: Cao đến Thấp</option>
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Mới nhất</option>
                    </select>
                </div>
                <div class="mt-3 pt-3 border-t border-stone-200 flex justify-end">
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-xs text-stone-500 hover:text-red-800 transition">
                        <i class="fas fa-times-circle"></i> Xóa tất cả bộ lọc
                    </a>
                </div>
            </form>
        </div>
    </section>

    {{-- TẤT CẢ SẢN PHẨM: vùng được cập nhật qua AJAX --}}
    <div id="product-data-container" aria-live="polite">
        <section class="max-w-7xl mx-auto py-10 sm:py-14 lg:py-16 px-4 sm:px-6">
            <div class="text-center mb-8 sm:mb-12">
                <h2 class="serif text-2xl sm:text-3xl font-bold text-stone-800 uppercase tracking-widest">Tất Cả Mẫu Thiết Kế</h2>
                <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 lg:gap-8">
                @forelse(($data['product'] ?? collect()) as $s)
                    <article class="heritage-product-card group bg-white overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-stone-100 flex flex-col h-full">
                        <div class="heritage-product-image relative overflow-hidden bg-stone-100">
                            @if($s->HinhAnh)
                                <img src="{{ asset('img/products/' . basename($s->HinhAnh)) }}" alt="{{ $s->TenSanPham }}"
                                     class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy"
                                     onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}';">
                            @else
                                <div class="flex h-full items-center justify-center text-stone-400 text-sm">Chưa có hình ảnh sản phẩm</div>
                            @endif
                        </div>
                        <div class="p-4 sm:p-5 lg:p-6 text-center flex-1 flex flex-col justify-between">
                            <div>
                                <h3 class="serif text-sm sm:text-base font-semibold text-stone-700 uppercase tracking-tight mb-1">{{ $s->TenSanPham }}</h3>
                                <p class="text-xs text-stone-400 uppercase tracking-wider mb-3 italic">{{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}</p>
                            </div>
                            <div>
                                @php $giaSanPham = $s->chiTietSanPham->min('Gia'); @endphp
                                <p class="text-red-800 font-bold text-lg mb-4">
                                    {{ $giaSanPham !== null ? number_format((float) $giaSanPham, 0, ',', '.') . 'đ' : 'Liên hệ' }}
                                </p>
                                <div class="pt-4 border-t border-stone-100">
                                    <a href="{{ route('product.detail', $s->MaSanPham) }}" class="inline-block border-b border-stone-800 text-xs hover:text-red-700 hover:border-red-700 transition uppercase tracking-widest pb-1 font-bold">Xem chi tiết</a>
                                </div>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="col-span-full text-center py-16 text-stone-500 italic">Không tìm thấy mẫu thiết kế nào phù hợp với yêu cầu của bạn.</div>
                @endforelse
            </div>
            <div class="mt-10 text-center">
                <a href="{{ route('products.index') }}" class="heritage-view-all">Xem tất cả</a>
            </div>
        </section>
    </div>

    {{-- SẢN PHẨM MỚI --}}
    <section class="max-w-7xl mx-auto py-10 sm:py-14 lg:py-16 px-4 sm:px-6">
        <div class="text-center mb-8 sm:mb-12">
            <h2 class="serif text-2xl sm:text-3xl font-bold text-stone-800 uppercase tracking-widest">Sản Phẩm Mới</h2>
            <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 sm:gap-6 lg:gap-8">
            @forelse(($data['newProducts'] ?? collect()) as $s)
                <article class="heritage-product-card group bg-white overflow-hidden shadow-sm hover:shadow-xl transition duration-300 border border-stone-100 flex flex-col h-full">
                    <div class="heritage-product-image relative overflow-hidden bg-stone-100">
                        <img src="{{ !empty($s->HinhAnh) ? asset('img/products/' . basename($s->HinhAnh)) : asset('img/no-image.png') }}"
                             alt="{{ $s->TenSanPham }}" class="w-full h-full object-cover group-hover:scale-105 transition duration-700" loading="lazy"
                             onerror="this.onerror=null;this.src='{{ asset('img/no-image.png') }}';">
                    </div>
                    <div class="p-4 sm:p-5 lg:p-6 text-center flex-1 flex flex-col justify-between">
                        <div>
                            <h3 class="serif text-sm sm:text-base font-semibold text-stone-700 uppercase tracking-tight mb-1">{{ $s->TenSanPham }}</h3>
                            <p class="text-xs text-stone-400 uppercase tracking-wider mb-3 italic">{{ $s->chatlieu->TenChatLieu ?? 'Vải lụa truyền thống' }}</p>
                        </div>
                        <div>
                            @php $giaMoi = $s->chiTietSanPham->min('Gia'); @endphp
                            <p class="text-red-800 font-bold text-lg mb-4">{{ $giaMoi !== null ? number_format((float) $giaMoi, 0, ',', '.') . 'đ' : 'Liên hệ' }}</p>
                            <div class="pt-4 border-t border-stone-100">
                                <a href="{{ route('product.detail', $s->MaSanPham) }}" class="inline-block border-b border-stone-800 text-xs hover:text-red-700 hover:border-red-700 transition uppercase tracking-widest pb-1 font-bold">Xem chi tiết</a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <div class="col-span-full text-center py-16 text-stone-500 italic">Không có sản phẩm mới.</div>
            @endforelse
        </div>
        <div class="mt-10 text-center"><a href="{{ route('products.index') }}" class="heritage-view-all">Xem tất cả</a></div>
    </section>

    {{-- KHÁM PHÁ DANH MỤC --}}
    <section class="py-10 sm:py-14 lg:py-16 bg-stone-50 border-t border-stone-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 text-center">
            <div class="mb-8 sm:mb-12">
                <h2 class="serif text-2xl sm:text-3xl font-bold text-stone-800 uppercase tracking-widest">Khám phá danh mục</h2>
                <div class="h-1 w-20 bg-red-800 mx-auto mt-4"></div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-6">
                @foreach(($data['categories'] ?? collect())->take(4) as $cat)
                    <a href="{{ route('category.show', ['id' => $cat->MaDanhMuc]) }}" class="block min-w-0">
                        <div class="heritage-category-card relative group overflow-hidden rounded-xl shadow-md bg-stone-500 flex items-center justify-center">
                            <div class="absolute inset-0 bg-stone-900/40 group-hover:bg-red-900/60 transition-colors duration-500"></div>
                            <span class="relative text-white font-bold uppercase tracking-wider text-xs sm:text-sm px-2 sm:px-4 text-center break-words">{{ $cat->TenDanhMuc }}</span>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-10"><a href="{{ route('products.category') }}" class="heritage-view-all">Xem tất cả</a></div>
        </div>
    </section>
</div>

<style>
    #heritage-home { width:100%; min-width:0; overflow-x:clip; }
    /* Ảnh banner hiển thị toàn bộ theo đúng tỷ lệ gốc, không crop */
    #heritage-home .heritage-hero { position:relative; width:100%; aspect-ratio:3 / 1; height:auto; margin:0; padding:0; overflow:hidden; background:#211b19; isolation:isolate; transition:aspect-ratio .35s ease; }
    #heritage-home .heritage-hero-track { position:absolute; inset:0; width:100%; height:100%; }
    #heritage-home .heritage-hero-slide { position:absolute; inset:0; width:100%; height:100%; opacity:0; visibility:hidden; transition:opacity .7s ease, visibility .7s ease; }
    #heritage-home .heritage-hero-slide.is-active { opacity:1; visibility:visible; z-index:1; }
    #heritage-home .heritage-hero-slide > img { display:block; width:100%; height:100%; max-width:100%; object-fit:contain; object-position:center; }
    #heritage-home .heritage-hero-content { position:absolute; top:50%; left:50%; transform:translate(-50%,-50%); z-index:2; width:min(90%,760px); padding:20px 48px; color:white; text-align:center; text-shadow:0 2px 12px rgba(0,0,0,.85); }
    #heritage-home .heritage-hero-content h1 { font-size:clamp(22px,3.7vw,54px); font-weight:700; line-height:1.2; margin:0 0 12px; }
    #heritage-home .heritage-hero-content p { font-size:clamp(12px,1.4vw,18px); line-height:1.5; margin:0 0 20px; }
    #heritage-home .heritage-hero-content a { display:inline-flex; align-items:center; justify-content:center; padding:12px 26px; border-radius:6px; background:#991b1b; color:white; text-decoration:none; font-size:13px; font-weight:700; text-shadow:none; transition:background .2s; }
    #heritage-home .heritage-hero-content a:hover { background:#7f1d1d; }
    #heritage-home .heritage-hero-arrow { position:absolute; top:50%; transform:translateY(-50%); z-index:3; width:38px; height:38px; border-radius:50%; background:rgba(0,0,0,.42); color:white; cursor:pointer; display:grid; place-items:center; }
    #heritage-home .heritage-prev { left:10px; } #heritage-home .heritage-next { right:10px; }
    #heritage-home .heritage-hero-dots { position:absolute; bottom:13px; left:50%; transform:translateX(-50%); z-index:3; display:flex; gap:8px; }
    #heritage-home .heritage-hero-dots button { width:9px; height:9px; border-radius:50%; background:rgba(255,255,255,.65); cursor:pointer; }
    #heritage-home .heritage-hero-dots button.is-active { background:#fff; width:23px; border-radius:8px; }
    #heritage-home .heritage-filter-input { display:block; width:100%; min-width:0; height:48px; padding:0 12px; border:1px solid #e7e5e4; border-radius:12px; background:white; color:#57534e; font-size:14px; outline:none; }
    #heritage-home .heritage-filter-input:focus { border-color:#991b1b; box-shadow:0 0 0 3px rgba(153,27,27,.1); }
    #heritage-home .heritage-product-image { width:100%; aspect-ratio:3/4; }
    #heritage-home .heritage-category-card { min-height:160px; }
    #heritage-home .heritage-view-all { display:inline-block; border:1px solid #292524; padding:12px 28px; font-size:12px; letter-spacing:.12em; text-transform:uppercase; border-radius:9999px; font-weight:700; color:#292524; transition:background .2s,color .2s; }
    #heritage-home .heritage-view-all:hover { background:#292524; color:white; }
    @media(max-width:767px) {
        #heritage-home .heritage-hero-content { width:92%; padding:6px 26px; }
        #heritage-home .heritage-hero-content h1 { font-size:clamp(14px,4vw,24px); margin-bottom:5px; }
        #heritage-home .heritage-hero-content p { font-size:clamp(9px,2.5vw,12px); margin-bottom:9px; }
        #heritage-home .heritage-hero-content a { padding:7px 12px; font-size:10px; }
        #heritage-home .heritage-hero-arrow { width:28px; height:28px; font-size:12px; }
        #heritage-home .heritage-prev { left:3px; } #heritage-home .heritage-next { right:3px; }
        #heritage-home .heritage-category-card { min-height:120px; }
    }
    @media(max-width:360px) {
        #heritage-home .heritage-hero-content h1 { font-size:14px; }
        #heritage-home .heritage-hero-content p { font-size:9px; }
    }
    @media(prefers-reduced-motion:reduce) { #heritage-home .heritage-hero-slide { transition:none; } }
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const root = document.getElementById('heritage-home');
    if (!root) return;

    // Slider độc lập, không phụ thuộc CSS/JS carousel của layout.
    const slides = Array.from(root.querySelectorAll('.heritage-hero-slide'));
    const dots = Array.from(root.querySelectorAll('.heritage-hero-dots button'));
    const hero = root.querySelector('.heritage-hero');
    let currentSlide = 0;
    let slideTimer;
    // Tự tính tỷ lệ từng ảnh: banner ôm vừa ảnh, không cắt ảnh và không dư khoảng trắng.
    function fitHeroToImage(index) {
        const img = slides[index]?.querySelector('img');
        if (!img) return;
        const update = () => {
            if (currentSlide !== index || !img.naturalWidth || !img.naturalHeight) return;
            hero.style.aspectRatio = `${img.naturalWidth} / ${img.naturalHeight}`;
        };
        if (img.complete && img.naturalWidth) update();
        else img.addEventListener('load', update, { once: true });
    }
    function showSlide(index) {
        currentSlide = (index + slides.length) % slides.length;
        slides.forEach((slide, i) => {
            const active = i === currentSlide;
            slide.classList.toggle('is-active', active);
            slide.setAttribute('aria-hidden', String(!active));
            slide.querySelectorAll('a').forEach(a => { a.tabIndex = active ? 0 : -1; });
        });
        fitHeroToImage(currentSlide);
        dots.forEach((dot, i) => {
            dot.classList.toggle('is-active', i === currentSlide);
            if (i === currentSlide) dot.setAttribute('aria-current', 'true');
            else dot.removeAttribute('aria-current');
        });
    }
    function startSlider() {
        clearInterval(slideTimer);
        if (slides.length > 1 && !window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
            slideTimer = setInterval(() => showSlide(currentSlide + 1), 5000);
        }
    }
    root.querySelector('.heritage-prev')?.addEventListener('click', () => { showSlide(currentSlide - 1); startSlider(); });
    root.querySelector('.heritage-next')?.addEventListener('click', () => { showSlide(currentSlide + 1); startSlider(); });
    dots.forEach((dot, i) => dot.addEventListener('click', () => { showSlide(i); startSlider(); }));
    hero?.addEventListener('mouseenter', () => clearInterval(slideTimer));
    hero?.addEventListener('mouseleave', startSlider);
    document.addEventListener('visibilitychange', () => document.hidden ? clearInterval(slideTimer) : startSlider());
    showSlide(0);
    startSlider();

    // Tìm kiếm / lọc AJAX, không thay đổi phần banner.
    const form = document.getElementById('filter-form');
    const container = document.getElementById('product-data-container');
    if (!form || !container) return;
    let searchTimer;
    let controller;
    async function fetchProducts(url = form.action, pushHistory = true) {
        if (controller) controller.abort();
        controller = new AbortController();
        const params = new URLSearchParams(new FormData(form));
        const requestUrl = new URL(url, window.location.origin);
        // Giữ tham số phân trang từ URL khi click trang.
        const page = requestUrl.searchParams.get('page');
        requestUrl.search = params.toString();
        if (page) requestUrl.searchParams.set('page', page);
        container.style.opacity = '0.5';
        try {
            const response = await fetch(requestUrl.toString(), {
                signal: controller.signal,
                headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html' }
            });
            if (!response.ok) throw new Error('HTTP ' + response.status);
            const html = await response.text();
            const doc = new DOMParser().parseFromString(html, 'text/html');
            const updated = doc.querySelector('#product-data-container');
            if (!updated) throw new Error('Không tìm thấy vùng sản phẩm trong phản hồi');
            container.innerHTML = updated.innerHTML;
            if (pushHistory) window.history.pushState({}, '', requestUrl.pathname + requestUrl.search);
        } catch (error) {
            if (error.name !== 'AbortError') {
                console.error('Lỗi lọc sản phẩm:', error);
                alert('Không thể tải sản phẩm. Vui lòng thử lại.');
            }
        } finally {
            if (!controller.signal.aborted) container.style.opacity = '1';
        }
    }
    form.querySelectorAll('.filter-select').forEach(select => select.addEventListener('change', () => fetchProducts(form.action)));
    form.querySelector('[name="search"]')?.addEventListener('input', () => {
        clearTimeout(searchTimer);
        searchTimer = setTimeout(() => fetchProducts(form.action), 500);
    });
    form.addEventListener('submit', event => { event.preventDefault(); clearTimeout(searchTimer); fetchProducts(form.action); });
    container.addEventListener('click', event => {
        const link = event.target.closest('.ajax-pagination a');
        if (link) { event.preventDefault(); fetchProducts(link.href); }
    });
    window.addEventListener('popstate', () => window.location.reload());
});
</script>
@endsection
