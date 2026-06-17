<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <title>Tuoora - Smart Institute Management & Payments</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS for interactive slider -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    },
                    colors: {
                        brand: {
                            50: '#fff7ed',
                            100: '#ffedd5',
                            500: '#ff6b00',
                            600: '#ea580c',
                            700: '#c2410c',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }

        /* Custom scrollbar styling for events list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }

        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 9999px;
        }

        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <!-- STICKY NAVIGATION BAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="#home" class="flex items-center gap-2">
                <span class="text-2xl font-black text-brand-500 tracking-tight">
                    {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE<span class="text-slate-800">ACADEMY</span>' !!}
                </span>
            </a>

            <nav class="hidden lg:flex items-center gap-8 text-sm font-bold text-slate-600">
                @if($hasHeroSlides || $isEditable)
                    <a href="#home" class="hover:text-brand-500 transition-colors">Home</a>
                @endif
                @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                    <a href="#about" class="hover:text-brand-500 transition-colors">About Us</a>
                @endif
                @if($hasAchievements)
                    <a href="#achievements" class="hover:text-brand-500 transition-colors">Achievements</a>
                @endif
                @if($hasGallery)
                    <a href="#gallery" class="hover:text-brand-500 transition-colors">Gallery</a>
                @endif
                @if($hasEvents)
                    <a href="#events" class="hover:text-brand-500 transition-colors">Events</a>
                @endif
            </nav>


        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobile-nav"
            class="hidden lg:hidden border-b border-slate-100 bg-white px-6 py-4 space-y-3 font-semibold text-sm text-slate-600">
            @if($hasHeroSlides || $isEditable)
                <a href="#home" class="block hover:text-brand-500">Home</a>
            @endif
            @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                <a href="#about" class="block hover:text-brand-500">About Us</a>
            @endif
            @if($hasAchievements)
                <a href="#achievements" class="block hover:text-brand-500">Achievements</a>
            @endif
            @if($hasGallery)
                <a href="#gallery" class="block hover:text-brand-500">Gallery</a>
            @endif
            @if($hasEvents)
                <a href="#events" class="block hover:text-brand-500">Events</a>
            @endif
            <a href="#social" class="block hover:text-brand-500">Social Feed</a>
            <a href="#contact" class="block hover:text-brand-500">Contact</a>
        </div>
    </header>

    @if($hasHeroSlides || $isEditable)
        <!-- HERO IMAGE SLIDER SECTION (LIGHT THEME) -->
        <section id="home"
            class="relative bg-slate-50 text-slate-900 overflow-hidden py-10 md:py-2 border-b border-slate-100" x-data="{ 
                activeSlide: 0, 
                progress: 0,
                slides: {{ json_encode(!empty($heroSlides) ? $heroSlides : [
            [
                'img' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80',
                'badge' => 'Empowering Minds',
                'title' => 'Empowering Minds, Shaping Futures',
                'desc' => 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1600&q=80',
                'badge' => 'Interactive Learning',
                'title' => 'Innovative Academic Programs',
                'desc' => 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80',
                'badge' => 'Future-Ready Labs',
                'title' => 'State-of-the-Art Infrastructure',
                'desc' => 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
            ]
        ]) }},
                next() { 
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length; 
                    this.progress = 0;
                },
                prev() { 
                    this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length; 
                    this.progress = 0;
                }
            }" x-init="
                setInterval(() => {
                    progress += 2;
                    if (progress >= 100) {
                        next();
                    }
                }, 100)
            ">

            <!-- Ambient Glow Orbs -->
            <div class="absolute -top-32 -left-32 w-80 h-80 bg-brand-500/5 rounded-full blur-[100px] pointer-events-none">
            </div>
            <div
                class="absolute -bottom-32 -right-32 w-80 h-80 bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none">
            </div>

            <div
                class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center min-h-[380px] md:min-h-[440px]">

                <!-- Left Side: Staggered Content Container -->
                <div
                    class="lg:col-span-6 space-y-5 text-center lg:text-left relative min-h-[250px] flex flex-col justify-center">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700 delay-100"
                            x-transition:enter-start="opacity-0 translate-y-6"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300 absolute" class="space-y-4 w-full">

                            <span
                                class="inline-flex px-3 py-1 bg-brand-50 border border-brand-100 rounded-full text-[11px] font-extrabold text-brand-500 uppercase tracking-widest"
                                x-text="slide.badge"></span>

                            <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight text-slate-900"
                                x-text="slide.title"></h1>

                            <p class="text-xs md:text-sm text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium"
                                x-text="slide.desc"></p>


                        </div>
                    </template>
                </div>

                <!-- Right Side: Double Framed Image -->
                <div class="lg:col-span-6 relative flex justify-center items-center">
                    <div
                        class="bg-white p-3 rounded-[2.5rem] border border-slate-200/60 shadow-xl w-full max-w-md md:max-w-lg">
                        <div class="relative aspect-[16/11] rounded-[2rem] overflow-hidden bg-slate-100 group">
                            <template x-for="(slide, index) in slides" :key="index">
                                <div x-show="activeSlide === index" class="absolute inset-0">
                                    <!-- Ken Burns Zoom Effect -->
                                    <img :src="slide.img"
                                        class="w-full h-full object-cover transition-transform duration-[5000ms] ease-out"
                                        :class="activeSlide === index ? 'scale-105' : ''">
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-900/20 via-transparent to-transparent">
                                    </div>
                                </div>
                            </template>

                            @if($isEditable)
                                <!-- Upload Button Overlay -->
                                <div
                                    class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                                    <button @click="document.getElementById('hero-file-input-' + activeSlide).click()"
                                        class="bg-white text-slate-900 px-4 py-2 rounded-xl text-xs font-bold shadow-md hover:scale-105 active:scale-95 transition-all">
                                        Change Image
                                    </button>
                                </div>

                                <!-- Hidden File Inputs -->
                                <input type="file" id="hero-file-input-0" data-slide-index="0" accept="image/*" class="hidden"
                                    @change="window.uploadCustomizerImage($event, (url) => { slides[0].img = url; })">
                                <input type="file" id="hero-file-input-1" data-slide-index="1" accept="image/*" class="hidden"
                                    @change="window.uploadCustomizerImage($event, (url) => { slides[1].img = url; })">
                                <input type="file" id="hero-file-input-2" data-slide-index="2" accept="image/*" class="hidden"
                                    @change="window.uploadCustomizerImage($event, (url) => { slides[2].img = url; })">

                                <!-- Hidden inputs bound to Alpine slides array to automatically save via Customizer -->
                                <input type="hidden" class="dynamic-editable-img" data-key="hero_image_1"
                                    :value="slides[0].img">
                                <input type="hidden" class="dynamic-editable-img" data-key="hero_image_2"
                                    :value="slides[1].img">
                                <input type="hidden" class="dynamic-editable-img" data-key="hero_image_3"
                                    :value="slides[2].img">
                            @endif

                            <!-- Floating Top Badge -->
                            <div
                                class="absolute top-4 right-4 px-2.5 py-1 bg-white/90 backdrop-blur-sm border border-slate-200/50 rounded-lg text-[9px] font-bold text-slate-800 shadow-sm">
                                ★ Top Choice
                            </div>
                        </div>
                    </div>

                </div>

            </div>

            <!-- Slider Controls -->
            <div
                class="max-w-7xl mx-auto px-6 mt-2 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-200/50 pt-4 z-10 relative">
                <!-- Indicators -->
                <div class="flex items-center gap-2">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index; progress = 0;"
                            class="group relative flex items-center justify-center h-6 w-6 rounded-full border transition-all duration-300"
                            :class="activeSlide === index ? 'border-brand-500' : 'border-slate-200 hover:border-slate-400'">
                            <span class="h-1 w-1 rounded-full transition-all duration-300"
                                :class="activeSlide === index ? 'bg-brand-500 scale-125' : 'bg-slate-300 group-hover:bg-slate-500'"></span>
                        </button>
                    </template>
                </div>

                <!-- Slide Numbers & Arrows -->
                <div class="flex items-center gap-4">
                    <div class="text-[10px] font-bold text-slate-400 tracking-wider">
                        <span class="text-slate-800 text-xs font-black" x-text="'0' + (activeSlide + 1)"></span> / <span
                            x-text="'0' + slides.length"></span>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button @click="prev()"
                            class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all active:scale-95 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="next()"
                            class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all active:scale-95 shadow-sm">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Progress bar tracking slider timer -->
            <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-slate-100">
                <div class="h-full bg-brand-500 transition-all duration-100 ease-linear"
                    :style="'width: ' + progress + '%'"></div>
            </div>
        </section>
    @endif

    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0)
        <!-- ABOUT US SECTION -->
        <section id="about" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-white border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-6 space-y-8">

                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest dynamic-editable"
                        data-key="about_badge"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['about_badge'] ?? 'About Our Academy' !!}</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable"
                        data-key="about_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_title'] ?? 'Our Core Pillars' !!}
                    </h2>
                    <p class="text-xs text-slate-500 dynamic-editable" data-key="about_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow.' !!}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Vision Box -->
                    @foreach($visionItems as $item)
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom">
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="h-11 w-11 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-500 border border-brand-100 group-hover:scale-110 transition-transform duration-300 text-lg">
                                        🔭</div>
                                    <span
                                        class="text-[9px] font-extrabold text-brand-500/50 uppercase tracking-widest">Vision</span>
                                </div>

                                <div class="space-y-1.5">
                                    <h3 class="text-sm font-black text-slate-800">{!! $item['title'] !!}</h3>
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{!! $item['desc'] !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Mission Box -->
                    @foreach($missionItems as $item)
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom">
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="h-11 w-11 bg-indigo-550 rounded-2xl flex items-center justify-center text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform duration-300 text-lg">
                                        🚀</div>
                                    <span
                                        class="text-[9px] font-extrabold text-indigo-500/50 uppercase tracking-widest">Mission</span>
                                </div>

                                <div class="space-y-1.5">
                                    <h3 class="text-sm font-black text-slate-800">{!! $item['title'] !!}</h3>
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{!! $item['desc'] !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach

                    <!-- Values Box -->
                    @foreach($valuesItems as $item)
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                            <div
                                class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom">
                            </div>

                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div
                                        class="h-11 w-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform duration-300 text-lg">
                                        🛡️</div>
                                    <span
                                        class="text-[9px] font-extrabold text-emerald-500/50 uppercase tracking-widest">Values</span>
                                </div>

                                <div class="space-y-1.5">
                                    <h3 class="text-sm font-black text-slate-800">{!! $item['title'] !!}</h3>
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">{!! $item['desc'] !!}</p>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

            </div>
        </section>
    @endif

    <!-- ACHIEVEMENTS SECTION -->
    @if($hasAchievements || $isEditable)
        <section id="achievements" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-slate-50 border-b border-slate-100">
            <div class="max-w-7xl mx-auto px-6 space-y-8">
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest dynamic-editable"
                        data-key="achieve_badge"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['achieve_badge'] ?? 'Our Milestones' !!}</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable"
                        data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_title'] ?? 'Recent Achievements' !!}
                    </h2>
                    <p class="text-xs text-slate-500 dynamic-editable" data-key="achieve_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_desc'] ?? 'Proud moments demonstrating our dedication to academic and athletic excellence.' !!}
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Achievement 1 -->
                    @if(!empty($settings['ach1_title']))
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                            <div
                                class="absolute top-0 left-0 h-1 bg-amber-500 w-0 group-hover:w-full transition-all duration-300">
                            </div>
                            <div class="flex items-center justify-between">
                                <div
                                    class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 border border-amber-100 group-hover:scale-110 transition-transform duration-300">
                                    🏆</div>
                                <span
                                    class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">01</span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach1_title"
                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach1_title'] ?? 'Best School Award 2025' !!}
                            </h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                data-key="ach1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach1_desc'] ?? 'Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.' !!}
                            </p>
                        </div>
                    @endif

                    <!-- Achievement 2 -->
                    @if(!empty($settings['ach2_title']))
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                            <div
                                class="absolute top-0 left-0 h-1 bg-indigo-500 w-0 group-hover:w-full transition-all duration-300">
                            </div>
                            <div class="flex items-center justify-between">
                                <div
                                    class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform duration-300">
                                    🎓</div>
                                <span
                                    class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">02</span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach2_title"
                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach2_title'] ?? '100% Board Exam Success' !!}
                            </h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                data-key="ach2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach2_desc'] ?? 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.' !!}
                            </p>
                        </div>
                    @endif

                    <!-- Achievement 3 -->
                    @if(!empty($settings['ach3_title']))
                        <div
                            class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                            <div
                                class="absolute top-0 left-0 h-1 bg-emerald-500 w-0 group-hover:w-full transition-all duration-300">
                            </div>
                            <div class="flex items-center justify-between">
                                <div
                                    class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform duration-300">
                                    🏅</div>
                                <span
                                    class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">03</span>
                            </div>

                            <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach3_title"
                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach3_title'] ?? 'National Sports Champions' !!}
                            </h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                data-key="ach3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['ach3_desc'] ?? 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.' !!}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- GALLERY SECTION -->
    @if($hasGallery)
        <section id="gallery" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-white border-b border-slate-100" x-data='{ 
                    activeFilter: "all",
                    currentIndex: 0,
                    itemsPerPage: 3,
                    items: {{ json_encode($galleryItems) }},
                    updateItemsPerPage() {
                        if (window.innerWidth >= 1024) {
                            this.itemsPerPage = 3;
                        } else if (window.innerWidth >= 640) {
                            this.itemsPerPage = 2;
                        } else {
                            this.itemsPerPage = 1;
                        }
                    },
                    get filteredItems() {
                        if (this.activeFilter === "all") return this.items;
                        return this.items.filter(item => item.cat === this.activeFilter);
                    },
                    get maxIndex() {
                        return Math.max(0, this.filteredItems.length - this.itemsPerPage);
                    },
                    next() {
                        if (this.currentIndex < this.maxIndex) {
                            this.currentIndex++;
                        } else {
                            this.currentIndex = 0;
                        }
                    },
                    prev() {
                        if (this.currentIndex > 0) {
                            this.currentIndex--;
                        } else {
                            this.currentIndex = this.maxIndex;
                        }
                    }
                }'
            x-init="updateItemsPerPage(); window.addEventListener('resize', () => { updateItemsPerPage(); currentIndex = Math.min(currentIndex, maxIndex); })">

            <div class="max-w-7xl mx-auto px-6 space-y-8">
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">Visual Tour</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
                    <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of our
                        Academy.</p>
                </div>

                <!-- Gallery Carousel / Slider -->
                <div class="relative w-full overflow-hidden p-1">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${currentIndex * (100 / filteredItems.length)}%); width: ${filteredItems.length * (100 / itemsPerPage)}%;`"
                        style="width: 100%;">
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <div class="px-3 flex-shrink-0 transition-all duration-500 ease-out"
                                :style="`width: ${100 / filteredItems.length}%;`">
                                <div
                                    class="group relative rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 aspect-video lg:aspect-square bg-slate-50 w-full h-full">
                                    <!-- Zoom image -->
                                    <img :src="item.img"
                                        class="w-full h-full object-cover transition-transform duration-[6000ms] ease-out group-hover:scale-105 group-hover:rotate-1">

                                    <!-- Overlay gradient -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300">
                                    </div>

                                    <!-- Floating Category Pill -->
                                    <div
                                        class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm border border-slate-200/20 px-3 py-1 rounded-xl text-[9px] font-black text-slate-800 uppercase tracking-widest shadow-sm">
                                        <span x-text="item.tag"></span>
                                    </div>

                                    <!-- Info Content sliding up on hover -->
                                    <div
                                        class="absolute inset-0 flex flex-col justify-end p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                        <h4 class="text-white font-black text-base leading-tight mt-1" x-text="item.title">
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex justify-between items-center mt-6 max-w-xs mx-auto"
                    x-show="filteredItems.length > itemsPerPage">
                    <button @click="prev()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-brand-600 flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &larr;
                    </button>
                    <!-- Indicators -->
                    <div class="flex items-center gap-1.5">
                        <template x-for="i in Math.max(1, filteredItems.length - itemsPerPage + 1)" :key="i">
                            <span class="h-1.5 rounded-full transition-all duration-350 cursor-pointer"
                                :class="currentIndex === (i - 1) ? 'w-6 bg-brand-500' : 'w-2 bg-slate-200'"
                                @click="currentIndex = (i - 1)"></span>
                        </template>
                    </div>
                    <button @click="next()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-brand-600 flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &rarr;
                    </button>
                </div>
            </div>
        </section>
    @endif

    <!-- EVENTS SECTION -->
    @if($hasEvents)
        <section id="events" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-slate-50 border-b border-slate-100">
            <div class="max-w-4xl mx-auto px-6 space-y-8">
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">Upcoming
                        Activities</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                    <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and
                        educational programs.</p>
                </div>

                <!-- Vertical Timeline Feed -->
                <div class="space-y-4 max-h-[480px] overflow-y-auto pr-3 custom-scrollbar">
                    @foreach($events as $index => $event)
                        @php
                            $colors = ['brand-500', 'indigo-500', 'emerald-500'];
                            $bgColors = ['brand-50', 'indigo-50', 'emerald-50'];
                            $borderColors = ['brand-100', 'indigo-100', 'emerald-100'];
                            $textColors = ['brand-600', 'indigo-600', 'emerald-600'];

                            $color = $colors[$index % 3];
                            $bgColor = $bgColors[$index % 3];
                            $borderColor = $borderColors[$index % 3];
                            $textColor = $textColors[$index % 3];
                        @endphp
                        <!-- Event Item -->
                        <div
                            class="group relative bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-4 overflow-hidden">
                            <div class="absolute top-0 left-0 bottom-0 w-1 bg-{{ $color }}"></div>

                            <div class="flex items-center gap-4">
                                <!-- Date Block -->
                                <div
                                    class="h-14 w-14 shrink-0 bg-{{ $bgColor }} border border-{{ $borderColor }} text-{{ $textColor }} rounded-2xl flex flex-col items-center justify-center transition-all duration-300 group-hover:bg-{{ $color }} group-hover:text-white group-hover:rotate-3">
                                    <span class="text-lg font-black leading-none">{{ $event['day'] }}</span>
                                    <span
                                        class="text-[9px] font-black uppercase tracking-wider leading-none mt-1">{{ $event['month'] }}</span>
                                </div>

                                <!-- Event Info -->
                                <div class="space-y-1.5 flex-1 min-w-0">
                                    <div class="flex flex-wrap items-center gap-2 text-[10px] text-slate-400 font-bold">
                                        <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider" x-text="event.tag">{{ $event['tag'] }}</span>
                                        <span class="flex items-center gap-1">🕒 <span>{{ $event['time'] }}</span></span>
                                        <span class="flex items-center gap-1">📍 <span class="truncate max-w-[150px]">{{ $event['location'] }}</span></span>
                                    </div>
                                    <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-{{ $color }}">
                                        {{ $event['title'] }}
                                    </h3>
                                    <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed">{{ $event['desc'] }}</p>

                                    @if(!empty($event['speaker']))
                                        <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-xl border border-slate-100 mt-2 max-w-xs">
                                            <span class="text-xs shrink-0 font-normal">🎙️</span>
                                            <div class="leading-tight min-w-0">
                                                <div class="text-[9px] font-extrabold text-slate-700 truncate">{{ $event['speaker'] }}</div>
                                                <div class="text-[7.5px] font-bold text-slate-400 truncate mt-0.5">{{ $event['speaker_role'] ?? 'Speaker' }}</div>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
    @endif


    <!-- FOOTER -->
    <!-- FOOTER -->
    <footer class="bg-slate-950 text-slate-400 py-12 px-6 border-t border-slate-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 border-b border-slate-900 pb-8">
            <div class="space-y-4">
                <span class="text-2xl font-black text-brand-500 tracking-tight">
                    {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE<span class="text-white">ACADEMY</span>' !!}
                </span>
                <p class="text-xs text-slate-400 leading-relaxed dynamic-editable" data-key="footer_desc"
                    contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['footer_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow since 2012.' !!}
                </p>

                <!-- Interactive Newsletter Form -->

            </div>
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="text-xs space-y-2 font-medium">
                    @if($hasHeroSlides || $isEditable)
                        <li>
                            <a href="#home"
                                class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                                Home
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                        <li>
                            <a href="#about"
                                class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                                About Us
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if($hasAchievements)
                        <li>
                            <a href="#achievements"
                                class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                                Achievements
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if($hasGallery)
                        <li>
                            <a href="#gallery"
                                class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                                Gallery
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-wider mb-4">Contact Info</h4>
                <ul class="text-xs space-y-3 font-semibold">
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📧</span>
                        <span class="dynamic-editable" data-key="footer_email"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_email'] ?? 'admissions@nobleacademy.edu' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📞</span>
                        <span class="dynamic-editable" data-key="footer_phone"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_phone'] ?? '+91 98765 43210' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📍</span>
                        <span class="dynamic-editable" data-key="footer_address"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_address'] ?? 'Ahmedabad, Gujarat, India' !!}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium">
            <p>&copy; {{ date('Y') }}
                {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'Noble Academy' !!}.
                All rights reserved.
            </p>

            <!-- Interactive Social Media Badges -->
            <div class="flex gap-2.5 mt-4 md:mt-0">
                <a href="{{ !empty($content->facebook) ? $content->facebook : '#' }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#1877F2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1877F2]/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z" />
                    </svg>
                </a>
                <a href="{{ !empty($content->twitter) ? $content->twitter : '#' }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#1DA1F2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1DA1F2]/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z" />
                    </svg>
                </a>
                <a href="{{ !empty($content->linkedin) ? $content->linkedin : '#' }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#0A66C2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#0A66C2]/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z" />
                    </svg>
                </a>
                <a href="{{ !empty($content->instagram) ? $content->instagram : '#' }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-gradient-to-tr hover:from-purple-600 hover:to-orange-500 hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-pink-500/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z" />
                    </svg>
                </a>
                <a href="{{ !empty($content->youtube) ? $content->youtube : '#' }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#FF0000] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#FF0000]/20">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.507a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.507 9.388.507 9.388.507s7.518 0 9.388-.507a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z" />
                    </svg>
                </a>
            </div>

            <!-- Floating Back to Top Button -->
            <div x-data="{ showTopBtn: false }"
                @scroll.window="showTopBtn = (window.pageYOffset || document.documentElement.scrollTop) > 200">
                <button x-show="showTopBtn" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-10 scale-95"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'})"
                    class="fixed bottom-6 right-6 z-50 flex items-center justify-center h-10 w-10 bg-brand-500 hover:bg-brand-600 text-white rounded-2xl shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105 active:scale-95 group">
                    <span
                        class="text-sm font-black transform group-hover:-translate-y-0.5 transition-transform">↑</span>
                </button>
            </div>
        </div>
    </footer>

    <script>
        // Mobile navigation toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');

        menuBtn.addEventListener('click', () => {
            mobileNav.classList.toggle('hidden');
        });

        // Hide mobile nav on link click
        const navLinks = mobileNav.querySelectorAll('a');
        navLinks.forEach(link => {
            link.addEventListener('click', () => {
                mobileNav.classList.add('hidden');
            });
        });
    </script>

    @if(!$isEditable && !$hasHeroSlides && count($visionItems) === 0 && count($missionItems) === 0 && count($valuesItems) === 0 && !$hasAchievements && !$hasGallery && !$hasEvents)
        <div class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900/60 backdrop-blur-md px-6">
            <div
                class="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-10 max-w-md w-full shadow-2xl text-center space-y-6 transform scale-100 transition-all">
                <div
                    class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-3xl mx-auto animate-bounce">
                    🌐
                </div>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-slate-950 tracking-tight">Website Setup Required</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">
                        This institute website has not been configured yet. Please log in to your dashboard to set up your
                        template and sections.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('institute.profile.website.index') }}"
                        class="inline-flex w-full items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/25 transition-all hover:scale-102 active:scale-98">
                        Go to Setup Panel
                    </a>
                </div>
            </div>
        </div>
    @endif
</body>

</html>