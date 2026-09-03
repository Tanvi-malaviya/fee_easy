@extends('layouts.teacher')

@section('title', 'Salary Slips')

@section('content')
<h1 class="text-2xl font-black text-slate-900 tracking-tight mb-1">Salary History</h1>
<p class="text-sm text-slate-500 mb-6">Your salary slips and payment history.</p>

<div class="bg-white rounded-2xl border border-slate-100 overflow-hidden">
    <table class="w-full text-sm">
        <thead class="bg-slate-50 text-[10px] uppercase tracking-wide text-slate-500 font-black">
            <tr>
                <th class="text-left p-3">Month / Year</th>
                <th class="text-left p-3">Base Salary</th>
                <th class="text-left p-3">Bonus</th>
                <th class="text-left p-3">Deductions</th>
                <th class="text-left p-3">Net Salary</th>
                <th class="text-left p-3">Status</th>
                <th class="text-right p-3">Slip</th>
            </tr>
        </thead>
        <tbody>
            @forelse($salaries as $salary)
                <tr class="border-t border-slate-50">
                    <td class="p-3 font-semibold">{{ \Carbon\Carbon::create()->month($salary->month)->format('F') }} {{ $salary->year }}</td>
                    <td class="p-3 text-slate-500">₹{{ number_format($salary->base_salary, 2) }}</td>
                    <td class="p-3 text-slate-500">₹{{ number_format($salary->bonus, 2) }}</td>
                    <td class="p-3 text-slate-500">₹{{ number_format($salary->deductions, 2) }}</td>
                    <td class="p-3 font-bold text-slate-800">₹{{ number_format($salary->net_salary, 2) }}</td>
                    <td class="p-3">
                        <span class="text-[10px] font-black uppercase tracking-wide px-2 py-1 rounded-full {{ $salary->status === 'Paid' ? 'bg-emerald-50 text-emerald-600' : 'bg-amber-50 text-amber-600' }}">{{ $salary->status }}</span>
                    </td>
                    <td class="p-3 text-right">
                        <a href="/api/v1/teacher/salaries/{{ $salary->id }}/download" class="text-primary text-xs font-bold hover:underline">Download PDF</a>
                    </td>
                </tr>
            @empty
                <tr><td class="p-4 text-slate-400 text-xs text-center" colspan="7">No salary records yet.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">{{ $salaries->links() }}</div>
@endsection
