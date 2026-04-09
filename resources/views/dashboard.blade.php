<x-admin-layout title="Dashboard Overview">

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header Section -->
            <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8 text-left">
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 leading-tight tracking-tight">Dashboard Overview</h2>
                    <p class="text-sm font-medium text-gray-500 mt-1 uppercase tracking-wider">System-wide performance metrics and Quick Actions</p>
                </div>
            </div>

            <!-- Metrics Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">

                <!-- Total Institutes -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-indigo-50 rounded-full opacity-50 group-hover:bg-indigo-100 transition-colors"></div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Total Institutes</h3>
                    <p class="text-4xl font-bold text-gray-900 mt-2 z-10">{{ $totalInstitutes }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-indigo-600 z-10">
                        <span>View Portfolio</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"></path></svg>
                    </div>
                </div>

                <!-- Active Subscriptions -->
                <div class="p-6 bg-gradient-to-br from-indigo-600 to-indigo-700 rounded-2xl shadow-lg border border-indigo-500 flex flex-col justify-between relative overflow-hidden group hover:scale-[1.02] transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-white/10 rounded-full"></div>
                    <h3 class="text-indigo-100 text-[10px] font-semibold tracking-widest uppercase z-10">Active Subscriptions</h3>
                    <p class="text-4xl font-bold text-white mt-2 z-10">{{ $activeSubscriptions }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-indigo-200 z-10">
                        <span class="bg-indigo-500/30 px-2 py-0.5 rounded text-[10px] uppercase">Standard SaaS</span>
                    </div>
                </div>

                <!-- Total Revenue -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-green-50 rounded-full opacity-50 group-hover:bg-green-100 transition-colors"></div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Total Revenue</h3>
                    <p class="text-4xl font-bold text-emerald-600 mt-2 z-10">₹{{ number_format($totalRevenue, 0) }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-emerald-600 z-10">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        <span>Growth Active</span>
                    </div>
                </div>

                <!-- Expired Accounts -->
                <div class="p-6 bg-white rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between relative overflow-hidden group hover:shadow-md transition-all duration-300">
                    <div class="absolute -right-6 -top-6 w-24 h-24 bg-red-50 rounded-full opacity-50 group-hover:bg-red-100 transition-colors"></div>
                    <h3 class="text-gray-500 text-[10px] font-bold tracking-widest uppercase z-10">Expired Clients</h3>
                    <p class="text-4xl font-bold text-red-500 mt-2 z-10">{{ $expiredSubscriptions }}</p>
                    <div class="mt-4 flex items-center text-xs font-semibold text-red-500 z-10">
                        <span>Requires Attention</span>
                    </div>
                </div>
            </div>

            <!-- Content Section -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition hover:shadow-md duration-300 text-left">
                <div class="p-8">
                    <h3 class="font-bold text-xl text-gray-900 mb-2">Platform Management</h3>
                    <p class="text-gray-500 mb-8 text-sm max-w-2xl">Effortlessly manage your application configuration, institute portfolios, and automated messaging integrations from a single control plane.</p>

                    <div class="flex flex-wrap gap-4">
                        <a href="{{ route('institutes.index') }}" class="inline-flex items-center justify-center px-6 py-3 border border-transparent text-sm font-bold rounded-xl text-white bg-indigo-600 hover:bg-indigo-700 shadow-xl shadow-indigo-600/30 transition duration-150">
                            <svg class="w-5 h-5 mr-3 -ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                            Manage Portfolio
                        </a>
                        <a href="{{ route('institutes.create') }}" class="inline-flex items-center justify-center px-6 py-3 border border-gray-200 text-sm font-bold rounded-xl text-gray-700 bg-white hover:bg-gray-50 transition duration-150">
                            <svg class="w-5 h-5 mr-3 -ml-1 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                            Onboard Institute
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-admin-layout>