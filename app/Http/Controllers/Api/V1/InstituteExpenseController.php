<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ExpenseCategory;
use App\Models\Expense;
use Illuminate\Support\Carbon;

class InstituteExpenseController extends Controller
{
    /**
     * Get all expense categories.
     */
    public function getCategories(Request $request)
    {
        $institute = $request->user();
        $categories = $institute->expenseCategories()->orderBy('name', 'asc')->get();

        return response()->json([
            'status' => 'success',
            'data' => $categories
        ]);
    }

    /**
     * Add a built-in category.
     */
    public function storeCategory(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        $category = $institute->expenseCategories()->create([
            'name' => $request->name
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully.',
            'data' => $category
        ], 201);
    }

    /**
     * Get all expenses (with optional date filtering).
     */
    public function index(Request $request)
    {
        $institute = $request->user();
        
        $query = $institute->expenses()->with('category');

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        } elseif ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        } elseif ($request->has('start_date') && $request->has('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }

        if ($request->has('category_id')) {
            $query->where('expense_category_id', $request->category_id);
        }

        $paginator = $query->orderBy('date', 'desc')->paginate(15);

        return response()->json([
            'status' => 'success',
            'data' => [
                'items' => $paginator->items(),
                'total' => $paginator->total(),
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
            ]
        ]);
    }

    /**
     * Store a new expense.
     * Note: File upload for receipt_image can be added here if needed using Storage::put()
     */
    public function store(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'expense_category_id' => 'required|exists:expense_categories,id,institute_id,' . $institute->id,
            'amount' => 'required|numeric|min:0',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'payment_method' => 'nullable|string|in:Cash,Online Payment',
            'receipt_image' => 'nullable|image|max:2048' 
        ]);

        $path = null;
        if ($request->hasFile('receipt_image')) {
            $path = $request->file('receipt_image')->store('expenses', 'public');
        }

        $expense = $institute->expenses()->create([
            'expense_category_id' => $request->expense_category_id,
            'amount' => $request->amount,
            'date' => \Illuminate\Support\Carbon::parse($request->date)->format('Y-m-d'),
            'description' => $request->description,
            'payment_method' => $request->payment_method ?? 'Cash',
            'receipt_image' => $path,
        ]);

        $expense->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Expense added successfully.',
            'data' => $expense
        ], 201);
    }

    /**
     * Update an expense.
     */
    public function update(Request $request, $id)
    {
        $institute = $request->user();

        $expense = $institute->expenses()->findOrFail($id);

        $request->validate([
            'expense_category_id' => 'sometimes|required|exists:expense_categories,id,institute_id,' . $institute->id,
            'amount' => 'sometimes|required|numeric|min:0',
            'date' => 'sometimes|required|date',
            'description' => 'nullable|string',
            'payment_method' => 'nullable|string|in:Cash,Online Payment',
            'receipt_image' => 'nullable|image|max:2048'
        ]);

        $data = $request->except('receipt_image');

        if ($request->hasFile('receipt_image')) {
            // Delete old image if exists
            if ($expense->receipt_image && \Illuminate\Support\Facades\Storage::disk('public')->exists($expense->receipt_image)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($expense->receipt_image);
            }
            $data['receipt_image'] = $request->file('receipt_image')->store('expenses', 'public');
        }

        if ($request->has('date')) {
            $data['date'] = \Illuminate\Support\Carbon::parse($request->date)->format('Y-m-d');
        }

        $expense->update($data);

        $expense->load('category');

        return response()->json([
            'status' => 'success',
            'message' => 'Expense updated successfully.',
            'data' => $expense
        ]);
    }

    /**
     * Delete an expense.
     */
    public function destroy(Request $request, $id)
    {
        $institute = $request->user();

        $expense = $institute->expenses()->findOrFail($id);
        $expense->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Expense deleted successfully.'
        ]);
    }

    /**
     * Get dashboard data for expenses.
     */
    public function dashboard(Request $request)
    {
        $institute = $request->user();
        $now = Carbon::now();
        $thisMonth = $now->month;
        $thisYear = $now->year;
        
        $lastMonthDate = $now->copy()->subMonth();
        $lastMonth = $lastMonthDate->month;
        $lastYear = $lastMonthDate->year;

        // 1. Total Spend (Current Month)
        $totalSpend = $institute->expenses()
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->sum('amount');

        // 2. Spending Trends (This Month vs Last Month)
        $thisMonthTrends = $institute->expenses()
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->selectRaw('DAY(date) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        $lastMonthTrends = $institute->expenses()
            ->whereMonth('date', $lastMonth)
            ->whereYear('date', $lastYear)
            ->selectRaw('DAY(date) as day, SUM(amount) as total')
            ->groupBy('day')
            ->orderBy('day')
            ->get();

        // 3. Category Breakdown
        $categoryBreakdown = $institute->expenses()
            ->with('category')
            ->whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->select('expense_category_id', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total'))
            ->groupBy('expense_category_id')
            ->get()
            ->map(function ($item) {
                return [
                    'category_name' => $item->category->name ?? 'Unknown',
                    'total' => (float)$item->total
                ];
            });

        // 4. Recent Transactions
        $recentTransactions = $institute->expenses()
            ->with('category')
            ->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return response()->json([
            'status' => 'success',
            'data' => [
                'total_spend' => (float)$totalSpend,
                'month_name' => $now->format('F Y'),
                'trends' => [
                    'this_month' => $thisMonthTrends,
                    'last_month' => $lastMonthTrends
                ],
                'category_breakdown' => $categoryBreakdown,
                'recent_transactions' => $recentTransactions
            ]
        ]);
    }

    /**
     * Expense Report generated by category.
     */
    public function report(Request $request)
    {
        $institute = $request->user();

        $request->validate([
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date',
            'month' => 'nullable|integer|min:1|max:12',
            'year' => 'nullable|integer'
        ]);

        $query = $institute->expenses()->with('category');

        if ($request->filled('month') && $request->filled('year')) {
            $query->whereMonth('date', $request->month)
                  ->whereYear('date', $request->year);
        } elseif ($request->filled('year')) {
            $query->whereYear('date', $request->year);
        } elseif ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        } else {
            // Default to current month if no filter provided
            $query->whereMonth('date', now()->month)
                  ->whereYear('date', now()->year);
        }

        $expenses = $query->get();

        $groupedByCategory = $expenses->groupBy('expense_category_id');

        $report = [];
        $totalOverall = 0;

        foreach ($groupedByCategory as $categoryId => $categoryExpenses) {
            $categoryTotal = $categoryExpenses->sum('amount');
            $totalOverall += $categoryTotal;

            $report[] = [
                'category_id' => $categoryId,
                'category_name' => $categoryExpenses->first()->category->name ?? 'Unknown',
                'total_amount' => $categoryTotal,
                'expense_count' => $categoryExpenses->count()
            ];
        }

        return response()->json([
            'status' => 'success',
            'total_expense' => $totalOverall,
            'breakdown' => $report
        ]);
    }
}
