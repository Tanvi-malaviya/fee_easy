<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-20">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <title>Noble Academy - Luxury Academic Template</title>
    <!-- Tailwind CSS for modern responsive styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Cormorant Garamond & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- AlpineJS for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme: {
                            primary: '#0a1128',     // Deep Royal Navy
                            secondary: '#1c2541',   // Classic Slate
                            accent: '#b89047',      // Luxury Warm Gold
                            cream: '#fcfbf9',       // Premium Off-white
                            ivory: '#f5f3ef',       // Warm Alabaster
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                        serif: ['Cormorant Garamond', 'serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --theme-primary: #0a1128;
            --theme-secondary: #1c2541;
            --theme-accent: #b89047;
            --theme-cream: #fcfbf9;
            --theme-ivory: #f5f3ef;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--theme-cream);
        }

        .serif-title {
            font-family: 'Cormorant Garamond', serif;
        }

        /* Editorial Underline Animation */
        .editorial-link {
            position: relative;
            font-size: 12.5px;
            font-weight: 800;
            color: #64748b;
            transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            letter-spacing: 0.15em;
            text-transform: uppercase;
        }

        .editorial-link::after {
            content: "";
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 1.5px;
            background: var(--theme-accent);
            transition: all 0.4s cubic-bezier(0.25, 1, 0.5, 1);
            transform: translateX(-50%);
        }

        .editorial-link:hover {
            color: var(--theme-primary);
        }

        .editorial-link:hover::after,
        .editorial-link.active::after {
            width: 80%;
        }

        .editorial-link.active {
            color: var(--theme-primary);
        }

        /* Thin framed double borders for luxury look */
        .luxury-frame {
            border: 1px solid rgba(184, 144, 71, 0.2);
            outline: 3px solid var(--theme-cream);
            outline-offset: -4px;
        }

        .luxury-frame-hover {
            transition: all 0.5s ease;
        }

        .luxury-frame-hover:hover {
            border-color: rgba(184, 144, 71, 0.6);
            outline-color: var(--theme-cream);
            box-shadow: 0 15px 30px rgba(10, 17, 40, 0.04);
        }

        /* Custom scrollbar styling for events list */
        .custom-scrollbar::-webkit-scrollbar {
            width: 4px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(184, 144, 71, 0.25);
            border-radius: 9999px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(184, 144, 71, 0.45);
        }
    </style>
</head>

<body class="bg-theme-cream text-slate-800 antialiased min-h-screen">

    <!-- NAVIGATION HEADER -->
    <header class="sticky top-0 z-50 w-full bg-theme-cream/90 backdrop-blur-xl border-b border-theme-accent/10 py-4">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <!-- LOGO -->
            <a href="#home" class="flex flex-col leading-none">
                <span class="text-lg font-black tracking-widest text-theme-primary uppercase">
                    {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE <span class="text-theme-accent font-light italic serif-title">Academy</span>' !!}
                </span>
                <span class="text-[7.5px] uppercase tracking-[0.45em] text-slate-400 mt-1 font-bold dynamic-editable"
                    data-key="logo_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['logo_subtitle'] ?? 'Est. 2012 • Legacy of Excellence' !!}
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-6">
                @if($hasHeroSlides || $isEditable)
                    <a href="#home" class="editorial-link active">Home</a>
                @endif
                @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                    <a href="#about" class="editorial-link">About</a>
                @endif
                @if($hasAchievements)
                    <a href="#achievements" class="editorial-link">Milestones</a>
                @endif
                @if($hasGallery)
                    <a href="#gallery" class="editorial-link">Gallery</a>
                @endif
                @if($hasEvents)
                    <a href="#events" class="editorial-link">Events</a>
                @endif
            </nav>



        </div>
    </header>

    @if($hasHeroSlides || $isEditable)
        <!-- HERO SECTION -->
        <section id="home" class="relative py-12 md:py-16 bg-theme-ivory border-b border-theme-accent/10 overflow-hidden"
            x-data="{
                activeSlide: 0,
                slides: {{ json_encode(!empty($heroSlides) ? $heroSlides : [
            [
                'img' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                'badge' => 'NOBLE',
                'badgeText' => 'Academy Campus',
                'title1' => 'Empowering',
                'accent' => 'Minds',
                'title2' => 'Shaping Futures',
                'desc' => 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80',
                'badge' => 'ACADEMICS',
                'badgeText' => 'Innovative Programs',
                'title1' => 'Innovative',
                'accent' => 'Academic',
                'title2' => 'Interactive Learning',
                'desc' => 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=800&q=80',
                'badge' => 'INFRASTRUCTURE',
                'badgeText' => 'State-Of-The-Art Labs',
                'title1' => 'Modern',
                'accent' => 'Classrooms',
                'title2' => 'Future Infrastructure',
                'desc' => 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
            ]
        ]) }}
            }">

            <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
                <!-- Left Side: Editorial Typography -->
                <div class="lg:col-span-7 space-y-3 relative z-10">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700 transform"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300 absolute inset-0 flex flex-col justify-center"
                            class="space-y-2">

                            <div class="inline-flex items-center gap-2">
                                <span class="text-[9px] font-black uppercase tracking-[0.25em] text-theme-accent"
                                    x-text="slide.badge"></span>
                                <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider"
                                    x-text="slide.badgeText"></span>
                            </div>

                            <h1
                                class="text-4xl md:text-5xl lg:text-6xl font-black text-theme-primary leading-[1.05] tracking-tight serif-title">
                                <span x-text="slide.title1"></span><br>
                                <span class="text-theme-accent italic font-light font-serif"
                                    x-text="slide.accent"></span><br>
                                <span x-text="slide.title2"></span>
                            </h1>

                            <p class="text-xs md:text-sm text-slate-500 max-w-lg leading-relaxed font-medium"
                                x-text="slide.desc"></p>
                        </div>
                    </template>
                </div>

                <!-- Right Side: Luxury Framed Slide Showcase -->
                <div class="lg:col-span-5 relative flex flex-col items-center">
                    <div
                        class="w-full aspect-[4/3] rounded-3xl overflow-hidden luxury-frame bg-slate-100 relative shadow-2xl group">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide.img"
                                class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out"
                                :class="activeSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
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
                            <input type="hidden" class="dynamic-editable-img" data-key="hero_image_1" :value="slides[0].img">
                            <input type="hidden" class="dynamic-editable-img" data-key="hero_image_2" :value="slides[1].img">
                            <input type="hidden" class="dynamic-editable-img" data-key="hero_image_3" :value="slides[2].img">
                        @endif
                    </div>

                    <!-- Custom Slider Controls -->
                    <div class="flex items-center gap-4 mt-3">
                        <button @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length"
                            class="h-8 w-8 rounded-full border border-theme-accent/30 flex items-center justify-center text-theme-accent hover:bg-theme-accent hover:text-white transition duration-300">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                    d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <!-- Indicators -->
                        <div class="flex items-center gap-2">
                            <template x-for="(slide, index) in slides" :key="index">
                                <button @click="activeSlide = index" class="h-1.5 rounded-full transition-all duration-300"
                                    :class="activeSlide === index ? 'w-6 bg-theme-accent' : 'w-1.5 bg-slate-300'"></button>
                            </template>
                        </div>
                        <button @click="activeSlide = (activeSlide + 1) % slides.length"
                            class="h-8 w-8 rounded-full border border-theme-accent/30 flex items-center justify-center text-theme-accent hover:bg-theme-accent hover:text-white transition duration-300">
                            <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </section>
    @endif

    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0)
        @php
            $defaultTab = 0;
            if (count($visionItems) > 0) {
                $defaultTab = 0;
            } elseif (count($missionItems) > 0) {
                $defaultTab = 1;
            } elseif (count($valuesItems) > 0) {
                $defaultTab = 2;
            }
        @endphp
        <!-- ABOUT US SECTION -->
        <section id="about" class="py-12 md:py-16 px-6 bg-theme-cream border-b border-theme-accent/10" x-data="{ 
                activeTab: {{ $defaultTab }},
                pillars: [
                    {
                        num: '01',
                        icon: '🔭',
                        badge: 'VISION',
                        title: 'Nurturing Leaders Since 2012',
                        desc: 'To establish a global standard in education that balances academic rigor with creative expression, cultivating visionary leaders of tomorrow.',
                        focus: ['Holistic Growth', 'Integrated Tech', 'Creative Innovation'],
                        img: {{ json_encode($settings['vision_image'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80') }}
                    },
                    {
                        num: '02',
                        icon: '🚀',
                        badge: 'MISSION',
                        title: 'Fostering Excellence & Integrity',
                        desc: 'To provide a stimulating learning environment where students excel academically, develop strong moral values, and become responsible global citizens.',
                        focus: ['Qualified Mentors', 'Student curriculum', 'Civic Foundations'],
                        img: {{ json_encode($settings['mission_image'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80') }}
                    },
                    {
                        num: '03',
                        icon: '🛡️',
                        badge: 'VALUES',
                        title: 'Our Core Pillars of Success',
                        desc: 'We are anchored in key moral and academic tenets that guide every lesson, interaction, and milestone achieved within our campus.',
                        focus: ['Unyielding Integrity', 'Empathetic Collaboration', 'Inquisitive Mindsets'],
                        img: {{ json_encode($settings['values_image'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80') }}
                    }
                ]
            }">
            <div class="max-w-6xl mx-auto space-y-10">
                <!-- Header -->
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent dynamic-editable"
                        data-key="about_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_badge'] ?? 'About Our Academy' !!}
                    </span>
                    <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title dynamic-editable"
                        data-key="about_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_title'] ?? 'Our Core Pillars' !!}
                    </h2>
                    <p class="text-xs text-slate-500 dynamic-editable" data-key="about_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow.' !!}
                    </p>
                </div>

                <!-- Centered Editorial Spread -->
                <div class="max-w-3xl mx-auto">
                    <!-- Editorial Typography Tab Switcher -->
                    <div class="space-y-6">
                        <!-- Tab Headers -->
                        <div class="flex border-b border-theme-accent/15">
                            @if(count($visionItems) > 0)
                            <button @click="activeTab = 0"
                                class="flex-1 pb-3 text-center transition-all duration-300 relative focus:outline-none"
                                :class="activeTab === 0 ? 'text-theme-accent' : 'text-slate-400 hover:text-theme-primary'">
                                <span class="block text-[8px] font-black uppercase tracking-widest">VISION</span>
                                <!-- Active Underline Indicator -->
                                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-theme-accent transition-all duration-500 transform origin-left"
                                    x-show="activeTab === 0" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="scale-x-0" x-transition:enter-end="scale-x-100"></div>
                            </button>
                            @endif

                            @if(count($missionItems) > 0)
                            <button @click="activeTab = 1"
                                class="flex-1 pb-3 text-center transition-all duration-300 relative focus:outline-none"
                                :class="activeTab === 1 ? 'text-theme-accent' : 'text-slate-400 hover:text-theme-primary'">
                                <span class="block text-[8px] font-black uppercase tracking-widest">MISSION</span>
                                <!-- Active Underline Indicator -->
                                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-theme-accent transition-all duration-500 transform origin-left"
                                    x-show="activeTab === 1" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="scale-x-0" x-transition:enter-end="scale-x-100"></div>
                            </button>
                            @endif

                            @if(count($valuesItems) > 0)
                            <button @click="activeTab = 2"
                                class="flex-1 pb-3 text-center transition-all duration-300 relative focus:outline-none"
                                :class="activeTab === 2 ? 'text-theme-accent' : 'text-slate-400 hover:text-theme-primary'">
                                <span class="block text-[8px] font-black uppercase tracking-widest">VALUES</span>
                                <!-- Active Underline Indicator -->
                                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-theme-accent transition-all duration-500 transform origin-left"
                                    x-show="activeTab === 2" x-transition:enter="transition ease-out duration-300"
                                    x-transition:enter-start="scale-x-0" x-transition:enter-end="scale-x-100"></div>
                            </button>
                            @endif
                        </div>

                        <!-- Tab Content Panel -->
                        <div class="min-h-[180px] flex flex-col justify-between">
                            <!-- Vision Pillar -->
                            <div x-show="activeTab === 0" x-transition:enter="transition ease-out duration-500 transform"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                                @foreach($visionItems as $index => $item)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-3xl font-black italic serif-title text-theme-accent/30">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <h3 class="text-lg md:text-xl font-bold text-theme-primary serif-title">
                                                {!! $item['title'] !!}
                                            </h3>
                                        </div>

                                        <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-medium">
                                            {!! $item['desc'] !!}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Mission Pillar -->
                            <div x-show="activeTab === 1" x-transition:enter="transition ease-out duration-500 transform"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                                @foreach($missionItems as $index => $item)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-3xl font-black italic serif-title text-theme-accent/30">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <h3 class="text-lg md:text-xl font-bold text-theme-primary serif-title">
                                                {!! $item['title'] !!}
                                            </h3>
                                        </div>

                                        <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-medium">
                                            {!! $item['desc'] !!}
                                        </p>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Values Pillar -->
                            <div x-show="activeTab === 2" x-transition:enter="transition ease-out duration-500 transform"
                                x-transition:enter-start="opacity-0 translate-x-4"
                                x-transition:enter-end="opacity-100 translate-x-0" class="space-y-6">
                                @foreach($valuesItems as $index => $item)
                                    <div class="space-y-4">
                                        <div class="flex items-center gap-3">
                                            <span
                                                class="text-3xl font-black italic serif-title text-theme-accent/30">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                            <h3 class="text-lg md:text-xl font-bold text-theme-primary serif-title">
                                                {!! $item['title'] !!}
                                            </h3>
                                        </div>

                                        <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-medium">
                                            {!! $item['desc'] !!}
                                        </p>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- ACHIEVEMENTS SECTION -->
    @if($hasAchievements || $isEditable)
        <section id="achievements"
            class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10 relative overflow-hidden">
            <div class="max-w-6xl mx-auto px-6 relative z-10">
                <!-- Header -->
                <div class="text-center max-w-xl mx-auto mb-8">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent dynamic-editable"
                        data-key="achieve_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_badge'] ?? 'Our Milestones' !!}
                    </span>
                    <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title dynamic-editable"
                        data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_title'] ?? 'Recent Achievements' !!}
                    </h2>
                    <p class="text-xs text-slate-500 dynamic-editable" data-key="achieve_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_desc'] ?? 'Proud moments demonstrating our dedication to academic and athletic excellence.' !!}
                    </p>
                </div>

                <!-- Asymmetric Grid Layout -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                    <!-- Card 1 -->
                    @if(!empty($settings['ach1_title']))
                        <div
                            class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="text-2xl font-light italic serif-title text-theme-accent">01</span>
                                    <span
                                        class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20 dynamic-editable"
                                        data-key="ach1_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach1_tag'] ?? 'Award' !!}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300 dynamic-editable"
                                        data-key="ach1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach1_title'] ?? 'Best School Award 2025' !!}
                                    </h3>
                                    <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                        data-key="ach1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach1_desc'] ?? 'Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.' !!}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                                <span>Milestone Reach</span>
                                <span class="text-theme-accent">Completed</span>
                            </div>
                        </div>
                    @endif

                    <!-- Card 2 -->
                    @if(!empty($settings['ach2_title']))
                        <div
                            class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="text-2xl font-light italic serif-title text-theme-accent">02</span>
                                    <span
                                        class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20 dynamic-editable"
                                        data-key="ach2_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach2_tag'] ?? 'Academics' !!}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300 dynamic-editable"
                                        data-key="ach2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach2_title'] ?? '100% Board Exam Success' !!}
                                    </h3>
                                    <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                        data-key="ach2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach2_desc'] ?? 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.' !!}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                                <span>Milestone Reach</span>
                                <span class="text-theme-accent">Completed</span>
                            </div>
                        </div>
                    @endif

                    <!-- Card 3 -->
                    @if(!empty($settings['ach3_title']))
                        <div
                            class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                            <div class="space-y-4">
                                <div class="flex justify-between items-start">
                                    <span class="text-2xl font-light italic serif-title text-theme-accent">03</span>
                                    <span
                                        class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20 dynamic-editable"
                                        data-key="ach3_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach3_tag'] ?? 'Sports' !!}
                                    </span>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300 dynamic-editable"
                                        data-key="ach3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach3_title'] ?? 'National Sports Champions' !!}
                                    </h3>
                                    <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                        data-key="ach3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach3_desc'] ?? 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.' !!}
                                    </p>
                                </div>
                            </div>
                            <div
                                class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                                <span>Milestone Reach</span>
                                <span class="text-theme-accent">Completed</span>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
    @endif

    <!-- GALLERY SECTION -->
    @if($hasGallery)
        <section id="gallery" class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10" x-data='{     
                activeFilter: "all",
                lightboxOpen: false,
                lightboxImg: "",
                lightboxTitle: "",
                lightboxTag: "",
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
            }' x-init="updateItemsPerPage(); window.addEventListener('resize', () => { updateItemsPerPage(); currentIndex = Math.min(currentIndex, maxIndex); })">

            <div class="max-w-6xl mx-auto px-6 space-y-8">
                <!-- Header -->
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">Visual Tour</span>
                    <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title font-outfit">Campus Gallery</h2>
                    <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of our Academy.</p>
                </div>

                <!-- Gallery Carousel / Slider -->
                <div class="relative w-full overflow-hidden p-1">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${currentIndex * (100 / filteredItems.length)}%); width: ${filteredItems.length * (100 / itemsPerPage)}%;`"
                        style="width: 100%;">
                        <template x-for="(item, index) in filteredItems" :key="index">
                            <div class="px-3 flex-shrink-0 transition-all duration-500 ease-out"
                                :style="`width: ${100 / filteredItems.length}%;`"
                                @click="lightboxImg = item.img; lightboxTitle = item.title; lightboxTag = item.tag; lightboxOpen = true">
                                <div class="group relative overflow-hidden border border-theme-accent/20 cursor-pointer bg-slate-50 transition-all duration-500 hover:shadow-xl rounded-2xl aspect-square w-full h-full">
                                    <!-- Zoom image -->
                                    <img :src="item.img"
                                        class="w-full h-full object-cover transition-transform duration-[4000ms] ease-out group-hover:scale-105">

                                    <!-- Overlay gradient -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-300">
                                    </div>

                                    <!-- Floating Tag -->
                                    <span
                                        class="absolute top-4 left-4 bg-white/90 border border-slate-200/25 px-2.5 py-0.5 rounded text-[8px] font-bold text-slate-800 uppercase tracking-widest shadow-sm"
                                        x-text="item.tag"></span>

                                    <!-- Info Content sliding up on hover -->
                                    <div class="absolute bottom-4 left-4 right-4 flex flex-col justify-end">
                                        <span class="text-[7.5px] font-black tracking-widest text-theme-accent uppercase"
                                            x-text="item.tag"></span>
                                        <h4 class="text-white font-black text-xs leading-tight mt-0.5" x-text="item.title"></h4>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex justify-between items-center mt-6 max-w-xs mx-auto" x-show="filteredItems.length > itemsPerPage">
                    <button @click="prev()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-accent flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &larr;
                    </button>
                    <!-- Indicators -->
                    <div class="flex items-center gap-1.5">
                        <template x-for="i in Math.max(1, filteredItems.length - itemsPerPage + 1)" :key="i">
                            <span class="h-1.5 rounded-full transition-all duration-350 cursor-pointer"
                                :class="currentIndex === (i - 1) ? 'w-6 bg-theme-accent' : 'w-2 bg-slate-200'"
                                @click="currentIndex = (i - 1)"></span>
                        </template>
                    </div>
                    <button @click="next()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-accent flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &rarr;
                    </button>
                </div>
            </div>

            <!-- Lightbox Modal -->
            <template x-teleport="body">
                <div x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-lg p-4"
                    @keydown.escape.window="lightboxOpen = false" style="display: none;">

                    <!-- Close Button -->
                    <button @click="lightboxOpen = false"
                        class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition duration-300 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Lightbox Content Container -->
                    <div class="max-w-4xl w-full flex flex-col items-center gap-4" @click.away="lightboxOpen = false">
                        <img :src="lightboxImg"
                            class="max-h-[80vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl border border-white/10"
                            x-show="lightboxOpen" x-transition:enter="transition ease-out duration-300 transform scale-95"
                            x-transition:enter-start="scale-95 opacity-0" x-transition:enter-end="scale-100 opacity-100">

                        <div class="text-center space-y-1">
                            <span class="text-[10px] font-black text-theme-accent uppercase tracking-[0.2em]"
                                x-text="lightboxTag"></span>
                            <h3 class="text-white text-base font-black" x-text="lightboxTitle"></h3>
                        </div>
                    </div>
                </div>
            </template>
        </section>
    @endif

    <!-- EVENTS SECTION -->
    @if($hasEvents)
        <section id="events" class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10">
            <div class="max-w-6xl mx-auto px-6 space-y-8">
                <!-- Header -->
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">Upcoming
                        Activities</span>
                    <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title">Events
                        Calendar</h2>
                    <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and
                        educational programs.</p>
                </div>

                <!-- Magazine Editorial Row Layout -->
                <div class="max-h-[480px] overflow-y-auto pr-3 custom-scrollbar divide-y divide-theme-accent/15">
                    @foreach($events as $index => $event)
                        @php
                            $tags = ['emerald', 'teal', 'purple'];
                            $tag = $tags[$index % 3];
                        @endphp
                        <div
                            class="group py-4 grid grid-cols-1 md:grid-cols-12 gap-4 items-start transition-all duration-300 hover:px-2">
                            <div class="md:col-span-2 flex flex-col leading-none">
                                <span
                                    class="text-3xl font-light serif-title text-theme-accent group-hover:scale-105 transform origin-left transition duration-300">{{ $event['day'] }}</span>
                                <span
                                    class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400 mt-1">{{ $event['month'] }}
                                    {{ $event['year'] }}</span>
                            </div>
                            <div class="md:col-span-10 space-y-1.5 min-w-0">
                                <div class="flex flex-wrap items-center gap-2.5 text-[9px] text-slate-400 font-medium">
                                    <span
                                        class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-{{ $tag }}-50 text-{{ $tag }}-600 border border-{{ $tag }}-100/50">{{ $event['tag'] }}</span>
                                    <span class="flex items-center gap-1">📍 <span>{{ $event['location'] }}</span></span>
                                    <span class="flex items-center gap-1">🕒 <span>{{ $event['time'] }}</span></span>
                                </div>
                                <h3
                                    class="text-sm font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">
                                    {{ $event['title'] }}</h3>
                                <p class="text-[10.5px] text-slate-500 leading-relaxed max-w-xl">
                                    {{ $event['desc'] }}
                                </p>

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
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <!-- FOOTER SECTION -->
    <footer class="bg-theme-primary text-slate-400 py-12 border-t border-slate-900/60 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="space-y-4">
                <a href="#home" class="flex flex-col leading-none">
                    <span class="text-lg font-black tracking-widest text-white uppercase">
                        {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE <span class="text-theme-accent font-light italic serif-title">Academy</span>' !!}
                    </span>
                </a>
                <p class="text-[11px] leading-relaxed text-slate-500 dynamic-editable" data-key="footer_desc"
                    contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['footer_desc'] ?? 'Empowering students through innovative education, holistic value-building, and robust global mentorship programs.' !!}
                </p>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Quick Links</h4>
                <div class="flex flex-col space-y-2 text-[11px]">
                    @if($hasHeroSlides || $isEditable)
                        <a href="#home" class="hover:text-white transition">Home</a>
                    @endif
                    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                        <a href="#about" class="hover:text-white transition">About Us</a>
                    @endif
                    @if($hasAchievements)
                        <a href="#achievements" class="hover:text-white transition">Achievements</a>
                    @endif
                    @if($hasGallery)
                        <a href="#gallery" class="hover:text-white transition">Gallery</a>
                    @endif
                    @if($hasEvents)
                        <a href="#events" class="hover:text-white transition">Events</a>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Contact Info</h4>
                <div class="space-y-2 text-[11px] text-slate-500">
                    <p class="flex items-center gap-2">📍 <span class="dynamic-editable inline-block"
                            data-key="footer_address"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_address'] ?? 'Education Valley' !!}</span>
                    </p>
                    <p class="flex items-center gap-2">📞 <span class="dynamic-editable inline-block"
                            data-key="footer_phone"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_phone'] ?? '+1 (555) 019-2834' !!}</span>
                    </p>
                    <p class="flex items-center gap-2">📧 <span class="dynamic-editable inline-block"
                            data-key="footer_email"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_email'] ?? 'info@nobleacademy.edu' !!}</span>
                    </p>
                </div>
            </div>
        </div>

        <div
            class="max-w-6xl mx-auto px-6 mt-10 pt-6 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4 text-[10.5px] text-slate-600 font-medium relative z-10">
            <p>&copy; {{ date('Y') }}
              Tuoora.
                All rights reserved.</p>

            <!-- Interactive Social Media Badges -->
            <div class="flex gap-4">
                @if(!empty($content->facebook))
                <a href="{{ $content->facebook }}" target="_blank"
                    class="hover:text-theme-accent transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                </a>
                @endif
                @if(!empty($content->twitter))
                <a href="{{ $content->twitter }}" target="_blank"
                    class="hover:text-theme-accent transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                @if(!empty($content->linkedin))
                <a href="{{ $content->linkedin }}" target="_blank"
                    class="hover:text-theme-accent transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                @endif
                @if(!empty($content->instagram))
                <a href="{{ $content->instagram }}" target="_blank"
                    class="hover:text-theme-accent transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                @endif
                @if(!empty($content->youtube))
                <a href="{{ $content->youtube }}" target="_blank"
                    class="hover:text-theme-accent transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.507a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.507 9.388.507 9.388.507s7.518 0 9.388-.507a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
            </div>

            <div class="flex gap-4">
                <a href="#" class="hover:text-white transition">Privacy Policy</a>
                <a href="#" class="hover:text-white transition">Terms of Service</a>
            </div>
        </div>
    </footer>

    <!-- MOBILE NAV TOGGLE & NAVBAR ACTIVE LINK HIGHLIGHTER SCRIPTS -->
    <script>
        // Active navigation link highlighter (on Scroll & Click)
        const menuLinks = document.querySelectorAll('.editorial-link');
        const sections = Array.from(menuLinks).map(link => {
            const hash = link.getAttribute('href');
            return document.querySelector(hash);
        }).filter(Boolean);

        function setActiveLink(hash) {
            menuLinks.forEach(link => {
                if (link.getAttribute('href') === hash) {
                    link.classList.add('active');
                } else {
                    link.classList.remove('active');
                }
            });
        }

        // Click handler to immediately set active state
        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                const hash = link.getAttribute('href');
                setActiveLink(hash);
            });
        });

        // IntersectionObserver to set active state during scroll
        const observerOptions = {
            root: null,
            rootMargin: '-30% 0px -50% 0px',
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    setActiveLink(`#${id}`);
                }
            });
        }, observerOptions);

        sections.forEach(section => {
            if (section) observer.observe(section);
        });

        // Initial active link check based on hash or scroll position
        const currentHash = window.location.hash;
        if (currentHash && document.querySelector(currentHash)) {
            setActiveLink(currentHash);
        } else {
            let currentSection = sections[0];
            const scrollPos = window.scrollY + 200;
            for (const section of sections) {
                if (section && section.offsetTop <= scrollPos) {
                    currentSection = section;
                }
            }
            if (currentSection) {
                setActiveLink(`#${currentSection.getAttribute('id')}`);
            }
        }
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