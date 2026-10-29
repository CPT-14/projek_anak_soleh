@extends('layouts.user')

@section('title', 'QITSPREM - Katalog Layanan')

@section('styles')
<style>
    /* --- MAIN CONTENT KHUSUS HOME --- */
    .home-container { 
        max-width: 1250px; 
        margin: 0 auto; 
        padding: 20px; 
    } 
    
    /* --- BANNER SLIDER (DIBUAT FIT & RESPONSIVE) --- */
    .banner-container {
        position: relative;
        width: 100%;
        max-width: 1250px;
        margin: 0 auto 30px auto;
        border-radius: 16px;
        overflow: hidden;
        background-color: #f0f0f0;
        aspect-ratio: 21 / 9; /* Rasio lebar dibanding tinggi (Standar banner) */
        height: auto; 
    }
    .slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        background-size: 100% 100%; /* Memaksa gambar fit sepenuhnya tanpa terpotong */
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 24px;
        font-weight: bold;
    }
    .slide.active { opacity: 1; z-index: 1; }
    .banner-dots {
        position: absolute;
        bottom: 15px;
        width: 100%;
        display: flex;
        justify-content: center;
        gap: 8px;
        z-index: 2;
    }
    .dot {
        width: 30px; height: 6px;
        background: rgba(255,255,255,0.4);
        border-radius: 4px;
        cursor: pointer;
        transition: 0.3s;
    }
    .dot.active { background: white; }

    /* --- SEARCH SECTION MERAH --- */
    .search-band {
        background-color: #790D16; 
        padding: 30px 20px;
        margin-bottom: 40px;
    }
    .search-container {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }
    .search-icon { 
        position: absolute; left: 16px; top: 50%; transform: translateY(-50%); 
        color: #999; font-size: 16px; 
    }
    .search-input { 
        width: 100%; padding: 16px 20px 16px 45px; 
        border: none; border-radius: 8px; 
        font-size: 15px; outline: none; background-color: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    /* --- TOMBOL SEARCH (DI DALAM BAR) --- */
    .search-btn {
        position: absolute; right: 8px; top: 50%; transform: translateY(-50%);
        background: #111; color: white; border: none; 
        padding: 8px 20px; border-radius: 6px; font-weight: bold; cursor: pointer;
        transition: 0.2s;
    }
    .search-btn:hover { background: #333; }

    /* --- RECENT CHIPS --- */
    .recent-chips-container {
        max-width: 800px;
        margin: 20px auto 0 auto;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .recent-chip {
        background: white; color: #790D16;
        padding: 8px 20px; border-radius: 4px;
        font-size: 13px; font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: 0.2s;
    }
    .recent-chip:hover { background: #f8f9fa; transform: translateY(-2px); }

    /* --- KATEGORI PILLS DENGAN FITUR SLIDE HORIZONTAL --- */
    .categories { 
        display: flex; 
        gap: 12px; 
        margin-bottom: 32px; 
        overflow-x: auto; /* Kunci utamanya di sini: mengizinkan scroll ke samping */
        -webkit-overflow-scrolling: touch; /* Efek swipe mulus di iOS */
        padding-bottom: 10px; /* Jarak untuk scrollbar */
        flex-wrap: nowrap; /* Jangan biarkan turun ke baris baru */
    }
    
    /* Menyembunyikan tampilan scrollbar agar terlihat seperti App */
    .categories::-webkit-scrollbar {
        height: 6px; /* Tinggi scrollbar horizontal */
    }
    .categories::-webkit-scrollbar-track {
        background: #f1f1f1; 
        border-radius: 10px;
    }
    .categories::-webkit-scrollbar-thumb {
        background: #cbd5e1; 
        border-radius: 10px;
    }
    .categories::-webkit-scrollbar-thumb:hover {
        background: #94a3b8; 
    }

    .kategori-btn { 
        padding: 8px 20px; 
        border-radius: 30px; 
        font-size: 13px; 
        font-weight: 600; 
        cursor: pointer; 
        border: none; 
        transition: 0.2s; 
        white-space: nowrap; /* Teks tidak boleh patah ke bawah */
        flex-shrink: 0; /* Mencegah tombol mengecil saat dipaksa sebaris */
    }
    .kategori-btn.active { background-color: #790D16; color: white; }
    .kategori-btn.inactive { background-color: #e2e8f0; color: #475569; }
    .kategori-btn.inactive:hover { background-color: #cbd5e1; }

    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .section-title { font-size: 18px; font-weight: 700; }
    
    /* --- PRODUCT GRID RESPONSIVE --- */
    .product-grid { 
        display: grid; 
        grid-template-columns: repeat(2, 1fr); 
        gap: 12px; 
    }
    
    @media (min-width: 768px) { 
        .product-grid { grid-template-columns: repeat(3, 1fr); gap: 20px; } 
    }
    @media (min-width: 1024px) { 
        .product-grid { grid-template-columns: repeat(4, 1fr); gap: 24px; } 
    }

    /* -- KARTU PRODUK -- */
    .product-card { 
        background-color: #f4f6f8; 
        border: 1px solid #eee; 
        border-radius: 12px; 
        display: flex; 
        flex-direction: column; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        transition: transform 0.2s; 
        overflow: hidden; 
    }
    .product-card:hover { transform: translateY(-3px); }
    
    .product-icon { 
        width: 100%; height: 140px; object-fit: cover; background-color: white; 
    }
    
    .product-info { flex: 1; display: flex; flex-direction: column; padding: 12px; }
    .product-name { font-size: 14px; font-weight: 700; color: #111; margin-bottom: 4px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; } 
    .product-desc { font-size: 11px; color: #777; line-height: 1.4; margin-bottom: 8px; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; } 
    .product-price { font-size: 14px; font-weight: 800; color: #790D16; margin-bottom: 8px; }
    .btn-detail { background-color: #790D16; color: white; border: none; padding: 8px 16px; border-radius: 20px; font-size: 11px; font-weight: 600; cursor: pointer; text-decoration: none; text-align: center; width: 100%; }

    @media (min-width: 768px) {
        .product-icon { height: 200px; }
        .product-info { padding: 20px; }
        .product-name { font-size: 18px; -webkit-line-clamp: unset; }
        .product-desc { font-size: 13px; -webkit-line-clamp: 3; }
        .product-price { font-size: 18px; }
        .btn-detail { width: auto; align-self: flex-start; padding: 10px 24px; font-size: 13px; }
    }

    @media (max-width: 768px) {
        .banner-container { border-radius: 8px; margin-bottom: 20px; }
        .search-band { padding: 20px 15px; margin-bottom: 25px; }
        .home-container { padding: 10px; }
    }
</style>
@endsection

@section('content')

@if(session('success'))
<div style="background-color: #d1e7dd; color: #0f5132; padding: 16px; margin-bottom: 24px; text-align: center;">
    ✅ {{ session('success') }}
</div>
@endif  

<div class="banner-container">
    <div class="slide active" style="background-image: url('{{ asset('images/banner1.jpeg') }}');"></div>
    <div class="slide" style="background-image: url('{{ asset('images/banner2.jpeg') }}');"></div>
    <div class="slide" style="background-image: url('{{ asset('images/banner3.jpeg') }}');"></div>

    <div class="banner-dots">
        <span class="dot active" onclick="setSlide(0)"></span>
        <span class="dot" onclick="setSlide(1)"></span>
        <span class="dot" onclick="setSlide(2)"></span>
    </div>
</div>

<!-- BAND PENCARIAN & RECENT -->
<div class="search-band">
    <div class="search-container">
        <!-- FORM PENCARIAN -->
        <form action="{{ route('home') }}" method="GET" style="margin: 0;">
            <span class="search-icon">🔍</span>
            <input type="text" name="search" value="{{ $searchQuery ?? '' }}" class="search-input" placeholder="Cari layanan premium (contoh: Netflix)..." required>
            
            <div style="position: absolute; right: 8px; top: 50%; transform: translateY(-50%); display: flex; gap: 6px;">
                @if(!empty($searchQuery))
                    <a href="{{ route('home') }}" style="background: #e2e8f0; color: #475569; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; display: flex; align-items: center;">X</a>
                @endif
                <button type="submit" class="search-btn">🔍</button>
            </div>
        </form>
    </div>
    
    @if(isset($recentViews) && count($recentViews) > 0)
        <div class="recent-chips-container">
            @foreach($recentViews as $recent)
                <a href="{{ route('product.detail', $recent['id']) }}" class="recent-chip">
                    {{ $recent['name'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>

<!-- KATALOG PRODUK BAWAH -->
<div class="home-container">
    
    <!-- INFO PENCARIAN -->
    @if(!empty($searchQuery))
    <div style="text-align: center; margin-bottom: 24px; padding: 12px; background: #fff3cd; color: #856404; border-radius: 8px;">
        Menampilkan hasil pencarian untuk: <strong>"{{ $searchQuery }}"</strong> 
        (Ditemukan {{ $products->count() }} produk)
    </div>
    @endif

    <!-- MENU KATEGORI SLIDER HORIZONTAL -->
    <div class="categories" id="categoryContainer">
        <button class="kategori-btn active" data-filter="all">Semua layanan</button>
        @foreach($categories as $category)
            @if($category)
                <button class="kategori-btn inactive" data-filter="{{ $category }}">{{ $category }}</button>
            @endif
        @endforeach
    </div>

    <div class="section-header">
        <h2 class="section-title">Aplikasi Premium</h2>
    </div>

    <div class="product-grid" id="product-list">
        @forelse ($products as $product)
            <div class="product-card" data-category="{{ $product->category }}">
                <img src="{{ !empty($product->image) ? $product->image : 'https://ui-avatars.com/api/?name=' . urlencode($product->name) . '&background=790D16&color=fff' }}" alt="{{ $product->name }}" class="product-icon">
                
                <div class="product-info">
                    <h3 class="product-name">{{ $product->name }}</h3>
                    <p class="product-desc">{{ Str::limit($product->description, 65) }}</p>

                    <div class="product-price">
                        @if($product->variants->isEmpty())
                            <span style="font-size: 14px; color: #dc2626;">Belum Tersedia</span>
                        @else
                            @php
                                $minPrice = $product->variants->min('price');
                                $maxPrice = $product->variants->max('price');
                            @endphp
                            @if($minPrice == $maxPrice)
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @else
                                <span style="font-size: 12px; font-weight: 600; display: block; color: gray;">Start from</span>
                                Rp {{ number_format($minPrice, 0, ',', '.') }}
                            @endif
                        @endif
                    </div>

                    @php $stokTersedia = $product->stocks()->where('is_available', true)->count(); @endphp
                    @if($stokTersedia > 0)
                        <p style="font-size: 11px; font-weight: 700; color: #49a85d; margin-bottom: 12px;">✅ Sisa: {{ $stokTersedia }} Akun</p>
                    @else
                        <p style="font-size: 11px; font-weight: 700; color: #dc2626; margin-bottom: 12px;">❌ Stok Habis</p>
                    @endif
                    
                    <a href="{{ route('product.detail', $product->id) }}" class="btn-detail">Selengkapnya</a>
                </div>
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 40px; color: #777;">
                Maaf, tidak ada produk yang ditemukan.
            </div>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    // --- 1. SCRIPT UNTUK SLIDER BANNER ---
    let currentSlideIndex = 0;
    const slides = document.querySelectorAll('.slide');
    const dots = document.querySelectorAll('.dot');
    let slideInterval;

    function showSlide(index) {
        slides.forEach(s => s.classList.remove('active'));
        dots.forEach(d => d.classList.remove('active'));
        slides[index].classList.add('active');
        dots[index].classList.add('active');
        currentSlideIndex = index;
    }

    function nextSlide() {
        let nextIndex = (currentSlideIndex + 1) % slides.length;
        showSlide(nextIndex);
    }

    function setSlide(index) {
        clearInterval(slideInterval);
        showSlide(index);
        slideInterval = setInterval(nextSlide, 7000);
    }

    if(slides.length > 0) {
        slideInterval = setInterval(nextSlide, 7000);
    }

    // --- 2. SCRIPT FILTER KATEGORI ---
    const categoryBtns = document.querySelectorAll('.kategori-btn');
    const productCards = document.querySelectorAll('.product-card');

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            categoryBtns.forEach(b => {
                b.classList.remove('active');
                b.classList.add('inactive');
            });
            this.classList.remove('inactive');
            this.classList.add('active');

            const filterValue = this.getAttribute('data-filter');

            productCards.forEach(card => {
                const productCategory = card.getAttribute('data-category'); 
                if (filterValue === 'all' || productCategory === filterValue) {
                    card.style.display = 'flex';
                } else {
                    card.style.display = 'none';
                }
            });
            
            // Opsional: Gulir slider kategori agar tombol yang diklik berada di tengah layar HP
            this.scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
        });
    });

    // --- 3. SCRIPT DRAG UNTUK SLIDER KATEGORI DI DESKTOP ---
    const slider = document.getElementById('categoryContainer');
    let isDown = false;
    let startX;
    let scrollLeft;

    slider.addEventListener('mousedown', (e) => {
        isDown = true;
        slider.style.cursor = 'grabbing';
        startX = e.pageX - slider.offsetLeft;
        scrollLeft = slider.scrollLeft;
    });
    slider.addEventListener('mouseleave', () => {
        isDown = false;
        slider.style.cursor = 'default';
    });
    slider.addEventListener('mouseup', () => {
        isDown = false;
        slider.style.cursor = 'default';
    });
    slider.addEventListener('mousemove', (e) => {
        if (!isDown) return;
        e.preventDefault();
        const x = e.pageX - slider.offsetLeft;
        const walk = (x - startX) * 2; // Angka 2 untuk mempercepat geseran (scroll speed)
        slider.scrollLeft = scrollLeft - walk;
    });
</script>
@endsection