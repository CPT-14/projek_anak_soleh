@extends('layouts.admin')
@section('title', 'Kelola Pesanan')

@section('content')
<div class="welcome-section" style="margin-bottom: 24px;">
    <h1>Kelola Pesanan 🛒</h1>
    <p>Daftar seluruh pesanan masuk. Klik "Lihat Detail" untuk memvalidasi pembayaran dan mengubah status.</p>
</div>

<section class="admin-section" style="background: #fff; padding: 24px; border-radius: 12px; box-shadow: 0 4px 10px rgba(0,0,0,0.04); border: 1px solid #f1f5f9;">
    <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 14px;">
        <thead>
            <tr style="background: #f8fafc;">
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">ID Pesanan</th>
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Pembeli</th>
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Produk</th>
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Total (Rp)</th>
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Status</th>
                <th style="padding: 14px; border-bottom: 2px solid #e2e8f0; color: #475569;">Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($orders as $order): ?>
            <tr>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9; font-weight: bold;">#ORD-<?php echo e($order->id); ?></td>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9;"><?php echo e($order->user->name ?? 'User Dihapus'); ?></td>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9;">
                    <div style="font-weight: 600; color: #2563eb;"><?php echo e($order->variant->product->name ?? '-'); ?></div>
                    <div style="font-size: 12px; color: #64748b;"><?php echo e($order->variant->name ?? '-'); ?></div>
                </td>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9; font-weight: bold; color: #10b981;">
                    Rp <?php echo e(number_format($order->total_price, 0, ',', '.')); ?>

                </td>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9;">
                    <?php if($order->status === 'selesai' || strtolower($order->status) === 'success'): ?>
                        <span style="background: #d1e7dd; color: #0f5132; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Selesai</span>
                    <?php elseif($order->status === 'menunggu pembayaran'): ?>
                        <span style="background: #fff3cd; color: #856404; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Menunggu</span>
                    <?php elseif($order->status === 'diproses'): ?>
                        <span style="background: #cff4fc; color: #055160; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">Diproses</span>
                    <?php else: ?>
                        <span style="background: #f8d7da; color: #842029; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;"><?php echo e(ucfirst($order->status)); ?></span>
                    <?php endif; ?>
                </td>
                <td style="padding: 14px; border-bottom: 1px solid #f1f5f9;">
                    <a href="<?php echo e(route('admin.order.detail', $order->id)); ?>" style="background: #2563eb; color: white; padding: 8px 12px; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold; display: inline-block;">
                        Lihat Detail &rarr;
                    </a>
                </td>
            </tr>
            <?php endforeach; ?>
            
            <?php if($orders->count() === 0): ?>
            <tr>
                <td colspan="6" style="padding: 30px; text-align: center; color: #94a3b8;">Belum ada data pesanan yang masuk.</td>
            </tr>
            <?php endif; ?>
        </tbody>
    </table>
</section>
@endsection