@extends('layouts.user')

@section('title', 'QITSPREM - Katalog Layanan')

@section('styles')
<style>
    /* --- MAIN CONTENT KHUSUS HOME --- */
    .home-container { max-width: 1250px; margin: 0 auto; padding: 20px; } 
    
    /* --- BANNER SLIDER (BARU) --- */
    .banner-container {
        position: relative;
        max-width: 1250px;
        margin: 0 auto 30px auto;
        border-radius: 16px;
        overflow: hidden;
        background-color: #f0f0f0;
        height: 350px; /* Sesuaikan tinggi banner */
    }
    .slide {
        position: absolute;
        top: 0; left: 0; width: 100%; height: 100%;
        opacity: 0;
        transition: opacity 1s ease-in-out;
        background-size: cover;
        background-position: center;
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

    /* --- SEARCH SECTION MERAH (BARU) --- */
    .search-band {
        background-color: #790D16; /* Warna merah gelap sesuai desain */
        padding: 30px 20px;
        margin-bottom: 40px;
    }
    .search-container {
        position: relative;
        max-width: 800px;
        margin: 0 auto;
    }
    .search-icon { position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: #999; font-size: 16px; }
    .search-input { 
        width: 100%; padding: 16px 20px 16px 45px; 
        border: none; border-radius: 8px; 
        font-size: 15px; outline: none; background-color: #fff; 
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
    
    /* --- RECENT CHIPS (BARU) --- */
    .recent-chips-container {
        max-width: 800px;
        margin: 20px auto 0 auto;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        justify-content: center;
    }
    .recent-chip {
        background: white;
        color: #790D16;
        padding: 8px 20px;
        border-radius: 4px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        transition: 0.2s;
    }
    .recent-chip:hover { background: #f8f9fa; transform: translateY(-2px); }

    /* --- KATEGORI PILLS & GRID (ASLI) --- */
    .categories { display: flex; gap: 12px; margin-bottom: 32px; flex-wrap: wrap; }
    .kategori-btn { padding: 8px 20px; border-radius: 30px; font-size: 13px; font-weight: 600; cursor: pointer; border: none; transition: 0.2s; }
    .kategori-btn.active { background-color: #790D16; color: white; }
    .kategori-btn.inactive { background-color: transparent; color: #555; }
    .kategori-btn.inactive:hover { background-color: #f0f0f0; }

    .section-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
    .section-title { font-size: 18px; font-weight: 700; }
    
    .product-grid { display: grid; grid-template-columns: 1fr; gap: 24px; }
    @media (min-width: 768px) { .product-grid { grid-template-columns: repeat(4, 1fr); } }

    /* -- PERUBAHAN DESAIN KARTU PRODUK -- */
    .product-card { 
        background-color: #f4f6f8; /* Warna abu-abu terang seperti di gambar */
        border: 1px solid #eee; 
        border-radius: 16px; 
        display: flex; 
        flex-direction: column; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.03); 
        transition: transform 0.2s; 
        overflow: hidden; /* Penting: agar gambar mengikuti sudut melengkung kartu */
    }
    .product-card:hover { transform: translateY(-3px); }
    
    .product-icon { 
        width: 100%; 
        height: 220px; /* Sedikit dilebarkan tingginya */
        object-fit: cover; 
        background-color: white; /* Latar belakang gambar jadi putih */
        /* Border radius bawah dihilangkan agar menyatu dengan info */
        border-bottom-left-radius: 0; 
        border-bottom-right-radius: 0; 
        margin-bottom: 0; /* Margin dihilangkan */
    }
    
    .product-info { 
        flex: 1; 
        display: flex; 
        flex-direction: column; 
        padding: 20px; /* Padding dipindah ke sini agar teks tidak mepet ujung */
    }
    .product-name { font-size: 19px; font-weight: 700; color: #111; margin-bottom: 6px; } 
    .product-desc { font-size: 14px; color: #777; line-height: 1.6; margin-bottom: 12px; flex-grow: 1; } 
    .product-price { font-size: 18px; font-weight: 800; color: #790D16; margin-bottom: 16px; }
    .btn-detail { 
        background-color: #790D16; color: white; border: none; padding: 10px 28px;
        border-radius: 20px; font-size: 13px; font-weight: 600; cursor: pointer; text-decoration: none; align-self: flex-start;
    }
</style>
@endsection

@section('content')

<!-- NOTIFIKASI SUKSES -->
@if(session('success'))
<div style="background-color: #d1e7dd; color: #0f5132; padding: 16px; margin-bottom: 24px; text-align: center;">
    ✅ {{ session('success') }}
</div>
@endif  

<div class="banner-container">
        <!-- Slide 1 -->
        <div class="slide active" style="background-image: url('{{ asset('images/banner1.jpeg') }}');">
            <!-- Kosongkan teks di sini jika di dalam foto sudah ada tulisannya -->
        </div>
        
        <!-- Slide 2 -->
        <div class="slide" style="background-image: url('{{ asset('images/banner2.jpeg') }}');">
        </div>
        
        <!-- Slide 3 -->
        <div class="slide" style="background-image: url('{{ asset('images/banner3.jpeg') }}');">
        </div>

        <div class="banner-dots">
            <span class="dot active" onclick="setSlide(0)"></span>
            <span class="dot" onclick="setSlide(1)"></span>
            <span class="dot" onclick="setSlide(2)"></span>
        </div>
    </div>

<!-- BAND PENCARIAN & RECENT (BACKGROUND MERAH PENUH) -->
<div class="search-band">
    <div class="search-container">
        <span class="search-icon">🔍</span>
        <!-- Fitur Search Hiasan -->
        <input type="text" class="search-input" placeholder="Cari layanan premium...">
    </div>
    
    <!-- LOGIKA TAMPIL RECENT VIEWS JIKA ADA (1 - 5 KOTAK) -->
    @if(isset($recentViews) && count($recentViews) > 0)
        <div class="recent-chips-container">
            @foreach($recentViews as $recent)
                <!-- Tombol ini mengarah langsung ke produk yang pernah dilihat -->
                <a href="{{ route('product.detail', $recent['id']) }}" class="recent-chip">
                    {{ $recent['name'] }}
                </a>
            @endforeach
        </div>
    @endif
</div>

<!-- KATALOG PRODUK BAWAH -->
<div class="home-container">
    <div class="categories">
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
            <p>Belum ada produk.</p>
        @endforelse
    </div>
</div>
@endsection

@section('scripts')
<script>
    // --- 1. SCRIPT UNTUK SLIDER BANNER (7 DETIK) ---
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
        clearInterval(slideInterval); // Reset timer kalau user klik manual
        showSlide(index);
        slideInterval = setInterval(nextSlide, 7000); // Mulai lagi 7 detiknya
    }

    // Jalankan interval otomatis
    if(slides.length > 0) {
        slideInterval = setInterval(nextSlide, 7000);
    }

    // --- 2. SCRIPT FILTER KATEGORI ASLI ANDA ---
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
        });
    });
</script>
@endsection