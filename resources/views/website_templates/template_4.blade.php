<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-20">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Academy - Luxury Academic Template</title>
    <!-- Tailwind CSS for modern responsive styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Cormorant Garamond & Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
    </style>
</head>

<body class="bg-theme-cream text-slate-800 antialiased min-h-screen">

    <!-- NAVIGATION HEADER -->
    <header class="sticky top-0 z-50 w-full bg-theme-cream/90 backdrop-blur-xl border-b border-theme-accent/10 py-4">
        <div class="max-w-6xl mx-auto px-6 flex items-center justify-between">
            <!-- LOGO -->
            <a href="#home" class="flex flex-col leading-none">
                <span class="text-lg font-black tracking-widest text-theme-primary uppercase">
                    NOBLE <span class="text-theme-accent font-light italic serif-title">Academy</span>
                </span>
                <span class="text-[7.5px] uppercase tracking-[0.45em] text-slate-400 mt-1 font-bold">
                    Est. 2012 • Legacy of Excellence
                </span>
            </a>

            <!-- Navigation Links -->
            <nav class="hidden md:flex items-center gap-6">
                <a href="#home" class="editorial-link active">Home</a>
                <a href="#about" class="editorial-link">About</a>
                <a href="#achievements" class="editorial-link">Milestones</a>
                <a href="#gallery" class="editorial-link">Gallery</a>
                <a href="#events" class="editorial-link">Events</a>
            </nav>

           
           
        </div>
    </header>

    <!-- HERO SECTION -->
    <section id="home" class="relative py-12 md:py-16 bg-theme-ivory border-b border-theme-accent/10 overflow-hidden"
        x-data="{
            activeSlide: 0,
            slides: [
                {
                    img: 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                    badge: 'NOBLE',
                    badgeText: 'Academy Campus',
                    title1: 'Empowering',
                    accent: 'Minds',
                    title2: 'Shaping Futures',
                    desc: 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=800&q=80',
                    badge: 'ACADEMICS',
                    badgeText: 'Innovative Programs',
                    title1: 'Innovative',
                    accent: 'Academic',
                    title2: 'Interactive Learning',
                    desc: 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=800&q=80',
                    badge: 'INFRASTRUCTURE',
                    badgeText: 'State-Of-The-Art Labs',
                    title1: 'Modern',
                    accent: 'Classrooms',
                    title2: 'Future Infrastructure',
                    desc: 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
                }
            ]
        }">

        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 lg:grid-cols-12 gap-4 items-center">
            <!-- Left Side: Editorial Typography -->
            <div class="lg:col-span-7 space-y-3 relative z-10">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                        x-transition:enter="transition ease-out duration-700 transform"
                        x-transition:enter-start="opacity-0 translate-y-4"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300 absolute inset-0 flex flex-col justify-center"
                        class="space-y-2">
                        
                        <div class="inline-flex items-center gap-2">
                            <span class="text-[9px] font-black uppercase tracking-[0.25em] text-theme-accent" x-text="slide.badge"></span>
                            <span class="text-[9px] font-bold text-slate-400 uppercase tracking-wider" x-text="slide.badgeText"></span>
                        </div>

                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-theme-primary leading-[1.05] tracking-tight serif-title">
                            <span x-text="slide.title1"></span><br>
                            <span class="text-theme-accent italic font-light font-serif" x-text="slide.accent"></span><br>
                            <span x-text="slide.title2"></span>
                        </h1>

                        <p class="text-xs md:text-sm text-slate-500 max-w-lg leading-relaxed font-medium" x-text="slide.desc"></p>
                    </div>
                </template>
            </div>

            <!-- Right Side: Luxury Framed Slide Showcase -->
            <div class="lg:col-span-5 relative flex flex-col items-center">
                <div class="w-full aspect-[4/3] rounded-3xl overflow-hidden luxury-frame bg-slate-100 relative shadow-2xl">
                    <template x-for="(slide, index) in slides" :key="index">
                        <img :src="slide.img" 
                             class="absolute inset-0 w-full h-full object-cover transition-opacity duration-1000 ease-in-out"
                             :class="activeSlide === index ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
                    </template>
                </div>

                <!-- Custom Slider Controls -->
                <div class="flex items-center gap-4 mt-3">
                    <button @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length" 
                            class="h-8 w-8 rounded-full border border-theme-accent/30 flex items-center justify-center text-theme-accent hover:bg-theme-accent hover:text-white transition duration-300">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <!-- Indicators -->
                    <div class="flex items-center gap-2">
                        <template x-for="(slide, index) in slides" :key="index">
                            <button @click="activeSlide = index" 
                                    class="h-1.5 rounded-full transition-all duration-300"
                                    :class="activeSlide === index ? 'w-6 bg-theme-accent' : 'w-1.5 bg-slate-300'"></button>
                        </template>
                    </div>
                    <button @click="activeSlide = (activeSlide + 1) % slides.length" 
                            class="h-8 w-8 rounded-full border border-theme-accent/30 flex items-center justify-center text-theme-accent hover:bg-theme-accent hover:text-white transition duration-300">
                        <svg class="h-3 w-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="py-12 md:py-16 px-6 bg-theme-cream border-b border-theme-accent/10"
        x-data="{ 
            activeTab: 0,
            pillars: [
                {
                    num: '01',
                    icon: '🔭',
                    badge: 'VISION',
                    title: 'Nurturing Leaders Since 2012',
                    desc: 'To establish a global standard in education that balances academic rigor with creative expression, cultivating visionary leaders of tomorrow.',
                    focus: ['Holistic Growth', 'Integrated Tech', 'Creative Innovation'],
                    img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80'
                },
                {
                    num: '02',
                    icon: '🚀',
                    badge: 'MISSION',
                    title: 'Fostering Excellence & Integrity',
                    desc: 'To provide a stimulating learning environment where students excel academically, develop strong moral values, and become responsible global citizens.',
                    focus: ['Qualified Mentors', 'Student curriculum', 'Civic Foundations'],
                    img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80'
                },
                {
                    num: '03',
                    icon: '🛡️',
                    badge: 'VALUES',
                    title: 'Our Core Pillars of Success',
                    desc: 'We are anchored in key moral and academic tenets that guide every lesson, interaction, and milestone achieved within our campus.',
                    focus: ['Unyielding Integrity', 'Empathetic Collaboration', 'Inquisitive Mindsets'],
                    img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=600&q=80'
                }
            ]
        }">
        <div class="max-w-6xl mx-auto space-y-10">
            <!-- Header -->
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">About Our Academy</span>
                <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title">Our Foundational Framework</h2>
                <p class="text-xs text-slate-500">Combining academic innovation with core values to empower the leaders of tomorrow.</p>
            </div>

            <!-- Asymmetric Editorial Spread -->
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <!-- Left: Luxury Framed Visuals (Dynamic based on tab) -->
                <div class="lg:col-span-5 relative flex items-center justify-center py-4">
                    <div class="relative w-full aspect-square max-w-[320px]">
                        <!-- Background Gold Decorative Frame -->
                        <div class="absolute -inset-3 border border-theme-accent/30 rounded-3xl translate-x-2 translate-y-2 pointer-events-none"></div>
                        
                        <!-- Main Image frame -->
                        <div class="w-full h-full rounded-2xl overflow-hidden luxury-frame bg-slate-100 relative shadow-xl">
                            <template x-for="(pillar, index) in pillars" :key="index">
                                <img :src="pillar.img" 
                                     class="absolute inset-0 w-full h-full object-cover transition-opacity duration-700 ease-in-out"
                                     :class="activeTab === index ? 'opacity-100 scale-100' : 'opacity-0 scale-95'">
                            </template>
                        </div>

                        <!-- Accent Stamp -->
                        <!-- <div class="absolute -bottom-4 -left-4 bg-theme-primary text-theme-cream border border-theme-accent/25 px-4 py-2.5 rounded-xl shadow-lg flex items-center gap-2">
                            <span class="text-xs tracking-[0.2em] font-black uppercase">EST. 2012</span>
                        </div> -->
                    </div>
                </div>

                <!-- Right: Editorial Typography Tab Switcher -->
                <div class="lg:col-span-7 space-y-6">
                    <!-- Tab Headers -->
                    <div class="flex border-b border-theme-accent/15">
                        <template x-for="(pillar, index) in pillars" :key="index">
                            <button @click="activeTab = index" 
                                    class="flex-1 pb-3 text-center transition-all duration-300 relative focus:outline-none"
                                    :class="activeTab === index ? 'text-theme-accent' : 'text-slate-400 hover:text-theme-primary'">
                                <span class="block text-[8px] font-black uppercase tracking-widest" x-text="pillar.badge"></span>
                                <!-- Active Underline Indicator -->
                                <div class="absolute bottom-0 left-0 right-0 h-[2px] bg-theme-accent transition-all duration-500 transform origin-left"
                                     x-show="activeTab === index"
                                     x-transition:enter="transition ease-out duration-300"
                                     x-transition:enter-start="scale-x-0"
                                     x-transition:enter-end="scale-x-100"></div>
                            </button>
                        </template>
                    </div>

                    <!-- Tab Content Panel -->
                    <div class="min-h-[180px] flex flex-col justify-between">
                        <template x-for="(pillar, index) in pillars" :key="index">
                            <div x-show="activeTab === index"
                                 x-transition:enter="transition ease-out duration-500 transform"
                                 x-transition:enter-start="opacity-0 translate-x-4"
                                 x-transition:enter-end="opacity-100 translate-x-0"
                                 class="space-y-4">
                                
                                <div class="flex items-center gap-3">
                                    <span class="text-3xl font-black italic serif-title text-theme-accent/30" x-text="pillar.num"></span>
                                    <h3 class="text-lg md:text-xl font-bold text-theme-primary serif-title" x-text="pillar.title"></h3>
                                </div>

                                <p class="text-xs md:text-sm text-slate-500 leading-relaxed font-medium" x-text="pillar.desc"></p>

                                <div class="pt-4 border-t border-theme-accent/10">
                                    <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-2">Key Areas of Impact</span>
                                    <div class="flex flex-wrap gap-2">
                                        <template x-for="item in pillar.focus" :key="item">
                                            <span class="px-2.5 py-1 bg-white border border-theme-accent/15 rounded-md text-[9px] font-bold text-theme-accent tracking-wide" x-text="item"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- ACHIEVEMENTS SECTION -->
    <section id="achievements" class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 relative z-10">
            <!-- Header -->
            <div class="text-center max-w-xl mx-auto mb-8">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">Our Milestones</span>
                <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title">Recent Achievements</h2>
                <p class="text-xs text-slate-500">Proud moments demonstrating our dedication to academic and athletic excellence.</p>
            </div>

            <!-- Asymmetric Grid Layout -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 relative">
                <!-- Card 1 -->
                <div class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-2xl font-light italic serif-title text-theme-accent">01</span>
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20">Award</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">Best School Award 2025</h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                        <span>Milestone Reach</span>
                        <span class="text-theme-accent">Completed</span>
                    </div>
                </div>

                <!-- Card 2 -->
                <div class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-2xl font-light italic serif-title text-theme-accent">02</span>
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20">Academics</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">100% Board Exam Success</h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                        <span>Milestone Reach</span>
                        <span class="text-theme-accent">Completed</span>
                    </div>
                </div>

                <!-- Card 3 -->
                <div class="group luxury-frame luxury-frame-hover rounded-2xl p-5 bg-white flex flex-col justify-between min-h-[280px]">
                    <div class="space-y-4">
                        <div class="flex justify-between items-start">
                            <span class="text-2xl font-light italic serif-title text-theme-accent">03</span>
                            <span class="px-2 py-0.5 rounded-lg text-[8px] font-black uppercase tracking-wider bg-theme-accent/10 text-theme-accent border border-theme-accent/20">Sports</span>
                        </div>
                        <div class="space-y-2">
                            <h3 class="text-xs font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">National Sports Champions</h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.
                            </p>
                        </div>
                    </div>
                    <div class="pt-4 border-t border-slate-100 flex items-center justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">
                        <span>Milestone Reach</span>
                        <span class="text-theme-accent">Completed</span>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY SECTION -->
    <section id="gallery" class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10" x-data="{ 
            activeFilter: 'all',
            lightboxOpen: false,
            lightboxImg: '',
            lightboxTitle: '',
            lightboxTag: '',
            items: [
                {
                    img: 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80',
                    cat: 'academics',
                    tag: 'Laboratory',
                    title: 'Chemistry Research Lab'
                },
                {
                    img: 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80',
                    cat: 'academics',
                    tag: 'Academics',
                    title: 'Resource Center & Library'
                },
                {
                    img: 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
                    cat: 'sports',
                    tag: 'Sports',
                    title: 'Athletic Running Track'
                },
                {
                    img: 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80',
                    cat: 'co-curricular',
                    tag: 'Co-Curricular',
                    title: 'Creative Arts & Pottery'
                },
                {
                    img: 'https://images.unsplash.com/photo-1581092918056-0c4c3acd37bd?auto=format&fit=crop&w=800&q=80',
                    cat: 'infrastructure',
                    tag: 'Technology',
                    title: 'Digital IT Hub & Coding Lab'
                },
                {
                    img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80',
                    cat: 'infrastructure',
                    tag: 'Campus',
                    title: 'Central University Courtyard'
                }
            ]
        }">

        <div class="max-w-6xl mx-auto px-6 space-y-8">
            <!-- Header -->
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">Visual Tour</span>
                <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title">Campus Gallery</h2>
                <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of Noble Academy.</p>
            </div>

            <!-- Filter Navigation -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 max-w-fit mx-auto border-b border-slate-200 pb-3">
                <button @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'text-theme-accent font-black' : 'text-slate-400 hover:text-slate-650'"
                    class="px-4 py-1 text-[10px] font-bold transition-all uppercase tracking-widest">
                    All
                </button>
                <span class="text-slate-200">|</span>
                <button @click="activeFilter = 'academics'"
                    :class="activeFilter === 'academics' ? 'text-theme-accent font-black' : 'text-slate-400 hover:text-slate-650'"
                    class="px-4 py-1 text-[10px] font-bold transition-all uppercase tracking-widest">
                    Academics
                </button>
                <span class="text-slate-200">|</span>
                <button @click="activeFilter = 'infrastructure'"
                    :class="activeFilter === 'infrastructure' ? 'text-theme-accent font-black' : 'text-slate-400 hover:text-slate-650'"
                    class="px-4 py-1 text-[10px] font-bold transition-all uppercase tracking-widest">
                    Infrastructure
                </button>
                <span class="text-slate-200">|</span>
                <button @click="activeFilter = 'sports'"
                    :class="activeFilter === 'sports' ? 'text-theme-accent font-black' : 'text-slate-400 hover:text-slate-650'"
                    class="px-4 py-1 text-[10px] font-bold transition-all uppercase tracking-widest">
                    Sports
                </button>
                <span class="text-slate-200">|</span>
                <button @click="activeFilter = 'co-curricular'"
                    :class="activeFilter === 'co-curricular' ? 'text-theme-accent font-black' : 'text-slate-400 hover:text-slate-650'"
                    class="px-4 py-1 text-[10px] font-bold transition-all uppercase tracking-widest">
                    Co-Curricular
                </button>
            </div>

            <!-- Bento Grid Layout -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(item, index) in items" :key="index">
                    <div x-show="activeFilter === 'all' || activeFilter === item.cat"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200 absolute"
                        @click="lightboxImg = item.img; lightboxTitle = item.title; lightboxTag = item.tag; lightboxOpen = true"
                        class="group relative overflow-hidden border border-theme-accent/20 cursor-pointer bg-slate-50 transition-all duration-500 hover:shadow-xl rounded-2xl"
                        :class="index === 0 || index === 5 ? 'lg:col-span-2 aspect-[16/9]' : 'aspect-square'">

                        <!-- Zoom image -->
                        <img :src="item.img"
                            class="w-full h-full object-cover transition-transform duration-[4000ms] ease-out group-hover:scale-105">

                        <!-- Overlay gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-300"></div>

                        <!-- Floating Tag -->
                        <span class="absolute top-4 left-4 bg-white/90 border border-slate-200/25 px-2.5 py-0.5 rounded text-[8px] font-bold text-slate-800 uppercase tracking-widest shadow-sm" x-text="item.tag"></span>

                        <!-- Info Content sliding up on hover -->
                        <div class="absolute bottom-4 left-4 right-4 flex flex-col justify-end">
                            <span class="text-[7.5px] font-black tracking-widest text-theme-accent uppercase" x-text="item.tag"></span>
                            <h4 class="text-white font-black text-xs leading-tight mt-0.5" x-text="item.title"></h4>
                        </div>
                    </div>
                </template>
            </div>
        </div>

        <!-- Lightbox Modal -->
        <template x-teleport="body">
            <div x-show="lightboxOpen" 
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100"
                 x-transition:leave-end="opacity-0"
                 class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/95 backdrop-blur-lg p-4"
                 @keydown.escape.window="lightboxOpen = false"
                 style="display: none;">
                
                <!-- Close Button -->
                <button @click="lightboxOpen = false" 
                        class="absolute top-6 right-6 text-white/70 hover:text-white bg-white/10 hover:bg-white/20 p-3 rounded-full transition duration-300 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>

                <!-- Lightbox Content Container -->
                <div class="max-w-4xl w-full flex flex-col items-center gap-4" @click.away="lightboxOpen = false">
                    <img :src="lightboxImg" 
                         class="max-h-[80vh] w-auto max-w-full rounded-2xl object-contain shadow-2xl border border-white/10"
                         x-show="lightboxOpen"
                         x-transition:enter="transition ease-out duration-300 transform scale-95"
                         x-transition:enter-start="scale-95 opacity-0"
                         x-transition:enter-end="scale-100 opacity-100">
                    
                    <div class="text-center space-y-1">
                        <span class="text-[10px] font-black text-theme-accent uppercase tracking-[0.2em]" x-text="lightboxTag"></span>
                        <h3 class="text-white text-base font-black" x-text="lightboxTitle"></h3>
                    </div>
                </div>
            </div>
        </template>
    </section>

    <!-- EVENTS SECTION -->
    <section id="events" class="py-10 md:py-12 bg-theme-cream border-b border-theme-accent/10">
        <div class="max-w-6xl mx-auto px-6 space-y-8">
            <!-- Header -->
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-accent">Upcoming Activities</span>
                <h2 class="text-2xl md:text-3xl font-black text-theme-primary tracking-tight serif-title">Events Calendar</h2>
                <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and educational programs.</p>
            </div>

            <!-- Magazine Editorial Row Layout -->
            <div class="divide-y divide-theme-accent/15">
                <!-- Event 1 -->
                <div class="group py-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center transition-all duration-300 hover:px-2">
                    <div class="md:col-span-2 flex flex-col leading-none">
                        <span class="text-3xl font-light serif-title text-theme-accent group-hover:scale-105 transform origin-left transition duration-300">25</span>
                        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400 mt-1">JUN 2026</span>
                    </div>
                    <div class="md:col-span-6 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/50">Networking</span>
                            <span class="text-[9px] text-slate-400 font-medium">📍 Main Auditorium</span>
                        </div>
                        <h3 class="text-sm font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">Global Alumni Summit 2026</h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed max-w-xl">
                            Connecting current students with notable alumni across top tech companies & research hubs worldwide for mentorship.
                        </p>
                    </div>
                    <div class="md:col-span-4 space-y-1.5">
                        <div class="flex justify-between text-[7.5px] font-black uppercase tracking-wider text-slate-500">
                            <span>Slot Occupancy</span>
                            <span class="text-theme-accent font-extrabold">88% Filled</span>
                        </div>
                        <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-theme-accent rounded-full transition-all duration-1000 w-[88%]"></div>
                        </div>
                    </div>
                </div>

                <!-- Event 2 -->
                <div class="group py-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center transition-all duration-300 hover:px-2">
                    <div class="md:col-span-2 flex flex-col leading-none">
                        <span class="text-3xl font-light serif-title text-theme-accent group-hover:scale-105 transform origin-left transition duration-300">10</span>
                        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400 mt-1">JUL 2026</span>
                    </div>
                    <div class="md:col-span-6 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-teal-50 text-teal-600 border border-teal-100/50">Ecology</span>
                            <span class="text-[9px] text-slate-400 font-medium">📍 Science Block</span>
                        </div>
                        <h3 class="text-sm font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">Sustainability & Green Initiative</h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed max-w-xl">
                            A campus-wide campaign featuring organic plantation drives, renewable energy workshop models, and zero-waste goals.
                        </p>
                    </div>
                    <div class="md:col-span-4 space-y-1.5">
                        <div class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                            <span>Slot Occupancy</span>
                            <span class="text-theme-accent font-extrabold">65% Filled</span>
                        </div>
                        <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-theme-accent rounded-full transition-all duration-1000 w-[65%]"></div>
                        </div>
                    </div>
                </div>

                <!-- Event 3 -->
                <div class="group py-6 grid grid-cols-1 md:grid-cols-12 gap-4 items-center transition-all duration-300 hover:px-2">
                    <div class="md:col-span-2 flex flex-col leading-none">
                        <span class="text-3xl font-light serif-title text-theme-accent group-hover:scale-105 transform origin-left transition duration-300">05</span>
                        <span class="text-[9px] font-black uppercase tracking-[0.15em] text-slate-400 mt-1">AUG 2026</span>
                    </div>
                    <div class="md:col-span-6 space-y-1">
                        <div class="flex items-center gap-2">
                            <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-purple-50 text-purple-600 border border-purple-100/50">Exhibition</span>
                            <span class="text-[9px] text-slate-400 font-medium">📍 Creative Arts Center</span>
                        </div>
                        <h3 class="text-sm font-black text-theme-primary group-hover:text-theme-accent transition-colors duration-300">Art & Film Showcase (Aura 2026)</h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed max-w-xl">
                            Exhibition of student-produced documentaries, canvas installations, and classical acoustic music.
                        </p>
                    </div>
                    <div class="md:col-span-4 space-y-1.5">
                        <div class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                            <span>Slot Occupancy</span>
                            <span class="text-theme-accent font-extrabold">92% Filled</span>
                        </div>
                        <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-theme-accent rounded-full transition-all duration-1000 w-[92%]"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- FOOTER SECTION -->
    <footer class="bg-theme-primary text-slate-400 py-12 border-t border-slate-900/60 relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="space-y-4">
                <a href="#home" class="flex flex-col leading-none">
                    <span class="text-lg font-black tracking-widest text-white uppercase">
                        NOBLE <span class="text-theme-accent font-light italic serif-title">Academy</span>
                    </span>
                </a>
                <p class="text-[11px] leading-relaxed text-slate-500">
                    Empowering students through innovative education, holistic value-building, and robust global mentorship programs.
                </p>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Quick Links</h4>
                <div class="flex flex-col space-y-2 text-[11px]">
                    <a href="#home" class="hover:text-white transition">Home</a>
                    <a href="#about" class="hover:text-white transition">About Us</a>
                    <a href="#achievements" class="hover:text-white transition">Achievements</a>
                    <a href="#gallery" class="hover:text-white transition">Gallery</a>
                    <a href="#events" class="hover:text-white transition">Events</a>
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Contact Info</h4>
                <div class="space-y-2 text-[11px] text-slate-500">
                    <p class="flex items-center gap-2">📍 Education Valley</p>
                    <p class="flex items-center gap-2">📞 +1 (555) 019-2834</p>
                    <p class="flex items-center gap-2">📧 info@nobleacademy.edu</p>
                </div>
            </div>
        </div>

        <div class="max-w-6xl mx-auto px-6 mt-10 pt-6 border-t border-slate-900 flex flex-col md:flex-row justify-between items-center gap-4 text-[10.5px] text-slate-600 font-medium relative z-10">
            <p>&copy; {{ date('Y') }} Noble Academy. All rights reserved.</p>
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
</body>

</html>
