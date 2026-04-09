<x-admin-layout title="Dashboard Overview">
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-800 leading-tight tracking-tight">
            {{ __('Dashboard Overview') }}
        </h2>

    </x-slot>

    <!-- Metrics Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8 mt-4">

        <!-- Metric Card 1 -->
        <div
            class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:bg-indigo-100 transition-colors">
            </div>
            <h3 class="text-gray-500 text-sm font-semibold tracking-wide uppercase z-10">Total Institutes</h3>
            <p class="text-4xl font-black text-gray-900 mt-2 z-10">{{ $totalInstitutes }}</p>
            <div class="mt-4 flex items-center text-sm font-medium text-indigo-600 z-10">
                <span>View all</span>
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </div>
        </div>

        <!-- Metric Card 2 -->
        <div
            class="p-6 bg-gradient-to-br from-indigo-600 to-purple-600 rounded-2xl shadow-md border border-indigo-700 flex flex-col justify-between relative overflow-hidden group">
            <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
            <h3 class="text-indigo-100 text-sm font-semibold tracking-wide uppercase z-10">Active Subscriptions</h3>
            <p class="text-4xl font-black text-white mt-2 z-10">{{ $activeSubscriptions }}</p>
            <div class="mt-4 flex items-center text-sm font-medium text-indigo-200 z-10">
                <span class="bg-indigo-500/30 px-2 py-0.5 rounded text-xs">Standard SaaS</span>
            </div>
        </div>

        <!-- Metric Card 3 -->
        <div
            class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:bg-green-100 transition-colors">
            </div>
            <h3 class="text-gray-500 text-sm font-semibold tracking-wide uppercase z-10">Total Revenue</h3>
            <p class="text-4xl font-black text-green-600 mt-2 z-10">${{ number_format($totalRevenue, 2) }}</p>
            <div class="mt-4 flex items-center text-sm font-medium text-green-600 z-10">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                </svg>
                <span>Increased</span>
            </div>
        </div>

        <!-- Metric Card 4 -->
        <div
            class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all">
            <div
                class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:bg-red-100 transition-colors">
            </div>
            <h3 class="text-gray-500 text-sm font-semibold tracking-wide uppercase z-10">Expired Accounts</h3>
            <p class="text-4xl font-black text-red-500 mt-2 z-10">{{ $expiredSubscriptions }}</p>
            <div class="mt-4 flex items-center text-sm font-medium text-red-500 z-10">
                <span>Requires attention</span>
            </div>
        </div>

    </div>

    <!-- Content Section -->
    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="p-8">
            <h3 class="font-bold text-xl text-gray-900 mb-2">Quick Actions</h3>
            <p class="text-gray-500 mb-6 text-sm">Jump directly into managing your application configuration and
                platform clients.</p>

            <div class="flex flex-wrap gap-4">
                <a href="{{ route('institutes.index') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-medium rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-lg shadow-indigo-600/30 transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4">
                        </path>
                    </svg>
                    Manage Institutes
                </a>
                <a href="{{ route('institutes.create') }}"
                    class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-sm font-medium rounded-xl text-gray-700 bg-white hover:bg-gray-50 hover:text-gray-900 hover:border-gray-300 shadow-sm transition duration-150 ease-in-out focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="w-5 h-5 mr-2 -ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New Institute
                </a>
            </div>
        </div>
    </div>
</x-admin-layout>