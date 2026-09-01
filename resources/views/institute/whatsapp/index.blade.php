@extends('layouts.institute')

@section('title', 'WhatsApp Integration')

@section('content')
<div class="p-6">
    <div class="mb-8 flex items-center gap-4">
        <a href="{{ route('institute.profile.index') }}" class="h-10 w-10 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 hover:text-orange-600 hover:border-orange-600/30 transition-all shadow-sm group">
            <svg class="w-5 h-5 group-hover:-translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
        </a>
        <div>
            <h1 class="text-xl font-semibold text-slate-800 tracking-tight">WhatsApp Integration</h1>
            <p class="text-xs text-slate-400 mt-0.5 font-medium">Connect and automate notifications through WhatsApp</p>
        </div>
    </div>

    <div class="max-w-3xl mx-auto mt-8">
        <div class="bg-white rounded-3xl border border-slate-100 shadow-xl overflow-hidden relative">
            <!-- Top Gradient Accents -->
            <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 via-[#FF6B00] to-orange-500"></div>

            <div class="p-8 md:p-12 text-center relative z-10 flex flex-col items-center">
                <!-- Icon with Pulse Glow -->
                <div class="relative mb-6">
                    <div class="absolute inset-0 bg-emerald-500/10 rounded-3xl blur-xl animate-pulse"></div>
                    <div class="h-20 w-20 bg-emerald-50 rounded-3xl flex items-center justify-center text-emerald-500 border border-emerald-100 shadow-inner relative z-10">
                        <svg class="w-10 h-10" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.71.306 1.263.489 1.694.625.712.227 1.36.195 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.002-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                    </div>
                </div>

                <!-- Coming Soon Badge -->
                <span class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-full bg-orange-50 border border-orange-100 text-[#FF6B00] text-[10px] font-black uppercase tracking-widest mb-4">
                    <span class="h-1.5 w-1.5 rounded-full bg-[#FF6B00] animate-ping"></span>
                    Coming Soon
                </span>

                <h2 class="text-2xl font-black text-slate-800 tracking-tight mb-3">Automate Your Communication</h2>
                <p class="text-sm text-slate-500 font-medium leading-relaxed max-w-md mb-8">
                    We are currently building a direct integration with the Meta WhatsApp Cloud API. Soon you'll be able to send reminders, receipts, and daily updates directly to parents' phones.
                </p>

                <!-- Feature Preview Grid -->
                <div class="w-full grid grid-cols-1 md:grid-cols-3 gap-4 text-left border-t border-slate-50 pt-8 mt-4">
                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 hover:border-emerald-500/20 hover:bg-white transition-all duration-300">
                        <div class="h-8 w-8 bg-emerald-50 rounded-lg flex items-center justify-center text-emerald-500 mb-3">
                            <i class="fas fa-indian-rupee-sign text-xs"></i>
                        </div>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Fee Reminders</h4>
                        <p class="text-[10px] text-slate-400 font-semibold leading-normal">Send automated outstanding fee notifications and digital payment links.</p>
                    </div>

                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 hover:border-emerald-500/20 hover:bg-white transition-all duration-300">
                        <div class="h-8 w-8 bg-blue-50 rounded-lg flex items-center justify-center text-blue-500 mb-3">
                            <i class="fas fa-calendar-check text-xs"></i>
                        </div>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Attendance Logs</h4>
                        <p class="text-[10px] text-slate-400 font-semibold leading-normal">Alert parents instantly when students are absent or enter/leave class.</p>
                    </div>

                    <div class="p-4 bg-slate-50/50 rounded-2xl border border-slate-100/50 hover:border-emerald-500/20 hover:bg-white transition-all duration-300">
                        <div class="h-8 w-8 bg-purple-50 rounded-lg flex items-center justify-center text-purple-500 mb-3">
                            <i class="fas fa-bullhorn text-xs"></i>
                        </div>
                        <h4 class="text-xs font-black text-slate-700 uppercase tracking-wider mb-1">Custom Broadcasts</h4>
                        <p class="text-[10px] text-slate-400 font-semibold leading-normal">Send announcements, exam schedules, and holiday updates to entire batches.</p>
                    </div>
                </div>

                <div class="mt-8 text-xs font-bold text-slate-400">
                    © {{ date('Y') }} Tuoora · A bridge of knowledge for all
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
