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
                <span class="text-2xl font-black text-brand-500 tracking-tight">
                    {!! ($institute && $institute->template_id == 1) ? ($institute->institute_name ?? 'NOBLE<span class="text-slate-800">ACADEMY</span>') : 'NOBLE<span class="text-slate-800">ACADEMY</span>' !!}
                </span>
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
                    img: {{ json_encode($settings['hero_image_1'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80') }},
                    badge: 'Empowering Minds',
                    title: 'Empowering Minds, Shaping Futures',
                    desc: 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
                },
                {
                    img: {{ json_encode($settings['hero_image_2'] ?? 'https://images.unsplash.com/photo-1427504494785-3a9ca7044f45?auto=format&fit=crop&w=1600&q=80') }},
                    badge: 'Interactive Learning',
                    title: 'Innovative Academic Programs',
                    desc: 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
                },
                {
                    img: {{ json_encode($settings['hero_image_3'] ?? 'https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=1600&q=80') }},
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
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest dynamic-editable" data-key="about_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['about_badge'] ?? 'About Our Academy' !!}</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable" data-key="about_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['about_title'] ?? 'Our Core Pillars' !!}</h2>
                <p class="text-xs text-slate-500 dynamic-editable" data-key="about_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['about_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow.' !!}</p>
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
                            <h3 class="text-sm font-black text-slate-800 dynamic-editable" data-key="vision_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['vision_title'] ?? 'Nurturing Leaders Since 2012' !!}</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="vision_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['vision_desc'] ?? 'To establish a global standard in education that balances academic rigor with creative expression, cultivating visionary leaders of tomorrow.' !!}
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-brand-600 leading-normal dynamic-editable" data-key="vision_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['vision_focus'] ?? 'Holistic Growth • Integrated Technology • Creative Innovation' !!}
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
                            <h3 class="text-sm font-black text-slate-800 dynamic-editable" data-key="mission_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['mission_title'] ?? 'Fostering Excellence & Integrity' !!}</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="mission_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['mission_desc'] ?? 'To provide a stimulating learning environment where students excel academically, develop strong moral values, and become responsible global citizens.' !!}
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-indigo-600 leading-normal dynamic-editable" data-key="mission_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['mission_focus'] ?? 'Qualified Mentors • Student curriculum • Civic Foundations' !!}
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
                            <h3 class="text-sm font-black text-slate-800 dynamic-editable" data-key="values_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['values_title'] ?? 'Our Core Pillars of Success' !!}</h3>
                            <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="values_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['values_desc'] ?? 'We are anchored in key moral and academic tenets that guide every lesson, interaction, and milestone achieved within our campus.' !!}
                            </p>
                        </div>
                    </div>

                    <!-- Key Pillars Text Field -->
                    <div class="pt-3 border-t border-slate-100/80">
                        <span class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key Focus</span>
                        <p class="text-[10px] font-extrabold text-emerald-600 leading-normal dynamic-editable" data-key="values_focus" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['values_focus'] ?? 'Unyielding Integrity • Empathetic Collaboration • Inquisitive Mindsets' !!}
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
                <span class="text-[10px] font-extrabold text-brand-500 uppercase tracking-widest dynamic-editable" data-key="achieve_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['achieve_badge'] ?? 'Our Milestones' !!}</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable" data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['achieve_title'] ?? 'Recent Achievements' !!}</h2>
                <p class="text-xs text-slate-500 dynamic-editable" data-key="achieve_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['achieve_desc'] ?? 'Proud moments demonstrating our dedication to academic and athletic excellence.' !!}</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Achievement 1 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 h-1 bg-amber-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-amber-50 rounded-xl flex items-center justify-center text-amber-500 border border-amber-100 group-hover:scale-110 transition-transform duration-300">🏆</div>
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">01</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['ach1_title'] ?? 'Best School Award 2025' !!}</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="ach1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['ach1_desc'] ?? 'Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.' !!}
                    </p>
                </div>

                <!-- Achievement 2 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 h-1 bg-indigo-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-500 border border-indigo-100 group-hover:scale-110 transition-transform duration-300">🎓</div>
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">02</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['ach2_title'] ?? '100% Board Exam Success' !!}</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="ach2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['ach2_desc'] ?? 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.' !!}
                    </p>
                </div>

                <!-- Achievement 3 -->
                <div class="group relative bg-white border border-slate-100 p-6 rounded-[2rem] space-y-3.5 shadow-sm hover:shadow-lg hover:shadow-slate-200/50 hover:-translate-y-1.5 transition-all duration-300 overflow-hidden">
                    <div class="absolute top-0 left-0 h-1 bg-emerald-500 w-0 group-hover:w-full transition-all duration-300"></div>
                    <div class="flex items-center justify-between">
                        <div class="h-10 w-10 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-500 border border-emerald-100 group-hover:scale-110 transition-transform duration-300">🏅</div>
                        <span class="text-5xl font-black text-slate-100/50 group-hover:text-slate-100 transition-colors duration-300 select-none">03</span>
                    </div>
                    
                    <h3 class="text-sm font-bold text-slate-800 pt-1 dynamic-editable" data-key="ach3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['ach3_title'] ?? 'National Sports Champions' !!}</h3>
                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable" data-key="ach3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['ach3_desc'] ?? 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.' !!}
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
                    img: {{ json_encode($settings['gallery_image_1'] ?? 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'academics',
                    tag: 'Laboratory',
                    title: 'Chemistry Research Lab'
                },
                {
                    img: {{ json_encode($settings['gallery_image_2'] ?? 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'academics',
                    tag: 'Academics',
                    title: 'Resource Center & Library'
                },
                {
                    img: {{ json_encode($settings['gallery_image_3'] ?? 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'sports',
                    tag: 'Sports',
                    title: 'Athletic Running Track'
                },
                {
                    img: {{ json_encode($settings['gallery_image_4'] ?? 'https://images.unsplash.com/photo-1460661419201-fd4cecdf8a8b?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'co-curricular',
                    tag: 'Co-Curricular',
                    title: 'Creative Arts & Pottery'
                },
                {
                    img: {{ json_encode($settings['gallery_image_5'] ?? 'https://images.unsplash.com/photo-1581092918056-0c4c3acd37bd?auto=format&fit=crop&w=800&q=80') }},
                    cat: 'infrastructure',
                    tag: 'Technology',
                    title: 'Digital IT Hub & Coding Lab'
                },
                {
                    img: {{ json_encode($settings['gallery_image_6'] ?? 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80') }},
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
                            <span class="text-lg font-black leading-none dynamic-editable" data-key="event_1_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_day'] ?? '25' !!}</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1 dynamic-editable" data-key="event_1_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_month'] ?? 'Jun' !!}</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider dynamic-editable" data-key="event_1_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_tag'] ?? 'Networking' !!}</span>
                                <span class="text-[10px] text-slate-400 font-bold dynamic-editable" data-key="event_1_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_time'] ?? '09:00 AM - 04:00 PM' !!}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-brand-500 dynamic-editable" data-key="event_1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_1_title'] ?? 'Global Alumni Summit 2026' !!}</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed dynamic-editable" data-key="event_1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['event_1_desc'] ?? 'Connecting current students with notable alumni across top tech companies & research hubs worldwide for mentorship.' !!}
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
                            <span class="text-lg font-black leading-none dynamic-editable" data-key="event_2_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_day'] ?? '10' !!}</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1 dynamic-editable" data-key="event_2_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_month'] ?? 'Jul' !!}</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider dynamic-editable" data-key="event_2_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_tag'] ?? 'Ecology' !!}</span>
                                <span class="text-[10px] text-slate-400 font-bold dynamic-editable" data-key="event_2_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_time'] ?? '10:00 AM - 02:00 PM' !!}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-indigo-500 dynamic-editable" data-key="event_2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_2_title'] ?? 'Sustainability & Green Initiative' !!}</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed dynamic-editable" data-key="event_2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['event_2_desc'] ?? 'A student-led program launching campus solar grids and active waste-recycling workshops.' !!}
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
                            <span class="text-lg font-black leading-none dynamic-editable" data-key="event_3_day" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_day'] ?? '05' !!}</span>
                            <span class="text-[9px] font-black uppercase tracking-wider leading-none mt-1 dynamic-editable" data-key="event_3_month" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_month'] ?? 'Aug' !!}</span>
                        </div>
                        
                        <!-- Event Info -->
                        <div class="space-y-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded-lg text-[8px] font-bold uppercase tracking-wider dynamic-editable" data-key="event_3_tag" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_tag'] ?? 'Exhibition' !!}</span>
                                <span class="text-[10px] text-slate-400 font-bold dynamic-editable" data-key="event_3_time" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_time'] ?? '04:30 PM - 09:00 PM' !!}</span>
                            </div>
                            <h3 class="text-sm font-bold text-slate-800 transition-colors group-hover:text-emerald-500 dynamic-editable" data-key="event_3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['event_3_title'] ?? 'Art & Film Showcase (Aura 2026)' !!}</h3>
                            <p class="text-[11px] text-slate-500 max-w-xl leading-relaxed dynamic-editable" data-key="event_3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                {!! $settings['event_3_desc'] ?? 'Exhibition of student-produced documentaries, canvas installations, and classical acoustic music.' !!}
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
                <span class="text-2xl font-black text-brand-500 tracking-tight">
                    {!! ($institute && $institute->template_id == 1) ? ($institute->institute_name ?? 'NOBLE<span class="text-white">ACADEMY</span>') : 'NOBLE<span class="text-white">ACADEMY</span>' !!}
                </span>
                <p class="text-xs text-slate-400 leading-relaxed dynamic-editable" data-key="footer_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['footer_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow since 2012.' !!}
                </p>
                
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
                        <span class="dynamic-editable" data-key="footer_email" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_email'] ?? 'admissions@nobleacademy.edu' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📞</span>
                        <span class="dynamic-editable" data-key="footer_phone" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_phone'] ?? '+91 98765 43210' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-400 hover:text-white transition-colors">
                        <span class="h-6 w-6 rounded-lg bg-slate-900 border border-slate-850 flex items-center justify-center text-[10px]">📍</span>
                        <span class="dynamic-editable" data-key="footer_address" contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_address'] ?? 'Ahmedabad, Gujarat, India' !!}</span>
                    </li>
                </ul>
            </div>
        </div>
        <div class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs text-slate-500 font-medium">
            <p>&copy; {{ date('Y') }} {!! ($institute && $institute->template_id == 1) ? ($institute->institute_name ?? 'Noble Academy') : 'Noble Academy' !!}. All rights reserved.</p>
            
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
    @include('website_templates.customizer_script')
</body>
</html>
