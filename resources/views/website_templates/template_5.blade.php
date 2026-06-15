<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-24">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Academy - Modern Academic Portal</title>
    <!-- Tailwind CSS for modern responsive styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800;900&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap" rel="stylesheet">
    <!-- AlpineJS for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            primary: '#0f172a',    // Deep slate 900
                            secondary: '#4f46e5',  // Indigo 600
                            accent: '#7c3aed',     // Purple 600
                            cream: '#f8fafc',      // Slate 50 canvas
                            rose: '#f43f5e',       // Rose accent
                        }
                    },
                    fontFamily: {
                        sans: ['Plus Jakarta Sans', 'sans-serif'],
                        outfit: ['Outfit', 'sans-serif'],
                        mono: ['Space Mono', 'monospace'],
                    }
                }
            }
        }
    </script>
    <style>
        html, body {
            overflow-x: hidden;
            width: 100%;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f8fafc;
            position: relative;
        }

        /* Ambient background glow orbs */
        .ambient-orb {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.08;
            pointer-events: none;
            z-index: 0;
        }

        .orb-1 {
            background: radial-gradient(circle, #4f46e5 0%, #7c3aed 100%);
            top: 10%;
            left: -100px;
        }

        .orb-2 {
            background: radial-gradient(circle, #f43f5e 0%, #ec4899 100%);
            top: 45%;
            right: -100px;
        }

        .orb-3 {
            background: radial-gradient(circle, #3b82f6 0%, #06b6d4 100%);
            bottom: 10%;
            left: 20%;
        }

        /* Modern text underline styling */
        .nav-link {
            position: relative;
            color: #475569;
            transition: color 0.3s ease;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -4px;
            left: 50%;
            width: 0;
            height: 2px;
            background: linear-gradient(to right, #4f46e5, #7c3aed);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover {
            color: #4f46e5;
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }

        .nav-link.active {
            color: #4f46e5;
            font-weight: 600;
        }
    </style>
</head>

<body class="bg-brand-cream text-brand-primary antialiased min-h-screen">

    <!-- Decorative Ambient Orbs -->
    <div class="ambient-orb orb-1"></div>
    <div class="ambient-orb orb-2"></div>
    <div class="ambient-orb orb-3"></div>

    <!-- FLOATING GLASS NAVIGATION HEADER -->
    <div class="sticky top-0 z-50 w-full px-4 sm:px-6 py-4">
        <header class="max-w-6xl mx-auto backdrop-blur-xl bg-white/70 border border-slate-200/50 rounded-full px-6 py-3.5 shadow-lg shadow-slate-100/40 flex items-center justify-between">
            <!-- LOGO / BRANDING -->
            <a href="#home" class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-xl bg-gradient-to-tr from-brand-secondary to-brand-accent flex items-center justify-center text-white font-black text-sm shadow-md shadow-brand-secondary/20">
                    {!! ($institute && $institute->template_id == 5 && isset($institute->institute_name)) ? strtoupper(substr($institute->institute_name, 0, 1)) : 'N' !!}
                </div>
                <span class="text-base font-extrabold tracking-tight font-outfit uppercase">
                    {!! ($institute && $institute->template_id == 5) ? ($institute->institute_name ?? 'NOBLE <span class="bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent font-medium">ACADEMY</span>') : 'NOBLE <span class="bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent font-medium">ACADEMY</span>' !!}
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-6 text-sm font-medium">
                <a href="#home" class="nav-link active">Home</a>
                <a href="#about" class="nav-link">Pillars</a>
                <a href="#achievements" class="nav-link">Milestones</a>
                <a href="#gallery" class="nav-link">Gallery</a>
                <a href="#events" class="nav-link">Timetable</a>
            </nav>

          
        </header>
    </div>

    <!-- HERO SECTION -->
    <section id="home" class="relative py-8 md:py-16 overflow-hidden" x-data="{
            activeSlide: 0,
            slides: [
                {
                    img: {{ json_encode($settings['hero_image_1'] ?? 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=1000&q=80') }},
                    tag: 'CAMPUS LIFE',
                    title1: 'Experiment & Innovate',
                    highlight: 'Future Pioneers',
                    title2: 'Lead With Purpose',
                    desc: 'Noble Academy offers a state-of-the-art environment promoting creative learning, academic rigor, and global perspectives.'
                },
                {
                    img: {{ json_encode($settings['hero_image_2'] ?? 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1000&q=80') }},
                    tag: 'ACADEMIC EXCELLENCE',
                    title1: 'Fostering Innovation',
                    highlight: 'Academic Honors',
                    title2: 'Pathways To Success',
                    desc: 'Our interactive curriculum prepares students for future challenges through robust theoretical and hands-on modules.'
                },
                {
                    img: {{ json_encode($settings['hero_image_3'] ?? 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1000&q=80') }},
                    tag: 'MODERN RESEARCH',
                    title1: 'Creative Workspaces',
                    highlight: 'Tech Incubators',
                    title2: 'Scientific Growth',
                    desc: 'Explore campus resources featuring advanced scientific labs, extensive research libraries, and modern sports fields.'
                }
            ]
        }">

        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center relative z-10">
            <!-- Left Column: Content Showcase -->
            <div class="lg:col-span-7 flex flex-col justify-between min-h-[380px]">
                <div class="relative flex-1 flex flex-col justify-center">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index"
                            x-transition:enter="transition ease-out duration-500 transform"
                            x-transition:enter-start="opacity-0 translate-y-4"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300 absolute inset-y-0 left-0 right-0 flex flex-col justify-center"
                            class="space-y-6">

                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-brand-secondary/5 rounded-full text-[10px] font-bold text-brand-secondary uppercase tracking-widest font-outfit"
                                x-text="slide.tag"></span>

                            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight text-brand-primary leading-tight font-outfit">
                                <span x-text="slide.title1"></span><br>
                                <span class="bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent" x-text="slide.highlight"></span><br>
                                <span x-text="slide.title2"></span>
                            </h1>

                            <p class="text-sm sm:text-base text-slate-500 max-w-lg leading-relaxed" x-text="slide.desc"></p>
                        </div>
                    </template>
                </div>

                <!-- Navigation Controls -->
                <div class="flex items-center gap-4 pt-8">
                    <button @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-55 hover:text-brand-secondary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200">
                        &larr;
                    </button>
                    <!-- Pagination Indicators -->
                    <div class="flex items-center gap-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <span class="h-1.5 rounded-full transition-all duration-300"
                                :class="activeSlide === index ? 'w-6 bg-brand-secondary' : 'w-2 bg-slate-250'"
                                @click="activeSlide = index"></span>
                        </template>
                    </div>
                    <button @click="activeSlide = (activeSlide + 1) % slides.length"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-600 hover:bg-slate-55 hover:text-brand-secondary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200">
                        &rarr;
                    </button>
                </div>
            </div>

            <!-- Right Column: Interactive Image Frame with Ambient Shadows -->
            <div class="lg:col-span-5 relative flex items-center justify-center">
                <div class="relative w-full aspect-[4/3] rounded-3xl overflow-hidden shadow-2xl shadow-indigo-900/10 border border-slate-200/40 p-2 bg-white/50 backdrop-blur-sm group">
                    <div class="w-full h-full rounded-2xl overflow-hidden relative">
                        <template x-for="(slide, index) in slides" :key="index">
                            <img :src="slide.img"
                                class="absolute inset-0 w-full h-full object-cover transition-all duration-1000 ease-in-out"
                                :class="activeSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-105'">
                        </template>

                        @if($isEditable)
                            <!-- Upload Button Overlay -->
                            <div class="absolute inset-0 bg-slate-950/40 opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex items-center justify-center z-20">
                                <button @click="document.getElementById('hero-file-input-' + activeSlide).click()" class="bg-white text-slate-900 px-4 py-2 rounded-xl text-xs font-bold shadow-md hover:scale-105 active:scale-95 transition-all">
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
                </div>
            </div>
        </div>
    </section>

    <!-- PILLARS (ABOUT) SECTION -->
    <section id="about" class="py-16 bg-white relative overflow-hidden" x-data="{
        selectedPillar: 0,
        pillars: [
            {
                num: '01',
                emoji: '🔭',
                title: 'Vision & Standard',
                desc: 'To establish global benchmarks in academic training by combining creative science labs with classical artistic expression.',
                focus: 'Holistic Tech',
                accentBg: 'bg-indigo-50',
                accentText: 'text-brand-secondary',
                detail: 'Our vision centers on nurturing multidisciplinary thinking. We integrate high-level software coding and scientific prototyping directly with classical humanities, ensuring students develop both analytical depth and creative adaptability.'
            },
            {
                num: '02',
                emoji: '🚀',
                title: 'Mission Excellence',
                desc: 'Providing stimulating learning areas where students push past academic norms to secure leadership traits and moral integrity.',
                focus: 'Leadership',
                accentBg: 'bg-purple-50',
                accentText: 'text-brand-accent',
                detail: 'Our mission is executed daily through active student mentorship and collaborative problem-solving bootcamps. We push students beyond memorization to construct real-world projects, developing critical leadership qualities.'
            },
            {
                num: '03',
                emoji: '🛡️',
                title: 'Values & Principles',
                desc: 'Anchored on unyielding tenets of mutual collaboration, active civic duties, and robust competitive growth.',
                focus: 'Core Integrity',
                accentBg: 'bg-rose-50',
                accentText: 'text-brand-rose',
                detail: 'Honor and civic responsibility form the bedrock of our student registry. We believe true excellence is collaborative, encouraging shared milestones, peer support networks, and community-enriching civic contributions.'
            }
        ]
    }">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
                
                <!-- Left Column: Interactive Selector -->
                <div class="lg:col-span-5 space-y-8">
                    <div class="space-y-3">
                        <span class="inline-block px-3 py-1 bg-brand-secondary/5 rounded-full text-[10px] font-bold text-brand-secondary uppercase tracking-widest font-outfit dynamic-editable" data-key="about_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['about_badge'] ?? 'Core Foundations' !!}
                        </span>
                        <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-brand-primary font-outfit dynamic-editable" data-key="about_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['about_title'] ?? 'OUR EDUCATIONAL PILLARS' !!}
                        </h2>
                        <p class="text-sm text-slate-500 leading-relaxed dynamic-editable" data-key="about_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['about_desc'] ?? 'Click through our key pillars below to explore the educational frameworks that define the Noble Academy learning experience.' !!}
                        </p>
                    </div>

                    <!-- Vertical Button Group -->
                    <div class="space-y-3">
                        <!-- Vision Button -->
                        <button @click="selectedPillar = 0" 
                                class="w-full text-left p-4 rounded-2xl border transition-all duration-200 flex items-center justify-between group"
                                :class="selectedPillar === 0 ? 'border-brand-secondary/35 bg-indigo-50/20 shadow-md shadow-indigo-600/5' : 'border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-200'">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-mono font-bold text-slate-400 group-hover:text-brand-secondary transition-colors">01</span>
                                <span class="text-lg group-hover:scale-110 transition-transform">🔭</span>
                                <span class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="pillar1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar1_title'] ?? 'Vision & Standard' !!}
                                </span>
                            </div>
                            <span class="text-xs font-bold transition-all" :class="selectedPillar === 0 ? 'text-brand-secondary translate-x-0' : 'text-slate-400 opacity-0 group-hover:opacity-100 group-hover:translate-x-1'">Explore &rarr;</span>
                        </button>

                        <!-- Mission Button -->
                        <button @click="selectedPillar = 1" 
                                class="w-full text-left p-4 rounded-2xl border transition-all duration-200 flex items-center justify-between group"
                                :class="selectedPillar === 1 ? 'border-brand-secondary/35 bg-indigo-50/20 shadow-md shadow-indigo-600/5' : 'border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-200'">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-mono font-bold text-slate-400 group-hover:text-brand-secondary transition-colors">02</span>
                                <span class="text-lg group-hover:scale-110 transition-transform">🚀</span>
                                <span class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="pillar2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar2_title'] ?? 'Mission Excellence' !!}
                                </span>
                            </div>
                            <span class="text-xs font-bold transition-all" :class="selectedPillar === 1 ? 'text-brand-secondary translate-x-0' : 'text-slate-400 opacity-0 group-hover:opacity-100 group-hover:translate-x-1'">Explore &rarr;</span>
                        </button>

                        <!-- Values Button -->
                        <button @click="selectedPillar = 2" 
                                class="w-full text-left p-4 rounded-2xl border transition-all duration-200 flex items-center justify-between group"
                                :class="selectedPillar === 2 ? 'border-brand-secondary/35 bg-indigo-50/20 shadow-md shadow-indigo-600/5' : 'border-slate-100 bg-slate-50/50 hover:bg-white hover:border-slate-200'">
                            <div class="flex items-center gap-4">
                                <span class="text-xs font-mono font-bold text-slate-400 group-hover:text-brand-secondary transition-colors">03</span>
                                <span class="text-lg group-hover:scale-110 transition-transform">🛡️</span>
                                <span class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="pillar3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar3_title'] ?? 'Values & Principles' !!}
                                </span>
                            </div>
                            <span class="text-xs font-bold transition-all" :class="selectedPillar === 2 ? 'text-brand-secondary translate-x-0' : 'text-slate-400 opacity-0 group-hover:opacity-100 group-hover:translate-x-1'">Explore &rarr;</span>
                        </button>
                    </div>
                </div>

                <!-- Right Column: Interactive Details Panel Card -->
                <div class="lg:col-span-7 relative">
                    <!-- Subtle background glow behind the card -->
                    <div class="absolute -inset-1 bg-gradient-to-tr from-brand-secondary to-brand-accent rounded-3xl blur opacity-10"></div>
                    
                    <div class="relative bg-white border border-slate-100 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-150/30 min-h-[380px] flex flex-col justify-between">
                        <!-- Vision Detail -->
                        <div x-show="selectedPillar === 0"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner bg-indigo-50">🔭</div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest font-mono text-slate-400">Pillar Reference</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <h3 class="text-2xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="pillar1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar1_title'] ?? 'Vision & Standard' !!}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-400 dynamic-editable" data-key="pillar1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar1_desc'] ?? 'To establish global benchmarks in academic training by combining creative science labs with classical artistic expression.' !!}
                                    </p>
                                </div>
                                
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed pt-2 dynamic-editable" data-key="pillar1_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar1_detail'] ?? 'Our vision centers on nurturing multidisciplinary thinking. We integrate high-level software coding and scientific prototyping directly with classical humanities, ensuring students develop both analytical depth and creative adaptability.' !!}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                <span class="text-slate-400">Strategic Target</span>
                                <span class="px-3 py-1 rounded-full text-[9px] bg-indigo-50 text-brand-secondary dynamic-editable" data-key="pillar1_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar1_focus'] ?? 'Holistic Tech' !!}
                                </span>
                            </div>
                        </div>

                        <!-- Mission Detail -->
                        <div x-show="selectedPillar === 1"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner bg-purple-50">🚀</div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest font-mono text-slate-400">Pillar Reference</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <h3 class="text-2xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="pillar2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar2_title'] ?? 'Mission Excellence' !!}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-400 dynamic-editable" data-key="pillar2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar2_desc'] ?? 'Providing stimulating learning areas where students push past academic norms to secure leadership traits and moral integrity.' !!}
                                    </p>
                                </div>
                                
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed pt-2 dynamic-editable" data-key="pillar2_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar2_detail'] ?? 'Our mission is executed daily through active student mentorship and collaborative problem-solving bootcamps. We push students beyond memorization to construct real-world projects, developing critical leadership qualities.' !!}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                <span class="text-slate-400">Strategic Target</span>
                                <span class="px-3 py-1 rounded-full text-[9px] bg-purple-50 text-brand-accent dynamic-editable" data-key="pillar2_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar2_focus'] ?? 'Leadership' !!}
                                </span>
                            </div>
                        </div>

                        <!-- Values Detail -->
                        <div x-show="selectedPillar === 2"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-4">
                                <div class="flex items-center justify-between">
                                    <div class="w-14 h-14 rounded-2xl flex items-center justify-center text-2xl shadow-inner bg-rose-50">🛡️</div>
                                    <span class="text-[10px] font-bold uppercase tracking-widest font-mono text-slate-400">Pillar Reference</span>
                                </div>
                                
                                <div class="space-y-2">
                                    <h3 class="text-2xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="pillar3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar3_title'] ?? 'Values & Principles' !!}
                                    </h3>
                                    <p class="text-xs font-semibold text-slate-400 dynamic-editable" data-key="pillar3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['pillar3_desc'] ?? 'Anchored on unyielding tenets of mutual collaboration, active civic duties, and robust competitive growth.' !!}
                                    </p>
                                </div>
                                
                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed pt-2 dynamic-editable" data-key="pillar3_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar3_detail'] ?? 'Honor and civic responsibility form the bedrock of our student registry. We believe true excellence is collaborative, encouraging shared milestones, peer support networks, and community-enriching civic contributions.' !!}
                                </p>
                            </div>

                            <div class="pt-6 border-t border-slate-100 flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                <span class="text-slate-400">Strategic Target</span>
                                <span class="px-3 py-1 rounded-full text-[9px] bg-rose-50 text-brand-rose dynamic-editable" data-key="pillar3_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['pillar3_focus'] ?? 'Core Integrity' !!}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- ACHIEVEMENTS SECTION -->
    <section id="achievements" class="py-16 bg-brand-cream relative overflow-hidden" x-data="{
        selectedStat: 0,
        stats: [
            {
                year: '2025',
                tag: 'AWARD',
                status: 'CONFIRMED',
                statusBg: 'bg-emerald-50',
                statusText: 'text-emerald-600',
                title: 'Best Innovative Campus',
                subtitle: 'Recognized state-wide for incorporating next-gen interactive workspaces.',
                hash: '#AC-992',
                detail: 'Approved by the Higher Education Council for state-of-the-art laboratory systems, research workspaces, and collaborative lecture environments that foster multi-disciplinary engagement.',
                progress: 95
            },
            {
                year: '100%',
                tag: 'RECORD',
                status: 'EXCELLENCE',
                statusBg: 'bg-indigo-55/60 bg-indigo-50',
                statusText: 'text-brand-secondary',
                title: 'Senior Success Rate',
                subtitle: 'For 8 consecutive years, achieving complete success distinctions.',
                hash: '#EX-100',
                detail: 'Our senior student cohort achieved a perfect graduation and college enrollment rate. 100% of graduating students secured immediate offers to global top-tier institutions.',
                progress: 100
            },
            {
                year: '04 GD',
                tag: 'SPORTS',
                status: 'ATHLETICS',
                statusBg: 'bg-rose-50',
                statusText: 'text-brand-rose',
                title: 'National Championship',
                subtitle: 'Winning top accolades in track events and secondary divisions.',
                hash: '#SP-4G2',
                detail: 'Noble Academy track and field athletes earned first-place gold distinctions at the annual National Athletics Championship across four major individual track divisions.',
                progress: 88
            }
        ]
    }">
        <div class="max-w-6xl mx-auto px-6 space-y-12 relative z-10">
            <!-- Header -->
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 pb-6 border-b border-slate-200/60">
                <div class="space-y-2">
                    <span class="text-xs font-bold uppercase tracking-wider text-brand-accent font-outfit dynamic-editable" data-key="achieve_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_badge'] ?? 'Campus Statistics' !!}
                    </span>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-brand-primary font-outfit dynamic-editable" data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_title'] ?? 'MILESTONE ACHIEVEMENTS' !!}
                    </h2>
                </div>
                <p class="text-sm text-slate-500 max-w-sm dynamic-editable" data-key="achieve_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['achieve_desc'] ?? 'Historic milestones marking our dedication towards research, scholarship, and global athletics.' !!}
                </p>
            </div>

            <!-- Modern Timeline & Details layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-stretch">
                <!-- Left: Interactive Vertical Milestones Navigator (Col-span 5) -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <!-- Stat 1 Button -->
                        <div @click="selectedStat = 0" 
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center justify-between group"
                             :class="selectedStat === 0 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center font-bold font-outfit text-sm transition-transform group-hover:scale-105"
                                     :class="selectedStat === 0 ? 'text-brand-secondary ring-2 ring-indigo-100' : 'text-slate-500'"
                                     x-text="'2025'">
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="stat1_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat1_tag'] ?? 'AWARD' !!}
                                    </span>
                                    <h4 class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat1_title'] ?? 'Best Innovative Campus' !!}
                                    </h4>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full border border-slate-150 bg-white flex items-center justify-center text-slate-400 group-hover:text-brand-secondary transition-colors">
                                <span class="text-xs font-bold" x-text="selectedStat === 0 ? '●' : '→'"></span>
                            </div>
                        </div>

                        <!-- Stat 2 Button -->
                        <div @click="selectedStat = 1" 
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center justify-between group"
                             :class="selectedStat === 1 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center font-bold font-outfit text-sm transition-transform group-hover:scale-105"
                                     :class="selectedStat === 1 ? 'text-brand-secondary ring-2 ring-indigo-100' : 'text-slate-500'"
                                     x-text="'100%'">
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="stat2_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat2_tag'] ?? 'RECORD' !!}
                                    </span>
                                    <h4 class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat2_title'] ?? 'Senior Success Rate' !!}
                                    </h4>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full border border-slate-150 bg-white flex items-center justify-center text-slate-400 group-hover:text-brand-secondary transition-colors">
                                <span class="text-xs font-bold" x-text="selectedStat === 1 ? '●' : '→'"></span>
                            </div>
                        </div>

                        <!-- Stat 3 Button -->
                        <div @click="selectedStat = 2" 
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center justify-between group"
                             :class="selectedStat === 2 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-2xl bg-white shadow-sm flex items-center justify-center font-bold font-outfit text-sm transition-transform group-hover:scale-105"
                                     :class="selectedStat === 2 ? 'text-brand-secondary ring-2 ring-indigo-100' : 'text-slate-500'"
                                     x-text="'04 GD'">
                                </div>
                                <div>
                                    <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="stat3_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat3_tag'] ?? 'SPORTS' !!}
                                    </span>
                                    <h4 class="text-sm font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat3_title'] ?? 'National Championship' !!}
                                    </h4>
                                </div>
                            </div>
                            <div class="w-8 h-8 rounded-full border border-slate-150 bg-white flex items-center justify-center text-slate-400 group-hover:text-brand-secondary transition-colors">
                                <span class="text-xs font-bold" x-text="selectedStat === 2 ? '●' : '→'"></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Immersive Interactive Details Showcase Card (Col-span 7) -->
                <div class="lg:col-span-7 relative flex">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-brand-secondary to-brand-accent rounded-3xl blur opacity-5"></div>
                    
                    <div class="relative w-full bg-white border border-slate-100 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-150/20 flex flex-col justify-between">
                        <!-- Stat 1 Detail -->
                        <div x-show="selectedStat === 0"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold font-mono text-slate-400">MILESTONE // <span class="dynamic-editable inline-block" data-key="stat1_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['stat1_tag'] ?? 'AWARD' !!}</span></span>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600">CONFIRMED</span>
                                </div>

                                <div class="space-y-2">
                                    <h3 class="text-5xl font-black font-outfit bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent">2025</h3>
                                    <h4 class="text-xl font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat1_title'] ?? 'Best Innovative Campus' !!}
                                    </h4>
                                    <p class="text-xs text-slate-500 font-semibold leading-relaxed dynamic-editable" data-key="stat1_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat1_subtitle'] ?? 'Recognized state-wide for incorporating next-gen interactive workspaces.' !!}
                                    </p>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed dynamic-editable" data-key="stat1_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['stat1_detail'] ?? 'Approved by the Higher Education Council for state-of-the-art laboratory systems, research workspaces, and collaborative lecture environments that foster multi-disciplinary engagement.' !!}
                                </p>
                            </div>

                            <!-- Mini Progress Gauge -->
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Verified Accreditation</span>
                                    <span class="text-brand-secondary font-mono">#AC-992</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out"
                                         style="width: 95%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 2 Detail -->
                        <div x-show="selectedStat === 1"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold font-mono text-slate-400">MILESTONE // <span class="dynamic-editable inline-block" data-key="stat2_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['stat2_tag'] ?? 'RECORD' !!}</span></span>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-indigo-50 text-brand-secondary">EXCELLENCE</span>
                                </div>

                                <div class="space-y-2">
                                    <h3 class="text-5xl font-black font-outfit bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent">100%</h3>
                                    <h4 class="text-xl font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat2_title'] ?? 'Senior Success Rate' !!}
                                    </h4>
                                    <p class="text-xs text-slate-500 font-semibold leading-relaxed dynamic-editable" data-key="stat2_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat2_subtitle'] ?? 'For 8 consecutive years, achieving complete success distinctions.' !!}
                                    </p>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed dynamic-editable" data-key="stat2_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['stat2_detail'] ?? 'Our senior student cohort achieved a perfect graduation and college enrollment rate. 100% of graduating students secured immediate offers to global top-tier institutions.' !!}
                                </p>
                            </div>

                            <!-- Mini Progress Gauge -->
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Verified Accreditation</span>
                                    <span class="text-brand-secondary font-mono">#EX-100</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out"
                                         style="width: 100%"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Stat 3 Detail -->
                        <div x-show="selectedStat === 2"
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            
                            <div class="space-y-6">
                                <div class="flex justify-between items-center">
                                    <span class="text-xs font-bold font-mono text-slate-400">MILESTONE // <span class="dynamic-editable inline-block" data-key="stat3_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['stat3_tag'] ?? 'SPORTS' !!}</span></span>
                                    <span class="px-3 py-1 rounded-full text-[9px] font-bold uppercase tracking-wider bg-rose-50 text-brand-rose">ATHLETICS</span>
                                </div>

                                <div class="space-y-2">
                                    <h3 class="text-5xl font-black font-outfit bg-gradient-to-r from-brand-secondary to-brand-accent bg-clip-text text-transparent">04 GD</h3>
                                    <h4 class="text-xl font-bold text-brand-primary font-outfit dynamic-editable" data-key="stat3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat3_title'] ?? 'National Championship' !!}
                                    </h4>
                                    <p class="text-xs text-slate-500 font-semibold leading-relaxed dynamic-editable" data-key="stat3_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['stat3_subtitle'] ?? 'Winning top accolades in track events and secondary divisions.' !!}
                                    </p>
                                </div>

                                <p class="text-xs sm:text-sm text-slate-500 leading-relaxed dynamic-editable" data-key="stat3_detail" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                    {!! $settings['stat3_detail'] ?? 'Noble Academy track and field athletes earned first-place gold distinctions at the annual National Athletics Championship across four major individual track divisions.' !!}
                                </p>
                            </div>

                            <!-- Mini Progress Gauge -->
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Verified Accreditation</span>
                                    <span class="text-brand-secondary font-mono">#SP-4G2</span>
                                </div>
                                <div class="w-full h-2 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out"
                                         style="width: 88%"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- GALLERY SECTION -->
    <section id="gallery" class="py-16 bg-white relative overflow-hidden" x-data="{ 
            activeFilter: 'all',
            lightboxOpen: false,
            lightboxImg: '',
            lightboxTitle: '',
            lightboxTag: '',
            lightboxIndex: 0,
            items: [
                {
                    img: {{ json_encode($settings['gallery_image_1'] ?? 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'academics',
                    tag: 'LABORATORY',
                    title: 'Chemistry Workstation'
                },
                {
                    img: {{ json_encode($settings['gallery_image_2'] ?? 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'academics',
                    tag: 'RESOURCES',
                    title: 'Central Library Archives'
                },
                {
                    img: {{ json_encode($settings['gallery_image_3'] ?? 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'infrastructure',
                    tag: 'CREATIVE LAB',
                    title: 'Digital IT Hub'
                },
                {
                    img: {{ json_encode($settings['gallery_image_4'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'infrastructure',
                    tag: 'CAMPUS',
                    title: 'University Courtyard'
                },
                {
                    img: {{ json_encode($settings['gallery_image_5'] ?? 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'sports',
                    tag: 'TRACK',
                    title: 'Running Track & Courts'
                },
                {
                    img: {{ json_encode($settings['gallery_image_6'] ?? 'https://images.unsplash.com/photo-1513364776144-60967b0f800f?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'co-curricular',
                    tag: 'ARTS',
                    title: 'Fine Arts Studio'
                }
            ]
        }">

        <div class="max-w-6xl mx-auto px-6 space-y-12 relative z-10">
            <!-- Header & Filter Navigation -->
            <div class="text-center space-y-6 max-w-2xl mx-auto">
                <div class="space-y-3">
                    <span class="inline-block px-3 py-1 bg-brand-secondary/5 rounded-full text-[10px] font-bold text-brand-secondary uppercase tracking-widest font-outfit">Visual Archives</span>
                    <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-brand-primary font-outfit">CAMPUS LIFE GALLERY</h2>
                    <p class="text-sm text-slate-500 leading-relaxed">Hover over each visual track to expand the details, or click on any item to inspect in high-fidelity view.</p>
                </div>

                <!-- Floating Glass Filter bar -->
                <div class="inline-flex flex-wrap justify-center gap-1.5 p-1.5 bg-slate-50 border border-slate-100 rounded-full shadow-inner">
                    <button @click="activeFilter = 'all'" 
                            class="px-4 py-2 rounded-full text-[10px] font-bold tracking-wide uppercase transition-all"
                            :class="activeFilter === 'all' ? 'bg-brand-secondary text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                        All
                    </button>
                    <button @click="activeFilter = 'academics'" 
                            class="px-4 py-2 rounded-full text-[10px] font-bold tracking-wide uppercase transition-all"
                            :class="activeFilter === 'academics' ? 'bg-brand-secondary text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                        Academics
                    </button>
                    <button @click="activeFilter = 'infrastructure'" 
                            class="px-4 py-2 rounded-full text-[10px] font-bold tracking-wide uppercase transition-all"
                            :class="activeFilter === 'infrastructure' ? 'bg-brand-secondary text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                        Infrastructure
                    </button>
                    <button @click="activeFilter = 'sports'" 
                            class="px-4 py-2 rounded-full text-[10px] font-bold tracking-wide uppercase transition-all"
                            :class="activeFilter === 'sports' ? 'bg-brand-secondary text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                        Sports
                    </button>
                    <button @click="activeFilter = 'co-curricular'" 
                            class="px-4 py-2 rounded-full text-[10px] font-bold tracking-wide uppercase transition-all"
                            :class="activeFilter === 'co-curricular' ? 'bg-brand-secondary text-white shadow-sm' : 'text-slate-500 hover:text-slate-800'">
                        Co-Curricular
                    </button>
                </div>
            </div>

            <!-- Hidden inputs to bind gallery images to website_settings keys -->
            @if(isset($isEditable) && $isEditable)
            <div class="hidden">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_1" :value="items[0] ? items[0].img : ''">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_2" :value="items[1] ? items[1].img : ''">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_3" :value="items[2] ? items[2].img : ''">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_4" :value="items[3] ? items[3].img : ''">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_5" :value="items[4] ? items[4].img : ''">
                <input type="hidden" class="dynamic-editable-img" data-key="gallery_image_6" :value="items[5] ? items[5].img : ''">
            </div>
            @endif

            <!-- Immersive Expanding Accordion Gallery Grid -->
            <div class="flex flex-col md:flex-row items-stretch gap-4 min-h-[460px] w-full">
                <template x-for="(item, index) in items" :key="index">
                    <div x-show="activeFilter === 'all' || activeFilter === item.cat"
                         x-transition:enter="transition ease-out duration-300 transform"
                         x-transition:enter-start="opacity-0 scale-95"
                         x-transition:enter-end="opacity-100 scale-100"
                         @click="lightboxImg = item.img; lightboxTitle = item.title; lightboxTag = item.tag; lightboxIndex = index; lightboxOpen = true"
                         class="group relative flex-1 md:hover:flex-[3.5] transition-all duration-500 ease-out overflow-hidden rounded-[2rem] cursor-pointer shadow-md hover:shadow-xl border border-slate-100 aspect-[4/3] md:aspect-auto">
                        
                        <!-- Zooming background image -->
                        <img :src="item.img" 
                             class="absolute inset-0 w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-105">

                        @if(isset($isEditable) && $isEditable)
                        <div class="absolute top-4 right-4 z-20 pointer-events-auto">
                            <button @click.stop="const fileInput = document.getElementById('gallery-file-input-' + index); if(fileInput) fileInput.click()" class="bg-black/60 hover:bg-black/80 text-white px-3 py-1.5 rounded-xl text-[10px] font-bold uppercase tracking-wider backdrop-blur-md transition flex items-center gap-1.5 shadow-lg">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                Change Image
                            </button>
                            <input type="file" :id="'gallery-file-input-' + index" class="hidden" accept="image/*" @change="uploadGalleryImage($event, index)">
                        </div>
                        @endif

                        <!-- Soft Vignette Gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-brand-primary/90 via-brand-primary/10 to-transparent transition-opacity duration-300 opacity-60 group-hover:opacity-100"></div>

                        <!-- Card text overlays -->
                        <div class="absolute inset-x-0 bottom-0 p-8 flex flex-col justify-end text-left h-full">
                            <span class="text-[9px] font-bold text-indigo-300 tracking-widest uppercase font-outfit" x-text="item.tag"></span>
                            <h4 class="text-white text-lg font-bold font-outfit mt-1 transform translate-y-2 group-hover:translate-y-0 transition-transform duration-300" x-text="item.title"></h4>
                            <p class="text-[10px] text-slate-300 mt-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 delay-100">Click to view full dimension inspect mode.</p>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- STARK LIGHTBOX -->
        <template x-teleport="body">
            <div x-show="lightboxOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-brand-primary/95 p-4"
                 @keydown.escape.window="lightboxOpen = false"
                 style="display: none;">
                
                <!-- Close Button -->
                <button @click="lightboxOpen = false" 
                        class="absolute top-6 right-6 w-12 h-12 rounded-full border border-white/20 bg-white/10 hover:bg-white/20 text-white flex items-center justify-center shadow-lg transition duration-200 focus:outline-none">
                    &times;
                </button>

                <!-- Lightbox Box -->
                <div class="max-w-3xl w-full bg-white rounded-3xl overflow-hidden p-5 shadow-2xl relative flex flex-col gap-4" @click.away="lightboxOpen = false">
                    <!-- Image panel -->
                    <div class="w-full bg-slate-50 rounded-2xl overflow-hidden p-1 flex items-center justify-center">
                        <img :src="lightboxImg" class="max-h-[65vh] w-auto max-w-full rounded-xl object-contain">
                    </div>
                    
                    <!-- Simple Footer Control Row -->
                    <div class="flex flex-col sm:flex-row items-center justify-between gap-4 font-outfit text-xs border-t border-slate-100 pt-4 px-2">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="text-[9px] bg-brand-secondary/10 text-brand-secondary px-2.5 py-0.5 rounded-full font-bold uppercase tracking-wider" x-text="lightboxTag"></span>
                            <span class="text-[9px] bg-slate-100 text-slate-500 px-2 py-0.5 rounded font-bold uppercase tracking-wider">0<span x-text="lightboxIndex + 1"></span>/0<span x-text="items.length"></span></span>
                            <h3 class="text-brand-primary font-bold ml-2 text-sm" x-text="lightboxTitle"></h3>
                        </div>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <button @click="lightboxIndex = (lightboxIndex - 1 + items.length) % items.length; lightboxImg = items[lightboxIndex].img; lightboxTitle = items[lightboxIndex].title; lightboxTag = items[lightboxIndex].tag" 
                                    class="flex-1 sm:flex-none px-4 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-55 hover:text-brand-secondary text-[10px] font-bold uppercase transition duration-150">
                                [PREV]
                            </button>
                            <button @click="lightboxIndex = (lightboxIndex + 1) % items.length; lightboxImg = items[lightboxIndex].img; lightboxTitle = items[lightboxIndex].title; lightboxTag = items[lightboxIndex].tag" 
                                    class="flex-1 sm:flex-none px-4 py-2 rounded-full border border-slate-200 bg-white hover:bg-slate-55 hover:text-brand-secondary text-[10px] font-bold uppercase transition duration-150">
                                [NEXT]
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </section>

    <!-- SCHEDULE EVENTS SECTION -->
    <section id="events" class="py-16 bg-brand-cream relative overflow-hidden" x-data="{ selectedEvent: 0 }">
        <div class="max-w-6xl mx-auto px-6 space-y-12 relative z-10">
            <!-- Header -->
            <div class="text-center space-y-3 max-w-xl mx-auto">
                <span class="inline-block px-3 py-1 bg-brand-accent/5 rounded-full text-[10px] font-bold text-brand-accent uppercase tracking-widest font-outfit">Live Updates</span>
                <h2 class="text-3xl md:text-4xl font-extrabold tracking-tight text-brand-primary font-outfit">ACADEMIC SCHEDULE</h2>
                <p class="text-sm text-slate-500 leading-relaxed">Stay updated with upcoming global university seminars, active coding drives, and annual classical showcases.</p>
            </div>

            <!-- Modern Split Dashboard Layout -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-stretch">
                <!-- Left: Event Selector Cards (Col-span 5) -->
                <div class="lg:col-span-5 flex flex-col justify-between space-y-4">
                    <div class="space-y-3">
                        <!-- Selector 1 -->
                        <div @click="selectedEvent = 0"
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center gap-5 group"
                             :class="selectedEvent === 0 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center leading-none transition-transform group-hover:scale-105"
                                 :class="selectedEvent === 0 ? 'bg-indigo-50 text-brand-secondary ring-2 ring-indigo-100' : 'bg-white text-slate-500 shadow-sm'">
                                <span class="text-base font-extrabold font-outfit dynamic-editable" data-key="event_1_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_day'] ?? '25' !!}</span>
                                <span class="text-[8px] font-bold uppercase tracking-wider mt-0.5 dynamic-editable" data-key="event_1_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_month'] ?? 'JUN' !!}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="event_1_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_category'] ?? 'SUMMIT' !!}</span>
                                <h4 class="text-sm font-bold text-brand-primary font-outfit truncate dynamic-editable" data-key="event_1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_title'] ?? 'Global Alumni Summit 2026' !!}</h4>
                                <p class="text-[10px] text-slate-400 font-mono truncate"><span class="dynamic-editable" data-key="event_1_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_time'] ?? '10:00 AM' !!}</span> | <span class="dynamic-editable" data-key="event_1_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_location'] ?? 'Main Auditorium' !!}</span></p>
                            </div>
                        </div>
                        <!-- Selector 2 -->
                        <div @click="selectedEvent = 1"
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center gap-5 group"
                             :class="selectedEvent === 1 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center leading-none transition-transform group-hover:scale-105"
                                 :class="selectedEvent === 1 ? 'bg-indigo-50 text-brand-secondary ring-2 ring-indigo-100' : 'bg-white text-slate-500 shadow-sm'">
                                <span class="text-base font-extrabold font-outfit dynamic-editable" data-key="event_2_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_day'] ?? '10' !!}</span>
                                <span class="text-[8px] font-bold uppercase tracking-wider mt-0.5 dynamic-editable" data-key="event_2_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_month'] ?? 'JUL' !!}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="event_2_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_category'] ?? 'ECOLOGY' !!}</span>
                                <h4 class="text-sm font-bold text-brand-primary font-outfit truncate dynamic-editable" data-key="event_2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_title'] ?? 'Sustainability Campaign Drive' !!}</h4>
                                <p class="text-[10px] text-slate-400 font-mono truncate"><span class="dynamic-editable" data-key="event_2_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_time'] ?? '02:30 PM' !!}</span> | <span class="dynamic-editable" data-key="event_2_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_location'] ?? 'Science Block Yard' !!}</span></p>
                            </div>
                        </div>
                        <!-- Selector 3 -->
                        <div @click="selectedEvent = 2"
                             class="p-5 rounded-3xl border transition-all duration-300 cursor-pointer flex items-center gap-5 group"
                             :class="selectedEvent === 2 ? 'bg-white border-slate-100 shadow-lg shadow-slate-150/20' : 'bg-transparent border-transparent hover:bg-slate-100/50'">
                            <div class="w-14 h-14 rounded-2xl flex flex-col items-center justify-center leading-none transition-transform group-hover:scale-105"
                                 :class="selectedEvent === 2 ? 'bg-indigo-50 text-brand-secondary ring-2 ring-indigo-100' : 'bg-white text-slate-500 shadow-sm'">
                                <span class="text-base font-extrabold font-outfit dynamic-editable" data-key="event_3_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_day'] ?? '05' !!}</span>
                                <span class="text-[8px] font-bold uppercase tracking-wider mt-0.5 dynamic-editable" data-key="event_3_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_month'] ?? 'AUG' !!}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <span class="text-[9px] font-bold text-slate-400 font-mono dynamic-editable" data-key="event_3_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_category'] ?? 'EXHIBITION' !!}</span>
                                <h4 class="text-sm font-bold text-brand-primary font-outfit truncate dynamic-editable" data-key="event_3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_title'] ?? 'Arts & Classical Showcase' !!}</h4>
                                <p class="text-[10px] text-slate-400 font-mono truncate"><span class="dynamic-editable" data-key="event_3_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_time'] ?? '06:00 PM' !!}</span> | <span class="dynamic-editable" data-key="event_3_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_location'] ?? 'Creative Center' !!}</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right: Detailed Event Card (Col-span 7) -->
                <div class="lg:col-span-7 relative flex">
                    <div class="absolute -inset-1 bg-gradient-to-tr from-brand-secondary to-brand-accent rounded-3xl blur opacity-5"></div>
                    
                    <div class="relative w-full bg-white border border-slate-100 rounded-3xl p-8 md:p-10 shadow-xl shadow-slate-150/20 flex flex-col justify-between">
                        <!-- Detail Panel 1 -->
                        <div x-show="selectedEvent === 0"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-5">
                                <div class="flex justify-between items-center">
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-bold uppercase tracking-wide dynamic-editable" data-key="event_1_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_category'] ?? 'SUMMIT' !!}</span>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-mono">
                                        <span>📍</span>
                                        <span class="dynamic-editable" data-key="event_1_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_location'] ?? 'Main Auditorium' !!}</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="event_1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_title'] ?? 'Global Alumni Summit 2026' !!}</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="event_1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_desc'] ?? 'Connecting graduating seniors with active technical leaders in worldwide tech divisions. Discover post-grad placement paths, industry integration challenges, and direct job offerings.' !!}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100/50 flex items-start gap-3">
                                    <div class="text-lg">🎙️</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-brand-primary dynamic-editable" data-key="event_1_speaker" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_speaker'] ?? 'Dr. Sarah Jenkins & Alumni Panel' !!}</h4>
                                        <p class="text-[9px] text-slate-400 mt-0.5 dynamic-editable" data-key="event_1_speaker_role" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_speaker_role'] ?? 'Director of Tech Innovation / Senior Alumni Members' !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Seat Booking Status</span>
                                    <span class="text-brand-secondary dynamic-editable" data-key="event_1_occupancy" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_occupancy'] ?? '88% Capacity Booked' !!}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out w-[88%]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Panel 2 -->
                        <div x-show="selectedEvent === 1"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-5">
                                <div class="flex justify-between items-center">
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-bold uppercase tracking-wide dynamic-editable" data-key="event_2_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_category'] ?? 'ECOLOGY' !!}</span>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-mono">
                                        <span>📍</span>
                                        <span class="dynamic-editable" data-key="event_2_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_location'] ?? 'Science Block Yard' !!}</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="event_2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_title'] ?? 'Sustainability Campaign Drive' !!}</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="event_2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_desc'] ?? 'Exploring green layouts, eco energy cells, and active recycling standards on campus. Participate in live solar panel workshops and campus ecosystem sustainability challenges.' !!}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100/50 flex items-start gap-3">
                                    <div class="text-lg">🎙️</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-brand-primary dynamic-editable" data-key="event_2_speaker" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_speaker'] ?? 'Prof. Marcus Vance' !!}</h4>
                                        <p class="text-[9px] text-slate-400 mt-0.5 dynamic-editable" data-key="event_2_speaker_role" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_speaker_role'] ?? 'Dean of Environmental Science Department' !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Seat Booking Status</span>
                                    <span class="text-brand-secondary dynamic-editable" data-key="event_2_occupancy" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_occupancy'] ?? '65% Capacity Booked' !!}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out w-[65%]"></div>
                                </div>
                            </div>
                        </div>

                        <!-- Detail Panel 3 -->
                        <div x-show="selectedEvent === 2"
                             x-transition:enter="transition ease-out duration-350 transform"
                             x-transition:enter-start="opacity-0 scale-95"
                             x-transition:enter-end="opacity-100 scale-100"
                             class="space-y-6 flex-1 flex flex-col justify-between">
                            <div class="space-y-5">
                                <div class="flex justify-between items-center">
                                    <span class="inline-block px-2.5 py-0.5 bg-emerald-50 text-emerald-600 rounded-full text-[8px] font-bold uppercase tracking-wide dynamic-editable" data-key="event_3_category" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_category'] ?? 'EXHIBITION' !!}</span>
                                    <div class="flex items-center gap-1.5 text-xs text-slate-400 font-mono">
                                        <span>📍</span>
                                        <span class="dynamic-editable" data-key="event_3_location" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_location'] ?? 'Creative Center' !!}</span>
                                    </div>
                                </div>
                                <div class="space-y-2">
                                    <h3 class="text-xl font-extrabold text-brand-primary font-outfit dynamic-editable" data-key="event_3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_title'] ?? 'Arts & Classical Showcase' !!}</h3>
                                    <p class="text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="event_3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_desc'] ?? 'Annual visual reviews, custom sculpting exhibits, and classical musical performances. Highlights include student art galleries, short film reviews, and live orchestra performances.' !!}</p>
                                </div>
                                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100/50 flex items-start gap-3">
                                    <div class="text-lg">🎙️</div>
                                    <div>
                                        <h4 class="text-xs font-bold text-brand-primary dynamic-editable" data-key="event_3_speaker" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_speaker'] ?? 'Elena Rostova' !!}</h4>
                                        <p class="text-[9px] text-slate-400 mt-0.5 dynamic-editable" data-key="event_3_speaker_role" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_speaker_role'] ?? 'Chairperson of Classical Fine Arts Division' !!}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="pt-6 border-t border-slate-100 space-y-3">
                                <div class="flex justify-between items-center text-[10px] font-bold uppercase tracking-wider font-outfit">
                                    <span class="text-slate-400">Seat Booking Status</span>
                                    <span class="text-brand-secondary dynamic-editable" data-key="event_3_occupancy" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_occupancy'] ?? '92% Capacity Booked' !!}</span>
                                </div>
                                <div class="w-full h-1.5 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-gradient-to-r from-brand-secondary to-brand-accent rounded-full transition-all duration-1000 ease-out w-[92%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-slate-50 text-slate-600 py-16 border-t border-slate-100 relative overflow-hidden">
        <!-- Ambient Background Glow Orbs -->
        <div class="absolute -top-40 -left-40 w-96 h-96 bg-brand-secondary/5 rounded-full filter blur-[100px] animate-pulse pointer-events-none" style="animation-duration: 8s;"></div>
        <div class="absolute -bottom-40 -right-40 w-96 h-96 bg-brand-accent/5 rounded-full filter blur-[100px] animate-pulse pointer-events-none" style="animation-duration: 12s;"></div>

        <div class="max-w-6xl mx-auto px-6 relative z-10 space-y-12">
            
            <!-- Top Column Grid: 3 columns -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-12 pb-12 border-b border-slate-200/80">
                
                <!-- Left: Branding & Bio -->
                <div class="space-y-5">
                    <a href="#home" class="flex items-center gap-2 group">
                        <div class="w-10 h-10 rounded-2xl bg-gradient-to-tr from-brand-secondary to-brand-accent flex items-center justify-center text-white font-black text-base shadow-lg shadow-indigo-600/20 group-hover:scale-105 transition-transform duration-300">
                            {!! ($institute && $institute->template_id == 5 && isset($institute->institute_name)) ? strtoupper(substr($institute->institute_name, 0, 1)) : 'N' !!}
                        </div>
                        <span class="text-lg font-extrabold tracking-tight text-brand-primary font-outfit uppercase">
                            {!! ($institute && $institute->template_id == 5) ? ($institute->institute_name ?? 'NOBLE <span class="text-brand-secondary font-medium">ACADEMY</span>') : 'NOBLE <span class="text-brand-secondary font-medium">ACADEMY</span>' !!}
                        </span>
                    </a>
                    <p class="text-xs text-slate-500 leading-relaxed max-w-sm dynamic-editable" data-key="footer_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['footer_desc'] ?? 'A premium and responsive design system featuring elegant glass components, rich fluid animations, and high-contrast styling.' !!}
                    </p>
                    <!-- Social icons -->
                    <div class="flex items-center gap-3 pt-2">
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-brand-secondary hover:text-white text-slate-650 flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/20">
                            <span class="text-[10px] font-bold">𝕏</span>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-brand-secondary hover:text-white text-slate-650 flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/20">
                            <span class="text-[10px] font-bold">IN</span>
                        </a>
                        <a href="#" class="w-8 h-8 rounded-full bg-slate-200 hover:bg-brand-secondary hover:text-white text-slate-650 flex items-center justify-center transition-all duration-300 hover:-translate-y-1 hover:shadow-lg hover:shadow-indigo-500/20">
                            <span class="text-[10px] font-bold">IG</span>
                        </a>
                    </div>
                </div>

                <!-- Center: Interactive Quick Links -->
                <div class="space-y-5 font-outfit">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">QUICK LINKS</h4>
                    <div class="grid grid-cols-2 gap-3 text-xs">
                        <div class="flex flex-col space-y-2.5">
                            <a href="#home" class="group flex items-center gap-1.5 text-slate-600 hover:text-brand-secondary transition-colors duration-200">
                                <span class="w-1 h-1 rounded-full bg-brand-secondary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Home</span>
                            </a>
                            <a href="#about" class="group flex items-center gap-1.5 text-slate-600 hover:text-brand-secondary transition-colors duration-200">
                                <span class="w-1 h-1 rounded-full bg-brand-secondary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Pillars</span>
                            </a>
                            <a href="#achievements" class="group flex items-center gap-1.5 text-slate-600 hover:text-brand-secondary transition-colors duration-200">
                                <span class="w-1 h-1 rounded-full bg-brand-secondary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Milestones</span>
                            </a>
                             <a href="#gallery" class="group flex items-center gap-1.5 text-slate-600 hover:text-brand-secondary transition-colors duration-200">
                                <span class="w-1 h-1 rounded-full bg-brand-secondary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Gallery</span>
                            </a>
                            <a href="#events" class="group flex items-center gap-1.5 text-slate-600 hover:text-brand-secondary transition-colors duration-200">
                                <span class="w-1 h-1 rounded-full bg-brand-secondary opacity-0 group-hover:opacity-100 transition-opacity"></span>
                                <span class="group-hover:translate-x-1 transition-transform">Timetable</span>
                            </a>
                        </div>
                      
                    </div>
                </div>

                <!-- Right: Office Contact Info -->
                <div class="space-y-5 font-outfit">
                    <h4 class="text-[10px] font-bold uppercase tracking-widest text-slate-400">OFFICE CONTACT</h4>
                    <div class="space-y-3.5 text-xs text-slate-500">
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📍</span>
                            <span class="dynamic-editable" data-key="footer_address" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['footer_address'] ?? 'Education Valley 12, Campus Zone' !!}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📞</span>
                            <span class="dynamic-editable" data-key="footer_phone" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['footer_phone'] ?? '+1 (555) 019-2834' !!}
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="text-sm">📧</span>
                            <span class="dynamic-editable" data-key="footer_email" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['footer_email'] ?? 'info@nobleacademy.edu' !!}
                            </span>
                        </div>
                    </div>
                </div>

            </div>

            <!-- Bottom: Legal Details -->
            <div class="flex flex-col md:flex-row justify-between items-center gap-6 text-[10px] text-slate-400 font-outfit">
                <div class="flex flex-col md:flex-row items-center gap-6">
                    <p>&copy; {{ date('Y') }} {!! ($institute && $institute->template_id == 5) ? ($institute->institute_name ?? 'Noble Academy') : 'Noble Academy' !!}. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="#" class="hover:text-slate-600 transition-colors">Privacy Policy</a>
                        <a href="#" class="hover:text-slate-600 transition-colors">Terms of Service</a>
                    </div>
                </div>
            </div>

        </div>
    </footer>

    <!-- NAVBAR HIGHLIGHTER & ACTIVE SCROLL SCRIPT -->
    <script>
        const menuLinks = document.querySelectorAll('.nav-link');
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

        menuLinks.forEach(link => {
            link.addEventListener('click', () => {
                setActiveLink(link.getAttribute('href'));
            });
        });

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
    </script>

    @include('website_templates.customizer_script')
</body>

</html>


