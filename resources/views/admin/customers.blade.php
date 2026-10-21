@extends('layouts.admin')

@section('title', 'Kelola Customer')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Customer 👥</h1>
    <p>Daftar pengguna pembeli yang terdaftar di sistem.</p>
</div>

<section class="admin-section" style="border-top: 4px solid #790D16;">
    <h2 class="section-title">👥 Daftar Pelanggan</h2>
    <div>
        @forelse($customers as $customer)
        <div style="display: flex; align-items: center; gap: 16px; border-bottom: 1px solid var(--card-border); padding: 16px 0;">
            <div style="width: 40px; height: 40px; border-radius: 50%; background-color: #f3f4f6; display: flex; justify-content: center; align-items: center; font-weight: bold; color: #790D16;">
                {{ substr($customer->name, 0, 1) }}
            </div>
            <div>
                <h4 style="font-size: 15px; font-weight: 600;">{{ $customer->name }}</h4>
                <p style="font-size: 13px; color: var(--text-muted); margin-top: 2px;">{{ $customer->email }}</p>
            </div>
        </div>
        @empty
        <p style="color: var(--text-muted); text-align: center; padding: 20px 0;">Belum ada customer terdaftar.</p>
        @endforelse
    </div>
</section>
@endsection