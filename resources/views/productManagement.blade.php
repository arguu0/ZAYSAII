<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZAYSAII</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-800 p-3">
    <div class="mx-auto space-y-6 max-w-7xl">

        <!-- Top Header & Excel Import -->
        <div class="bg-white p-6 rounded-lg shadow-sm flex flex-col md:flex-row md:items-center justify-between gap-4">
            <h1 class="text-2xl font-bold"><a href="/">ZAYSAII</a></h1>

            <!-- Local Import Form -->
            <form action="/products/import" method="POST" enctype="multipart/form-data" class="flex items-center gap-2">
                @csrf
                <input type="file" name="file" accept=".xlsx, .xls, .csv" required class="text-sm file:mr-3 file:py-2 file:px-4 file:rounded-md file:border-0 file:bg-gray-200 file:text-gray-700 hover:file:bg-gray-300 cursor-pointer">
                <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded-md">
                    Import Excel
                </button>
            </form>
        </div>

        <!-- Search & Category Filter Bar -->
        <div  class="bg-white p-4 rounded-lg shadow-sm flex flex-col sm:flex-row gap-4">
            <!-- Search Bar -->
            <form action="/" method="GET" class="flex-1 flex flex-row items-center gap-2"> 
                <div class="relative flex-1">
                    <input 
                        type="text" 
                        name="search" 
                        value="{{ request('search') }}" 
                        placeholder="Search product name..." 
                        class="w-full border border-gray-300 rounded-md pl-3 pr-8 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >

                    <!-- Clear 'X' Button (Only renders if search query exists) -->
                    @if(request('search'))
                        <a 
                            href="{{ request()->fullUrlWithQuery(['search' => null]) }}" 
                            class="absolute right-2.5 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600 font-bold text-sm px-1"
                            title="Clear search"
                        >
                            ✕
                        </a>
                    @endif
                </div>
                <button type="submit" class="bg-zinc-900 text-white text-sm font-medium rounded-lg px-4 py-2 shrink-0">Search</button>
            </form>
            <form action="/" method="GET">
                <!-- Category Filter -->
                <div class="w-full sm:w-48 font-semibold">
                    <select name="category" onchange="this.form.submit()" class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 bg-white">
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

        <!-- Product Table -->
        <div class="bg-white rounded-lg shadow-sm overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead class="bg-gray-50 border-b border-gray-200 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-3">Name</th>
                        <th class="px-6 py-3">Category</th>
                        <th class="px-6 py-3">Quantity</th>
                        <th class="px-6 py-3">Price</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-lg">
                    @forelse ($products as $product)
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-bold text-gray-900">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-gray-600">
                                <span class="bg-gray-100 text-gray-700 px-2 py-1 rounded text-lg font-bold">
                                    {{ $product->category ?? 'General' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-gray-600 font-semibold">{{ $product->quantity }}</td>
                            <td class="px-6 py-4 text-green-600 font-bold">{{ $product->price }}</td>
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
                {{ $products->links() }}
            </div>
        </div>

    </div>
</body>
</html>