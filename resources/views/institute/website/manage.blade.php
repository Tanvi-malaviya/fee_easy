@extends('layouts.institute')

@section('content')
<div class="max-w-[1200px] mx-auto pb-12 pt-4 px-4">
    <!-- Header banner -->
    <div class="mb-5 bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-950 rounded-2xl py-4 px-6 shadow-lg text-white relative overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_right,rgba(99,102,241,0.15),transparent_50%)]"></div>
        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
            <div>
                <span class="text-[9px] font-black uppercase tracking-[0.2em] text-indigo-400">Website CMS</span>
                <h1 class="text-xl md:text-2xl font-black tracking-tight mt-0.5">Manage Your Portal</h1>
                <p class="text-[11px] text-slate-300 font-medium mt-0.5">Choose a template and configure landing page content for your institute.</p>
            </div>
            
            @if($institute->template_id && $institute->institute_code && $institute->institute_name)
                @php
                    $slug = Str::slug($institute->institute_name);
                    $publicUrl = url("/{$institute->institute_code}/{$slug}");
                @endphp
                <div class="flex items-center gap-2 bg-white/10 backdrop-blur-md rounded-xl p-2.5 border border-white/10 w-full md:w-auto">
                    <div class="flex-1 min-w-0 pr-3">
                        <span class="block text-[8px] font-bold uppercase text-slate-400 tracking-wider">Your Active Website:</span>
                        <a href="{{ $publicUrl }}" target="_blank" class="text-xs font-bold text-indigo-300 hover:text-indigo-100 hover:underline truncate block">
                            {{ $publicUrl }}
                        </a>
                    </div>
                    <button onclick="copyWebsiteUrl('{{ $publicUrl }}')" class="p-1.5 bg-white/10 hover:bg-white/20 rounded-lg transition text-slate-300 hover:text-white" title="Copy Website Link">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                    </button>
                </div>
            @endif
        </div>
    </div>

    <!-- Template Selector Section -->
    @php
        $templates = [
            1 => ['name' => 'Classic Academic', 'tag' => 'Classic', 'desc' => 'Structured academic layout using emerald-600 accents and clean sections.', 'gradient' => 'from-emerald-500 to-teal-600'],
            2 => ['name' => 'Mint Glassmorphic', 'tag' => 'Glass', 'desc' => 'Beautiful modern cards with soft mint shadows, glass layers, and interactive panels.', 'gradient' => 'from-teal-400 to-emerald-500'],
            3 => ['name' => 'Cyber Indigo', 'tag' => 'Interactive', 'desc' => 'Sleek theme with a sticky floating navigation bar, sliding indicator, and rich animation details.', 'gradient' => 'from-indigo-600 to-purple-600'],
            4 => ['name' => 'Royal Corporate', 'tag' => 'Corporate', 'desc' => 'A professional corporate theme focusing on structure, stats displays, and reliable authority.', 'gradient' => 'from-blue-600 to-indigo-600'],
            5 => ['name' => 'Futuristic Neon', 'tag' => 'Modern', 'desc' => 'Futuristic timeline theme featuring milestones progress bars and ambient glow highlights.', 'gradient' => 'from-purple-600 to-pink-600'],
        ];
        $activeTplId = $institute->template_id ?? 1;
        $activeTpl = $templates[$activeTplId] ?? $templates[1];
    @endphp

    <div class="mb-8">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">1. Choose Template</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Select one of our premium high-converting templates</p>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-slate-50 border border-slate-200/60 rounded-2xl p-4">
            <div class="flex items-center gap-3">
                <div id="active-template-icon" class="h-10 w-10 rounded-xl bg-gradient-to-br {{ $activeTpl['gradient'] }} flex items-center justify-center text-white text-xs font-black shadow-sm">
                    T{{ $activeTplId }}
                </div>
                <div>
                    <span class="block text-[8px] font-black uppercase text-slate-400 tracking-wider">Active Website Theme:</span>
                    <div class="flex items-center gap-2 mt-0.5">
                        <span id="active-template-name" class="text-xs font-bold text-slate-800">{{ $activeTpl['name'] }}</span>
                        <span id="active-template-badge" class="inline-flex text-[7px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded-full bg-orange-100 text-orange-700">{{ $activeTpl['tag'] }}</span>
                    </div>
                </div>
            </div>
            <button type="button" onclick="openTemplateModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-4 py-2.5 rounded-xl font-bold text-xs uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 shadow-md shadow-orange-500/10 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Choose Template
            </button>
        </div>
    </div>

    <!-- Hero Section Sliders Manager -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">2. Hero Section Banner & Sliders</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Add and arrange sliders for the main landing page header</p>
            </div>
            <button type="button" id="add-slide-btn" onclick="openAddSlideModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Slide
            </button>
        </div>

        <!-- Slider list wrapper -->
        <div id="sliders-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
            <!-- Dynamic items loaded here -->
        </div>

        <!-- Empty State -->
        <div id="sliders-empty-state" class="hidden flex flex-col items-center justify-center py-6 text-center">
            <div class="h-12 w-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No Sliders Configured</h4>
            <p class="text-[9px] text-slate-400 mt-0.5 max-w-xs leading-relaxed">Add banner slides to show beautiful headlines, tags, and cover images on your home page.</p>
        </div>


    </div>

    <!-- Vision, Mission & Values Section -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">3. Mission, Vision, and Values</h2>
                <p class="text-[10px] text-slate-400 font-semibold font-medium">Add and manage items under each core educational pillar</p>
            </div>
            
            <!-- Add button -->
            <div class="flex items-center gap-3">
                <button type="button" onclick="openAddPillarItemModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                    </svg>
                    Add Item
                </button>
            </div>
        </div>

        <!-- Tab Buttons Bar -->
        <div class="flex border-b border-slate-200 mb-4">
            <button type="button" onclick="switchPillarTab('vision')" id="tab-btn-vision" class="px-4 py-2 text-xs font-bold border-b-2 transition duration-200 focus:outline-none border-[#ff6c00] text-[#ff6c00]">Vision</button>
            <button type="button" onclick="switchPillarTab('mission')" id="tab-btn-mission" class="px-4 py-2 text-xs font-bold border-b-2 transition duration-200 focus:outline-none border-transparent text-slate-500 hover:text-slate-700">Mission</button>
            <button type="button" onclick="switchPillarTab('values')" id="tab-btn-values" class="px-4 py-2 text-xs font-bold border-b-2 transition duration-200 focus:outline-none border-transparent text-slate-500 hover:text-slate-700">Values</button>
        </div>

        <!-- Pillars list wrapper -->
        <div id="pillars-container" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Dynamic cards loaded here -->
        </div>

        <!-- Empty State -->
        <div id="pillars-empty-state" class="hidden flex flex-col items-center justify-center py-6 text-center">
            <div class="h-12 w-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No items added to this tab</h4>
            <p class="text-[9px] text-slate-400 mt-0.5 max-w-xs leading-relaxed">Click "Add Item" to add headlines and descriptions under this section.</p>
        </div>
    </div>

    @php
        $achievementsData = $content->achievements ?? [
            'badge'  => 'Our Milestones',
            'title'  => 'Recent Achievements',
            'desc'   => 'Proud moments demonstrating our dedication to academic and athletic excellence.',
            'items'  => [
                ['tag' => 'Award',     'title' => 'Best School Award 2025',    'desc' => 'Named "State\'s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.'],
                ['tag' => 'Academics', 'title' => '100% Board Exam Success',   'desc' => 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.'],
                ['tag' => 'Sports',    'title' => 'National Sports Champions', 'desc' => 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.'],
            ],
        ];
    @endphp

    <!-- Achievements Section Manager -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">4. Achievements Section</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Add and manage achievement cards shown on your institute homepage</p>
            </div>
            <button type="button" id="add-achievement-btn" onclick="openAddAchievementModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Achievement
            </button>
        </div>



        <!-- Achievement Cards -->
        <div id="achievements-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5"></div>

        <!-- Empty State -->
        <div id="achievements-empty-state" class="hidden flex flex-col items-center justify-center py-6 text-center">
            <div class="h-12 w-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" /></svg>
            </div>
            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No Achievements Added</h4>
            <p class="text-[9px] text-slate-400 mt-0.5 max-w-xs leading-relaxed">Click "Add Achievement" to showcase your institute's milestones and awards.</p>
        </div>
    </div>

    <!-- Gallery Section Manager -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">5. Gallery Section</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Add and manage image cards shown in your institute gallery (maximum 20 items)</p>
            </div>
            <button type="button" id="add-gallery-btn" onclick="openAddGalleryModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Gallery Item
            </button>
        </div>

        <!-- Gallery list wrapper -->
        <div id="gallery-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
            <!-- Dynamic items loaded here -->
        </div>

        <!-- Empty State -->
        <div id="gallery-empty-state" class="hidden flex flex-col items-center justify-center py-6 text-center">
            <div class="h-12 w-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No Gallery Items Added</h4>
            <p class="text-[9px] text-slate-400 mt-0.5 max-w-xs leading-relaxed">Click "Add Gallery Item" to showcase campus photos, classrooms, and activities.</p>
        </div>
    </div>

    <!-- Events Section Manager -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">6. Events Section</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Add and manage upcoming events shown on your website (maximum 20 events)</p>
            </div>
            <button type="button" id="add-event-btn" onclick="openAddEventModal()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-3 py-1.5 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                </svg>
                Add Event
            </button>
        </div>

        <!-- Events list wrapper -->
        <div id="events-container" class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3.5">
            <!-- Dynamic items loaded here -->
        </div>

        <!-- Empty State -->
        <div id="events-empty-state" class="hidden flex flex-col items-center justify-center py-6 text-center">
            <div class="h-12 w-12 bg-white border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 mb-2 shadow-sm">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
            <h4 class="text-[10px] font-bold text-slate-600 uppercase tracking-wider">No Events Added</h4>
            <p class="text-[9px] text-slate-400 mt-0.5 max-w-xs leading-relaxed">Click "Add Event" to schedule upcoming seminars, workshops, or activities.</p>
        </div>
    </div>

    <!-- Social Links Section Manager -->
    <div class="bg-slate-50 border border-slate-200/60 rounded-2xl p-4 relative overflow-hidden mt-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 mb-4 pb-4 border-b border-slate-200/60">
            <div>
                <h2 class="text-sm font-extrabold text-slate-800 tracking-tight">7. Social Media Links</h2>
                <p class="text-[10px] text-slate-400 font-semibold">Provide links to your official social media channels to be displayed in the website footer</p>
            </div>
            <button type="button" onclick="saveSocialLinks()" class="bg-[#ff6c00] hover:bg-[#e05f00] text-white px-4 py-2 rounded-xl font-bold text-[10px] uppercase tracking-wider transition-all duration-300 hover:scale-[1.02] active:scale-95 flex items-center gap-1.5 shadow-md shadow-orange-500/10">
                Save Social Links
            </button>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <!-- Facebook -->
            <div class="space-y-1">
                <label for="social-facebook" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Facebook Link</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-slate-400">FB</span>
                    <input type="url" id="social-facebook" value="{{ $content->facebook ?? '' }}" placeholder="https://facebook.com/your-page" class="w-full h-10 pl-10 pr-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-xs text-slate-800 transition">
                </div>
            </div>
            <!-- Twitter -->
            <div class="space-y-1">
                <label for="social-twitter" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Twitter / X Link</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-slate-400">𝕏</span>
                    <input type="url" id="social-twitter" value="{{ $content->twitter ?? '' }}" placeholder="https://x.com/your-handle" class="w-full h-10 pl-10 pr-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-xs text-slate-800 transition">
                </div>
            </div>
            <!-- LinkedIn -->
            <div class="space-y-1">
                <label for="social-linkedin" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">LinkedIn Link</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-slate-400">IN</span>
                    <input type="url" id="social-linkedin" value="{{ $content->linkedin ?? '' }}" placeholder="https://linkedin.com/in/your-profile" class="w-full h-10 pl-10 pr-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-xs text-slate-800 transition">
                </div>
            </div>
            <!-- Instagram -->
            <div class="space-y-1">
                <label for="social-instagram" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Instagram Link</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-slate-400">IG</span>
                    <input type="url" id="social-instagram" value="{{ $content->instagram ?? '' }}" placeholder="https://instagram.com/your-profile" class="w-full h-10 pl-10 pr-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-xs text-slate-800 transition">
                </div>
            </div>
            <!-- YouTube -->
            <div class="space-y-1 md:col-span-2">
                <label for="social-youtube" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">YouTube Channel Link</label>
                <div class="relative flex items-center">
                    <span class="absolute left-3 text-xs font-bold text-slate-400">YT</span>
                    <input type="url" id="social-youtube" value="{{ $content->youtube ?? '' }}" placeholder="https://youtube.com/c/your-channel" class="w-full h-10 pl-10 pr-3 border border-slate-200 rounded-xl bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-medium text-xs text-slate-800 transition">
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Slide Pop-up Modal -->
<div id="slide-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col">
        <!-- Header -->
        <div class="py-3 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 id="slide-modal-title" class="text-xs font-bold tracking-wide">Add Banner Slide</h3>
                <p id="slide-modal-desc" class="text-[9px] text-orange-100 mt-0.5">Define banner background image, custom title, badge, and description.</p>
            </div>
            <button onclick="closeSlideModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Fields -->
        <div class="p-4 px-5 overflow-y-auto space-y-3.5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <!-- Image Selector & Preview -->
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Slide Cover Image</label>
                <div class="flex items-center gap-3">
                    <div id="image-preview-box" class="h-14 w-24 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-inner relative group">
                        <span id="preview-placeholder" class="text-[8px] text-slate-400 font-bold uppercase tracking-wider text-center px-1">No Image</span>
                        <img id="modal-image-preview" src="#" class="h-full w-full object-cover hidden" />
                        <div id="image-upload-spinner" class="absolute inset-0 bg-white/80 backdrop-blur-[1px] flex items-center justify-center hidden">
                            <div class="h-5 w-5 border-2 border-slate-200 border-t-[#ff6c00] rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div class="flex-1 space-y-0.5">
                        <input type="file" id="slide-image-file" accept="image/*" class="hidden" onchange="handleModalImageUpload(event)">
                        <button type="button" onclick="document.getElementById('slide-image-file').click()" class="bg-orange-50 hover:bg-orange-100 text-orange-600 px-3.5 py-1.5 rounded-lg font-bold text-[9px] uppercase tracking-wider border border-orange-100 transition-colors">
                            Choose Image
                        </button>
                        <p class="text-[8px] text-slate-400 font-medium">JPEG, PNG, JPG, GIF or WEBP. Max size 5MB.</p>
                    </div>
                </div>
                <input type="hidden" id="slide-image-url">
            </div>

            <!-- Badge / Tag -->
            <div class="space-y-1">
                <label for="slide-badge" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Badge / Tag (Optional)</label>
                <input type="text" id="slide-badge" placeholder="e.g. ADMISSION OPEN 2026" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <!-- Title -->
            <div class="space-y-1">
                <label for="slide-title" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Title / Headline</label>
                <input type="text" id="slide-title" placeholder="e.g. Welcome to Academic Excellence" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label for="slide-desc" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Description</label>
                <textarea id="slide-desc" rows="2" placeholder="e.g. Empowering students to build a brighter future through practical education and digital innovation." class="w-full p-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold text-[10px] text-slate-700 leading-relaxed transition"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-2.5 px-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeSlideModal()" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-[9px] uppercase tracking-wider transition">
                Cancel
            </button>
            <button type="button" id="slide-modal-submit-btn" onclick="addSlideToList()" class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[9px] uppercase tracking-wider shadow-md shadow-orange-500/10 transition">
                Add to Slider
            </button>
        </div>
    </div>
</div>

<!-- View Slide Pop-up Modal -->
<div id="view-slide-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="py-4 px-6 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 class="text-sm font-bold tracking-wide">View Banner Slide</h3>
                <p class="text-[10px] text-orange-100 mt-0.5">Details of the selected banner slide.</p>
            </div>
            <button onclick="closeViewSlideModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <!-- Image Cover -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Slide Cover Image</label>
                <div class="h-44 w-full rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden shadow-inner relative">
                    <img id="view-slide-image" src="#" class="h-full w-full object-cover" />
                    <span id="view-slide-badge" class="absolute top-3 left-3 inline-flex text-[8px] font-black uppercase tracking-wider bg-orange-500 text-white px-2.5 py-1 rounded-full shadow-md">Badge</span>
                </div>
            </div>

            <!-- Title -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Title / Headline</label>
                <div id="view-slide-title" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800 break-all break-words overflow-x-hidden">
                    Title
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</label>
                <div id="view-slide-desc" class="w-full p-4 border border-slate-100 rounded-xl bg-slate-50 font-semibold text-xs text-slate-700 leading-relaxed whitespace-pre-line break-all break-words overflow-x-hidden">
                    Description
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Event Pop-up Modal -->
<div id="event-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="py-3 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 id="event-modal-title" class="text-xs font-bold tracking-wide">Add Event</h3>
                <p id="event-modal-desc" class="text-[9px] text-orange-100 mt-0.5 font-medium">Define event schedule details, location, and description.</p>
            </div>
            <button onclick="closeEventModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Fields -->
        <div class="p-4 px-5 overflow-y-auto space-y-3.5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="space-y-1">
                <label for="event-date" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Event Date</label>
                <input type="date" id="event-date" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label for="event-tag" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Category Tag</label>
                    <input type="text" id="event-tag" placeholder="e.g. Summit" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
                </div>
                <div class="space-y-1">
                    <label for="event-time" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Time</label>
                    <input type="time" id="event-time" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="event-location" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Location</label>
                <input type="text" id="event-location" placeholder="e.g. Main Auditorium" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <div class="space-y-1">
                <label for="event-title" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Event Title</label>
                <input type="text" id="event-title" placeholder="e.g. Global Alumni Summit 2026" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <div class="grid grid-cols-2 gap-3">
                <div class="space-y-1">
                    <label for="event-speaker" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Speaker Name</label>
                    <input type="text" id="event-speaker" placeholder="e.g. Dr. Sarah Jenkins" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
                </div>
                <div class="space-y-1">
                    <label for="event-speaker-role" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Speaker Designation</label>
                    <input type="text" id="event-speaker-role" placeholder="e.g. Director of Tech Innovation" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
                </div>
            </div>

            <div class="space-y-1">
                <label for="event-desc" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Description</label>
                <textarea id="event-desc" rows="3" placeholder="e.g. Connecting graduating seniors with active technical leaders in worldwide tech divisions..." class="w-full p-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold text-[10px] text-slate-700 leading-relaxed transition"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-2.5 px-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeEventModal()" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-[9px] uppercase tracking-wider transition">
                Cancel
            </button>
            <button type="button" id="event-modal-submit-btn" onclick="addEventToList()" class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[9px] uppercase tracking-wider shadow-md shadow-orange-500/10 transition">
                Add Event
            </button>
        </div>
    </div>
</div>

<!-- View Event Pop-up Modal -->
<div id="view-event-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="py-4 px-6 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 class="text-sm font-bold tracking-wide">View Event Details</h3>
                <p class="text-[10px] text-orange-100 mt-0.5">Details of the selected scheduled event.</p>
            </div>
            <button onclick="closeViewEventModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="grid grid-cols-2 gap-4">
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Date & Time</label>
                    <div id="view-event-datetime" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800">
                        Date & Time
                    </div>
                </div>
                <div class="space-y-1">
                    <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Category Tag / Location</label>
                    <div id="view-event-tagloc" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800">
                        Tag & Location
                    </div>
                </div>
            </div>

            <!-- Title -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Event Title</label>
                <div id="view-event-title" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800 break-all break-words overflow-x-hidden">
                    Title
                </div>
            </div>

            <!-- Speaker -->
            <div id="view-event-speaker-container" class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Speaker / Presenter</label>
                <div id="view-event-speaker" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800">
                    Speaker Info
                </div>
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Description</label>
                <div id="view-event-desc" class="w-full p-4 border border-slate-100 rounded-xl bg-slate-50 font-semibold text-xs text-slate-700 leading-relaxed whitespace-pre-line break-all break-words overflow-x-hidden">
                    Description
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Pillar Pop-up Modal -->
<div id="pillar-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col">
        <!-- Header -->
        <div class="py-3 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 id="pillar-modal-title" class="text-xs font-bold tracking-wide">Edit Item</h3>
                <p id="pillar-modal-desc" class="text-[9px] text-orange-100 mt-0.5 font-medium font-medium">Define title and description for this item.</p>
            </div>
            <button onclick="closePillarModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Fields -->
        <div class="p-4 px-5 overflow-y-auto space-y-3.5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <!-- Title -->
            <div class="space-y-1">
                <label for="pillar-title" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Title / Headline</label>
                <input type="text" id="pillar-title" placeholder="e.g. Nurturing Leaders" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <!-- Description -->
            <div class="space-y-1">
                <label for="pillar-desc" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Description</label>
                <textarea id="pillar-desc" rows="3" placeholder="e.g. To establish a global standard in education..." class="w-full p-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold text-[10px] text-slate-700 leading-relaxed transition"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-2.5 px-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closePillarModal()" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-[9px] uppercase tracking-wider transition">
                Cancel
            </button>
            <button type="button" onclick="savePillarItemChanges()" class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[9px] uppercase tracking-wider shadow-md shadow-orange-500/10 transition">
                Save Changes
            </button>
        </div>
    </div>
</div>

<!-- Add/Edit Achievement Card Modal -->
<div id="achievement-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col">
        <!-- Header -->
        <div class="py-3 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 id="achievement-modal-title" class="text-xs font-bold tracking-wide">Add Achievement</h3>
                <p class="text-[9px] text-orange-100 mt-0.5 font-medium">Set the category tag, headline, and description.</p>
            </div>
            <button onclick="closeAchievementModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Fields -->
        <div class="p-4 px-5 space-y-3.5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <div class="space-y-1">
                <label for="ach-tag" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Category Badge / Tag</label>
                <input type="text" id="ach-tag" placeholder="e.g. Award, Academics, Sports"
                    class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>
            <div class="space-y-1">
                <label for="ach-title" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Title / Headline</label>
                <input type="text" id="ach-title" placeholder="e.g. Best School Award 2025"
                    class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>
            <div class="space-y-1">
                <label for="ach-desc" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Description</label>
                <textarea id="ach-desc" rows="3" placeholder="e.g. A brief description of the achievement..."
                    class="w-full p-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-semibold text-[10px] text-slate-700 leading-relaxed transition"></textarea>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-2.5 px-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeAchievementModal()" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-[9px] uppercase tracking-wider transition">
                Cancel
            </button>
            <button type="button" id="ach-submit-btn" onclick="saveAchievementCard()" class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[9px] uppercase tracking-wider shadow-md shadow-orange-500/10 transition">
                Add Achievement
            </button>
        </div>
    </div>
</div>

<!-- Loader Overlay for Global Actions -->
<div id="global-loader-overlay" class="fixed inset-0 bg-slate-900/40 backdrop-blur-[1px] flex flex-col items-center justify-center hidden z-[200]">
    <div class="bg-white p-6 rounded-2xl shadow-xl flex flex-col items-center gap-3">
        <div class="h-10 w-10 border-4 border-slate-200 border-t-[#ff6c00] rounded-full animate-spin"></div>
        <span id="global-loader-text" class="text-xs font-bold text-slate-700 uppercase tracking-widest">Saving Changes...</span>
    </div>
</div>

<!-- Choose Template Pop-up Modal -->
<div id="template-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-slate-50 w-full max-w-3xl rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="py-3.5 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 class="text-xs font-bold tracking-wide">Choose a Website Template</h3>
                <p class="text-[9px] text-orange-100 mt-0.5 font-medium">Select a premium high-converting layout style to instantly activate it for your public website.</p>
            </div>
            <button onclick="closeTemplateModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Templates Content List (Scrollable) -->
        <div class="p-2 overflow-y-auto">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-2">
                @foreach($templates as $id => $tpl)
                    @php $isActive = $institute->template_id == $id; @endphp
                    <div id="template-card-{{ $id }}" class="template-card relative border border-slate-200 {{ $isActive ? 'border-[#ff6c00] bg-orange-50/10 ring-2 ring-[#ff6c00]/25' : 'hover:border-slate-300' }} rounded-2xl p-3.5 flex flex-col justify-between transition-all duration-300 bg-white">
                        <div class="space-y-2.5">
                            <div class="h-20 rounded-xl bg-gradient-to-br {{ $tpl['gradient'] }} relative overflow-hidden flex items-center justify-center shadow-inner">
                                <span class="text-white font-black text-[10px] uppercase tracking-widest bg-black/25 px-2.5 py-0.5 rounded-full backdrop-blur-md">Template {{ $id }}</span>
                            </div>
                            <div>
                                <div class="flex items-center justify-between gap-1">
                                    <h4 class="text-[11px] font-extrabold text-slate-800 leading-tight">{{ $tpl['name'] }}</h4>
                                    <span class="inline-flex items-center gap-0.5 text-[7px] font-black uppercase tracking-widest px-1.5 py-0.5 rounded-full bg-slate-50 text-slate-500 border border-slate-100 whitespace-nowrap">{{ $tpl['tag'] }}</span>
                                </div>
                                <p class="text-[8px] text-slate-400 font-semibold mt-1 leading-relaxed">
                                    {{ $tpl['desc'] }}
                                </p>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-1.5 mt-3.5 pt-2.5 border-t border-slate-100">
                            <a href="{{ route('institute.templates.preview', $id) }}" target="_blank"
                                class="flex-1 py-1.5 bg-slate-50 hover:bg-slate-100 text-slate-700 rounded-lg font-bold text-[8px] uppercase tracking-wider text-center transition">
                                Preview
                            </a>
                            <button type="button" onclick="activateTemplate({{ $id }})" id="btn-activate-{{ $id }}"
                                class="btn-activate flex-1 py-1.5 rounded-lg font-bold text-[8px] uppercase tracking-wider text-center transition {{ $isActive ? 'bg-slate-100 text-slate-400 cursor-not-allowed' : 'bg-[#ff6c00] hover:bg-[#e05f00] text-white shadow-md shadow-orange-500/10' }}"
                                {{ $isActive ? 'disabled' : '' }}>
                                {{ $isActive ? 'Active' : 'Activate' }}
                            </button>
                        </div>

                        <div class="badge-active absolute -top-1.5 -right-1.5 bg-[#ff6c00] text-white p-0.5 rounded-full shadow-md border-2 border-white {{ $isActive ? '' : 'hidden' }}">
                            <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" d="M5 13l4 4L19 7" />
                            </svg>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Gallery Pop-up Modal -->
<div id="gallery-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-md rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col">
        <!-- Header -->
        <div class="py-3 px-5 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 id="gallery-modal-title" class="text-xs font-bold tracking-wide">Add Gallery Item</h3>
                <p id="gallery-modal-desc" class="text-[9px] text-orange-100 mt-0.5 font-medium">Define gallery image, custom title, and tag.</p>
            </div>
            <button onclick="closeGalleryModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Form Fields -->
        <div class="p-4 px-5 overflow-y-auto space-y-3.5 flex-1" style="scrollbar-width: none; -ms-overflow-style: none;">
            <!-- Image Selector & Preview -->
            <div class="space-y-1.5">
                <label class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Gallery Image</label>
                <div class="flex items-center gap-3">
                    <div id="gallery-image-preview-box" class="h-14 w-24 rounded-xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden shrink-0 shadow-inner relative group">
                        <span id="gallery-preview-placeholder" class="text-[8px] text-slate-400 font-bold uppercase tracking-wider text-center px-1">No Image</span>
                        <img id="gallery-modal-image-preview" src="#" class="h-full w-full object-cover hidden" />
                        <div id="gallery-image-upload-spinner" class="absolute inset-0 bg-white/80 backdrop-blur-[1px] flex items-center justify-center hidden">
                            <div class="h-5 w-5 border-2 border-slate-200 border-t-[#ff6c00] rounded-full animate-spin"></div>
                        </div>
                    </div>
                    <div class="flex-1 space-y-0.5">
                        <input type="file" id="gallery-image-file" accept="image/*" class="hidden" onchange="handleGalleryModalImageUpload(event)">
                        <button type="button" onclick="document.getElementById('gallery-image-file').click()" class="bg-orange-50 hover:bg-orange-100 text-orange-600 px-3.5 py-1.5 rounded-lg font-bold text-[9px] uppercase tracking-wider border border-orange-100 transition-colors">
                            Choose Image
                        </button>
                        <p class="text-[8px] text-slate-400 font-medium">JPEG, PNG, JPG, GIF or WEBP. Max size 5MB.</p>
                    </div>
                </div>
                <input type="hidden" id="gallery-image-url">
            </div>

            <!-- Tag / Category -->
            <div class="space-y-1">
                <label for="gallery-tag" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Tag / Category (e.g. Sports, Labs)</label>
                <input type="text" id="gallery-tag" placeholder="e.g. Laboratory" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>

            <!-- Title -->
            <div class="space-y-1">
                <label for="gallery-title" class="block text-[10px] font-bold text-slate-700 uppercase tracking-wider">Title / Description</label>
                <input type="text" id="gallery-title" placeholder="e.g. Chemistry Research Lab" class="w-full h-9 px-3 border border-slate-200 rounded-xl bg-slate-50 focus:bg-white focus:outline-none focus:ring-2 focus:ring-orange-500/20 focus:border-orange-500 font-bold text-[10px] text-slate-800 transition">
            </div>
        </div>

        <!-- Footer -->
        <div class="py-2.5 px-5 border-t border-slate-100 bg-slate-50 flex items-center justify-end gap-2.5 shrink-0">
            <button type="button" onclick="closeGalleryModal()" class="px-3.5 py-1.5 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-lg font-bold text-[9px] uppercase tracking-wider transition">
                Cancel
            </button>
            <button type="button" id="gallery-modal-submit-btn" onclick="addGalleryToList()" class="px-4 py-1.5 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-lg font-bold text-[9px] uppercase tracking-wider shadow-md shadow-orange-500/10 transition">
                Add Item
            </button>
        </div>
    </div>
</div>

<!-- View Gallery Item Pop-up Modal -->
<div id="view-gallery-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4">
    <div class="bg-white w-full max-w-lg rounded-3xl shadow-2xl overflow-hidden animate-in fade-in zoom-in duration-200 relative flex flex-col max-h-[90vh]">
        <!-- Header -->
        <div class="py-4 px-6 bg-[#ff6c00] flex items-center justify-between text-white relative shrink-0">
            <div>
                <h3 class="text-sm font-bold tracking-wide">View Gallery Item</h3>
                <p class="text-[10px] text-orange-100 mt-0.5">Details of the selected gallery item.</p>
            </div>
            <button onclick="closeViewGalleryModal()" class="text-orange-100 hover:text-white transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Content (Scrollable) -->
        <div class="p-6 overflow-y-auto space-y-5 flex-1">
            <!-- Image Cover -->
            <div class="space-y-2">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Image Preview</label>
                <div class="h-44 w-full rounded-2xl bg-slate-50 border border-slate-200 flex items-center justify-center overflow-hidden shadow-inner relative">
                    <img id="view-gallery-image" src="#" class="h-full w-full object-cover" />
                    <span id="view-gallery-badge" class="absolute top-3 left-3 inline-flex text-[8px] font-black uppercase tracking-wider bg-orange-500 text-white px-2.5 py-1 rounded-full shadow-md">Tag</span>
                </div>
            </div>

            <!-- Title -->
            <div class="space-y-1">
                <label class="block text-xs font-bold text-slate-400 uppercase tracking-wider">Title / Description</label>
                <div id="view-gallery-title" class="w-full px-4 py-3 border border-slate-100 rounded-xl bg-slate-50 font-bold text-xs text-slate-800 break-all break-words overflow-x-hidden">
                    Title
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="py-3 px-6 border-t border-slate-100 bg-slate-50 flex items-center justify-end shrink-0">
            <button type="button" onclick="closeViewGalleryModal()" class="px-4 py-2 bg-slate-200 hover:bg-slate-300 text-slate-700 rounded-xl font-bold text-xs uppercase tracking-wider transition">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Toast Container -->
<div id="toast-container" class="fixed top-6 right-6 z-[10000] flex flex-col gap-3 pointer-events-none"></div>

<script>
    // Initialize hero slides data
    let heroSlides = @json($content->hero_slides ?: []);
    let editingSlideIndex = -1;

    // Initialize pillars data
    let pillars = {
        vision: @json($vision),
        mission: @json($mission),
        values: @json($values)
    };
    let activePillarTab = 'vision';
    let editingPillarIndex = -1;

    // Initialize achievements data
    let achievementsData = @json($achievementsData);
    if (!achievementsData.items) achievementsData.items = [];
    let editingAchievementIndex = -1; // -1 = adding new

    // Initialize gallery items data
    let galleryList = @json($content->gallery ?: []);
    let editingGalleryIndex = -1;

    // Initialize events data
    let eventsList = @json($content->events ?: []);
    let editingEventIndex = -1;

    document.addEventListener('DOMContentLoaded', () => {
        renderSliders();
        renderPillars();
        renderAchievements();
        renderGallery();
        renderEvents();
    });

    // Copy website URL helper
    function copyWebsiteUrl(url) {
        navigator.clipboard.writeText(url).then(() => {
            showToastMessage('Website URL copied to clipboard!', 'success');
        }).catch(() => {
            showToastMessage('Could not copy URL.', 'error');
        });
    }

    // Render current slides list on UI
    function renderSliders() {
        const container = document.getElementById('sliders-container');
        const emptyState = document.getElementById('sliders-empty-state');
        
        // Handle Add Button State based on slide count limit (max 5)
        const addBtn = document.getElementById('add-slide-btn');
        if (addBtn) {
            if (heroSlides.length >= 5) {
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
                addBtn.setAttribute('title', 'Maximum of 5 slides reached');
            } else {
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                addBtn.removeAttribute('title');
            }
        }
        
        container.innerHTML = '';
        
        if (heroSlides.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        heroSlides.forEach((slide, index) => {
            const card = document.createElement('div');
            card.className = 'flex flex-col justify-between bg-white border border-slate-200/60 hover:border-[#ff6c00]/50 hover:ring-2 hover:ring-[#ff6c00]/10 rounded-2xl p-3 transition duration-300 hover:shadow-md hover:scale-[1.01] cursor-pointer relative overflow-hidden group';
            
            card.onclick = (e) => {
                if (e.target.closest('.btn-delete-slide') || e.target.closest('.btn-edit-slide')) {
                    return;
                }
                openViewSlideModal(index);
            };

            card.innerHTML = `
                <div class="space-y-2.5">
                    <!-- Cover Image -->
                    <div class="h-28 rounded-xl overflow-hidden shadow-inner border border-slate-100 bg-slate-50 relative">
                        <img src="${slide.image}" class="h-full w-full object-cover">
                        ${slide.badge ? `<span class="absolute top-2 left-2 inline-flex text-[7px] font-black uppercase tracking-wider bg-[#ff6c00] text-white px-2 py-0.5 rounded-full shadow-sm">${slide.badge}</span>` : ''}
                        
                        <!-- Overlay Edit & Delete Buttons -->
                        <div class="absolute top-2 right-2 flex items-center gap-1 z-10 opacity-90 group-hover:opacity-100 transition">
                            <button type="button" onclick="event.stopPropagation(); openEditSlideModal(${index})" class="btn-edit-slide p-1.5 bg-white/95 hover:bg-white text-orange-600 hover:text-orange-700 rounded-lg shadow-sm transition" title="Edit Slide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button type="button" onclick="event.stopPropagation(); deleteSlide(${index})" class="btn-delete-slide p-1.5 bg-white/95 hover:bg-white text-rose-600 hover:text-rose-700 rounded-lg shadow-sm transition" title="Delete Slide">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Details -->
                    <div class="space-y-1">
                        <h4 class="text-xs font-black text-slate-800 line-clamp-1 leading-tight group-hover:text-[#ff6c00] transition-colors" title="${slide.title}">${slide.title}</h4>
                        <p class="text-[9px] text-slate-400 font-bold leading-normal line-clamp-2" title="${slide.desc || ''}">${slide.desc || 'No description provided.'}</p>
                    </div>
                </div>
                
            
            `;
            container.appendChild(card);
        });
    }

    // Activate selected template via AJAX
    async function activateTemplate(id) {
        showGlobalLoader('Activating Template...');
        
        try {
            const response = await fetch('{{ route("institute.profile.website.template.update") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ template_id: id })
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                showToastMessage(result.message || 'Template updated successfully!', 'success');
                
                // Toggle active card styles
                document.querySelectorAll('.template-card').forEach(card => {
                    card.classList.remove('border-[#ff6c00]', 'bg-orange-50/10');
                    card.classList.add('border-slate-200');
                });
                document.querySelectorAll('.badge-active').forEach(b => b.classList.add('hidden'));
                document.querySelectorAll('.btn-activate').forEach(btn => {
                    btn.disabled = false;
                    btn.innerText = 'Activate';
                    btn.className = 'btn-activate flex-1 py-2 bg-[#ff6c00] hover:bg-[#e05f00] text-white rounded-xl font-bold text-[9px] uppercase tracking-wider text-center transition';
                });

                const activeCard = document.getElementById(`template-card-${id}`);
                if (activeCard) {
                    activeCard.classList.remove('border-slate-200');
                    activeCard.classList.add('border-[#ff6c00]', 'bg-orange-50/10');
                    activeCard.querySelector('.badge-active').classList.remove('hidden');
                }
                
                const activeBtn = document.getElementById(`btn-activate-${id}`);
                if (activeBtn) {
                    activeBtn.disabled = true;
                    activeBtn.innerText = 'Active';
                    activeBtn.className = 'btn-activate flex-1 py-2 bg-slate-100 text-slate-400 rounded-xl font-bold text-[9px] uppercase tracking-wider text-center cursor-not-allowed';
                }

                // Sync active template info next to button on main panel
                const templateNames = {
                    1: 'Classic Academic',
                    2: 'Mint Glassmorphic',
                    3: 'Cyber Indigo',
                    4: 'Royal Corporate',
                    5: 'Futuristic Neon'
                };
                const templateTags = {
                    1: 'Classic',
                    2: 'Glass',
                    3: 'Interactive',
                    4: 'Corporate',
                    5: 'Modern'
                };
                const templateGradients = {
                    1: ['from-emerald-500', 'to-teal-600'],
                    2: ['from-teal-400', 'to-emerald-500'],
                    3: ['from-indigo-600', 'to-purple-600'],
                    4: ['from-blue-600', 'to-indigo-600'],
                    5: ['from-purple-600', 'to-pink-600']
                };

                document.getElementById('active-template-name').innerText = templateNames[id] || 'Classic Academic';
                document.getElementById('active-template-badge').innerText = templateTags[id] || 'Classic';
                
                const iconDiv = document.getElementById('active-template-icon');
                if (iconDiv) {
                    iconDiv.innerText = `T${id}`;
                    Object.values(templateGradients).flat().forEach(cls => iconDiv.classList.remove(cls));
                    (templateGradients[id] || templateGradients[1]).forEach(cls => iconDiv.classList.add(cls));
                }

                // Close template modal
                closeTemplateModal();
            } else {
                showToastMessage(result.message || 'Failed to update template', 'error');
            }
        } catch (error) {
            console.error('Error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    // Modal Operations
    function openAddSlideModal() {
        if (heroSlides.length >= 5) {
            showToastMessage('You can upload a maximum of 5 slides.', 'error');
            return;
        }
        editingSlideIndex = -1;
        
        // Update header & submit button labels
        document.getElementById('slide-modal-title').innerText = 'Add Banner Slide';
        document.getElementById('slide-modal-submit-btn').innerText = 'Add to Slider';
        
        // Reset form
        document.getElementById('slide-image-file').value = '';
        document.getElementById('slide-image-url').value = '';
        document.getElementById('slide-badge').value = '';
        document.getElementById('slide-title').value = '';
        document.getElementById('slide-desc').value = '';
        
        // Hide preview
        document.getElementById('modal-image-preview').classList.add('hidden');
        document.getElementById('modal-image-preview').src = '#';
        document.getElementById('preview-placeholder').classList.remove('hidden');
        
        document.getElementById('slide-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function openEditSlideModal(index) {
        editingSlideIndex = index;
        const slide = heroSlides[index];
        
        // Update header & submit button labels
        document.getElementById('slide-modal-title').innerText = 'Edit Banner Slide';
        document.getElementById('slide-modal-submit-btn').innerText = 'Save Changes';
        
        // Reset form file selector
        document.getElementById('slide-image-file').value = '';
        
        // Populate fields
        document.getElementById('slide-image-url').value = slide.image;
        document.getElementById('slide-badge').value = slide.badge || '';
        document.getElementById('slide-title').value = slide.title || '';
        document.getElementById('slide-desc').value = slide.desc || '';
        
        // Show preview
        const previewImg = document.getElementById('modal-image-preview');
        previewImg.src = slide.image;
        previewImg.classList.remove('hidden');
        document.getElementById('preview-placeholder').classList.add('hidden');
        
        document.getElementById('slide-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeSlideModal() {
        document.getElementById('slide-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function openTemplateModal() {
        document.getElementById('template-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeTemplateModal() {
        document.getElementById('template-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function openViewSlideModal(index) {
        const slide = heroSlides[index];
        
        document.getElementById('view-slide-image').src = slide.image;
        
        const badgeEl = document.getElementById('view-slide-badge');
        if (slide.badge) {
            badgeEl.innerText = slide.badge;
            badgeEl.classList.remove('hidden');
        } else {
            badgeEl.classList.add('hidden');
        }
        
        document.getElementById('view-slide-title').innerText = slide.title || 'Untitled Slide';
        document.getElementById('view-slide-desc').innerText = slide.desc || 'No description provided.';
        

        
        document.getElementById('view-slide-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeViewSlideModal() {
        document.getElementById('view-slide-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    // Handle AJAX image uploading inside modal
    async function handleModalImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        const spinner = document.getElementById('image-upload-spinner');
        spinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("institute.profile.website.upload") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                document.getElementById('slide-image-url').value = data.url;
                
                // Show preview
                const previewImg = document.getElementById('modal-image-preview');
                previewImg.src = data.url;
                previewImg.classList.remove('hidden');
                document.getElementById('preview-placeholder').classList.add('hidden');
                
                showToastMessage('Image uploaded successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Image upload failed.', 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            showToastMessage('Failed to upload image.', 'error');
        } finally {
            spinner.classList.add('hidden');
        }
    }

    // Add slide to local array list
    function addSlideToList() {
        const imageUrl = document.getElementById('slide-image-url').value;
        const badge = document.getElementById('slide-badge').value.trim();
        const title = document.getElementById('slide-title').value.trim();
        const desc = document.getElementById('slide-desc').value.trim();

        if (!imageUrl) {
            showToastMessage('Please upload a cover image first.', 'error');
            return;
        }
        if (!title) {
            showToastMessage('Please enter a headline/title.', 'error');
            return;
        }

        const slideData = {
            image: imageUrl,
            badge: badge,
            title: title,
            desc: desc
        };

        if (editingSlideIndex !== -1) {
            heroSlides[editingSlideIndex] = slideData;
        } else {
            heroSlides.push(slideData);
        }

        renderSliders();
        closeSlideModal();
        saveHeroSliders();
    }

    // Delete slide from local list
    function deleteSlide(index) {
        showConfirmModal(
            'Delete Slide',
            'Are you sure you want to remove this slide? This action cannot be undone.',
            function () {
                heroSlides.splice(index, 1);
                renderSliders();
                saveHeroSliders();
            },
            'Delete',
            'bg-[#ff6c00] hover:bg-[#e05f00] shadow-orange-950/20',
            null,
            'Remove Banner Slide',
            'orange'
        );
    }

    // Save final slide collection to Database
    async function saveHeroSliders() {
        showGlobalLoader('Saving Hero Section sliders...');

        try {
            const response = await fetch('{{ route("institute.profile.website.hero.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ hero_slides: heroSlides })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Hero slides saved successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save settings.', 'error');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    function switchPillarTab(tab) {
        activePillarTab = tab;
        
        // Update tab buttons styles
        ['vision', 'mission', 'values'].forEach(t => {
            const btn = document.getElementById(`tab-btn-${t}`);
            if (btn) {
                if (t === tab) {
                    btn.classList.remove('border-transparent', 'text-slate-500', 'hover:text-slate-700');
                    btn.classList.add('border-[#ff6c00]', 'text-[#ff6c00]');
                } else {
                    btn.classList.remove('border-[#ff6c00]', 'text-[#ff6c00]');
                    btn.classList.add('border-transparent', 'text-slate-500', 'hover:text-slate-700');
                }
            }
        });

        renderPillars();
    }

    // Render website pillars list (Vision, Mission, Values)
    function renderPillars() {
        const container = document.getElementById('pillars-container');
        const emptyState = document.getElementById('pillars-empty-state');
        if (!container) return;
        container.innerHTML = '';

        const list = pillars[activePillarTab] || [];
        
        if (list.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        emptyState.classList.add('hidden');

        list.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'flex flex-col justify-between bg-white border border-slate-200/60 hover:border-[#ff6c00]/50 hover:ring-2 hover:ring-[#ff6c00]/10 rounded-2xl p-4 transition duration-300 hover:shadow-md relative group';
            
            card.innerHTML = `
                <div class="space-y-2">
                    <div class="flex items-start justify-between gap-2">
                        <h4 class="text-xs font-black text-slate-800 line-clamp-1 leading-tight group-hover:text-[#ff6c00] transition-colors" title="${item.title}">${item.title}</h4>
                        <div class="flex items-center gap-1 shrink-0">
                            <button type="button" onclick="openEditPillarItemModal(${index})" class="p-1.5 bg-slate-50 hover:bg-slate-100 text-orange-600 rounded-lg border border-slate-200/40 transition" title="Edit Item">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button type="button" onclick="deletePillarItem(${index})" class="p-1.5 bg-slate-50 hover:bg-slate-100 text-rose-600 rounded-lg border border-slate-200/40 transition" title="Delete Item">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    <p class="text-[9px] text-slate-400 font-semibold leading-relaxed line-clamp-4" title="${item.desc || ''}">${item.desc || 'No description.'}</p>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function openAddPillarItemModal() {
        editingPillarIndex = -1;
        document.getElementById('pillar-modal-title').innerText = `Add ${activePillarTab.charAt(0).toUpperCase() + activePillarTab.slice(1)} Item`;
        document.getElementById('pillar-title').value = '';
        document.getElementById('pillar-desc').value = '';
        document.getElementById('pillar-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function openEditPillarItemModal(index) {
        editingPillarIndex = index;
        const item = pillars[activePillarTab][index];
        document.getElementById('pillar-modal-title').innerText = `Edit ${activePillarTab.charAt(0).toUpperCase() + activePillarTab.slice(1)} Item`;
        document.getElementById('pillar-title').value = item.title;
        document.getElementById('pillar-desc').value = item.desc;
        document.getElementById('pillar-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closePillarModal() {
        document.getElementById('pillar-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function savePillarItemChanges() {
        const title = document.getElementById('pillar-title').value.trim();
        const desc = document.getElementById('pillar-desc').value.trim();

        if (!title) {
            showToastMessage('Please enter a title.', 'error');
            return;
        }
        if (!desc) {
            showToastMessage('Please enter a description.', 'error');
            return;
        }

        const itemData = { title, desc };

        if (!pillars[activePillarTab]) {
            pillars[activePillarTab] = [];
        }

        if (editingPillarIndex !== -1) {
            pillars[activePillarTab][editingPillarIndex] = itemData;
        } else {
            pillars[activePillarTab].push(itemData);
        }

        renderPillars();
        closePillarModal();
        savePillars();
    }

    function deletePillarItem(index) {
        showConfirmModal(
            'Delete Item',
            'Are you sure you want to remove this item? This action cannot be undone.',
            function () {
                pillars[activePillarTab].splice(index, 1);
                renderPillars();
                savePillars();
            },
            'Delete',
            'bg-[#ff6c00] hover:bg-[#e05f00] shadow-orange-950/20',
            null,
            'Remove Item',
            'orange'
        );
    }

    // Save pillars collection to Database via AJAX
    async function savePillars() {
        showGlobalLoader('Saving Vision, Mission & Values...');

        try {
            const response = await fetch('{{ route("institute.profile.website.pillars.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify(pillars)
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Pillars saved successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save settings.', 'error');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    // ───────────────────────────────────────────────
    //  ACHIEVEMENTS SECTION
    // ───────────────────────────────────────────────

    function renderAchievements() {
        const container  = document.getElementById('achievements-container');
        const emptyState = document.getElementById('achievements-empty-state');
        if (!container) return;
        container.innerHTML = '';

        if (achievementsData.items.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        emptyState.classList.add('hidden');

        const emojis = ['🏆', '🎓', '🏅', '🌟', '🥇', '🎯', '📚', '⭐'];

        achievementsData.items.forEach((item, index) => {
            const emoji = emojis[index % emojis.length];
            const card  = document.createElement('div');
            card.className = 'flex flex-col justify-between bg-white border border-slate-200/60 hover:border-[#ff6c00]/50 hover:ring-2 hover:ring-[#ff6c00]/10 rounded-2xl p-3 transition duration-300 hover:shadow-md hover:scale-[1.01] relative overflow-hidden group';
            card.innerHTML = `
                <div class="space-y-2">
                    <div class="flex items-center justify-between">
                        <span class="text-lg">${emoji}</span>
                        <div class="flex items-center gap-1 opacity-90 group-hover:opacity-100">
                            <button type="button" onclick="event.stopPropagation(); openEditAchievementModal(${index})" class="p-1 bg-white hover:bg-slate-100 text-orange-600 rounded-lg shadow-sm transition border border-slate-100" title="Edit">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                            </button>
                            <button type="button" onclick="event.stopPropagation(); deleteAchievement(${index})" class="p-1 bg-white hover:bg-rose-50 text-rose-500 rounded-lg shadow-sm transition border border-slate-100" title="Delete">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            </button>
                        </div>
                    </div>
                    ${item.tag ? `<span class="inline-flex text-[7px] font-black uppercase tracking-widest text-[#ff6c00] px-1.5 py-0.5 rounded-md bg-orange-50 border border-orange-100/50">${item.tag}</span>` : ''}
                    <h4 class="text-[11px] font-extrabold text-slate-800 leading-tight line-clamp-2">${item.title || 'Untitled'}</h4>
                    <p class="text-[9px] text-slate-400 font-semibold leading-relaxed line-clamp-3">${item.desc || ''}</p>
                </div>
            `;
            container.appendChild(card);
        });
    }

    function openAddAchievementModal() {
        editingAchievementIndex = -1;
        document.getElementById('achievement-modal-title').innerText = 'Add Achievement';
        document.getElementById('ach-submit-btn').innerText = 'Add Achievement';
        document.getElementById('ach-tag').value   = '';
        document.getElementById('ach-title').value = '';
        document.getElementById('ach-desc').value  = '';
        document.getElementById('achievement-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function openEditAchievementModal(index) {
        editingAchievementIndex = index;
        const item = achievementsData.items[index] || {};
        document.getElementById('achievement-modal-title').innerText = `Edit Achievement ${index + 1}`;
        document.getElementById('ach-submit-btn').innerText = 'Save Changes';
        document.getElementById('ach-tag').value   = item.tag   || '';
        document.getElementById('ach-title').value = item.title || '';
        document.getElementById('ach-desc').value  = item.desc  || '';
        document.getElementById('achievement-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeAchievementModal() {
        document.getElementById('achievement-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function saveAchievementCard() {
        const tag   = document.getElementById('ach-tag').value.trim();
        const title = document.getElementById('ach-title').value.trim();
        const desc  = document.getElementById('ach-desc').value.trim();
        if (!title) { showToastMessage('Please enter a title / headline.', 'error'); return; }

        if (editingAchievementIndex === -1) {
            achievementsData.items.push({ tag, title, desc });
        } else {
            achievementsData.items[editingAchievementIndex] = { tag, title, desc };
        }
        renderAchievements();
        closeAchievementModal();
        saveAchievements();
    }

    function deleteAchievement(index) {
        showConfirmModal(
            'Delete Achievement',
            'Are you sure you want to remove this achievement? This action cannot be undone.',
            function () {
                achievementsData.items.splice(index, 1);
                renderAchievements();
                saveAchievements();
            },
            'Delete',
            'bg-[#ff6c00] hover:bg-[#e05f00] shadow-orange-950/20',
            null,
            'Remove Achievement',
            'orange'
        );
    }

    async function saveAchievements() {
        showGlobalLoader('Saving Achievements...');
        try {
            const response = await fetch('{{ route("institute.profile.website.achievements.save") }}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: JSON.stringify(achievementsData)
            });
            const data = await response.json();
            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Achievements saved!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save achievements.', 'error');
            }
        } catch (error) {
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    // ───────────────────────────────────────────────
    //  GALLERY SECTION
    // ───────────────────────────────────────────────

    // Render current gallery items on UI
    function renderGallery() {
        const container = document.getElementById('gallery-container');
        const emptyState = document.getElementById('gallery-empty-state');
        if (!container) return;
        
        // Handle Add Button State based on gallery count limit (max 20)
        const addBtn = document.getElementById('add-gallery-btn');
        if (addBtn) {
            if (galleryList.length >= 20) {
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
                addBtn.setAttribute('title', 'Maximum of 20 gallery items reached');
            } else {
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                addBtn.removeAttribute('title');
            }
        }
        
        container.innerHTML = '';
        
        if (galleryList.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        galleryList.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'flex flex-col justify-between bg-white border border-slate-200/60 hover:border-[#ff6c00]/50 hover:ring-2 hover:ring-[#ff6c00]/10 rounded-2xl p-3 transition duration-300 hover:shadow-md hover:scale-[1.01] cursor-pointer relative overflow-hidden group';
            
            card.onclick = (e) => {
                if (e.target.closest('.btn-delete-gallery') || e.target.closest('.btn-edit-gallery')) {
                    return;
                }
                openViewGalleryModal(index);
            };

            card.innerHTML = `
                <div class="space-y-2.5">
                    <!-- Cover Image -->
                    <div class="h-28 rounded-xl overflow-hidden shadow-inner border border-slate-100 bg-slate-50 relative">
                        <img src="${item.image}" class="h-full w-full object-cover">
                        ${item.tag ? `<span class="absolute top-2 left-2 inline-flex text-[7px] font-black uppercase tracking-wider bg-[#ff6c00] text-white px-2 py-0.5 rounded-full shadow-sm">${item.tag}</span>` : ''}
                        
                        <!-- Overlay Edit & Delete Buttons -->
                        <div class="absolute top-2 right-2 flex items-center gap-1 z-10 opacity-90 group-hover:opacity-100 transition">
                            <button type="button" onclick="event.stopPropagation(); openEditGalleryModal(${index})" class="btn-edit-gallery p-1.5 bg-white/95 hover:bg-white text-orange-600 hover:text-orange-700 rounded-lg shadow-sm transition" title="Edit Gallery Item">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button type="button" onclick="event.stopPropagation(); deleteGalleryItem(${index})" class="btn-delete-gallery p-1.5 bg-white/95 hover:bg-white text-rose-600 hover:text-rose-700 rounded-lg shadow-sm transition" title="Delete Gallery Item">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Details -->
                    <div class="space-y-1">
                        <h4 class="text-xs font-black text-slate-800 line-clamp-1 leading-tight group-hover:text-[#ff6c00] transition-colors" title="${item.title}">${item.title}</h4>
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Modal Operations for Gallery
    function openAddGalleryModal() {
        if (galleryList.length >= 20) {
            showToastMessage('You can upload a maximum of 20 gallery items.', 'error');
            return;
        }
        editingGalleryIndex = -1;
        
        // Update header & submit button labels
        document.getElementById('gallery-modal-title').innerText = 'Add Gallery Item';
        document.getElementById('gallery-modal-submit-btn').innerText = 'Add Item';
        
        // Reset form
        document.getElementById('gallery-image-file').value = '';
        document.getElementById('gallery-image-url').value = '';
        document.getElementById('gallery-tag').value = '';
        document.getElementById('gallery-title').value = '';
        
        // Hide preview
        document.getElementById('gallery-modal-image-preview').classList.add('hidden');
        document.getElementById('gallery-modal-image-preview').src = '#';
        document.getElementById('gallery-preview-placeholder').classList.remove('hidden');
        
        document.getElementById('gallery-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function openEditGalleryModal(index) {
        editingGalleryIndex = index;
        const item = galleryList[index];
        
        // Update header & submit button labels
        document.getElementById('gallery-modal-title').innerText = 'Edit Gallery Item';
        document.getElementById('gallery-modal-submit-btn').innerText = 'Save Changes';
        
        // Reset form file selector
        document.getElementById('gallery-image-file').value = '';
        
        // Populate fields
        document.getElementById('gallery-image-url').value = item.image;
        document.getElementById('gallery-tag').value = item.tag || '';
        document.getElementById('gallery-title').value = item.title || '';
        
        // Show preview
        const previewImg = document.getElementById('gallery-modal-image-preview');
        previewImg.src = item.image;
        previewImg.classList.remove('hidden');
        document.getElementById('gallery-preview-placeholder').classList.add('hidden');
        
        document.getElementById('gallery-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeGalleryModal() {
        document.getElementById('gallery-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function openViewGalleryModal(index) {
        const item = galleryList[index];
        
        document.getElementById('view-gallery-image').src = item.image;
        
        const badgeEl = document.getElementById('view-gallery-badge');
        if (item.tag) {
            badgeEl.innerText = item.tag;
            badgeEl.classList.remove('hidden');
        } else {
            badgeEl.classList.add('hidden');
        }
        
        document.getElementById('view-gallery-title').innerText = item.title || 'Untitled Item';
        
        document.getElementById('view-gallery-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    // Handle AJAX image uploading inside gallery modal
    async function handleGalleryModalImageUpload(event) {
        const file = event.target.files[0];
        if (!file) return;

        const formData = new FormData();
        formData.append('image', file);

        const spinner = document.getElementById('gallery-image-upload-spinner');
        spinner.classList.remove('hidden');

        try {
            const response = await fetch('{{ route("institute.profile.website.upload") }}', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                document.getElementById('gallery-image-url').value = data.url;
                
                // Show preview
                const previewImg = document.getElementById('gallery-modal-image-preview');
                previewImg.src = data.url;
                previewImg.classList.remove('hidden');
                document.getElementById('gallery-preview-placeholder').classList.add('hidden');
                
                showToastMessage('Gallery image uploaded successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Image upload failed.', 'error');
            }
        } catch (error) {
            console.error('Upload error:', error);
            showToastMessage('Failed to upload image.', 'error');
        } finally {
            spinner.classList.add('hidden');
        }
    }

    // Add/Update gallery item locally
    function addGalleryToList() {
        const imageUrl = document.getElementById('gallery-image-url').value;
        const tag = document.getElementById('gallery-tag').value.trim();
        const title = document.getElementById('gallery-title').value.trim();

        if (!imageUrl) {
            showToastMessage('Please upload an image first.', 'error');
            return;
        }
        if (!title) {
            showToastMessage('Please enter a title.', 'error');
            return;
        }

        const galleryData = {
            image: imageUrl,
            tag: tag,
            title: title
        };

        if (editingGalleryIndex !== -1) {
            galleryList[editingGalleryIndex] = galleryData;
        } else {
            galleryList.push(galleryData);
        }

        renderGallery();
        closeGalleryModal();
        saveGallery();
    }

    // Delete gallery item from list
    function deleteGalleryItem(index) {
        showConfirmModal(
            'Delete Gallery Item',
            'Are you sure you want to remove this gallery item? This action cannot be undone.',
            function () {
                galleryList.splice(index, 1);
                renderGallery();
                saveGallery();
            },
            'Delete',
            'bg-[#ff6c00] hover:bg-[#e05f00] shadow-orange-950/20',
            null,
            'Remove Gallery Item',
            'orange'
        );
    }

    // Sync gallery items with DB
    async function saveGallery() {
        showGlobalLoader('Saving Gallery items...');

        try {
            const response = await fetch('{{ route("institute.profile.website.gallery.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ gallery: galleryList })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Gallery items saved successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save settings.', 'error');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    // ───────────────────────────────────────────────
    //  EVENTS SECTION
    // ───────────────────────────────────────────────

    // Render current events list on UI
    function renderEvents() {
        const container = document.getElementById('events-container');
        const emptyState = document.getElementById('events-empty-state');
        if (!container) return;
        
        // Handle Add Button State based on events count limit (max 20)
        const addBtn = document.getElementById('add-event-btn');
        if (addBtn) {
            if (eventsList.length >= 20) {
                addBtn.classList.add('opacity-50', 'cursor-not-allowed');
                addBtn.setAttribute('title', 'Maximum of 20 events reached');
            } else {
                addBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                addBtn.removeAttribute('title');
            }
        }
        
        container.innerHTML = '';
        
        if (eventsList.length === 0) {
            emptyState.classList.remove('hidden');
            return;
        }
        
        emptyState.classList.add('hidden');
        
        eventsList.forEach((item, index) => {
            const card = document.createElement('div');
            card.className = 'flex flex-col justify-between bg-white border border-slate-200/60 hover:border-[#ff6c00]/50 hover:ring-2 hover:ring-[#ff6c00]/10 rounded-2xl p-3 transition duration-300 hover:shadow-md hover:scale-[1.01] cursor-pointer relative overflow-hidden group';
            
            card.onclick = (e) => {
                if (e.target.closest('.btn-delete-event') || e.target.closest('.btn-edit-event')) {
                    return;
                }
                openViewEventModal(index);
            };

            card.innerHTML = `
                <div class="space-y-2.5">
                    <!-- Date badge style -->
                    <div class="h-20 rounded-xl bg-slate-50 border border-slate-100 flex flex-col items-center justify-center relative shadow-inner">
                        <span class="text-2xl font-black text-[#ff6c00] leading-none">${item.day}</span>
                        <span class="text-[9px] font-black text-slate-500 uppercase tracking-widest mt-1">${item.month} ${item.year}</span>
                        ${item.tag ? `<span class="absolute top-1.5 left-1.5 inline-flex text-[6px] font-black uppercase tracking-wider bg-slate-900 text-white px-1.5 py-0.5 rounded-full">${item.tag}</span>` : ''}
                        
                        <!-- Overlay Edit & Delete Buttons -->
                        <div class="absolute top-1.5 right-1.5 flex items-center gap-1 z-10 opacity-90 group-hover:opacity-100 transition">
                            <button type="button" onclick="event.stopPropagation(); openEditEventModal(${index})" class="btn-edit-event p-1 bg-white hover:bg-slate-100 text-orange-600 rounded-md shadow-sm border border-slate-200/50 transition" title="Edit Event">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                </svg>
                            </button>
                            <button type="button" onclick="event.stopPropagation(); deleteEventItem(${index})" class="btn-delete-event p-1 bg-white hover:bg-slate-100 text-rose-600 rounded-md shadow-sm border border-slate-200/50 transition" title="Delete Event">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    
                    <!-- Details -->
                    <div class="space-y-1">
                        <h4 class="text-[10px] font-black text-slate-800 line-clamp-1 leading-tight group-hover:text-[#ff6c00] transition-colors" title="${item.title}">${item.title}</h4>
                        ${item.location ? `<p class="text-[8px] font-bold text-slate-400 truncate">📍 ${item.location}</p>` : ''}
                    </div>
                </div>
            `;
            container.appendChild(card);
        });
    }

    // Modal Operations for Events
    function openAddEventModal() {
        if (eventsList.length >= 20) {
            showToastMessage('You can add a maximum of 20 events.', 'error');
            return;
        }
        editingEventIndex = -1;
        
        // Update header & submit button labels
        document.getElementById('event-modal-title').innerText = 'Add Event';
        document.getElementById('event-modal-submit-btn').innerText = 'Add Event';
        
        // Reset form
        const todayStr = new Date().toISOString().split('T')[0];
        document.getElementById('event-date').value = todayStr;
        document.getElementById('event-tag').value = '';
        document.getElementById('event-time').value = '';
        document.getElementById('event-location').value = '';
        document.getElementById('event-title').value = '';
        document.getElementById('event-speaker').value = '';
        document.getElementById('event-speaker-role').value = '';
        document.getElementById('event-desc').value = '';
        
        document.getElementById('event-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function openEditEventModal(index) {
        editingEventIndex = index;
        const item = eventsList[index];
        
        // Update header & submit button labels
        document.getElementById('event-modal-title').innerText = 'Edit Event Details';
        document.getElementById('event-modal-submit-btn').innerText = 'Save Changes';
        
        // Populate fields
        const monthsMap = {
            'JAN': '01', 'FEB': '02', 'MAR': '03', 'APR': '04', 'MAY': '05', 'JUN': '06',
            'JUL': '07', 'AUG': '08', 'SEP': '09', 'OCT': '10', 'NOV': '11', 'DEC': '12'
        };
        const y = item.year || new Date().getFullYear();
        const m = item.month ? (monthsMap[item.month.trim().toUpperCase().substring(0, 3)] || '01') : '01';
        const d = String(item.day || '01').padStart(2, '0');
        document.getElementById('event-date').value = `${y}-${m}-${d}`;
        
        document.getElementById('event-tag').value = item.tag || '';
        document.getElementById('event-time').value = item.time || '';
        document.getElementById('event-location').value = item.location || '';
        document.getElementById('event-title').value = item.title || '';
        document.getElementById('event-speaker').value = item.speaker || '';
        document.getElementById('event-speaker-role').value = item.speaker_role || '';
        document.getElementById('event-desc').value = item.desc || '';
        
        document.getElementById('event-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeEventModal() {
        document.getElementById('event-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    function openViewEventModal(index) {
        const item = eventsList[index];
        
        document.getElementById('view-event-datetime').innerHTML = `
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Date</div>
            <div class="text-sm font-black text-slate-800">${item.day} ${item.month} ${item.year}</div>
            ${item.time ? `<div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider mt-2">Time</div><div class="text-xs font-bold text-slate-700">${item.time}</div>` : ''}
        `;
        
        document.getElementById('view-event-tagloc').innerHTML = `
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Category Tag</div>
            <div class="text-xs font-bold text-slate-800 mb-2">${item.tag || 'No Tag'}</div>
            <div class="text-[10px] text-slate-400 font-bold uppercase tracking-wider">Location / Venue</div>
            <div class="text-xs font-bold text-slate-800">${item.location || 'Not Specified'}</div>
        `;
        
        document.getElementById('view-event-title').innerText = item.title || 'Untitled Event';
        
        const speakerContainer = document.getElementById('view-event-speaker-container');
        if (item.speaker) {
            document.getElementById('view-event-speaker').innerHTML = `
                <div class="font-bold text-slate-800 text-xs">${item.speaker}</div>
                ${item.speaker_role ? `<div class="text-[10px] font-semibold text-slate-500">${item.speaker_role}</div>` : ''}
            `;
            speakerContainer.classList.remove('hidden');
        } else {
            speakerContainer.classList.add('hidden');
        }
        
        document.getElementById('view-event-desc').innerText = item.desc || 'No description provided.';
        
        document.getElementById('view-event-modal').classList.replace('hidden', 'flex');
        document.body.style.overflow = 'hidden';
    }

    function closeViewEventModal() {
        document.getElementById('view-event-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    // Add/Update event item locally
    function addEventToList() {
        const dateVal = document.getElementById('event-date').value;
        const tag = document.getElementById('event-tag').value.trim();
        const time = document.getElementById('event-time').value.trim();
        const location = document.getElementById('event-location').value.trim();
        const title = document.getElementById('event-title').value.trim();
        const speaker = document.getElementById('event-speaker').value.trim();
        const speaker_role = document.getElementById('event-speaker-role').value.trim();
        const desc = document.getElementById('event-desc').value.trim();

        if (!dateVal) {
            showToastMessage('Please select an event date.', 'error');
            return;
        }
        if (!title) {
            showToastMessage('Please enter an event title.', 'error');
            return;
        }

        const dateParts = dateVal.split('-');
        const year = dateParts[0];
        const monthNum = parseInt(dateParts[1], 10);
        const day = String(parseInt(dateParts[2], 10)); // unpadded e.g. "5" instead of "05"

        const monthsArr = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
        const month = monthsArr[monthNum - 1] || 'JAN';

        const eventData = {
            day: day,
            month: month,
            year: year,
            tag: tag,
            time: time,
            location: location,
            title: title,
            speaker: speaker,
            speaker_role: speaker_role,
            desc: desc
        };

        if (editingEventIndex !== -1) {
            eventsList[editingEventIndex] = eventData;
        } else {
            eventsList.push(eventData);
        }

        renderEvents();
        closeEventModal();
        saveEvents();
    }

    // Delete event item from list
    function deleteEventItem(index) {
        showConfirmModal(
            'Delete Event',
            'Are you sure you want to remove this event? This action cannot be undone.',
            function () {
                eventsList.splice(index, 1);
                renderEvents();
                saveEvents();
            },
            'Delete',
            'bg-[#ff6c00] hover:bg-[#e05f00] shadow-orange-950/20',
            null,
            'Remove Event',
            'orange'
        );
    }

    // Sync events with DB
    async function saveEvents() {
        showGlobalLoader('Saving Events calendar...');

        try {
            const response = await fetch('{{ route("institute.profile.website.events.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ events: eventsList })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Events calendar saved successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save events.', 'error');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    // ───────────────────────────────────────────────
    //  SOCIAL LINKS SECTION
    // ───────────────────────────────────────────────

    // Save social media links to DB
    async function saveSocialLinks() {
        const facebook = document.getElementById('social-facebook').value.trim();
        const twitter = document.getElementById('social-twitter').value.trim();
        const linkedin = document.getElementById('social-linkedin').value.trim();
        const instagram = document.getElementById('social-instagram').value.trim();
        const youtube = document.getElementById('social-youtube').value.trim();

        showGlobalLoader('Saving Social Links...');

        try {
            const response = await fetch('{{ route("institute.profile.website.social.save") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    facebook: facebook,
                    twitter: twitter,
                    linkedin: linkedin,
                    instagram: instagram,
                    youtube: youtube
                })
            });

            const data = await response.json();

            if (response.ok && data.status === 'success') {
                showToastMessage(data.message || 'Social links saved successfully!', 'success');
            } else {
                showToastMessage(data.message || 'Failed to save social links.', 'error');
            }
        } catch (error) {
            console.error('Save error:', error);
            showToastMessage('Something went wrong.', 'error');
        } finally {
            hideGlobalLoader();
        }
    }

    function closeViewGalleryModal() {
        document.getElementById('view-gallery-modal').classList.replace('flex', 'hidden');
        document.body.style.overflow = 'auto';
    }

    // Helpers
    function showGlobalLoader(text) {
        document.getElementById('global-loader-text').innerText = text;
        document.getElementById('global-loader-overlay').classList.remove('hidden');
    }

    function hideGlobalLoader() {
        document.getElementById('global-loader-overlay').classList.add('hidden');
    }

    function showToastMessage(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        
        let borderClass = 'border-emerald-500/30';
        if (type === 'error') borderClass = 'border-rose-500/30';
        if (type === 'info') borderClass = 'border-indigo-500/30';
        
        toast.className = `transform translate-x-12 opacity-0 transition-all duration-500 ease-out flex items-center gap-3 bg-slate-900 border ${borderClass} text-white px-5 py-4 rounded-2xl shadow-xl pointer-events-auto min-w-[280px]`;
        
        let icon = '';
        if (type === 'success') {
            icon = `<div class="h-6 w-6 rounded-lg bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                    </div>`;
        } else if (type === 'error') {
            icon = `<div class="h-6 w-6 rounded-lg bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6M6 6l12 12" /></svg>
                    </div>`;
        } else {
            icon = `<div class="h-6 w-6 rounded-lg bg-indigo-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </div>`;
        }
               
        toast.innerHTML = `
            ${icon}
            <div class="flex flex-col gap-0.5 flex-1">
                <span class="text-xs font-bold tracking-wide">${type === 'error' ? 'Failed' : 'Success'}</span>
                <span class="text-[10px] text-slate-400 font-medium">${message}</span>
            </div>
        `;
        
        container.appendChild(toast);
        setTimeout(() => toast.classList.remove('translate-x-12', 'opacity-0'), 50);
        setTimeout(() => {
            toast.classList.add('translate-x-12', 'opacity-0');
            setTimeout(() => toast.remove(), 500);
        }, 3500);
    }
</script>
@endsection
