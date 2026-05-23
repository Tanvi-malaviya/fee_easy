@extends('layouts.institute')

@section('content')
    <div class="max-w-[1600px] mx-auto animate-in fade-in duration-500">
        <!-- Header Section -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-3 px-4 md:px-0">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-slate-800 tracking-tight">Financial Overview</h1>
                <p class="text-[10px] md:text-[11px] font-bold text-slate-400 uppercase tracking-widest mt-1">
                    Track and manage institutional expenses in real-time
                </p>
            </div>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <button onclick="document.getElementById('month-picker').showPicker()"
                        class="bg-white border border-slate-100 rounded-xl px-3 py-1.5 flex items-center gap-2 shadow-sm hover:border-primary/30 transition-all cursor-pointer group">
                        <span id="current-month-display"
                            class="text-xs font-bold text-slate-600 group-hover:text-primary transition-colors">May
                            2026</span>
                        <svg class="w-3.5 h-3.5 text-slate-400 group-hover:text-primary transition-colors" fill="none"
                            stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <input type="month" id="month-picker" class="absolute inset-0 opacity-0 pointer-events-none"
                        onchange="handleMonthChange(this.value)">
                </div>
                <button onclick="openExpenseModal()"
                    class="px-4 py-2 bg-primary text-white rounded-xl text-sm font-bold shadow-lg shadow-amber-900/20 hover:translate-y-[-1px] transition-all flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Add Expense
                </button>
            </div>
        </div>

        <!-- Dashboard Stats Grid -->
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4 mb-2 px-4 md:px-0">
            <!-- Spending Trends Chart -->
            <div class="md:col-span-8 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 relative overflow-hidden">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-bold text-slate-800">Spending Trends</h3>
                    <div class="flex items-center gap-4">
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-primary"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">This Month</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="h-2 w-2 rounded-full bg-slate-200"></div>
                            <span class="text-[10px] font-bold text-slate-400 uppercase tracking-wider">Last Month</span>
                        </div>
                    </div>
                </div>
                <div class="h-[220px] w-full relative">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- Category Breakdown -->
            <div
                class="md:col-span-4 bg-white rounded-2xl border border-slate-100 shadow-sm p-4 min-h-[340px] flex flex-col">
                <h3 class="text-sm font-bold text-slate-800 mb-6">Category Breakdown</h3>
                <div id="category-chart-container" class="flex-1 flex flex-col items-center justify-center pt-2">
                    <div class="relative h-44 w-44 mb-4">
                        <canvas id="categoryChart"></canvas>
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-[9px] font-black text-slate-300 uppercase tracking-[0.2em]">Total Spend</p>
                            <p id="total-spend-display" class="text-2xl font-black text-slate-800 tracking-tight">₹0</p>
                        </div>
                    </div>
                    <div id="category-legend"
                        class="w-full space-y-1.5 max-h-[120px] overflow-y-auto custom-scrollbar pr-2">
                        <!-- Legends injected here -->
                    </div>
                    <div id="no-data-message" class="hidden text-center py-10">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">No category data for this
                            month</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Transaction History Section -->
        <div class="mx-4 md:mx-0 bg-white rounded-2xl border border-slate-100 shadow-sm overflow-hidden mb-8">
            <div
                class="px-4 md:px-6 py-4 border-b border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/30">
                <h3 class="text-sm font-bold text-slate-800">Transaction History</h3>
                <button onclick="exportPDF()"
                    class="w-full sm:w-auto text-[11px] font-bold text-primary hover:underline flex items-center justify-center gap-1.5 uppercase tracking-widest">
                    Export Report
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                </button>
            </div>

            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="bg-slate-50/50 border-b border-slate-100">
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Category
                            </th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Description
                            </th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Date</th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Account
                            </th>
                            <th class="px-6 py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">
                                Amount</th>
                        </tr>
                    </thead>
                    <tbody id="transaction-table-body">
                        <!-- Transactions injected here -->
                    </tbody>
                </table>
                <div id="table-loader" class="p-12 text-center hidden">
                    <div class="h-8 w-8 border-3 border-slate-100 border-t-primary rounded-full animate-spin mx-auto mb-3">
                    </div>
                    <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Fetching Transactions...</p>
                </div>
            </div>

            <!-- Pagination Footer -->
            <div id="pagination-footer"
                class="px-4 md:px-6 py-4 border-t border-slate-50 flex flex-col sm:flex-row items-center justify-between gap-4 bg-slate-50/10">
                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest order-2 sm:order-1">
                    Showing <span id="pagination-from">0</span> - <span id="pagination-to">0</span> of <span
                        id="pagination-total">0</span>
                </p>
                <div class="flex items-center gap-2 order-1 sm:order-2">
                    <button onclick="changePage(currentPage - 1)" id="prev-page"
                        class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-500 hover:text-primary disabled:opacity-30 disabled:pointer-events-none transition-all">
                        Previous
                    </button>
                    <div id="page-numbers" class="flex items-center gap-1">
                        <!-- Page numbers injected here -->
                    </div>
                    <button onclick="changePage(currentPage + 1)" id="next-page"
                        class="px-3 py-1.5 bg-white border border-slate-100 rounded-lg text-[10px] font-bold text-slate-500 hover:text-primary disabled:opacity-30 disabled:pointer-events-none transition-all">
                        Next
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Expense Modal -->
    <div id="expense-modal" class="fixed inset-0 z-[200] hidden overflow-y-auto">
        <div class="flex min-h-screen items-center justify-center p-4">
            <div onclick="closeExpenseModal()" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity">
            </div>

            <div id="expense-modal-content"
                class="relative w-full max-w-md scale-95 opacity-0 bg-white rounded-3xl shadow-2xl transition-all duration-300 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-50 flex items-center justify-between">
                    <h3 class="text-sm font-bold text-slate-800">Add Transaction</h3>
                    <button onclick="closeExpenseModal()" class="text-slate-400 hover:text-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <form id="expense-form" class="p-6" onsubmit="saveExpense(event)">
                    <div class="text-center mb-8">
                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Enter Amount</p>
                        <div class="flex items-center justify-center gap-2">
                            <span class="text-2xl font-black text-primary">₹</span>
                            <input type="number" name="amount" required step="0.01" placeholder="0.00"
                                class="w-40 text-4xl font-black text-primary border-none focus:ring-0 p-0 placeholder-primary/20 bg-transparent text-center">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <div class="flex items-center justify-between mb-1.5">
                                <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest ml-1">Category</label>
                                <button type="button" onclick="toggleAddCategoryInline()" class="text-[10px] font-bold text-primary hover:underline uppercase tracking-wider">
                                    + New
                                </button>
                            </div>
                            <div class="relative" id="custom-category-dropdown">
                                <button type="button" onclick="toggleCategoryDropdown()"
                                    class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2.5 text-left text-xs font-bold flex items-center justify-between text-slate-700 hover:border-slate-200 transition-all cursor-pointer">
                                    <span id="selected-category-name" class="text-slate-400 font-bold text-xs">Select Category</span>
                                    <svg class="w-3.5 h-3.5 text-slate-400 transition-transform duration-200" id="dropdown-arrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                <input type="hidden" name="expense_category_id" id="category-id-input" required>
                                
                                <div id="category-dropdown-options"
                                    class="hidden absolute z-30 w-full mt-1 bg-white border border-slate-100 rounded-xl shadow-xl max-h-40 overflow-y-auto custom-scrollbar p-1">
                                    <!-- Options injected here -->
                                </div>
                            </div>
                        </div>
                        <div>
                            <label
                                class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5 mt-[2px] ml-1">Date</label>
                            <input type="date" name="date" required value="{{ date('Y-m-d') }}" max="{{ date('Y-m-d') }}"
                                class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-bold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all">
                        </div>
                    </div>

                    <!-- Inline Add Category Input -->
                    <div id="inline-category-container" class="hidden mb-4 bg-slate-50/50 border border-slate-100 p-3 rounded-2xl animate-in duration-300">
                        <div class="flex items-center justify-between mb-1.5 ml-1">
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">Create New Category</span>
                        </div>
                        <div class="flex gap-2">
                            <input type="text" id="new-category-name" placeholder="Category Name..." 
                                class="flex-1 bg-white border border-slate-200 rounded-xl px-3 py-1.5 text-xs font-bold text-slate-700 placeholder-slate-400 focus:border-primary outline-none transition-all">
                            <button type="button" id="add-category-btn" onclick="createCategory()"
                                class="px-4 py-1.5 bg-primary text-white text-xs font-bold rounded-xl hover:opacity-90 active:scale-95 transition-all">
                                Add
                            </button>
                        </div>
                        <span id="category-create-error" class="hidden text-[9px] font-bold text-rose-500 mt-1 ml-1 block"></span>
                    </div>

                    <div class="mb-4">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Account</label>
                        <div class="flex p-1 bg-slate-100 rounded-xl">
                            <label class="flex-1 relative cursor-pointer">
                                <input type="radio" name="payment_method" value="Cash" checked class="peer hidden">
                                <div
                                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-xs font-bold text-slate-400 peer-checked:bg-white peer-checked:text-primary peer-checked:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    Cash
                                </div>
                            </label>
                            <label class="flex-1 relative cursor-pointer">
                                <input type="radio" name="payment_method" value="Online" class="peer hidden">
                                <div
                                    class="flex items-center justify-center gap-2 px-4 py-2 rounded-lg text-xs font-bold text-slate-400 peer-checked:bg-white peer-checked:text-primary peer-checked:shadow-sm transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                    </svg>
                                    Online
                                </div>
                            </label>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label
                            class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Description</label>
                        <textarea name="description"
                            placeholder="What was this for? (e.g., Weekly groceries at Whole Foods)" rows="3"
                            class="w-full bg-slate-50 border border-slate-100 rounded-xl px-4 py-2 text-xs font-semibold text-slate-700 focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all resize-none"></textarea>
                    </div>

                    <div id="expense-error"
                        class="hidden px-4 py-2 bg-rose-50 border border-rose-100 rounded-xl text-[10px] font-bold text-rose-500 mb-4">
                        Something went wrong
                    </div>

                    <div class="flex items-center gap-3 mt-8">
                        <button type="button" onclick="closeExpenseModal()"
                            class="flex-1 py-3 bg-slate-50 text-slate-400 rounded-xl text-xs font-bold hover:bg-slate-100 transition-all uppercase tracking-widest">Cancel</button>
                        <button type="submit" id="save-expense-btn"
                            class="flex-1 py-3 bg-primary text-white rounded-xl text-xs font-bold shadow-lg shadow-primary/20 hover:shadow-primary/30 hover:translate-y-[-1px] active:scale-95 transition-all uppercase tracking-widest">
                            Save Transaction
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const API_BASE = '/api/v1/institute/expenses';
            const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            let trendsChart = null;
            let categoryChart = null;
            let selectedMonth = new Date().getMonth() + 1;
            let selectedYear = new Date().getFullYear();
            let currentPage = 1;
            let lastPage = 1;
            let isFiltering = false;

            async function initDashboard(isFilter = false, page = 1) {
                try {
                    isFiltering = isFilter;
                    currentPage = page;

                    const url = new URL(`${API_BASE}/dashboard`, window.location.origin);
                    if (isFiltering) {
                        url.searchParams.append('month', selectedMonth);
                        url.searchParams.append('year', selectedYear);
                    }
                    url.searchParams.append('page', currentPage);

                    const response = await fetch(url, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();

                    if (result.status === 'success') {
                        updateDashboardUI(result.data);
                    }
                } catch (error) {
                    console.error('Dashboard Load Error:', error);
                }
            }

            function changePage(page) {
                if (page < 1 || page > lastPage) return;
                initDashboard(isFiltering, page);
            }

            function exportPDF() {
                const url = new URL(`${API_BASE}/report`, window.location.origin);
                url.searchParams.append('format', 'pdf');
                if (isFiltering) {
                    url.searchParams.append('month', selectedMonth);
                    url.searchParams.append('year', selectedYear);
                }
                window.location.href = url.toString();
            }

            function handleMonthChange(value) {
                if (!value) return;
                const [year, month] = value.split('-');
                selectedYear = parseInt(year);
                selectedMonth = parseInt(month);
                initDashboard(true);
            }

            function updateDashboardUI(data) {
                document.getElementById('current-month-display').textContent = data.month_name;
                document.getElementById('total-spend-display').textContent = `₹${data.total_spend.toLocaleString()}`;

                renderTrendsChart(data.trends, data.month_name);
                renderCategoryChart(data.category_breakdown);
                renderTransactions(data.recent_transactions);
            }

            function renderTrendsChart(trends) {
                const ctx = document.getElementById('trendsChart').getContext('2d');

                if (trendsChart) trendsChart.destroy();

                const labels = Array.from({ length: 31 }, (_, i) => i + 1);

                // Process this month data
                const thisMonthData = Array(31).fill(0);
                trends.this_month.forEach(t => thisMonthData[t.day - 1] = t.total);

                trendsChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'This Month',
                                data: thisMonthData,
                                backgroundColor: '#ff6c00',
                                borderRadius: 4,
                                barThickness: 12,
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: {
                            y: { beginAtZero: true, grid: { display: false }, ticks: { display: false } },
                            x: { grid: { display: false }, ticks: { font: { size: 10, weight: 'bold' }, color: '#CBD5E1' } }
                        }
                    }
                });
            }

            function renderCategoryChart(breakdown) {
                const ctx = document.getElementById('categoryChart').getContext('2d');
                const legend = document.getElementById('category-legend');
                const noData = document.getElementById('no-data-message');
                const chartWrapper = document.querySelector('#categoryChart').parentElement;

                if (categoryChart) categoryChart.destroy();

                if (!breakdown || breakdown.length === 0) {
                    chartWrapper.classList.add('hidden');
                    legend.classList.add('hidden');
                    noData.classList.remove('hidden');
                    return;
                }

                chartWrapper.classList.remove('hidden');
                legend.classList.remove('hidden');
                noData.classList.add('hidden');

                const colors = ['#ff6c00', '#D97706', '#059669', '#2563EB', '#7C3AED', '#DB2777', '#0891B2'];

                categoryChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: breakdown.map(b => b.category_name),
                        datasets: [{
                            data: breakdown.map(b => b.total),
                            backgroundColor: colors,
                            borderWidth: 0,
                            cutout: '82%'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: { legend: { display: false } }
                    }
                });

                // Render Legend
                legend.innerHTML = breakdown.map((b, i) => `
                                    <div class="flex items-center justify-between text-[11px] font-bold border-b border-slate-50 pb-2 last:border-0">
                                        <div class="flex items-center gap-3">
                                            <div class="h-2 w-2 rounded-full" style="background-color: ${colors[i % colors.length]}"></div>
                                            <span class="text-slate-500">${b.category_name}</span>
                                        </div>
                                        <span class="text-slate-800 tracking-tight">₹${b.total.toLocaleString()}</span>
                                    </div>
                                `).join('');
            }

            function renderTransactions(pagination) {
                const body = document.getElementById('transaction-table-body');
                const transactions = pagination.items;

                // Update pagination metadata
                lastPage = pagination.last_page;
                currentPage = pagination.current_page;

                document.getElementById('pagination-total').textContent = pagination.total;
                document.getElementById('pagination-from').textContent = ((currentPage - 1) * pagination.per_page) + 1;
                document.getElementById('pagination-to').textContent = Math.min(currentPage * pagination.per_page, pagination.total);

                document.getElementById('prev-page').disabled = currentPage <= 1;
                document.getElementById('next-page').disabled = currentPage >= lastPage;

                // Render Page Numbers
                const pageNumbers = document.getElementById('page-numbers');
                let pagesHtml = '';
                for (let i = 1; i <= lastPage; i++) {
                    if (i === 1 || i === lastPage || (i >= currentPage - 1 && i <= currentPage + 1)) {
                        pagesHtml += `<button onclick="changePage(${i})" class="w-8 h-8 flex items-center justify-center rounded-lg text-[10px] font-bold transition-all ${i === currentPage ? 'bg-primary text-white shadow-sm' : 'bg-white border border-slate-100 text-slate-400 hover:border-primary/30 hover:text-primary'}">${i}</button>`;
                    } else if (i === currentPage - 2 || i === currentPage + 2) {
                        pagesHtml += `<span class="text-slate-300 text-[10px] px-1">...</span>`;
                    }
                }
                pageNumbers.innerHTML = pagesHtml;

                if (!transactions || transactions.length === 0) {
                    body.innerHTML = '<tr><td colspan="5" class="py-12 text-center text-xs font-bold text-slate-400 uppercase tracking-widest">No transactions found</td></tr>';
                    return;
                }

                body.innerHTML = transactions.map(t => `
                                    <tr class="border-b border-slate-50 hover:bg-slate-50/50 transition-colors">
                                        <td class="px-6 py-3">
                                            <div class="flex items-center gap-3">
                                                <div class="h-8 w-8 bg-slate-50 rounded-lg flex items-center justify-center text-slate-400">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                </div>
                                                <span class="text-xs font-bold text-slate-700">${t.category?.name || 'Uncategorized'}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="text-xs font-medium text-slate-500">${t.description || 'No description'}</span>
                                        </td>
                                        <td class="px-6 py-3 text-xs font-bold text-slate-400 tracking-tight">
                                            ${new Date(t.date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' })}
                                        </td>
                                        <td class="px-6 py-3">
                                            <span class="px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-wider ${t.payment_method === 'Online' ? 'bg-blue-50 text-blue-500' : 'bg-slate-100 text-slate-500'}">
                                                ${t.payment_method}
                                            </span>
                                        </td>
                                        <td class="px-6 py-3 text-right">
                                            <span class="text-xs font-black text-primary">₹${t.amount.toLocaleString()}</span>
                                        </td>
                                    </tr>
                                `).join('');
            }

            async function loadCategories() {
                try {
                    const response = await fetch(`${API_BASE}/categories`, {
                        headers: { 'Accept': 'application/json' }
                    });
                    const result = await response.json();
                    if (result.status === 'success') {
                        const optionsDiv = document.getElementById('category-dropdown-options');
                        let html = '';
                        if (result.data.length === 0) {
                            html = `<div class="px-4 py-2 text-xs text-slate-400 text-center font-medium">No categories. Click "+ New" to add one!</div>`;
                        } else {
                            html = result.data.map(c => `
                                <button type="button" onclick="selectCategory('${c.id}', '${c.name.replace(/'/g, "\\'")}')"
                                    class="w-full text-left px-4 py-2 rounded-lg text-xs font-bold text-slate-600 hover:bg-slate-50 hover:text-primary transition-all flex items-center justify-between group">
                                    <span>${c.name}</span>
                                    <svg class="w-3 h-3 text-primary opacity-0 group-hover:opacity-100 transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            `).join('');
                        }
                        optionsDiv.innerHTML = html;
                    }
                } catch (error) {
                    console.error('Category Load Error:', error);
                }
            }

            function toggleCategoryDropdown() {
                const options = document.getElementById('category-dropdown-options');
                const arrow = document.getElementById('dropdown-arrow');
                if (options.classList.contains('hidden')) {
                    options.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                } else {
                    options.classList.add('hidden');
                    arrow.classList.remove('rotate-180');
                }
            }

            function selectCategory(id, name) {
                const hiddenInput = document.getElementById('category-id-input');
                const selectedSpan = document.getElementById('selected-category-name');
                
                hiddenInput.value = id;
                selectedSpan.textContent = name;
                selectedSpan.classList.remove('text-slate-400');
                selectedSpan.classList.add('text-slate-900');

                // Close dropdown
                const options = document.getElementById('category-dropdown-options');
                const arrow = document.getElementById('dropdown-arrow');
                options.classList.add('hidden');
                arrow.classList.remove('rotate-180');
            }

            // Click outside to close category dropdown
            document.addEventListener('click', (e) => {
                const dropdown = document.getElementById('custom-category-dropdown');
                if (dropdown && !dropdown.contains(e.target)) {
                    const options = document.getElementById('category-dropdown-options');
                    const arrow = document.getElementById('dropdown-arrow');
                    if (options && !options.classList.contains('hidden')) {
                        options.classList.add('hidden');
                        arrow.classList.remove('rotate-180');
                    }
                }
            });

            function openExpenseModal() {
                const modal = document.getElementById('expense-modal');
                const content = document.getElementById('expense-modal-content');
                document.getElementById('expense-form').reset();
                document.getElementById('expense-error').classList.add('hidden');

                // Reset inline category adder
                const inlineContainer = document.getElementById('inline-category-container');
                if (inlineContainer) inlineContainer.classList.add('hidden');
                const newCatInput = document.getElementById('new-category-name');
                if (newCatInput) newCatInput.value = '';
                const catError = document.getElementById('category-create-error');
                if (catError) catError.classList.add('hidden');

                // Reset custom category select
                const hiddenInput = document.getElementById('category-id-input');
                if (hiddenInput) hiddenInput.value = '';
                const selectedSpan = document.getElementById('selected-category-name');
                if (selectedSpan) {
                    selectedSpan.textContent = 'Select Category';
                    selectedSpan.classList.add('text-slate-400');
                    selectedSpan.classList.remove('text-slate-900');
                }
                const options = document.getElementById('category-dropdown-options');
                if (options) options.classList.add('hidden');
                const arrow = document.getElementById('dropdown-arrow');
                if (arrow) arrow.classList.remove('rotate-180');

                modal.classList.remove('hidden');
                setTimeout(() => {
                    content.classList.remove('scale-95', 'opacity-0');
                    content.classList.add('scale-100', 'opacity-100');
                }, 10);

                loadCategories();
            }

            function closeExpenseModal() {
                const modal = document.getElementById('expense-modal');
                const content = document.getElementById('expense-modal-content');
                content.classList.add('scale-95', 'opacity-0');
                content.classList.remove('scale-100', 'opacity-100');
                setTimeout(() => modal.classList.add('hidden'), 300);
            }

            function toggleAddCategoryInline() {
                const container = document.getElementById('inline-category-container');
                const errSpan = document.getElementById('category-create-error');
                const nameInput = document.getElementById('new-category-name');
                
                if (container.classList.contains('hidden')) {
                    container.classList.remove('hidden');
                    nameInput.focus();
                } else {
                    container.classList.add('hidden');
                    nameInput.value = '';
                    errSpan.classList.add('hidden');
                }
            }

            async function createCategory() {
                const nameInput = document.getElementById('new-category-name');
                const name = nameInput.value.trim();
                const errSpan = document.getElementById('category-create-error');
                const addBtn = document.getElementById('add-category-btn');

                if (!name) {
                    errSpan.textContent = 'Please enter a category name.';
                    errSpan.classList.remove('hidden');
                    return;
                }

                errSpan.classList.add('hidden');
                addBtn.disabled = true;
                addBtn.innerHTML = '<div class="h-3.5 w-3.5 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto"></div>';

                try {
                    const response = await fetch(`${API_BASE}/categories`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: JSON.stringify({ name })
                    });
                    const result = await response.json();

                    if (response.ok && result.status === 'success') {
                        // Refresh categories list
                        await loadCategories();
                        
                        // Select the newly created category
                        selectCategory(result.data.id, result.data.name);

                        // Reset and hide inline adder
                        toggleAddCategoryInline();
                        
                        // Show success feedback
                        if (window.showToast) {
                            window.showToast('Category created successfully');
                        } else {
                            alert('Category created successfully');
                        }
                    } else {
                        errSpan.textContent = result.message || 'Failed to create category.';
                        errSpan.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Create Category Error:', error);
                    errSpan.textContent = 'Connection error. Please try again.';
                    errSpan.classList.remove('hidden');
                } finally {
                    addBtn.disabled = false;
                    addBtn.textContent = 'Add';
                }
            }

            async function saveExpense(event) {
                event.preventDefault();
                const form = event.target;
                const saveBtn = document.getElementById('save-expense-btn');
                const errDiv = document.getElementById('expense-error');

                saveBtn.disabled = true;
                saveBtn.innerHTML = '<div class="h-4 w-4 border-2 border-white/30 border-t-white rounded-full animate-spin mx-auto"></div>';

                try {
                    const formData = new FormData(form);
                    const response = await fetch(API_BASE, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': CSRF_TOKEN
                        },
                        body: formData
                    });

                    const result = await response.json();

                    if (response.ok) {
                        closeExpenseModal();
                        initDashboard();
                        if (window.showToast) window.showToast('Expense added successfully');
                    } else {
                        errDiv.textContent = result.message || 'Failed to save expense';
                        errDiv.classList.remove('hidden');
                    }
                } catch (error) {
                    console.error('Save Error:', error);
                    errDiv.textContent = 'Connection error. Please try again.';
                    errDiv.classList.remove('hidden');
                } finally {
                    saveBtn.disabled = false;
                    saveBtn.textContent = 'Save Transaction';
                }
            }

            document.addEventListener('DOMContentLoaded', () => initDashboard(false));
        </script>
    @endpush

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 3px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #E2E8F0;
            border-radius: 10px;
        }

        /* Remove arrows from number input */
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        @keyframes in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-in {
            animation: in 0.4s ease-out forwards;
        }
    </style>
@endsection