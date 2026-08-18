<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAYSAII</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-3 sm:p-6">
    <div class="mx-auto space-y-4 sm:space-y-6 max-w-7xl">

        <!-- Top Header & Excel Import -->
        <div class="bg-white p-4 sm:p-6 rounded-lg shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-2xl font-bold"><a href="/">ZAYSAII</a></h1>

            <!-- Form Actions Wrapper -->
            <div class="flex flex-col sm:flex-row sm:items-center gap-3 w-full md:w-auto">
                <!-- Local Import Form -->
                <form action="/products/import" method="POST" enctype="multipart/form-data" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 w-full sm:w-auto">
                    @csrf
                    <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="text-xs sm:text-sm w-full sm:w-auto file:mr-2 file:py-1.5 file:px-3 file:rounded-md file:border-0 file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-xs sm:text-sm font-medium px-4 py-2 rounded-md whitespace-nowrap text-center">
                        Import Excel
                    </button>
                </form>

                <!-- Delete Form -->
                <form action="/products/delete" method="POST" class="w-full sm:w-auto">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white text-xs sm:text-sm font-medium px-4 py-2 rounded-md whitespace-nowrap w-full sm:w-auto text-center">
                        Clear All
                    </button>
                </form>
            </div>
        </div>

        <!-- Search & Category Filter Bar -->
        <div class="bg-white p-4 rounded-lg shadow-sm flex flex-col sm:flex-row gap-3">
            <!-- Search Bar -->
            <form action="/" method="GET" class="flex-1 flex flex-row items-center gap-2"> 
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search product name..." 
                        class="w-full border border-gray-300 rounded-md pl-3 pr-8 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <!-- Clear 'X' Button -->
                    @if(request('search'))
                        <a 
                            href="{{ request()->fullUrlWithQuery(['search' => null]) }}" 
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 font-bold text-xs sm:text-sm px-1"
                            title="Clear search"
                        >
                            ✕
                        </a>
                    @endif
                </div>
                <button type="submit" class="bg-zinc-900 text-white text-xs sm:text-sm font-medium rounded-lg px-4 py-2 shrink-0">Search</button>
            </form>

            <!-- Category Filter -->
            <form action="/" method="GET" class="w-full sm:w-48">
                <div class="w-full font-semibold">
                    <select name="category" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
                        <option value="All Categories">All Categories</option>
                        @foreach($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>

        <!-- Product Table (Mobile-Optimized Layout) -->
        <div class="bg-white rounded-lg shadow-sm overflow-x-auto">
            <table class="w-full text-left border-collapse table-auto">
                <thead class="bg-gray-50 border-b border-gray-200 text-[10px] sm:text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-2 py-2 sm:px-6 sm:py-3">Name</th>
                        <th class="px-2 py-2 sm:px-6 sm:py-3 whitespace-nowrap">Category</th>
                        <th class="px-2 py-2 sm:px-6 sm:py-3 whitespace-nowrap text-center">Qty</th>
                        <th class="px-2 py-2 sm:px-6 sm:py-3 whitespace-nowrap text-right">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-xs sm:text-sm">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <!-- Long names wrap naturally without forcing horizontal scroll -->
                            <td class="px-2 py-2 sm:px-6 sm:py-4 font-bold text-gray-900 break-words max-w-[120px] sm:max-w-none">
                                {{ $product->name }}
                            </td>
                            <td class="px-2 py-2 sm:px-6 sm:py-4 text-gray-600 whitespace-nowrap">
                                <span class="bg-gray-100 text-gray-700 px-1.5 py-0.5 rounded text-[10px] sm:text-xs font-bold inline-block">
                                    {{ $product->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-2 py-2 sm:px-6 sm:py-4 text-gray-600 font-semibold text-center whitespace-nowrap">
                                {{ $product->quantity }}
                            </td>
                            <td class="px-2 py-2 sm:px-6 sm:py-4 text-green-600 font-bold text-right whitespace-nowrap">
                                {{ $product->price }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <!-- Built-in Laravel Pagination -->
            <div class="p-4 border-t border-gray-200">
                {{ $products->withQueryString()->links() }}
            </div>
        </div>

    </div>
</body>
</html>