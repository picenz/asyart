@extends('layouts.admin', ['title' => 'Admin - Transaction Detail'])

@section('content')
    <div class="max-w-4xl mx-auto space-y-6 pb-12">

        <!-- HEADER & BACK BUTTON -->
        <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-xs text-gray-500">
                <a href="{{ route('admin.qris.index') }}" class="hover:underline">QRIS Transactions</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Transaction Detail</span>
            </div>
            <a href="{{ route('admin.qris.index') }}"
                class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
                ← Back
            </a>
        </div>

        <!-- DETAIL CARD -->
        <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">

            <!-- Top Status Bar -->
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-100 gap-4">
                <div>
                    <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Reference Number</span>
                    <h1 class="text-xl font-bold text-gray-900 mt-0.5">{{ $transaction->reference ?? 'QR-20260512-8846' }}
                    </h1>
                </div>
                <div>
                    <!-- Status Badge -->
                    <span
                        class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200/50">
                        <span class="w-2 h-2 rounded-full bg-amber-500"></span> Pending Payment
                    </span>
                </div>
            </div>

            <!-- Grid Informasi Transaksi -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">

                <!-- Kolom Kiri -->
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 font-medium">Order ID</p>
                        <p class="text-gray-900 font-bold text-sm mt-0.5">{{ $transaction->order_number ?? '#FN-10433' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Customer Name</p>
                        <p class="text-gray-900 font-semibold mt-0.5">{{ $transaction->customer_name ?? 'Rani Pratama' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Total Amount</p>
                        <p class="text-gray-900 font-bold text-sm mt-0.5 text-black">Rp2.950.000</p>
                    </div>
                </div>

                <!-- Kolom Kanan -->
                <div class="space-y-4">
                    <div>
                        <p class="text-gray-400 font-medium">Created At</p>
                        <p class="text-gray-900 font-semibold mt-0.5">12 May, 14:41</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Paid / Expiry Status</p>
                        <p class="text-gray-900 font-semibold mt-0.5 text-amber-600">Expires 14:56 (Within 15 minutes)</p>
                    </div>
                    <div>
                        <p class="text-gray-400 font-medium">Payment Gateway</p>
                        <p class="text-gray-900 font-semibold mt-0.5">QRIS Dynamic Auto-Verify</p>
                    </div>
                </div>

            </div>

            <!-- QR CODE SNAPSHOT SECTION (MENAMPILKAN GAMBAR QRIS) -->
            <div class="pt-6 border-t border-gray-100 flex flex-col items-center justify-center space-y-3">
                <div
                    class="w-44 h-44 bg-white border border-gray-200 rounded-xl flex items-center justify-center p-3 shadow-sm">
                    <!-- Ganti asset('images/qris-code.png') sesuai dengan path file gambar QRIS lu atau user -->
                    <img src="{{ asset('images/qris.jpg') }}" alt="QRIS Payment Code"
                        class="w-full h-full object-contain rounded-lg">
                </div>
                <p class="text-[11px] text-gray-400 text-center">QR code generated automatically upon checkout request.</p>
            </div>

        </div>

    </div>
@endsection
