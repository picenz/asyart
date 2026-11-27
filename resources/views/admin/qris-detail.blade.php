
@extends('layouts.admin', ['title' => 'Admin - Transaction Detail'])

@section('content')
<div class="max-w-4xl mx-auto space-y-6 pb-12">

    {{-- HEADER --}}
    <div class="flex items-center justify-between">
        <a href="{{ route('admin.qris.index') }}"
            class="px-3 py-1.5 bg-white border border-gray-200 rounded-lg text-xs font-semibold text-gray-700 hover:bg-gray-50 transition shadow-sm">
            ← Back
        </a>
    </div>

    {{-- DETAIL CARD --}}
    <div class="bg-white rounded-xl border border-gray-200 p-6 shadow-sm space-y-6">

        {{-- STATUS --}}
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between pb-6 border-b border-gray-100 gap-4">
            <div>
                <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">
                    Reference Number
                </span>
                <h1 class="text-xl font-bold text-gray-900 mt-0.5">
                    {{ $transaction->reference }}
                </h1>
            </div>

            @php
                $statusStyles = [
                    'pending' => 'bg-amber-50 text-amber-700 border-amber-200',
                    'paid' => 'bg-green-50 text-green-700 border-green-200',
                    'rejected' => 'bg-red-50 text-red-700 border-red-200',
                    'expired' => 'bg-gray-100 text-gray-600 border-gray-200',
                ];

                $status = strtolower($transaction->status);
            @endphp

            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium border {{ $statusStyles[$status] ?? 'bg-gray-100 text-gray-600 border-gray-200' }}">
                {{ ucfirst($transaction->status) }}
            </span>
        </div>

        {{-- INFORMASI TRANSAKSI --}}
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 text-xs">

            <div class="space-y-4">
                <div>
                    <p class="text-gray-400 font-medium">Order ID</p>
                    <p class="text-gray-900 font-bold text-sm mt-0.5">
                        #{{ $transaction->order_id }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Customer Name</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->order->first_name ?? '-' }}
                        {{ $transaction->order->last_name ?? '' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Total Amount</p>
                    <p class="text-gray-900 font-bold text-sm mt-0.5">
                        Rp{{ number_format((float) $transaction->amount, 0, ',', '.') }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Customer Email</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->order->email ?? '-' }}
                    </p>
                </div>
            </div>

            <div class="space-y-4">
                <div>
                    <p class="text-gray-400 font-medium">Created At</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->created_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Paid At</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->paid_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Verified At</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->verified_at?->format('d M Y, H:i') ?? '-' }}
                    </p>
                </div>

                <div>
                    <p class="text-gray-400 font-medium">Payment Method</p>
                    <p class="text-gray-900 font-semibold mt-0.5">
                        {{ $transaction->payment_method ?? 'QRIS' }} - Manual Verification
                    </p>
                </div>
            </div>
        </div>

        {{-- QRIS STATIS --}}
        <div class="pt-6 border-t border-gray-100 flex flex-col items-center justify-center space-y-3">
            <h3 class="text-sm font-bold text-gray-800">Store QRIS Code</h3>

            <div class="w-44 h-44 bg-white border border-gray-200 rounded-xl flex items-center justify-center p-3 shadow-sm">
                <img src="{{ asset('images/qris.jpg') }}"
                    alt="QRIS Payment Code"
                    class="w-full h-full object-contain rounded-lg">
            </div>

            <p class="text-[11px] text-gray-400 text-center">
                Static QRIS code used for customer payments.
            </p>
        </div>

        {{-- BUKTI PEMBAYARAN CUSTOMER --}}
        <div class="pt-6 border-t border-gray-100 space-y-3">
            <h3 class="text-sm font-bold text-gray-800">
                Customer Payment Proof
            </h3>

            @if ($transaction->proof_path)
                <div class="border border-gray-200 rounded-xl p-4 space-y-3">
                    <img src="{{ asset('storage/' . $transaction->proof_path) }}"
                        alt="Customer Payment Proof"
                        class="max-w-full max-h-[500px] object-contain rounded-lg mx-auto">

                    <p class="text-xs text-gray-500 text-center">
                        Bukti pembayaran yang diunggah customer.
                    </p>
                </div>
            @else
                <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
                    <p class="text-xs text-gray-500">
                        Customer belum mengunggah bukti pembayaran.
                    </p>
                </div>
            @endif
        </div>

        {{-- CATATAN PENOLAKAN --}}
        @if ($transaction->rejection_note)
            <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                <p class="text-xs font-semibold text-red-700 mb-1">
                    Rejection Note
                </p>
                <p class="text-sm text-red-600">
                    {{ $transaction->rejection_note }}
                </p>
            </div>
        @endif

        {{-- ACTION ADMIN --}}
        @if ($transaction->status === 'pending' && $transaction->proof_path)
            <div class="pt-6 border-t border-gray-100 space-y-4">

                <h3 class="text-sm font-bold text-gray-800">
                    Payment Verification
                </h3>

                {{-- APPROVE --}}
                <form action="{{ route('admin.qris.approve', $transaction->id) }}"
                    method="POST">
                    @csrf
                    @method('PATCH')

                    <button type="submit"
                        onclick="return confirm('Yakin ingin menyetujui pembayaran ini?')"
                        class="w-full px-4 py-3 bg-green-600 hover:bg-green-700 text-white text-sm font-semibold rounded-lg transition">
                        Approve Payment
                    </button>
                </form>

                {{-- REJECT --}}
                <form action="{{ route('admin.qris.reject', $transaction->id) }}"
                    method="POST"
                    class="space-y-3">
                    @csrf
                    @method('PATCH')

                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-2">
                            Rejection Reason
                        </label>

                        <textarea name="rejection_note"
                            rows="3"
                            required
                            maxlength="1000"
                            class="w-full border border-gray-300 rounded-lg p-3 text-sm focus:ring-2 focus:ring-red-200 focus:border-red-400"
                            placeholder="Tuliskan alasan penolakan pembayaran...">{{ old('rejection_note') }}</textarea>

                        @error('rejection_note')
                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                        onclick="return confirm('Yakin ingin menolak pembayaran ini?')"
                        class="w-full px-4 py-3 bg-red-600 hover:bg-red-700 text-white text-sm font-semibold rounded-lg transition">
                        Reject Payment
                    </button>
                </form>
            </div>
        @endif

    </div>
</div>
@endsection
