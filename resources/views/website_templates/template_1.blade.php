<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tuoora - Smart Institute Management & Payments</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS for interactive slider -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
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
    </style>
</head>
<body class="bg-slate-50 text-slate-900 antialiased overflow-x-hidden">

    <!-- STICKY NAVIGATION BAR -->
    <header class="bg-white/95 backdrop-blur-md border-b border-slate-100 sticky top-0 z-50 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 h-16 flex items-center justify-between">
            <a href="#home" class="flex items-center gap-2">
                <span class="text-2xl font-black text-brand-500 tracking-tight">NOBLE<span class="text-slate-800">ACADEMY</span></span>
            </a>
            
            <nav class="hidden lg:flex items-center gap-8 text-sm font-bold text-slate-600">
                <a href="#home" class="hover:text-brand-500 transition-colors">Home</a>
                <a href="#about" class="hover:text-brand-500 transition-colors">About Us</a>
                <a href="#achievements" class="hover:text-brand-500 transition-colors">Achievements</a>
                <a href="#gallery" class="hover:text-brand-500 transition-colors">Gallery</a>
                <a href="#events" class="hover:text-brand-500 transition-colors">Events</a>
             
            </nav>

         
        </div>

        <!-- Mobile Nav Menu -->
        <div id="mobile-nav" class="hidden lg:hidden border-b border-slate-100 bg-white px-6 py-4 space-y-3 font-semibold text-sm text-slate-600">
            <a href="#home" class="block hover:text-brand-500">Home</a>
            <a href="#about" class="block hover:text-brand-500">About Us</a>
            <a href="#achievements" class="block hover:text-brand-500">Achievements</a>
            <a href="#gallery" class="block hover:text-brand-500">Gallery</a>
            <a href="#events" class="block hover:text-brand-500">Events</a>
            <a href="#social" class="block hover:text-brand-500">Social Feed</a>
            <a href="#contact" class="block hover:text-brand-500">Contact</a>
        </div>
    </header>

    <!-- HERO IMAGE SLIDER SECTION (LIGHT THEME) -->
    <section id="home" class="relative bg-slate-50 text-slate-900 overflow-hidden py-10 md:py-2 border-b border-slate-100" 
        x-data="{ 
            activeSlide: 0, 
            progress: 0,
            slides: [
                {
                    img: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80',
                    badge: 'Empowering Minds',
                    title: 'Empowering Minds, Shaping Futures',
                    desc: 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1600&q=80',
                    badge: 'Interactive Learning',
                    title: 'Innovative Academic Programs',
                    desc: 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80',
                    badge: 'Future-Ready Labs',
                    title: 'State-of-the-Art Infrastructure',
                    desc: 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
                }
            ],
            next() { 
                this.activeSlide = (this.activeSlide + 1) % this.slides.length; 
                this.progress = 0;
            },
            prev() { 
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length; 
                this.progress = 0;
            }
        }"
        x-init="
            setInterval(() => {
                progress += 2;
                if (progress >= 100) {
                    next();
                }
            }, 100)
        ">
        
        <!-- Ambient Glow Orbs -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-brand-500/5 rounded-full blur-[100px] pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-indigo-500/5 rounded-full blur-[100px] pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-6 relative z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center min-h-[380px] md:min-h-[440px]">
            
            <!-- Left Side: Staggered Content Container -->
            <div class="lg:col-span-6 space-y-5 text-center lg:text-left relative min-h-[250px] flex flex-col justify-center">
                <template x-for="(slide, index) in slides" :key="index">
                    <div x-show="activeSlide === index" 
                        x-transition:enter="transition ease-out duration-700 delay-100"
                        x-transition:enter-start="opacity-0 translate-y-6"
                        x-transition:enter-end="opacity-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-300 absolute"
                        class="space-y-4 w-full">
                        
                        <span class="inline-flex px-3 py-1 bg-brand-50 border border-brand-100 rounded-full text-[11px] font-extrabold text-brand-500 uppercase tracking-widest" x-text="slide.badge"></span>
                        
                        <h1 class="text-3xl md:text-5xl font-black tracking-tight leading-tight text-slate-900" x-text="slide.title"></h1>
                        
                        <p class="text-xs md:text-sm text-slate-500 max-w-xl mx-auto lg:mx-0 leading-relaxed font-medium" x-text="slide.desc"></p>
                        
                       
                    </div>
                </template>
            </div>

            <!-- Right Side: Double Framed Image -->
            <div class="lg:col-span-6 relative flex justify-center items-center">
                <div class="bg-white p-3 rounded-[2.5rem] border border-slate-200/60 shadow-xl w-full max-w-md md:max-w-lg">
                    <div class="relative aspect-[16/11] rounded-[2rem] overflow-hidden bg-slate-100 group">
                        <template x-for="(slide, index) in slides" :key="index">
                            <div x-show="activeSlide === index" class="absolute inset-0">
                                <!-- Ken Burns Zoom Effect -->
                                <img :src="slide.img" 
                                     class="w-full h-full object-cover transition-transform duration-[5000ms] ease-out"
                                     :class="activeSlide === index ? 'scale-105' : ''">
                                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/20 via-transparent to-transparent"></div>
                            </div>
                        </template>
                        
                        <!-- Floating Top Badge -->
                        <div class="absolute top-4 right-4 px-2.5 py-1 bg-white/90 backdrop-blur-sm border border-slate-200/50 rounded-lg text-[9px] font-bold text-slate-800 shadow-sm">
                            ★ Top Choice
                        </div>
                    </div>
                </div>

            </div>

        </div>

        <!-- Slider Controls -->
        <div class="max-w-7xl mx-auto px-6 mt-2 flex flex-col md:flex-row items-center justify-between gap-4 border-t border-slate-200/50 pt-4 z-10 relative">
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
                    <span class="text-slate-800 text-xs font-black" x-text="'0' + (activeSlide + 1)"></span> / <span x-text="'0' + slides.length"></span>
                </div>

                <div class="flex items-center gap-1.5">
                    <button @click="prev()" class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all active:scale-95 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7" /></svg>
                    </button>
                    <button @click="next()" class="h-8 w-8 rounded-lg bg-white border border-slate-200 hover:bg-slate-50 flex items-center justify-center text-slate-600 transition-all active:scale-95 shadow-sm">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 5l7 7-7 7" /></svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Progress bar tracking slider timer -->
        <div class="absolute bottom-0 left-0 right-0 h-0.5 bg-slate-100">
            <div class="h-full bg-brand-500 transition-all duration-100 ease-linear" :style="'width: ' + progress + '%'"></div>
        </div>
    </section>

    <!-- ABOUT US SECTION -->
    <section id="about" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-white border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">About Our Academy</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Our Core Pillars</h2>
                <p class="text-xs text-slate-500">Combining academic innovation with core values to empower the leaders of tomorrow.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Vision Box -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-brand-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom"></div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="h-11 w-11 bg-brand-50 rounded-2xl flex items-center justify-center text-brand-500 border border-brand-100 group-hover:scale-110 transition-transform duration-300 text-lg">🔭</div>
                            <span class="text-[9px] font-extrabold text-brand-500/50 uppercase tracking-widest">Vision</span>
                        </div>
                        
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-black text-slate-800">Nurturing Leaders Since 2012</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                To establish a global standard in education that balances academic rigor with creative expression, cultivating visionary leaders of tomorrow.
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-brand-600 leading-normal">
                            Holistic Growth • Integrated Technology • Creative Innovation
                        </p>
                    </div>
                </div>

                <!-- Mission Box -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-indigo-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom"></div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="h-11 w-11 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform duration-300 text-lg">🚀</div>
                            <span class="text-[9px] font-extrabold text-indigo-500/50 uppercase tracking-widest">Mission</span>
                        </div>
                        
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-black text-slate-800">Fostering Excellence & Integrity</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                To provide a stimulating learning environment where students excel academically, develop strong moral values, and become responsible global citizens.
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-indigo-600 leading-normal">
                            Qualified Mentors • Student curriculum • Civic Foundations
                        </p>
                    </div>
                </div>

                <!-- Values Box -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-4 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden flex flex-col justify-between">
                    <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500 scale-y-0 group-hover:scale-y-100 transition-transform duration-300 origin-bottom"></div>
                    
                    <div class="space-y-4">
                        <div class="flex items-center justify-between">
                            <div class="h-11 w-11 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform duration-300 text-lg">🛡️</div>
                            <span class="text-[9px] font-extrabold text-emerald-500/50 uppercase tracking-widest">Values</span>
                        </div>
                        
                        <div class="space-y-1.5">
                            <h3 class="text-sm font-black text-slate-800">Our Core Pillars of Success</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                                We are anchored in key moral and academic tenets that guide every lesson, interaction, and milestone achieved within our campus.
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-emerald-600 leading-normal">
                            Unyielding Integrity • Empathetic Collaboration • Inquisitive Mindsets
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <!-- ACHIEVEMENTS SECTION -->
    <section id="achievements" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-slate-50 border-b border-slate-100">
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">Our Milestones</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Recent Achievements</h2>
                <p class="text-xs text-slate-500">Proud moments demonstrating our dedication to academic and athletic excellence.</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Achievement 1 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <!-- Top Line Expand Animation -->
                    <div class="absolute top-0 left-0 h-1 bg-amber-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 border border-amber-100 group-hover:scale-110 transition-transform duration-300">🏆</div>
                        <!-- Watermark Number -->
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">01</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1">Best School Award 2025</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.
                    </p>
                </div>

                <!-- Achievement 2 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <!-- Top Line Expand Animation -->
                    <div class="absolute top-0 left-0 h-1 bg-indigo-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform duration-300">🎓</div>
                        <!-- Watermark Number -->
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">02</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1">100% Board Exam Success</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.
                    </p>
                </div>

                <!-- Achievement 3 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <!-- Top Line Expand Animation -->
                    <div class="absolute top-0 left-0 h-1 bg-emerald-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform duration-300">🏅</div>
                        <!-- Watermark Number -->
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">03</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1">National Sports Champions</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium">
                        Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- GALLERY SECTION -->
    <section id="gallery" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-white border-b border-slate-100"
        x-data="{ 
            activeFilter: 'all',
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
                    img: '  ',
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
        
        <div class="max-w-7xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">Visual Tour</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
                <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of Noble Academy.</p>
            </div>

            <!-- Filter Buttons Navigation -->
            <div class="flex flex-wrap items-center justify-center gap-2">
                <button @click="activeFilter = 'all'" 
                    :class="activeFilter === 'all' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/15' : 'bg-slate-100 hover:bg-slate-200 text-slate-600'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    All
                </button>
                <button @click="activeFilter = 'academics'" 
                    :class="activeFilter === 'academics' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/15' : 'bg-slate-100 hover:bg-slate-200 text-slate-600'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    Academics
                </button>
                <button @click="activeFilter = 'infrastructure'" 
                    :class="activeFilter === 'infrastructure' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/15' : 'bg-slate-100 hover:bg-slate-200 text-slate-600'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    Infrastructure
                </button>
                <button @click="activeFilter = 'sports'" 
                    :class="activeFilter === 'sports' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/15' : 'bg-slate-100 hover:bg-slate-200 text-slate-600'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    Sports
                </button>
                <button @click="activeFilter = 'co-curricular'" 
                    :class="activeFilter === 'co-curricular' ? 'bg-brand-500 text-white shadow-md shadow-brand-500/15' : 'bg-slate-100 hover:bg-slate-200 text-slate-600'" 
                    class="px-4 py-2 rounded-xl text-xs font-bold transition-all uppercase tracking-wider">
                    Co-Curricular
                </button>
            </div>

            <!-- Gallery Grid with Alpine Transitions -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                <template x-for="(item, index) in items" :key="index">
                    <div x-show="activeFilter === 'all' || activeFilter === item.cat"
                        x-transition:enter="transition ease-out duration-500"
                        x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                        x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                        x-transition:leave="transition ease-in duration-200 absolute"
                        class="group relative rounded-[2rem] overflow-hidden shadow-sm border border-slate-100 aspect-video lg:aspect-square bg-slate-50">
                        
                        <!-- Zoom image -->
                        <img :src="item.img" class="w-full h-full object-cover transition-transform duration-[6000ms] ease-out group-hover:scale-105 group-hover:rotate-1">
                        
                        <!-- Overlay gradient -->
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/80 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-100 transition-opacity duration-300"></div>
                        
                        <!-- Floating Category Pill -->
                        <div class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm border border-slate-200/20 px-3 py-1 rounded-xl text-[9px] font-black text-slate-800 uppercase tracking-widest shadow-sm">
                            <span x-text="item.tag"></span>
                        </div>

                        <!-- Info Content sliding up on hover -->
                        <div class="absolute inset-0 flex flex-col justify-end p-6 translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                            <h4 class="text-white font-black text-base leading-tight mt-1" x-text="item.title"></h4>
                            <!-- <p class="text-[9px] text-brand-500 font-extrabold uppercase tracking-widest mt-1 opacity-0 group-hover:opacity-100 transition-opacity duration-300">View Details</p> -->
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </section>

    <!-- EVENTS SECTION -->
    <section id="events" class="pt-8 pb-12 md:pt-10 md:pb-14 bg-slate-50 border-b border-slate-100">
        <div class="max-w-4xl mx-auto px-6 space-y-8">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest">Upcoming Activities</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and educational programs.</p>
            </div>

            <!-- Vertical Timeline Feed -->
            <div class="space-y-4">
                <!-- Event Item 1 -->
                <div class="group relative bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-4 overflow-hidden">
                    <div class="absolute top-0 left-0 bottom-0 w-1 bg-brand-500"></div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Date Block -->
                        <div class="h-14 w-14 shrink-0 bg-brand-50 border border-brand-100 text-brand-600 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 group-hover:bg-brand-500 group-hover:text-white group-hover:rotate-3">
                            <span class="text-lg font-black leading-none">25</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1">Jun</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider">Networking</span>
                                <span class="text-[10px] text-slate-400 font-bold">09:00 AM - 04:00 PM</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-brand-500">Global Alumni Summit 2026</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed">
                                Connecting current students with notable alumni across top tech companies & research hubs worldwide for mentorship.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Event Item 2 -->
                <div class="group relative bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-4 overflow-hidden">
                    <div class="absolute top-0 left-0 bottom-0 w-1 bg-indigo-500"></div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Date Block -->
                        <div class="h-14 w-14 shrink-0 bg-indigo-50 border border-indigo-100 text-indigo-600 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 group-hover:bg-indigo-500 group-hover:text-white group-hover:rotate-3">
                            <span class="text-lg font-black leading-none">10</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1">Jul</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider">Ecology</span>
                                <span class="text-[10px] text-slate-400 font-bold">10:00 AM - 02:00 PM</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-indigo-500">Sustainability & Green Initiative</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed">
                                A student-led program launching campus solar grids and active waste-recycling workshops.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Event Item 3 -->
                <div class="group relative bg-white border border-slate-100 p-5 rounded-[2rem] shadow-sm hover:shadow-md hover:-translate-y-1 transition-all duration-300 flex flex-col sm:flex-row items-start sm:items-center gap-4 overflow-hidden">
                    <div class="absolute top-0 left-0 bottom-0 w-1 bg-emerald-500"></div>
                    
                    <div class="flex items-center gap-4">
                        <!-- Date Block -->
                        <div class="h-14 w-14 shrink-0 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-2xl flex flex-col items-center justify-center transition-all duration-300 group-hover:bg-emerald-500 group-hover:text-white group-hover:rotate-3">
                            <span class="text-lg font-black leading-none">05</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1">Aug</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider">Exhibition</span>
                                <span class="text-[10px] text-slate-400 font-bold">04:30 PM - 09:00 PM</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-emerald-500">Art & Film Showcase (Aura 2026)</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed">
                                Exhibition of student-produced documentaries, canvas installations, and classical acoustic music.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

   
    <!-- FOOTER -->
    <!-- FOOTER -->
    <footer class="bg-slate-950 text-slate-400 py-12 px-6 border-t border-slate-900">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 border-b border-slate-900 pb-8">
            <div class="space-y-4">
                <span class="text-2xl font-black text-brand-500 tracking-tight">NOBLE<span class="text-white">ACADEMY</span></span>
                <p class="text-xs text-slate-400 leading-relaxed">Combining academic innovation with core values to empower the leaders of tomorrow since 2012.</p>
                
                <!-- Interactive Newsletter Form -->
               
            </div>
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="text-xs space-y-2 font-medium">
                    <li>
                        <a href="#home" class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                            Home
                            <span class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#about" class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                            About Us
                            <span class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#achievements" class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                            Achievements
                            <span class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#gallery" class="relative py-1 hover:text-white transition-colors duration-300 group inline-block">
                            Gallery
                            <span class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-brand-500 transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                </ul>
            </div>
           
            <div>
                <h4 class="text-white font-extrabold text-xs uppercase tracking-wider mb-4">Contact Info</h4>
                <ul class="text-xs space-y-3 font-semibold">
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📧</span>
                        <a href="mailto:admissions@nobleacademy.edu" class="hover:underline">admissions@nobleacademy.edu</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📞</span>
                        <a href="tel:+919876543210" class="hover:underline">+91 98765 43210</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📍</span>
                        <a href="https://maps.google.com" target="_blank" class="hover:underline">Ahmedabad, Gujarat, India</a>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium">
            <p>&copy; {{ date('Y') }} Noble Academy. All rights reserved.</p>
            
            <!-- Interactive Social Media Badges -->
            <div class="flex gap-2.5 mt-4 md:mt-0">
                <a href="#" class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#1877F2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1877F2]/20">
                    <span class="font-bold text-[10px]">FB</span>
                </a>
                <a href="#" class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#1DA1F2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1DA1F2]/20">
                    <span class="font-bold text-[10px]">𝕏</span>
                </a>
                <a href="#" class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#0A66C2] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#0A66C2]/20">
                    <span class="font-bold text-[10px]">IN</span>
                </a>
                <a href="#" class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-gradient-to-tr hover:from-purple-600 hover:to-orange-500 hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-pink-500/20">
                    <span class="font-bold text-[10px]">IG</span>
                </a>
                <a href="#" class="h-8 w-8 rounded-xl bg-slate-900 border border-slate-800/50 hover:bg-[#FF0000] hover:text-white text-slate-400 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#FF0000]/20">
                    <span class="font-bold text-[10px]">YT</span>
                </a>
            </div>

            <!-- Floating Back to Top Button -->
            <div x-data="{ showTopBtn: false }" @scroll.window="showTopBtn = (window.pageYOffset || document.documentElement.scrollTop) > 200">
                <button x-show="showTopBtn" 
                    x-transition:enter="transition ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-10 scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                    x-transition:leave="transition ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                    x-transition:leave-end="opacity-0 translate-y-10 scale-95"
                    onclick="window.scrollTo({top: 0, behavior: 'smooth'})" 
                    class="fixed bottom-6 right-6 z-50 flex items-center justify-center h-10 w-10 bg-brand-500 hover:bg-brand-600 text-white rounded-2xl shadow-lg shadow-brand-500/25 transition-all duration-300 hover:scale-105 active:scale-95 group">
                    <span class="text-sm font-black transform group-hover:-translate-y-0.5 transition-transform">↑</span>
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
</body>
</html>
