<x-app-layout>
    <div class="pt-32 pb-12 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- HEADER: Order Number & Actions --}}
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <div class="flex items-center gap-3">
                                                                @if ($order->status === 'approved' && empty($item->reviewItem) && Auth::user()->role === 'user')
                                                                    <div class="mt-4 bg-white border border-gray-200 rounded-lg p-4">
                                                                        <h5 class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-2">Review this product</h5>
                                                                        <form action="{{ route('review.store') }}" method="POST" class="space-y-2">
                                                                            @csrf
                                                                            <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                                                                            <div class="flex items-center gap-3">
                                                                                <label class="text-xs text-gray-600">Rating</label>
                                                                                <select name="rating" required class="border border-gray-300 rounded px-2 py-1 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                                                    <option value="">Select</option>
                                                                                    @for($r=1;$r<=5;$r++)
                                                                                        <option value="{{ $r }}">{{ $r }} ⭐</option>
                                                                                    @endfor
                                                                                </select>
                                                                            </div>
                                                                            <div>
                                                                                <textarea name="comment" rows="2" placeholder="Optional comment" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"></textarea>
                                                                            </div>
                                                                            <button type="submit" class="inline-flex items-center gap-2 px-3 py-1.5 text-xs font-bold uppercase tracking-widest bg-gray-900 text-white rounded hover:bg-gray-800 transition">
                                                                                Submit Review
                                                                            </button>
                                                                        </form>
                                                                    </div>
                                                                @elseif(!empty($item->reviewItem))
                                                                    <div class="mt-4 bg-gray-50 border border-gray-200 rounded-lg p-3">
                                                                        <p class="text-xs text-gray-500 uppercase font-bold tracking-wider">Your Review</p>
                                                                        <p class="text-sm text-gray-800 font-semibold">⭐ {{ $item->reviewItem->rating }}/5</p>
                                                                        @if($item->reviewItem->comment)
                                                                            <p class="text-sm text-gray-600 italic">"{{ $item->reviewItem->comment }}"</p>
                                                                        @endif
                                                                        <p class="text-xs text-gray-400">{{ $item->reviewItem->created_at->format('d M Y') }}</p>
                                                                    </div>
                                                                @endif
                        <a href="{{ route('orders.index') }}" class="text-gray-400 hover:text-gray-600 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                            </svg>
                        </a>
                        <h1 class="font-serif text-3xl font-bold text-gray-900 uppercase tracking-wide">
                            Order #{{ $order->order_number }}
                        </h1>
                    </div>
                    <p class="text-gray-500 text-sm mt-1 ml-9">Created on
                        {{ $order->created_at?->format('d M Y, H:i') ?? 'Date not available'}}
                    </p>
                </div>

                <div class="flex items-center gap-3">
                    <span
                        class="px-4 py-2 rounded-full text-sm font-bold uppercase tracking-wider
                        {{ $order->status === 'approved'
                            ? 'bg-green-100 text-green-700'
                            : ($order->status === 'rejected'
                                ? 'bg-red-100 text-red-700'
                                : 'bg-yellow-100 text-yellow-700') }}">
                        {{ $order->status }}
                    </span>

                    @if ($order->status === 'pending' && Auth::user()->role === 'admin')
                        <div class="flex gap-2">
                            <form action="{{ route('orders.approve', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="bg-black text-white px-4 py-2 rounded-lg text-sm font-bold hover:bg-gray-800 transition">
                                    Approve
                                </button>
                            </form>
                            <form action="{{ route('orders.reject', $order->id) }}" method="POST">
                                @csrf @method('PATCH')
                                <button type="submit"
                                    class="bg-white border border-gray-300 text-red-600 px-4 py-2 rounded-lg text-sm font-bold hover:bg-red-50 transition">
                                    Reject
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

                {{-- LEFT COLUMN: Items Table & Notes --}}
                <div class="lg:col-span-2">
                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                        <div class="p-6 border-b border-gray-100 flex justify-between items-center">
                            <h3 class="font-serif text-lg font-bold text-gray-900">Order Items</h3>

                            @if ($order->status === 'pending')
                                <span class="text-xs font-bold text-indigo-600 bg-indigo-50 px-2 py-1 rounded">
                                    Editing Enabled
                                </span>
                            @else
                                <span
                                    class="text-xs font-bold text-gray-500 bg-gray-100 px-2 py-1 rounded flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z">
                                        </path>
                                    </svg>
                                    Price Locked
                                </span>
                            @endif
                        </div>

                        <form action="{{ route('orders.updatePrice', $order->id) }}" method="POST">
                            @csrf
                            @method('PATCH')

                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50 text-xs uppercase text-gray-500 font-bold">
                                        <tr>
                                            <th class="px-6 py-4">Item Details</th>
                                            <th class="px-6 py-4 text-center">Qty (m)</th>
                                            <th class="px-6 py-4 text-center">Duration</th>
                                            <th class="px-6 py-4 text-right">Price / Meter</th>
                                            <th class="px-6 py-4 text-right">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-100">
                                        @foreach ($order->items as $item)
                                            <tr class="hover:bg-gray-50 transition">
                                                {{-- KOLOM 1: Detail Item (Fabric + Color + Venue) --}}
                                                <td class="px-6 py-4">
                                                    <div class="flex items-start gap-4">

                                                        {{-- 1. ICON KAIN UTAMA --}}
                                                        <div
                                                            class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center flex-shrink-0 border border-gray-200 shadow-sm">
                                                            <svg class="w-6 h-6 text-gray-400" fill="currentColor"
                                                                viewBox="0 0 20 20">
                                                                <path d="M4 3a2 2 0 100 4h12a2 2 0 100-4H4z" />
                                                                <path fill-rule="evenodd"
                                                                    d="M3 8h14v7a2 2 0 01-2 2H5a2 2 0 01-2-2V8zm5 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"
                                                                    clip-rule="evenodd" />
                                                            </svg>
                                                        </div>

                                                        <div class="flex-1 min-w-0">
                                                            {{-- JUDUL KAIN --}}
                                                            <div class="mb-3">
                                                                <p class="font-bold text-gray-900 text-sm truncate">
                                                                    {{ $item->fabric->name }}
                                                                </p>
                                                                <p class="text-xs text-gray-500">
                                                                    {{ $item->fabric->category->name ?? 'Fabric' }}
                                                                </p>
                                                            </div>

                                                            {{-- 2. BADGE LOKASI DENGAN STRIP WARNA --}}
                                                            {{-- Container flex-wrap agar kalau ada banyak item/badge, dia turun ke bawah rapi --}}
                                                            <div class="flex flex-wrap gap-2">

                                                                {{-- KARTU TIKET (Unified Badge) --}}
                                                                <div
                                                                    class="group flex bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden max-w-[220px] hover:shadow-md transition-shadow">

                                                                    {{-- STRIP WARNA (Kiri) - Menunjukkan "Masing-masing Color" --}}
                                                                    <div class="w-2 self-stretch flex-shrink-0"
                                                                        style="background-color: {{ $item->colors ?? '#cbd5e1' }};"
                                                                        title="Color: {{ $item->colors }}">
                                                                    </div>

                                                                    {{-- INFORMASI VENUE (Kanan) --}}
                                                                    <div
                                                                        class="px-3 py-2 flex flex-col justify-center min-w-0">
                                                                        {{-- Nama Hotel (Kecil, Uppercase) --}}
                                                                        <div class="flex items-center gap-1.5 mb-0.5">
                                                                            <svg class="w-3 h-3 text-gray-400 flex-shrink-0"
                                                                                fill="none" stroke="currentColor"
                                                                                viewBox="0 0 24 24">
                                                                                <path stroke-linecap="round"
                                                                                    stroke-linejoin="round"
                                                                                    stroke-width="2"
                                                                                    d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                                                                                </path>
                                                                            </svg>
                                                                            <span
                                                                                class="text-[10px] font-bold text-gray-500 uppercase tracking-wider truncate">
                                                                                {{ $item->room->area->venue->name ?? 'Unknown Venue' }}
                                                                            </span>
                                                                        </div>

                                                                        {{-- Nama Ruangan (Besar, Bold) --}}
                                                                        <span
                                                                            class="text-xs font-bold text-gray-800 truncate leading-tight">
                                                                            {{ $item->room->name ?? 'Unknown Room' }}
                                                                        </span>

                                                                        {{-- Nama Warna (Opsional, agar jelas) --}}
                                                                        <span
                                                                            class="text-[10px] text-gray-400 mt-0.5 truncate">
                                                                            Color: {{ $item->colors ?? 'N/A' }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </td>

                                                <td class="px-6 py-4 text-center text-sm font-medium">
                                                    {{ $item->quantity }}
                                                </td>
                                                <td class="px-6 py-4 text-center text-sm text-gray-500">
                                                    {{ $order->total_days }} Days
                                                </td>
                                                <td class="px-6 py-4 text-right">
                                                    @if ($order->status === 'pending' && Auth::user()->role === 'admin')
                                                        <div class="flex items-center justify-end gap-2">
                                                            <span class="text-gray-400 text-xs">Rp</span>
                                                            <input type="number"
                                                                name="items[{{ $item->id }}][price_per_meter]"
                                                                value="{{ $item->price_per_meter }}"
                                                                class="w-24 text-right text-sm border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring-indigo-500 py-1"
                                                                min="0">
                                                        </div>
                                                    @else
                                                        <span class="font-medium text-gray-900">
                                                            Rp {{ number_format($item->price_per_meter, 0, ',', '.') }}
                                                        </span>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 text-right font-bold text-gray-900">
                                                    Rp {{ number_format($item->subtotal, 0, ',', '.') }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            @if ($order->status === 'pending' && Auth::user()->role === 'admin')
                                <div
                                    class="bg-gray-50 px-6 py-4 flex justify-between items-center border-t border-gray-100">
                                    <p class="text-xs text-gray-500 italic">Changing price will auto-recalculate the
                                        Grand Total.</p>
                                    <button type="submit"
                                        class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-bold hover:bg-indigo-700 shadow-md transition">
                                        Save Price Changes
                                    </button>
                                </div>
                            @endif
                        </form>
                    </div>

                    <div class="mt-6 bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h4 class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Customer Notes</h4>
                        <p class="text-gray-600 italic text-sm">
                            "{{ $order->note ?? 'No notes provided by customer.' }}"
                        </p>
                    </div>
                </div>

                {{-- RIGHT COLUMN: Customer & Summary --}}
                <div class="lg:col-span-1 space-y-6">

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Customer Details</h3>
                        <div class="flex items-center gap-4 mb-4">
                            <div
                                class="w-12 h-12 bg-black text-white rounded-full flex items-center justify-center font-bold text-xl">
                                {{ substr($order->user->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="font-bold text-gray-900">{{ $order->user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $order->user->email }}</p>
                            </div>
                        </div>
                        <div class="border-t border-gray-100 pt-4 space-y-2">
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Phone (WA)</span>
                                <span class="font-medium text-gray-900">{{ $order->user->phone ?? '-' }}</span>
                            </div>
                            <div class="flex justify-between text-sm">
                                <span class="text-gray-500">Member Since</span>
                                <span
                                    class="font-medium text-gray-900">{{ $order->user->created_at->format('M Y') }}</span>
                            </div>
                        </div>

                        @if ($order->user->phone)
                            <a href="https://wa.me/{{ preg_replace('/^0/', '62', $order->user->phone) }}"
                                target="_blank"
                                class="mt-6 block w-full text-center bg-green-50 text-green-700 border border-green-200 py-2 rounded-lg font-bold text-sm hover:bg-green-100 transition">
                                Chat Customer
                            </a>
                        @endif
                    </div>

                    <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6">
                        <h3 class="font-serif text-lg font-bold text-gray-900 mb-4">Rental Summary</h3>

                        <div class="space-y-3 text-sm border-b border-gray-100 pb-4 mb-4">
                            <div class="flex justify-between">
                                <span class="text-gray-500">Start Date</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->start_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">End Date</span>
                                <span
                                    class="font-medium text-gray-900">{{ \Carbon\Carbon::parse($order->end_date)->format('d M Y') }}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Duration</span>
                                <span class="font-medium text-gray-900">{{ $order->total_days }} Days</span>
                            </div>
                        </div>

                        <div class="flex justify-between items-end">
                            <span class="font-serif font-bold text-gray-700">Grand Total</s$pan>
                            <span class="font-bold text-2xl text-indigo-600">
                                Rp {{ number_format($order->total_price, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</x-app-layout>
