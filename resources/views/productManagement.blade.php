<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAYSAII – ကုန်ပစ္စည်း စီမံခန့်ခွဲမှု</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        /* Burmese font rendering */
        body { font-family: 'Segoe UI', 'Noto Sans Myanmar', 'Pyidaungsu', Arial, sans-serif; }
        .modal-backdrop { transition: opacity 0.15s ease; }
        /* Prevent iOS input zoom */
        input, select, textarea { font-size: 16px !important; }
        @media (min-width: 640px) { input, select, textarea { font-size: 0.875rem !important; } }
    </style>
</head>
<body class="bg-gray-100 text-gray-800 min-h-screen">

<div class="mx-auto space-y-3 max-w-7xl p-3 sm:p-5">

    {{-- ═══ Flash Notifications ══════════════════════════════════════════ --}}
    @if(session('success'))
        <div id="flash-success" class="flex items-center gap-3 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg shadow-sm">
            <svg class="w-5 h-5 shrink-0 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
            <span class="text-sm font-medium">{{ session('success') }}</span>
            <button onclick="document.getElementById('flash-success').remove()" class="ml-auto text-green-400 hover:text-green-600">✕</button>
        </div>
    @endif
    @if($errors->any())
        <div id="flash-error" class="flex items-start gap-3 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg shadow-sm">
            <svg class="w-5 h-5 shrink-0 text-red-500 mt-0.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
            <div class="flex-1">
                <p class="text-sm font-semibold mb-1">အမှားများ ရှိသည်—</p>
                <ul class="text-sm space-y-0.5 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            <button onclick="document.getElementById('flash-error').remove()" class="text-red-400 hover:text-red-600">✕</button>
        </div>
    @endif

    {{-- ═══ Header ════════════════════════════════════════════════════════ --}}
    <div class="bg-white px-4 py-3 sm:px-6 sm:py-4 rounded-lg shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-3">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold tracking-tight"><a href="/" class="hover:text-blue-600">ZAYSAII</a></h1>
            <p class="text-xs text-gray-400 mt-0.5">ကုန်ပစ္စည်း စီမံခန့်ခွဲမှု</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- Add Product Button --}}
            <button onclick="openAddModal()" class="flex items-center gap-1.5 bg-blue-600 hover:bg-blue-700 active:bg-blue-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                ကုန်ပစ္စည်း ထည့်မည်
            </button>

            {{-- Excel Import --}}
            <button onclick="document.getElementById('import-panel').classList.toggle('hidden')" class="flex items-center gap-1.5 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                Excel တင်မည်
            </button>

            {{-- Export CSV --}}
            <a href="/products/export" class="flex items-center gap-1.5 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4 4l4 4m0 0l4-4m-4 4V4"/></svg>
                CSV ထုတ်မည်
            </a>

            {{-- Clear All --}}
            <button onclick="document.getElementById('clear-all-dialog').classList.remove('hidden')" class="flex items-center gap-1.5 bg-red-600 hover:bg-red-700 active:bg-red-800 text-white text-sm font-medium px-4 py-2 rounded-lg transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                အားလုံးဖျက်
            </button>
        </div>
    </div>

    {{-- Excel Import Panel (collapsible) --}}
    <div id="import-panel" class="hidden bg-white px-4 py-3 sm:px-6 rounded-lg shadow-sm">
        <p class="text-sm font-semibold text-gray-600 mb-2">Excel / CSV ဖိုင် တင်သွင်းမည်</p>
        <form action="/products/import" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            @csrf
            <input type="file" name="file" accept=".xlsx,.xls,.csv" required
                class="flex-1 text-sm border border-gray-300 rounded-lg px-3 py-2 file:mr-3 file:py-1 file:px-3 file:rounded-md file:border-0 file:bg-gray-100 file:text-gray-700 hover:file:bg-gray-200 cursor-pointer">
            <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium px-5 py-2 rounded-lg whitespace-nowrap">
                တင်သွင်းမည်
            </button>
        </form>
        <p class="text-xs text-gray-400 mt-2">ကော်လံများ — name, category, quantity, buy_price, sell_price</p>
    </div>

    {{-- ═══ Search & Filter ════════════════════════════════════════════════ --}}
    <div class="bg-white px-4 py-3 sm:px-6 rounded-lg shadow-sm flex flex-col sm:flex-row gap-3">
        {{-- Search --}}
        <form action="/" method="GET" class="flex-1 flex items-center gap-2">
            <div class="relative flex-1">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="ကုန်ပစ္စည်းအမည် ရှာဖွေမည်..."
                    class="w-full border border-gray-300 rounded-lg pl-9 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                <svg class="absolute left-2.5 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0"/></svg>
                @if(request('search'))
                    <a href="{{ request()->fullUrlWithQuery(['search' => null]) }}"
                        class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 text-xs px-1">✕</a>
                @endif
            </div>
            <button type="submit" class="bg-gray-900 hover:bg-gray-700 text-white text-sm font-medium rounded-lg px-4 py-2 shrink-0">ရှာမည်</button>
        </form>

        {{-- Category Filter --}}
        <form action="/" method="GET" class="w-full sm:w-44">
            <select name="category" onchange="this.form.submit()"
                class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                <option value="All Categories">အမျိုးအစားအားလုံး</option>
                @foreach($categories as $category)
                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                        {{ $category }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>

    {{-- ═══ Product Table ════════════════════════════════════════════════= --}}
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">

        {{-- Desktop Table --}}
        <div class="hidden sm:block overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr class="text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        <th class="px-5 py-3">ကုန်ပစ္စည်းအမည်</th>
                        <th class="px-5 py-3">အမျိုးအစား</th>
                        <th class="px-5 py-3 text-center">အရေအတွက်</th>
                        <th class="px-5 py-3 text-right">ဝယ်ဈေး</th>
                        <th class="px-5 py-3 text-right">ရောင်းဈေး</th>
                        <th class="px-5 py-3 text-center">လုပ်ဆောင်ချက်</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-5 py-3 font-semibold text-gray-900 max-w-[200px] break-words">{{ $product->name }}</td>
                            <td class="px-5 py-3">
                                <span class="bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                    {{ $product->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center font-semibold {{ $product->quantity == 0 ? 'text-red-500' : 'text-gray-700' }}">
                                {{ number_format($product->quantity) }}
                            </td>
                            <td class="px-5 py-3 text-right text-orange-600 font-medium">
                                {{ number_format($product->buy_price) }}
                            </td>
                            <td class="px-5 py-3 text-right text-green-600 font-bold">
                                {{ number_format($product->sell_price) }}
                            </td>
                            <td class="px-5 py-3">
                                <div class="flex items-center justify-center gap-2">
                                    <button
                                        onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->category) }}, {{ $product->quantity }}, {{ $product->buy_price }}, {{ $product->sell_price }})"
                                        class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="ပြင်ဆင်မည်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </button>
                                    <button
                                        onclick="openDeleteDialog({{ $product->id }}, {{ json_encode($product->name) }})"
                                        class="p-1.5 text-red-500 hover:bg-red-50 rounded-lg transition-colors" title="ဖျက်မည်">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                                <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                ကုန်ပစ္စည်း မတွေ့ပါ။
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Mobile Cards --}}
        <div class="sm:hidden divide-y divide-gray-100">
            @forelse ($products as $product)
                <div class="p-4 hover:bg-gray-50">
                    <div class="flex items-start justify-between gap-2 mb-2">
                        <div class="flex-1 min-w-0">
                            <p class="font-semibold text-gray-900 break-words leading-snug">{{ $product->name }}</p>
                            <span class="inline-block mt-1 bg-blue-50 text-blue-700 px-2 py-0.5 rounded-full text-xs font-medium">
                                {{ $product->category ?? 'General' }}
                            </span>
                        </div>
                        <div class="flex gap-1 shrink-0">
                            <button
                                onclick="openEditModal({{ $product->id }}, {{ json_encode($product->name) }}, {{ json_encode($product->category) }}, {{ $product->quantity }}, {{ $product->buy_price }}, {{ $product->sell_price }})"
                                class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg active:bg-blue-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                            </button>
                            <button
                                onclick="openDeleteDialog({{ $product->id }}, {{ json_encode($product->name) }})"
                                class="p-2 text-red-500 hover:bg-red-50 rounded-lg active:bg-red-100">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="grid grid-cols-3 gap-2 mt-2">
                        <div class="bg-gray-50 rounded-lg px-3 py-2 text-center">
                            <p class="text-xs text-gray-400">အရေအတွက်</p>
                            <p class="font-bold text-sm {{ $product->quantity == 0 ? 'text-red-500' : 'text-gray-800' }}">{{ number_format($product->quantity) }}</p>
                        </div>
                        <div class="bg-orange-50 rounded-lg px-3 py-2 text-center">
                            <p class="text-xs text-orange-400">ဝယ်ဈေး</p>
                            <p class="font-bold text-sm text-orange-600">{{ number_format($product->buy_price) }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg px-3 py-2 text-center">
                            <p class="text-xs text-green-400">ရောင်းဈေး</p>
                            <p class="font-bold text-sm text-green-600">{{ number_format($product->sell_price) }}</p>
                        </div>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400">
                    <svg class="w-10 h-10 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    ကုန်ပစ္စည်း မတွေ့ပါ။
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 border-t border-gray-100">
            {{ $products->withQueryString()->links() }}
        </div>
    </div>

</div>{{-- end container --}}


{{-- ═══════════════════════════════════════════════════════════════════════════
     ADD PRODUCT MODAL
════════════════════════════════════════════════════════════════════════════ --}}
<div id="add-modal" class="fixed inset-0 z-50 hidden">
    {{-- Backdrop --}}
    <div class="absolute inset-0 bg-black/40 modal-backdrop" onclick="closeAddModal()"></div>
    {{-- Panel --}}
    <div class="absolute inset-x-0 bottom-0 sm:inset-0 sm:flex sm:items-center sm:justify-center sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-md shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">ကုန်ပစ္စည်း အသစ်ထည့်မည်</h2>
                <button onclick="closeAddModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form action="/products" method="POST" class="px-5 py-4 space-y-4">
                @csrf
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">ကုန်ပစ္စည်းအမည် <span class="text-red-500">*</span></label>
                    <input type="text" name="name" required placeholder="ကုန်ပစ္စည်းအမည် ထည့်ပါ"
                        value="{{ old('name') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('name') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">အမျိုးအစား <span class="text-red-500">*</span></label>
                    <input type="text" name="category" required placeholder="ဥပမာ — ဆေးဝါး၊ အစားအသောက်"
                        value="{{ old('category') }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('category') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">အရေအတွက် <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" required min="0" step="1" placeholder="0"
                        value="{{ old('quantity', 0) }}"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    @error('quantity') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">ဝယ်ဈေး <span class="text-red-500">*</span></label>
                        <input type="number" name="buy_price" required min="0" step="any" placeholder="0"
                            value="{{ old('buy_price', 0) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                        @error('buy_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">ရောင်းဈေး <span class="text-red-500">*</span></label>
                        <input type="number" name="sell_price" required min="0" step="any" placeholder="0"
                            value="{{ old('sell_price', 0) }}"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                        @error('sell_price') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeAddModal()" class="flex-1 border border-gray-300 text-gray-700 font-medium py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        မသိမ်းဘဲ ပိတ်မည်
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl transition-colors">
                        သိမ်းဆည်းမည်
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     EDIT PRODUCT MODAL
════════════════════════════════════════════════════════════════════════════ --}}
<div id="edit-modal" class="fixed inset-0 z-50 hidden">
    <div class="absolute inset-0 bg-black/40 modal-backdrop" onclick="closeEditModal()"></div>
    <div class="absolute inset-x-0 bottom-0 sm:inset-0 sm:flex sm:items-center sm:justify-center sm:p-4">
        <div class="bg-white rounded-t-2xl sm:rounded-2xl w-full sm:max-w-md shadow-xl max-h-[90vh] overflow-y-auto">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-bold text-gray-800">ကုန်ပစ္စည်း ပြင်ဆင်မည်</h2>
                <button onclick="closeEditModal()" class="p-1 text-gray-400 hover:text-gray-600 rounded-lg hover:bg-gray-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <form id="edit-form" action="" method="POST" class="px-5 py-4 space-y-4">
                @csrf
                @method('PUT')
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">ကုန်ပစ္စည်းအမည် <span class="text-red-500">*</span></label>
                    <input type="text" name="name" id="edit-name" required placeholder="ကုန်ပစ္စည်းအမည် ထည့်ပါ"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">အမျိုးအစား <span class="text-red-500">*</span></label>
                    <input type="text" name="category" id="edit-category" required placeholder="ဥပမာ — ဆေးဝါး၊ အစားအသောက်"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="space-y-1">
                    <label class="block text-sm font-medium text-gray-700">အရေအတွက် <span class="text-red-500">*</span></label>
                    <input type="number" name="quantity" id="edit-quantity" required min="0" step="1" placeholder="0"
                        class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">ဝယ်ဈေး <span class="text-red-500">*</span></label>
                        <input type="number" name="buy_price" id="edit-buy-price" required min="0" step="any" placeholder="0"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-orange-400 focus:border-transparent">
                    </div>
                    <div class="space-y-1">
                        <label class="block text-sm font-medium text-gray-700">ရောင်းဈေး <span class="text-red-500">*</span></label>
                        <input type="number" name="sell_price" id="edit-sell-price" required min="0" step="any" placeholder="0"
                            class="w-full border border-gray-300 rounded-xl px-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent">
                    </div>
                </div>
                <div class="flex gap-3 pt-2">
                    <button type="button" onclick="closeEditModal()" class="flex-1 border border-gray-300 text-gray-700 font-medium py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                        မသိမ်းဘဲ ပိတ်မည်
                    </button>
                    <button type="submit" class="flex-1 bg-blue-600 hover:bg-blue-700 text-white font-medium py-2.5 rounded-xl transition-colors">
                        ပြင်ဆင်သိမ်းမည်
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     DELETE SINGLE CONFIRMATION DIALOG
════════════════════════════════════════════════════════════════════════════ --}}
<div id="delete-dialog" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="closeDeleteDialog()"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-center font-bold text-gray-800 mb-1">ဖျက်ရန် သေချာပါသလား?</h3>
        <p class="text-center text-sm text-gray-500 mb-5" id="delete-product-name-display"></p>
        <form id="delete-form" action="" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="closeDeleteDialog()" class="flex-1 border border-gray-300 text-gray-700 font-medium py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                    မဖျက်ပါ
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 rounded-xl transition-colors">
                    ဖျက်မည်
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     CLEAR ALL CONFIRMATION DIALOG
════════════════════════════════════════════════════════════════════════════ --}}
<div id="clear-all-dialog" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/40" onclick="document.getElementById('clear-all-dialog').classList.add('hidden')"></div>
    <div class="relative bg-white rounded-2xl shadow-xl w-full max-w-sm p-6">
        <div class="flex items-center justify-center w-12 h-12 bg-red-100 rounded-full mx-auto mb-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h3 class="text-center font-bold text-gray-800 mb-1">ကုန်ပစ္စည်းအားလုံး ဖျက်မည်လား?</h3>
        <p class="text-center text-sm text-gray-500 mb-5">ဤလုပ်ဆောင်ချက်ကို ပြန်မလုပ်နိုင်ပါ။ ဒေတာအားလုံး ပျောက်ဆုံးသွားမည်။</p>
        <form action="/products/delete" method="POST">
            @csrf
            @method('DELETE')
            <div class="flex gap-3">
                <button type="button" onclick="document.getElementById('clear-all-dialog').classList.add('hidden')"
                    class="flex-1 border border-gray-300 text-gray-700 font-medium py-2.5 rounded-xl hover:bg-gray-50 transition-colors">
                    မဖျက်ပါ
                </button>
                <button type="submit" class="flex-1 bg-red-600 hover:bg-red-700 text-white font-medium py-2.5 rounded-xl transition-colors">
                    အားလုံးဖျက်မည်
                </button>
            </div>
        </form>
    </div>
</div>


{{-- ═══════════════════════════════════════════════════════════════════════════
     ADD FORM INLINE FIELDS (included directly — no partial needed)
     Re-open modal if validation failed
════════════════════════════════════════════════════════════════════════════ --}}

{{-- Hidden add modal content is embedded above; validation re-open below --}}

<script>
    // ── Add Modal ─────────────────────────────────────────────────────────────
    function openAddModal() {
        document.getElementById('add-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeAddModal() {
        document.getElementById('add-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Edit Modal ────────────────────────────────────────────────────────────
    function openEditModal(id, name, category, quantity, buyPrice, sellPrice) {
        document.getElementById('edit-form').action = '/products/' + id;
        document.getElementById('edit-name').value      = name;
        document.getElementById('edit-category').value  = category || '';
        document.getElementById('edit-quantity').value  = quantity;
        document.getElementById('edit-buy-price').value  = buyPrice;
        document.getElementById('edit-sell-price').value = sellPrice;
        document.getElementById('edit-modal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeEditModal() {
        document.getElementById('edit-modal').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Delete Dialog ─────────────────────────────────────────────────────────
    function openDeleteDialog(id, name) {
        document.getElementById('delete-form').action = '/products/' + id;
        document.getElementById('delete-product-name-display').textContent =
            '"' + name + '" ကို ဖျက်မည်နေကြောင်း သေချာပါသလား?';
        document.getElementById('delete-dialog').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
    function closeDeleteDialog() {
        document.getElementById('delete-dialog').classList.add('hidden');
        document.body.style.overflow = '';
    }

    // ── Close modals on Escape ────────────────────────────────────────────────
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeAddModal();
            closeEditModal();
            closeDeleteDialog();
            document.getElementById('clear-all-dialog').classList.add('hidden');
            document.body.style.overflow = '';
        }
    });

    // ── Auto-dismiss flash after 5s ───────────────────────────────────────────
    setTimeout(function() {
        var el = document.getElementById('flash-success');
        if (el) el.style.opacity = '0', el.style.transition = 'opacity 0.5s', setTimeout(() => el.remove(), 500);
    }, 5000);

    // ── Re-open Add modal if validation failed on POST /products ─────────────
    @if($errors->any() && old('_method') !== 'PUT')
        document.addEventListener('DOMContentLoaded', function() { openAddModal(); });
    @endif
</script>

</body>
</html>
