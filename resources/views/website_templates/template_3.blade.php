<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <title>Noble Academy - Excellence & Innovation</title>
    <!-- Tailwind CSS for modern responsive styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Outfit -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <!-- AlpineJS for interactive components -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        theme: {
                            primary: '#059669',     // emerald-600
                            secondary: '#0d9488',   // teal-600
                            accent: '#10b981',      // emerald-500
                        }
                    },
                    fontFamily: {
                        sans: ['Outfit', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --theme-primary: #059669;
            --theme-secondary: #0d9488;
            --theme-accent: #10b981;
        }

        body {
            font-family: 'Outfit', sans-serif;
        }

        .menu-link {
            position: relative;
            padding: 8px 16px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            color: #64748b;
            transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .menu-link:hover {
            background: #f0fdf4;
            color: var(--theme-primary, #059669);
            transform: translateY(-1px);
        }

        .menu-link.active {
            background: #e6fbf1;
            color: #059669;
        }

        .menu-link.active::after {
            content: "";
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 14px;
            height: 3px;
            border-radius: 10px;
            background: #059669;
        }

        .mobile-item {
            display: block;
            padding: 12px 16px;
            border-radius: 14px;
            font-weight: 800;
            font-size: 13px;
            color: #475569;
            background: #f8fafc;
            transition: all 0.3s ease;
        }

        .mobile-item:hover {
            background: #059669;
            color: white;
            transform: translateX(6px);
        }

        .mobile-item.active {
            background: #059669;
            color: white;
        }
    </style>
</head>

<body class="bg-[#fafbfd] text-slate-800 antialiased min-h-screen">

    <!-- STICKY HEADER -->
    <header x-data="{ scrolled: false }" @scroll.window="scrolled = (window.pageYOffset > 25)"
        :class="scrolled ? 'py-2.5 shadow-[0_10px_30px_rgba(5,150,105,0.06)] bg-white/95 backdrop-blur-xl' : 'py-4 shadow-none bg-white/90 backdrop-blur-xl'"
        class="sticky top-0 z-50 w-full border-b border-slate-200/60 transition-all duration-300 px-4 md:px-8">
        <div class="max-w-7xl mx-auto flex items-center justify-between relative group">

            <!-- LOGO -->
            <a href="#home" class="
            flex
            items-center
            gap-3
            group/logo
            relative
            ">

                <!-- Logo Circle -->
                <div class="
                    relative
                    h-12
                    w-12
                    rounded-[18px]
                    bg-gradient-to-br
                    from-theme-primary
                    to-theme-secondary
                    flex
                    items-center
                    justify-center
                    shadow-xl
                    shadow-theme-primary/30
                    overflow-hidden
                    transition-all
                    duration-500
                    group-hover/logo:-rotate-12
                ">

                    <span class="
                    text-white
                    text-lg
                    font-black
                    z-10">
                        {!! ($institute && isset($institute->institute_name)) ? strtoupper(substr($institute->institute_name, 0, 1)) : 'N' !!}
                    </span>

                    <!-- Rotating Ring -->
                    <span class="
                        absolute
                        inset-1
                        rounded-xl
                        border
                        border-white/40
                        animate-[spin_6s_linear_infinite]
                    ">
                    </span>

                    <!-- Shine -->
                    <span class="
                    absolute
                    inset-0
                    bg-gradient-to-r
                    from-transparent
                    via-white/40
                    to-transparent
                    -translate-x-full
                    group-hover/logo:translate-x-full
                    transition-transform
                    duration-1000
                    ">
                    </span>

                </div>

                <div>
                    <h1 class="text-lg font-black tracking-tight text-slate-900 leading-none">
                        {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE <span class="text-theme-primary">ACADEMY</span>' !!}
                    </h1>

                    <p class="text-[7px] uppercase tracking-[0.35em] font-black text-slate-400 mt-1 dynamic-editable"
                        data-key="logo_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['logo_subtitle'] ?? 'Excellence • Innovation • Future' !!}
                    </p>
                </div>

            </a>

            <!-- NAVIGATION (SLIDING PILL) -->
            <nav class="relative hidden lg:flex items-center gap-1.5 bg-slate-100/70 p-1 rounded-full border border-slate-200/50 backdrop-blur-md"
                x-data="{
                     activeTab: '#home',
                     hoverTab: null,
                     setTab(hash) {
                         this.activeTab = hash;
                     }
                 }" x-on:scroll-active-tab.window="activeTab = $event.detail" @mouseleave="hoverTab = null">

                <!-- Sliding Pill Background Indicator -->
                <div class="absolute h-8 bg-white rounded-full shadow-sm border border-slate-200/60 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)]"
                    :style="`width: ${$el.querySelector('[href=\'' + (hoverTab || activeTab) + '\']')?.offsetWidth || 0}px; left: ${$el.querySelector('[href=\'' + (hoverTab || activeTab) + '\']')?.offsetLeft || 0}px;`"
                    x-show="hoverTab || activeTab">
                </div>

                @if($hasHeroSlides || $isEditable)
                    <a href="#home" @click="setTab('#home')" @mouseenter="hoverTab = '#home'"
                        class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                        :class="activeTab === '#home' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                        Home
                    </a>
                @endif
                @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                    <a href="#about" @click="setTab('#about')" @mouseenter="hoverTab = '#about'"
                        class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                        :class="activeTab === '#about' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                        About Us
                    </a>
                @endif
                @if($hasAchievements)
                    <a href="#achievements" @click="setTab('#achievements')" @mouseenter="hoverTab = '#achievements'"
                        class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                        :class="activeTab === '#achievements' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                        Achievements
                    </a>
                @endif
                @if($hasGallery)
                    <a href="#gallery" @click="setTab('#gallery')" @mouseenter="hoverTab = '#gallery'"
                        class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                        :class="activeTab === '#gallery' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                        Gallery
                    </a>
                @endif
                @if($hasEvents)
                    <a href="#events" @click="setTab('#events')" @mouseenter="hoverTab = '#events'"
                        class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                        :class="activeTab === '#events' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                        Events
                    </a>
                @endif
            </nav>

            <!-- MOBILE BUTTON -->
            <button id="mobile-menu-btn" class="
            lg:hidden
            h-11
            w-11
            rounded-2xl
            bg-slate-900
            text-white
            flex
            items-center
            justify-center
            hover:rotate-90
            transition-all
            duration-500
            ">

                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                        d="M4 6h16M4 12h16M4 18h16" />
                </svg>

            </button>

        </div>

        <!-- MOBILE MENU -->
        <div id="mobile-nav" class="
        hidden
        lg:hidden
        mt-2
        bg-white
        rounded-2xl
        border
        border-slate-100
        shadow-xl
        p-4
        space-y-2
        max-w-7xl
        mx-auto
        ">

            @if($hasHeroSlides || $isEditable)
                <a class="mobile-nav-item" href="#home">
                    Home
                </a>
            @endif

            @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                <a class="mobile-nav-item" href="#about">
                    About Us
                </a>
            @endif

            @if($hasAchievements)
                <a class="mobile-nav-item" href="#achievements">
                    Achievements
                </a>
            @endif

            @if($hasGallery)
                <a class="mobile-nav-item" href="#gallery">
                    Gallery
                </a>
            @endif

            @if($hasEvents)
                <a class="mobile-nav-item" href="#events">
                    Events
                </a>
            @endif

        </div>

    </header>



    <style>
        /* Desktop Navigation */

        .premium-nav {

            position: relative;
            padding: 12px 16px;
            font-size: 13px;
            font-weight: 800;
            color: #64748b;
            transition: .35s;
            border-radius: 18px;

        }



        .premium-nav:hover {

            color: var(--theme-primary);
            background: #f8fafc;
            transform: translateY(-4px);

        }



        .premium-nav::before {

            content: "";
            position: absolute;
            bottom: 4px;
            left: 50%;
            width: 0;
            height: 3px;
            border-radius: 20px;
            background: var(--theme-primary);
            transform: translateX(-50%);
            transition: .4s;

        }



        .premium-nav:hover::before,
        .premium-nav.active::before {

            width: 35px;

        }



        .premium-nav.active {

            color: var(--theme-primary);

        }




        .mobile-nav-item {

            display: block;
            padding: 14px 18px;
            border-radius: 18px;
            font-size: 14px;
            font-weight: 800;
            color: #475569;
            background: #f8fafc;
            transition: .3s;

        }



        .mobile-nav-item:hover {

            background: var(--theme-primary);
            color: white;
            transform: translateX(10px);

        }
    </style>

    <style>
        /* Glassmorphic overlay */
        .glass {
            background: rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.12);
        }

        /* Light theme overrides */
        .light .glass {
            background: rgba(0, 0, 0, 0.08);
            border: 1px solid rgba(0, 0, 0, 0.12);
        }

        @keyframes fadeZoomIn {
            0% {
                opacity: 0;
                transform: scale(0.95);
            }

            100% {
                opacity: 1;
                transform: scale(1);
            }
        }
    </style>

    @if($hasHeroSlides || $isEditable)
        <!-- HERO IMAGE SLIDER SECTION (EMERALD THEME) -->
        <section id="home"
            x-bind:class="'relative min-h-[460px] md:min-h-[560px] flex items-center overflow-hidden glass ' + (darkMode === 'dark' ? 'bg-slate-950 text-white' : 'bg-white text-slate-800')"
            @mousemove="handleParallax($event)" x-data="{ 
                darkMode: 'dark',
                activeSlide: 0, 
                progress: 0,
                parallaxX: 0,
                parallaxY: 0,
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
                'img' => 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1600&q=80',
                'badge' => 'Future Leaders',
                'title' => 'Cultivating Global Leaders',
                'desc' => 'Encouraging critical thinking, cross-cultural collaboration, and moral integrity to shape tomorrow\'s pioneers.'
            ]
        ]) }},
                nextSlide() {
                    this.activeSlide = (this.activeSlide + 1) % this.slides.length;
                    this.progress = 0;
                },
                prevSlide() {
                    this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
                    this.progress = 0;
                },
                handleParallax(event) {
                    const rect = event.target.getBoundingClientRect();
                    this.parallaxX = ((event.clientX - rect.left) / rect.width) - 0.5;
                    this.parallaxY = ((event.clientY - rect.top) / rect.height) - 0.5;
                },
                init() {
                    setInterval(() => {
                        this.progress += 2;
                        if (this.progress >= 100) {
                            this.nextSlide();
                        }
                    }, 100);
                }
            }" }">

            <!-- Background Slide Images -->
            <template x-for="(slide, index) in slides" :key="index">
                <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                    :style="'transform: translate(' + (parallaxX * 20) + 'px, ' + (parallaxY * 20) + 'px)'"
                    :class="activeSlide === index ? 'opacity-30' : 'opacity-0'">
                    <img :src="slide.img"
                        class="w-full h-full object-cover scale-102 transition-transform duration-[5000ms]"
                        :class="activeSlide === index ? 'scale-100' : 'scale-105'">
                </div>
            </template>

            <!-- Premium Overlay Gradients -->
            <div class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/70 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-slate-950 via-transparent to-transparent"></div>

            <!-- Slider Content -->
            <div
                class="relative max-w-7xl mx-auto px-6 py-20 w-full z-10 grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 space-y-6 max-w-2xl">

                    <!-- Floating category tag -->
                    <div
                        class="inline-flex items-center gap-2 px-3 py-1 bg-theme-primary/10 border border-theme-primary/20 rounded-full select-none">
                        <span class="h-1.5 w-1.5 rounded-full bg-theme-primary animate-pulse"></span>
                        <span class="text-[9px] font-black uppercase tracking-widest text-theme-accent"
                            x-text="slides[activeSlide].badge"></span>
                    </div>

                    <!-- Main dynamic heading -->
                    <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white tracking-tight leading-[1.05]"
                        x-text="slides[activeSlide].title"></h1>

                    <!-- Sub description -->
                    <p class="text-xs md:text-sm text-slate-300 leading-relaxed font-medium"
                        x-text="slides[activeSlide].desc"></p>


                </div>
            </div>

            <!-- Carousel navigation arrows -->
            <div class="absolute right-6 bottom-8 z-20 hidden md:flex items-center gap-2 select-none">
                @if($isEditable)
                    <button @click="document.getElementById('hero-file-input-' + activeSlide).click()"
                        class="h-10 w-10 border border-emerald-500/20 bg-emerald-500/80 hover:bg-emerald-600 text-white rounded-xl flex items-center justify-center transition active:scale-95"
                        title="Change Current Slide Background Image">
                        <svg xmlns="http://www.w3.org/2500/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </button>

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

                <button @click="prevSlide()"
                    class="h-10 w-10 border border-white/10 bg-slate-900/40 hover:bg-slate-900/80 text-white rounded-xl flex items-center justify-center transition active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button @click="nextSlide()"
                    class="h-10 w-10 border border-white/10 bg-slate-900/40 hover:bg-slate-900/80 text-white rounded-xl flex items-center justify-center transition active:scale-95">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>

            <!-- Progress bar tracking slider timer -->
            <div class="absolute top-4 right-4">
                <svg class="w-12 h-12" viewBox="0 0 36 36">
                    <circle class="text-slate-900/20" stroke-width="4" stroke="currentColor" fill="none" r="16" cx="18"
                        cy="18"></circle>
                    <circle x-bind:class="darkMode === 'dark' ? 'text-theme-primary' : 'text-theme-secondary'"
                        stroke-width="4" x-bind:stroke-dashoffset="100 - progress" stroke-dasharray="100"
                        stroke-linecap="round" stroke="currentColor" fill="none" r="16" cx="18" cy="18"></circle>
                </svg>
            </div>
        </section>
    @endif

    <!-- Theme Toggle Button -->
    <div class="absolute top-4 left-4 z-20">
        <button @click="darkMode = (darkMode === 'dark' ? 'light' : 'dark')"
            class="px-3 py-1 bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-md transition">
            @{{ darkMode === 'dark' ? 'Light Mode' : 'Dark Mode' }}
        </button>
    </div>

    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0)
        <!-- ABOUT US SECTION -->
        <section id="about" class="relative py-16 md:py-7 bg-white border-b border-slate-100 overflow-hidden">

            <!-- Background -->
            <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-slate-100 rounded-full blur-3xl opacity-50"></div>


            <div class="max-w-5xl mx-auto px-6 relative z-10">


                <!-- Header -->
                <div class="max-w-xl mb-8">

                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary dynamic-editable"
                        data-key="about_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_badge'] ?? 'About Our Academy' !!}
                    </span>

                    <h2 class="mt-3 text-3xl md:text-4xl font-black text-slate-900 dynamic-editable" data-key="about_title"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_title'] ?? 'Our Core Pillars' !!}
                    </h2>

                    <p class="mt-3 text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="about_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow.' !!}
                    </p>

                </div>




                <!-- Pillars -->
                <div class="space-y-6">
                    @php $idx = 1; @endphp

                    <!-- Vision Pillars -->
                    @foreach($visionItems as $item)
                        <div class="pillar-row group">
                            <div class="pillar-number">
                                {{ str_pad($idx, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="pillar-line"></div>
                            <div class="pillar-card">
                                <div class="flex items-start justify-between">
                                    <div>
                                        <div class="flex items-center gap-3">
                                            <span class="pillar-icon bg-emerald-50 text-theme-primary">🔭</span>
                                            <span class="pillar-label text-theme-primary">Vision</span>
                                        </div>
                                        <h3 class="pillar-title">{!! $item['title'] !!}</h3>
                                    </div>
                                </div>
                                <p class="pillar-desc">{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                        @php $idx++; @endphp
                    @endforeach

                    <!-- Mission Pillars -->
                    @foreach($missionItems as $item)
                        <div class="pillar-row group">
                            <div class="pillar-number">
                                {{ str_pad($idx, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="pillar-line"></div>
                            <div class="pillar-card">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="pillar-icon bg-teal-50 text-teal-600">🚀</span>
                                        <span class="pillar-label text-teal-500">Mission</span>
                                    </div>
                                    <h3 class="pillar-title">{!! $item['title'] !!}</h3>
                                </div>
                                <p class="pillar-desc">{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                        @php $idx++; @endphp
                    @endforeach

                    <!-- Values Pillars -->
                    @foreach($valuesItems as $item)
                        <div class="pillar-row group">
                            <div class="pillar-number">
                                {{ str_pad($idx, 2, '0', STR_PAD_LEFT) }}
                            </div>
                            <div class="pillar-line"></div>
                            <div class="pillar-card">
                                <div>
                                    <div class="flex items-center gap-3">
                                        <span class="pillar-icon bg-emerald-50 text-emerald-600">🛡️</span>
                                        <span class="pillar-label text-emerald-500">Values</span>
                                    </div>
                                    <h3 class="pillar-title">{!! $item['title'] !!}</h3>
                                </div>
                                <p class="pillar-desc">{!! $item['desc'] !!}</p>
                            </div>
                        </div>
                        @php $idx++; @endphp
                    @endforeach
                </div>


            </div>



            <style>
                .pillar-row {
                    display: grid;
                    grid-template-columns: 70px 24px 1fr;
                    align-items: stretch;
                    margin-bottom: 1.5rem;
                }

                .pillar-number {
                    font-size: 36px;
                    font-weight: 950;
                    color: #e2e8f0;
                    transition: .5s;
                    padding-top: 16px;
                }

                .pillar-line {
                    position: relative;
                }

                .pillar-line::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    left: 50%;
                    width: 2px;
                    background: #e2e8f0;
                }

                .pillar-card {
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 20px 24px;
                    transition: .5s;
                    position: relative;
                }

                .pillar-card:hover {
                    transform: translateX(10px);
                    border-color: #cbd5e1;
                    box-shadow: 0 15px 40px rgba(15, 23, 42, .06);
                }

                .pillar-card::after {
                    content: "";
                    position: absolute;
                    left: 0;
                    top: 20px;
                    height: 0;
                    width: 4px;
                    background: var(--theme-primary);
                    border-radius: 20px;
                    transition: .5s;
                }

                .pillar-card:hover::after {
                    height: 44px;
                }

                .pillar-icon {
                    height: 36px;
                    width: 36px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 16px;
                }

                .pillar-label {
                    font-size: 9px;
                    font-weight: 900;
                    letter-spacing: .25em;
                    text-transform: uppercase;
                }

                .pillar-title {
                    margin-top: 12px;
                    font-size: 15px;
                    font-weight: 950;
                    color: #0f172a;
                }

                .pillar-desc {
                    margin-top: 8px;
                    font-size: 11.5px;
                    line-height: 1.6;
                    color: #64748b;
                    max-width: 650px;
                }

                .pillar-footer {
                    margin-top: 14px;
                    padding-top: 10px;
                    border-top: 1px solid #f1f5f9;
                }

                .pillar-footer span {
                    font-size: 8px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .2em;
                    color: #94a3b8;
                }

                .pillar-footer p {
                    margin-top: 4px;
                    font-size: 10px;
                    font-weight: 900;
                }

                /* Animation */
                .pillar-row {
                    animation: slideUp .8s ease both;
                }

                .pillar-row:nth-child(2) {
                    animation-delay: .15s;
                }

                .pillar-row:nth-child(3) {
                    animation-delay: .3s;
                }



                @keyframes slideUp {

                    from {

                        opacity: 0;
                        transform: translateY(30px);

                    }

                    to {

                        opacity: 1;
                        transform: translateY(0);

                    }

                }



                @media(max-width:768px) {

                    .pillar-row {

                        grid-template-columns: 45px 15px 1fr;

                    }

                    .pillar-number {

                        font-size: 30px;

                    }

                }
            </style>


        </section>
    @endif

    <!-- ACHIEVEMENTS SECTION -->
    @if($hasAchievements || $isEditable)
        <section id="achievements" class="relative py-10 md:py-12 bg-[#fafbfd] border-b border-slate-100 overflow-hidden">


            <!-- Background Elements -->
            <div class="absolute -top-40 -right-40 w-[450px] h-[450px] bg-theme-primary/5 rounded-full blur-3xl"></div>

            <div class="absolute -bottom-40 -left-40 w-[450px] h-[450px] bg-emerald-100/40 rounded-full blur-3xl"></div>



            <div class="max-w-5xl mx-auto px-6 relative z-10">



                <!-- Header -->
                <div class="text-center max-w-xl mx-auto mb-8">


                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary dynamic-editable"
                        data-key="achieve_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_badge'] ?? 'Our Milestones' !!}
                    </span>

                    <h2 class="mt-3 text-3xl md:text-4xl font-black text-slate-900 dynamic-editable"
                        data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_title'] ?? 'Recent Achievements' !!}
                    </h2>

                    <p class="mt-3 text-xs text-slate-500 dynamic-editable" data-key="achieve_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_desc'] ?? 'Proud moments demonstrating our dedication to academic and athletic excellence.' !!}
                    </p>


                </div>






                <!-- Timeline -->

                <div class="relative space-y-6">


                    <!-- Center Line -->

                    <div class="
                    absolute
                    left-8
                    md:left-1/2
                    top-0
                    bottom-0
                    w-[2px]
                    bg-slate-200
                    md:-translate-x-1/2
                    ">

                        <div class="
                        w-full
                        h-1/3
                        bg-gradient-to-b
                        from-theme-primary
                        via-emerald-400
                        to-transparent
                        animate-line
                        ">
                        </div>


                    </div>






                    <!-- ITEM 1 -->
                    @if(!empty($settings['ach1_title']))
                        <div class="
                                achievement-item
                                md:flex
                                md:justify-start
                                ">


                            <div class="md:w-1/2 md:pr-8">



                                <div class="achievement-card">


                                    <span class="achievement-number">
                                        01
                                    </span>



                                    <div class="flex items-center gap-4">


                                        <div class="
                                                achievement-icon
                                                bg-emerald-50
                                                text-theme-primary
                                                ">
                                            🏆
                                        </div>


                                        <div>
                                            <span class="achievement-tag dynamic-editable" data-key="ach1_tag"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach1_tag'] ?? 'Award' !!}
                                            </span>
                                            <h3 class="dynamic-editable" data-key="ach1_title"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach1_title'] ?? 'Best School Award 2025' !!}
                                            </h3>
                                        </div>
                                    </div>

                                    <p class="dynamic-editable" data-key="ach1_desc"
                                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach1_desc'] ?? 'Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.' !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ITEM 2 -->
                    @if(!empty($settings['ach2_title']))
                        <div class="achievement-item md:flex md:justify-end">
                            <div class="md:w-1/2 md:pl-8">
                                <div class="achievement-card">
                                    <span class="achievement-number">02</span>
                                    <div class="flex items-center gap-4">
                                        <div class="achievement-icon bg-teal-50 text-teal-600">🎓</div>
                                        <div>
                                            <span class="achievement-tag text-teal-500 dynamic-editable" data-key="ach2_tag"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach2_tag'] ?? 'Academics' !!}
                                            </span>
                                            <h3 class="dynamic-editable" data-key="ach2_title"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach2_title'] ?? '100% Board Exam Success' !!}
                                            </h3>
                                        </div>
                                    </div>

                                    <p class="dynamic-editable" data-key="ach2_desc"
                                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach2_desc'] ?? 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.' !!}
                                    </p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- ITEM 3 -->
                    @if(!empty($settings['ach3_title']))
                        <div class="achievement-item md:flex md:justify-start">
                            <div class="md:w-1/2 md:pr-8">
                                <div class="achievement-card">
                                    <span class="achievement-number">03</span>
                                    <div class="flex items-center gap-4">
                                        <div class="achievement-icon bg-emerald-50 text-emerald-600">🏅</div>
                                        <div>
                                            <span class="achievement-tag text-emerald-500 dynamic-editable" data-key="ach3_tag"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach3_tag'] ?? 'Sports' !!}
                                            </span>
                                            <h3 class="dynamic-editable" data-key="ach3_title"
                                                contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach3_title'] ?? 'National Sports Champions' !!}
                                            </h3>
                                        </div>
                                    </div>

                                    <p class="dynamic-editable" data-key="ach3_desc"
                                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                        {!! $settings['ach3_desc'] ?? 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.' !!}
                                    </p>


                                </div>


                            </div>


                        </div>
                    @endif




                </div>


            </div>





            <style>
                /* Card */

                .achievement-card {
                    position: relative;
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 20px;
                    padding: 18px 22px;
                    overflow: hidden;
                    transition: .5s;
                    animation: fadeUp .8s ease both;
                }

                .achievement-card:hover {
                    transform: translateY(-4px) scale(1.01);
                    box-shadow: 0 15px 40px rgba(15, 23, 42, .06);
                    border-color: #cbd5e1;
                }

                /* Shine */

                .achievement-card::before {
                    content: "";
                    position: absolute;
                    top: 0;
                    left: -120%;
                    width: 80%;
                    height: 100%;
                    background: linear-gradient(120deg, transparent, rgba(255, 255, 255, .8), transparent);
                    transition: .8s;
                }

                .achievement-card:hover::before {
                    left: 120%;
                }

                .achievement-number {
                    position: absolute;
                    right: 16px;
                    top: 6px;
                    font-size: 48px;
                    font-weight: 950;
                    color: #f1f5f9;
                    transition: .5s;
                }

                .achievement-card:hover .achievement-number {
                    color: #e2e8f0;
                    transform: scale(1.1);
                }

                .achievement-icon {
                    height: 40px;
                    width: 40px;
                    border-radius: 10px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    font-size: 18px;
                    transition: .5s;
                }

                .achievement-card:hover .achievement-icon {
                    transform: rotate(12deg) scale(1.1);
                }

                .achievement-tag {
                    font-size: 9px;
                    font-weight: 900;
                    text-transform: uppercase;
                    letter-spacing: .25em;
                    color: var(--theme-primary);
                }

                .achievement-card h3 {
                    margin-top: 4px;
                    font-size: 14px;
                    font-weight: 950;
                    color: #0f172a;
                }

                .achievement-card p {
                    margin-top: 10px;
                    font-size: 11px;
                    line-height: 1.6;
                    font-weight: 500;
                    color: #64748b;
                }

                @keyframes fadeUp {
                    from {
                        opacity: 0;
                        transform: translateY(20px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes line {
                    from {
                        height: 0;
                    }

                    to {
                        height: 100%;
                    }
                }

                .animate-line {
                    animation: line 4s infinite alternate;
                }

                @media(max-width:768px) {
                    .achievement-number {
                        font-size: 36px;
                    }
                }
            </style>


        </section>
    @endif

    <!-- GALLERY SECTION -->
    @if($hasGallery)
        <section id="gallery" class="py-10 md:py-12 bg-white border-b border-slate-100" x-data='{ 
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
            }'
            x-init="updateItemsPerPage(); window.addEventListener('resize', () => { updateItemsPerPage(); currentIndex = Math.min(currentIndex, maxIndex); })">

            <div class="max-w-5xl mx-auto px-6 relative z-10">
                <!-- Header -->
                <div class="text-center max-w-xl mx-auto mb-8">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary">Visual Tour</span>
                    <h2 class="text-3xl md:text-4xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
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
                                :style="`width: ${100 / filteredItems.length}%;`"
                                @click="lightboxImg = item.img; lightboxTitle = item.title; lightboxTag = item.tag; lightboxOpen = true">
                                <div
                                    class="group relative rounded-3xl overflow-hidden border border-slate-100 aspect-[4/3] bg-slate-50 cursor-pointer shadow-sm hover:shadow-lg transition-shadow duration-300 w-full h-full">
                                    <!-- Zoom image -->
                                    <img :src="item.img"
                                        class="w-full h-full object-cover transition-transform duration-700 ease-out group-hover:scale-110">

                                    <!-- Overlay gradient -->
                                    <div
                                        class="absolute inset-0 bg-gradient-to-t from-slate-950/70 via-slate-950/20 to-transparent opacity-80 group-hover:opacity-95 transition-opacity duration-300">
                                    </div>

                                    <!-- Floating Category Pill -->
                                    <div
                                        class="absolute top-4 left-4 bg-white/95 backdrop-blur-sm px-2.5 py-0.5 rounded-lg text-[8px] font-black text-slate-800 uppercase tracking-widest shadow-sm">
                                        <span x-text="item.tag"></span>
                                    </div>

                                    <!-- Info Content sliding up on hover -->
                                    <div
                                        class="absolute bottom-4 left-4 right-4 bg-slate-950/45 backdrop-blur-md border border-white/10 rounded-2xl p-4 transition-all duration-300 transform translate-y-1 group-hover:translate-y-0 group-hover:bg-slate-950/70">
                                        <div class="flex items-center justify-between">
                                            <div class="space-y-0.5">
                                                <span
                                                    class="text-[7.5px] font-black tracking-widest text-theme-primary uppercase"
                                                    x-text="item.tag"></span>
                                                <h4 class="text-white font-bold text-xs leading-tight" x-text="item.title">
                                                </h4>
                                            </div>
                                            <div
                                                class="h-7 w-7 bg-white/10 group-hover:bg-theme-primary rounded-xl flex items-center justify-center text-white transition-colors duration-300 flex-shrink-0 ml-3">
                                                <svg xmlns="http://www.w3.org/2000/svg"
                                                    class="h-3.5 w-3.5 transform group-hover:translate-x-0.5 transition-transform duration-300"
                                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5"
                                                        d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                                </svg>
                                            </div>
                                        </div>
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
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-primary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &larr;
                    </button>
                    <!-- Indicators -->
                    <div class="flex items-center gap-1.5">
                        <template x-for="i in Math.max(1, filteredItems.length - itemsPerPage + 1)" :key="i">
                            <span class="h-1.5 rounded-full transition-all duration-350 cursor-pointer"
                                :class="currentIndex === (i - 1) ? 'w-6 bg-theme-primary' : 'w-2 bg-slate-200'"
                                @click="currentIndex = (i - 1)"></span>
                        </template>
                    </div>
                    <button @click="next()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-primary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
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
                            <span class="text-[10px] font-black text-theme-primary uppercase tracking-[0.2em]"
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
        <section id="events" class="py-10 md:py-12 bg-[#fafbfd] border-b border-slate-100" x-data="{
                currentIndex: 0,
                itemsPerPage: 3,
                events: {{ json_encode($events) }},
                updateItemsPerPage() {
                    if (window.innerWidth >= 1024) {
                        this.itemsPerPage = 3;
                    } else if (window.innerWidth >= 768) {
                        this.itemsPerPage = 2;
                    } else {
                        this.itemsPerPage = 1;
                    }
                },
                get maxIndex() {
                    return Math.max(0, this.events.length - this.itemsPerPage);
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
            }"
            x-init="updateItemsPerPage(); window.addEventListener('resize', () => { updateItemsPerPage(); currentIndex = Math.min(currentIndex, maxIndex); })">
            <div class="max-w-5xl mx-auto space-y-8">

                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary">Upcoming
                        Activities</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                    <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and
                        educational programs.</p>
                </div>

                <!-- Events Carousel Viewport -->
                <div class="relative w-full overflow-hidden p-1">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${currentIndex * (100 / events.length)}%); width: ${events.length * (100 / itemsPerPage)}%;`"
                        style="width: 100%;">
                        <template x-for="(event, index) in events" :key="index">
                            <div class="px-3 flex-shrink-0 transition-all duration-500 ease-out"
                                :style="`width: ${100 / events.length}%;`">
                                <!-- Card -->
                                <div class="event-ticket group hover:border-theme-primary/30 flex flex-col justify-between h-full w-full p-5"
                                    :class="{
                                        'hover:border-theme-primary/30': index % 3 === 0,
                                        'hover:border-teal-500/30': index % 3 === 1,
                                        'hover:border-emerald-600/30': index % 3 === 2
                                    }">
                                    <!-- Ticket Notches -->
                                    <div class="event-ticket-notch-l"></div>
                                    <div class="event-ticket-notch-r"></div>

                                    <!-- Top Row -->
                                    <div class="flex items-center justify-between gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <!-- Date Stamp Box -->
                                            <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-xl w-11 h-11 p-1 transition-all duration-500"
                                                :class="{
                                                    'group-hover:bg-theme-primary group-hover:text-white group-hover:border-transparent': index % 3 === 0,
                                                    'group-hover:bg-teal-500 group-hover:text-white group-hover:border-transparent': index % 3 === 1,
                                                    'group-hover:bg-emerald-600 group-hover:text-white group-hover:border-transparent': index % 3 === 2
                                                }">
                                                <span class="text-base font-black tracking-tighter leading-none" x-text="event.day"></span>
                                                <span class="text-[7.5px] font-black uppercase tracking-widest leading-none mt-0.5 opacity-70" x-text="event.month"></span>
                                            </div>
                                            <div class="flex flex-col leading-tight">
                                                <span class="text-[8px] font-bold text-slate-400">Year <span x-text="event.year"></span></span>
                                                <span class="text-[7.5px] font-black uppercase tracking-wider px-1.5 py-0.5 rounded-md mt-0.5 font-outfit"
                                                    :class="{
                                                        'text-theme-primary bg-emerald-50': index % 3 === 0,
                                                        'text-teal-600 bg-teal-50': index % 3 === 1,
                                                        'text-emerald-600 bg-emerald-50': index % 3 === 2
                                                    }"
                                                    x-text="event.tag"></span>
                                            </div>
                                        </div>
                                        <!-- Event Icon Badge -->
                                        <div class="h-7 w-7 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 transition-colors duration-500"
                                            :class="{
                                                'group-hover:bg-theme-primary/10 group-hover:text-theme-primary': index % 3 === 0,
                                                'group-hover:bg-teal-500/10 group-hover:text-teal-650': index % 3 === 1,
                                                'group-hover:bg-emerald-600/10 group-hover:text-emerald-650': index % 3 === 2
                                            }">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24"
                                                stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                            </svg>
                                        </div>
                                    </div>

                                    <!-- Dashed Ticket Separator -->
                                    <div class="my-3 border-t-2 border-dashed border-slate-100 relative z-10"></div>

                                    <!-- Middle Content -->
                                    <div class="flex-grow flex flex-col justify-between">
                                        <div class="space-y-1.5 flex-1 flex flex-col justify-between">
                                            <div class="space-y-1">
                                                <h3 class="text-xs font-black text-slate-800 transition-colors duration-300 font-outfit"
                                                    :class="{
                                                        'group-hover:text-theme-primary': index % 3 === 0,
                                                        'group-hover:text-teal-600': index % 3 === 1,
                                                        'group-hover:text-emerald-600': index % 3 === 2
                                                    }"
                                                    x-text="event.title">
                                                </h3>
                                                <p class="text-[10px] text-slate-500 leading-relaxed font-medium line-clamp-3" x-text="event.desc">
                                                </p>
                                            </div>

                                            <!-- Speaker Info -->
                                            <template x-if="event.speaker">
                                                <div class="flex items-center gap-2 p-2 bg-slate-50 rounded-xl border border-slate-100 mt-2">
                                                    <span class="text-xs shrink-0">🎙️</span>
                                                    <div class="leading-tight min-w-0">
                                                        <div class="text-[8.5px] font-black text-slate-700 truncate" x-text="event.speaker"></div>
                                                        <div class="text-[7px] font-bold text-slate-400 truncate mt-0.5" x-text="event.speaker_role || 'Speaker'"></div>
                                                    </div>
                                                </div>
                                            </template>
                                        </div>

                                        <!-- Bottom Details -->
                                        <div class="pt-3 flex flex-col gap-3">
                                            <div class="flex justify-between items-center text-[8.5px] font-bold text-slate-400 gap-2">
                                                <span class="flex items-center gap-1.5 min-w-0">📍 <span class="truncate" x-text="event.location"></span></span>
                                                <span class="flex items-center gap-1.5 shrink-0">🕒 <span x-text="event.time"></span></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Navigation Controls -->
                <div class="flex justify-between items-center mt-6 max-w-xs mx-auto" x-show="events.length > itemsPerPage">
                    <button @click="prev()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-primary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &larr;
                    </button>
                    <!-- Indicators -->
                    <div class="flex items-center gap-1.5">
                        <template x-for="i in Math.max(1, events.length - itemsPerPage + 1)" :key="i">
                            <span class="h-1.5 rounded-full transition-all duration-350 cursor-pointer"
                                :class="currentIndex === (i - 1) ? 'w-6 bg-theme-primary' : 'w-2 bg-slate-200'"
                                @click="currentIndex = (i - 1)"></span>
                        </template>
                    </div>
                    <button @click="next()"
                        class="w-10 h-10 rounded-full border border-slate-200 bg-white text-slate-650 hover:bg-slate-50 hover:text-theme-primary flex items-center justify-center shadow-sm hover:shadow-md hover:scale-105 transition-all duration-200 focus:outline-none">
                        &rarr;
                    </button>
                </div>
            </div>

            <style>
                .event-ticket {
                    position: relative;
                    background: white;
                    border: 1px solid #e2e8f0;
                    border-radius: 24px;
                    padding: 20px;
                    transition: all 0.5s cubic-bezier(0.25, 1, 0.5, 1);
                    transform-style: preserve-3d;
                    perspective: 1000px;
                    display: flex;
                    flex-direction: column;
                    min-height: 330px;
                    overflow: hidden;
                }

                .event-ticket:hover {
                    transform: translateY(-6px) rotateX(4deg) rotateY(-4deg);
                    box-shadow: 0 15px 35px rgba(15, 23, 42, 0.05);
                }

                .event-ticket-notch-l,
                .event-ticket-notch-r {
                    position: absolute;
                    top: 76px;
                    width: 10px;
                    height: 20px;
                    background: #fafbfd;
                    border: 1px solid #e2e8f0;
                    z-index: 10;
                    transition: .5s;
                }

                .event-ticket-notch-l {
                    left: -1px;
                    border-radius: 0 10px 10px 0;
                    border-left: none;
                }

                .event-ticket-notch-r {
                    right: -1px;
                    border-radius: 10px 0 0 10px;
                    border-right: none;
                }

                .event-ticket:hover .event-ticket-notch-l,
                .event-ticket:hover .event-ticket-notch-r {
                    border-color: #cbd5e1;
                }
            </style>
        </section>
    @endif

    <!-- FOOTER SECTION -->
    <div class="glowing-footer-border"></div>
    <footer class="bg-slate-950 text-slate-400 py-10 border-t border-slate-900/60 relative overflow-hidden">
        <!-- Ambient background aura -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-theme-primary/5 rounded-full blur-3xl pointer-events-none">
        </div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none">
        </div>

        <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="space-y-3">
                <a href="#home" class="flex items-center gap-2 group">
                    <span
                        class="text-lg font-black tracking-tight text-white transition-all duration-300 group-hover:text-theme-primary">
                        {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE<span class="text-theme-primary group-hover:text-white transition-colors">ACADEMY</span>' !!}
                    </span>
                </a>
                <p class="text-[11px] leading-relaxed text-slate-500 dynamic-editable" data-key="footer_desc"
                    contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['footer_desc'] ?? 'Empowering students through innovative education, holistic value-building, and robust global mentorship programs.' !!}
                </p>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Quick Links</h4>
                <div class="flex flex-col space-y-1.5 text-[11px]">
                    @if($hasHeroSlides || $isEditable)
                        <a href="#home" class="footer-link hover:text-white transition">Home</a>
                    @endif
                    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                        <a href="#about" class="footer-link hover:text-white transition">About Us</a>
                    @endif
                    @if($hasAchievements)
                        <a href="#achievements" class="footer-link hover:text-white transition">Achievements</a>
                    @endif
                    @if($hasGallery)
                        <a href="#gallery" class="footer-link hover:text-white transition">Gallery</a>
                    @endif
                    @if($hasEvents)
                        <a href="#events" class="footer-link hover:text-white transition">Events</a>
                    @endif
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Contact Info</h4>
                <div class="space-y-2 text-[11px] text-slate-500">
                    <div
                        class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📍</span>
                        <span class="leading-snug dynamic-editable" data-key="footer_address"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_address'] ?? 'Education Valley' !!}</span>
                    </div>
                    <div
                        class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📞</span>
                        <span class="dynamic-editable" data-key="footer_phone"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_phone'] ?? '+1 (555) 019-2834' !!}</span>
                    </div>
                    <div
                        class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📧</span>
                        <span class="truncate dynamic-editable" data-key="footer_email"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_email'] ?? 'info@nobleacademy.edu' !!}</span>
                    </div>
                </div>
            </div>


        </div>

        <div
            class="max-w-5xl mx-auto px-6 mt-8 pt-6 border-t border-slate-900/80 flex flex-col md:flex-row justify-between items-center gap-4 text-[10.5px] text-slate-600 font-medium relative z-10">
            <p>&copy; {{ date('Y') }}
                {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'Noble Academy' !!}.
                All rights reserved.</p>

            <!-- Interactive Social Media Badges -->
            <div class="flex gap-4">
                <a href="{{ !empty($content->facebook) ? $content->facebook : '#' }}" target="_blank"
                    class="hover:text-[#1877F2] transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                </a>
                <a href="{{ !empty($content->twitter) ? $content->twitter : '#' }}" target="_blank"
                    class="hover:text-[#1DA1F2] transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                <a href="{{ !empty($content->linkedin) ? $content->linkedin : '#' }}" target="_blank"
                    class="hover:text-[#0A66C2] transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                <a href="{{ !empty($content->instagram) ? $content->instagram : '#' }}" target="_blank"
                    class="hover:text-pink-500 transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                <a href="{{ !empty($content->youtube) ? $content->youtube : '#' }}" target="_blank"
                    class="hover:text-[#FF0000] transition-colors duration-200">
                    <svg class="w-5 h-5 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.507a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.507 9.388.507 9.388.507s7.518 0 9.388-.507a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
            </div>

            <div class="flex gap-4">
                <a href="#" class="hover:text-theme-primary transition">Privacy Policy</a>
                <a href="#" class="hover:text-theme-primary transition">Terms of Service</a>
            </div>
        </div>

        <style>
            .glowing-footer-border {
                height: 2px;
                background: linear-gradient(90deg, transparent, var(--theme-primary), #10b981, transparent);
                background-size: 200% 100%;
                animation: borderMove 6s linear infinite;
            }

            @keyframes borderMove {
                0% {
                    background-position: 0% 50%;
                }

                100% {
                    background-position: 200% 50%;
                }
            }

            .footer-link {
                position: relative;
                display: inline-flex;
                align-items: center;
                transition: all 0.3s ease;
            }

            .footer-link::before {
                content: "";
                position: absolute;
                left: -12px;
                width: 3px;
                height: 3px;
                border-radius: 50%;
                background: var(--theme-primary);
                opacity: 0;
                transition: all 0.3s ease;
            }

            .footer-link:hover {
                transform: translateX(10px);
            }

            .footer-link:hover::before {
                opacity: 1;
                left: -8px;
            }
        </style>
    </footer>

    <!-- MOBILE NAV TOGGLE & NAVBAR ACTIVE LINK HIGHLIGHTER SCRIPTS -->
    <script>
        // Mobile navigation toggle
        const menuBtn = document.getElementById('mobile-menu-btn');
        const mobileNav = document.getElementById('mobile-nav');

        if (menuBtn && mobileNav) {
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
        }

        // Active navigation link highlighter (on Scroll & Click)
        const menuLinks = document.querySelectorAll('.premium-nav');
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
            window.dispatchEvent(new CustomEvent('scroll-active-tab', { detail: hash }));
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
            rootMargin: '-30% 0px -50% 0px', // Triggers when section is comfortably in view
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
            <div class="bg-white border border-slate-100 rounded-[2.5rem] p-8 md:p-10 max-w-md w-full shadow-2xl text-center space-y-6 transform scale-100 transition-all">
                <div class="w-16 h-16 bg-indigo-50 text-indigo-600 rounded-full flex items-center justify-center text-3xl mx-auto animate-bounce">
                    🌐
                </div>
                <div class="space-y-2">
                    <h3 class="text-2xl font-black text-slate-950 tracking-tight">Website Setup Required</h3>
                    <p class="text-sm text-slate-500 leading-relaxed font-medium">
                        This institute website has not been configured yet. Please log in to your dashboard to set up your template and sections.
                    </p>
                </div>
                <div class="pt-2">
                    <a href="{{ route('institute.profile.website.index') }}" class="inline-flex w-full items-center justify-center px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-2xl shadow-lg shadow-indigo-600/25 transition-all hover:scale-102 active:scale-98">
                        Go to Setup Panel
                    </a>
                </div>
            </div>
        </div>
    @endif
</body>

</html>