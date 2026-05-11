use App\Models\Institute;
use App\Models\Expense;
use Illuminate\Support\Carbon;

// Assuming we want to check for the first institute
$institute = Institute::first();

if (!$institute) {
    echo "No institute found\n";
    exit;
}

$now = Carbon::now();
$thisMonth = $now->month;
$thisYear = $now->year;

$totalSpend = $institute->expenses()
    ->whereMonth('date', $thisMonth)
    ->whereYear('date', $thisYear)
    ->sum('amount');

$categoryBreakdown = $institute->expenses()
    ->with('category')
    ->whereMonth('date', $thisMonth)
    ->whereYear('date', $thisYear)
    ->select('expense_category_id', \Illuminate\Support\Facades\DB::raw('SUM(amount) as total'))
    ->groupBy('expense_category_id')
    ->get();

echo "Total Spend: " . $totalSpend . "\n";
echo "Category Breakdown Count: " . $categoryBreakdown->count() . "\n";

foreach ($categoryBreakdown as $item) {
    echo "Category ID: " . $item->expense_category_id . " | Total: " . $item->total . " | Name: " . ($item->category->name ?? 'Unknown') . "\n";
}
