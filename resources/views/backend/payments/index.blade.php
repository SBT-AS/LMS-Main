@extends('backend.layouts.master')

@section('content')
<div class="space-y-6 animate-in fade-in duration-500">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 tracking-tight">Payment History</h1>
            <p class="text-sm text-gray-500 mt-1">Track all transactions and financial records</p>
        </div>
        <div class="flex gap-3">
             <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-100 text-sm font-medium text-gray-600">
                <i class="bi bi-wallet2 mr-2 text-indigo-500"></i> Total: <span class="text-gray-900 font-bold">₹{{ number_format($payments->sum('amount'), 2) }}</span>
            </div>
        </div>
    </div>

    <!-- Payments Table -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gray-50/50 border-b border-gray-100">
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">ID</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">User Details</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Transaction Info</th>
                        <th class="px-8 py-5 text-left text-xs font-bold text-gray-400 uppercase tracking-widest">Amount</th>
                        <th class="px-8 py-5 text-center text-xs font-bold text-gray-400 uppercase tracking-widest">Status</th>
                        <th class="px-8 py-5 text-right text-xs font-bold text-gray-400 uppercase tracking-widest">Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse ($payments as $payment)
                    <tr class="hover:bg-gray-50/50 transition-colors group">
                        <td class="px-8 py-5 text-sm font-medium text-gray-500">
                            #{{ $payment->id }}
                        </td>
                        <td class="px-8 py-5">
                            <div class="flex items-center">
                                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-violet-600 text-white flex items-center justify-center text-sm font-bold shadow-sm group-hover:scale-110 transition-transform overflow-hidden">
                                    @if($payment->user->profile_photo_path)
                                        <img src="{{ Storage::url($payment->user->profile_photo_path) }}" alt="" class="h-full w-full object-cover">
                                    @else
                                        {{ substr($payment->user->name ?? 'U', 0, 1) }}
                                    @endif
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-bold text-gray-900">{{ $payment->user->name ?? 'Unknown User' }}</div>
                                    <div class="text-xs text-gray-400">{{ $payment->user->email ?? 'N/A' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <div class="space-y-1">
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="w-16 font-medium text-gray-400">Payment:</span>
                                    <span class="font-mono text-gray-700 bg-gray-50 px-1.5 py-0.5 rounded">{{ Str::limit($payment->rwp_payment_id, 14) }}</span>
                                </div>
                                <div class="flex items-center text-xs text-gray-500">
                                    <span class="w-16 font-medium text-gray-400">Order:</span>
                                    <span class="font-mono text-gray-700 bg-gray-50 px-1.5 py-0.5 rounded">{{ Str::limit($payment->rwp_order_id, 14) }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-8 py-5">
                            <span class="text-sm font-extrabold text-gray-900">₹{{ number_format($payment->amount, 2) }}</span>
                        </td>
                        <td class="px-8 py-5 text-center">
                            @if($payment->status === 'success')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-green-100 text-green-600 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Success
                                </span>
                            @elseif($payment->status === 'failed')
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-red-100 text-red-600 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Failed
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase bg-gray-100 text-gray-600 inline-flex items-center gap-1.5">
                                    <span class="w-1.5 h-1.5 rounded-full bg-gray-500"></span> {{ ucfirst($payment->status) }}
                                </span>
                            @endif
                        </td>
                        <td class="px-8 py-5 text-right">
                            <div class="text-sm font-medium text-gray-900">{{ $payment->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-400">{{ $payment->created_at->format('h:i A') }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-8 py-16 text-center">
                            <div class="flex flex-col items-center justify-center">
                                <div class="h-16 w-16 bg-gray-50 rounded-full flex items-center justify-center mb-4">
                                    <i class="bi bi-wallet2 text-2xl text-gray-300"></i>
                                </div>
                                <h3 class="text-lg font-medium text-gray-900">No payments found</h3>
                                <p class="text-gray-500 mt-1">Transactions will appear here once users make payments.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($payments->hasPages())
        <div class="px-8 py-4 border-t border-gray-50 bg-gray-50/30">
            {{ $payments->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
