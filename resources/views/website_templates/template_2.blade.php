<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="/images/favicon.png" type="image/png">
    <title>Tuoora - Smart Institute Management & Payments</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- AlpineJS for Interactive States -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        theme: {
                            primary: '#4f46e5', // Indigo
                            secondary: '#0ea5e9', // Sky Blue
                            dark: '#0f172a'
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-[#fafbfd] text-slate-800 antialiased min-h-screen">

    <!-- STICKY NAVIGATION BAR -->
    <!-- UNIQUE ACADEMY NAVBAR -->
    <header class="sticky top-0 z-50 bg-white border-b border-slate-100">

        <div class="max-w-7xl mx-auto px-6">

            <div class="h-[74px] flex items-center justify-between">


                <!-- Logo -->
                <a href="#home" class="group flex items-center gap-3">


                    <!-- Logo Badge -->
                    <div class="
                    relative
                    h-12
                    w-12
                    rounded-2xl
                    bg-gradient-to-br
                    from-theme-primary
                    to-theme-secondary
                    flex
                    items-center
                    justify-center
                    shadow-lg
                    shadow-theme-primary/20
                    overflow-hidden
                    group-hover:rotate-6
                    transition-all
                    duration-500
                ">

                        <span class="
                        text-white
                        text-lg
                        font-black">
                            {!! ($institute && isset($institute->institute_name)) ? strtoupper(substr($institute->institute_name, 0, 1)) : 'N' !!}
                        </span>


                        <!-- Shine Animation -->
                        <span class="
                        absolute
                        inset-0
                        bg-white/20
                        translate-x-[-100%]
                        group-hover:translate-x-[100%]
                        transition-transform
                        duration-700
                        skew-x-12
                    ">
                        </span>


                    </div>



                    <div>
                        <h1 class="text-xl font-black tracking-tight text-slate-900 leading-none">
                            {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE <span class="text-theme-primary">ACADEMY</span>' !!}
                        </h1>

                        <p class="text-[8px] uppercase tracking-[0.35em] font-bold text-slate-400 mt-1 dynamic-editable"
                            data-key="logo_subtitle" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                            {!! $settings['logo_subtitle'] ?? 'Excellence • Innovation • Future' !!}
                        </p>
                    </div>


                </a>





                <!-- Desktop Navigation -->
                <nav class="
                hidden
                lg:flex
                items-center
                gap-1
                bg-slate-50
                rounded-full
                p-1.5
                border
                border-slate-100
            ">


                    @if($hasHeroSlides || $isEditable)
                        <a href="#home" class="menu-link active">
                            Home
                        </a>
                    @endif

                    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                        <a href="#about" class="menu-link">
                            About Us
                        </a>
                    @endif

                    @if($hasAchievements)
                        <a href="#achievements" class="menu-link">
                            Achievements
                        </a>
                    @endif

                    @if($hasGallery)
                        <a href="#gallery" class="menu-link">
                            Gallery
                        </a>
                    @endif
                    @if($hasEvents)
                        <a href="#events" class="menu-link">
                            Events
                        </a>
                    @endif


                </nav>











                <!-- Mobile Button -->
                <button id="mobile-menu-btn" class="
            lg:hidden
            h-11
            w-11
            rounded-xl
            bg-slate-900
            text-white
            flex
            items-center
            justify-center
            hover:scale-105
            transition
            ">


                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">

                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />

                    </svg>


                </button>



            </div>





            <!-- Mobile Menu -->

            <div id="mobile-nav" class="
        hidden
        lg:hidden
        pb-5
        space-y-2
        animate-menu
        ">


                @if($hasHeroSlides || $isEditable)
                    <a class="mobile-item" href="#home">
                        Home
                    </a>
                @endif

                @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                    <a class="mobile-item" href="#about">
                        About Us
                    </a>
                @endif

                @if($hasAchievements)
                    <a class="mobile-item" href="#achievements">
                        Achievements
                    </a>
                @endif

                @if($hasGallery)
                    <a class="mobile-item" href="#gallery">
                        Gallery
                    </a>
                @endif
                @if($hasEvents)
                    <a class="mobile-item" href="#events">
                        Events
                    </a>
                @endif


            </div>


        </div>


    </header>




    <style>
        .menu-link {

            position: relative;
            padding: 10px 18px;
            border-radius: 999px;
            font-size: 13px;
            font-weight: 800;
            color: #64748b;
            transition: .35s;

        }


        .menu-link:hover {

            background: white;
            color: var(--theme-primary);
            box-shadow: 0 8px 20px rgba(15, 23, 42, .08);
            transform: translateY(-2px);

        }



        .menu-link.active {

            background: white;
            color: var(--theme-primary);
            box-shadow: 0 5px 15px rgba(15, 23, 42, .08);

        }


        .menu-link.active::after {

            content: "";
            position: absolute;
            bottom: 4px;
            left: 50%;
            transform: translateX(-50%);
            width: 18px;
            height: 3px;
            border-radius: 10px;
            background: var(--theme-primary);

        }



        .mobile-item {

            display: block;
            padding: 14px 18px;
            border-radius: 18px;
            font-weight: 800;
            font-size: 14px;
            color: #475569;
            background: #f8fafc;
            transition: .3s;

        }


        .mobile-item:hover {

            background: var(--theme-primary);
            color: white;
            transform: translateX(8px);

        }



        @keyframes menu {

            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }

        }


        .animate-menu {

            animation: menu .3s ease;

        }
    </style>

    @if($hasHeroSlides || $isEditable)
        <!-- HERO SECTION -->
        <section id="home" class="relative min-h-[450px] md:min-h-[550px] flex items-center bg-slate-950 overflow-hidden"
            x-data="{
                activeSlide: 0,
                slides: {{ json_encode(!empty($heroSlides) ? $heroSlides : [
            [
                'img' => 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                'badge' => 'NOBLE',
                'badgeText' => 'Academy Campus',
                'title1' => 'Empowering',
                'title2' => 'Minds.',
                'accent' => 'Shaping',
                'title3' => 'Futures.',
                'desc' => 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80',
                'badge' => 'ACADEMICS',
                'badgeText' => 'Innovative Programs',
                'title1' => 'Innovative',
                'title2' => 'Academic',
                'accent' => 'Interactive',
                'title3' => 'Learning.',
                'desc' => 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
            ],
            [
                'img' => 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1920&q=80',
                'badge' => 'INFRA',
                'badgeText' => 'State-Of-The-Art Labs',
                'title1' => 'Modern',
                'title2' => 'Classrooms.',
                'accent' => 'Future-Ready',
                'title3' => 'Infrastructure.',
                'desc' => 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
            ]
        ]) }}
            }">

            <div class="absolute inset-0 z-0 pointer-events-none">
                <template x-for="(slide, index) in slides" :key="index">
                    <div class="absolute inset-0 transition-opacity duration-1000 ease-in-out"
                        :class="activeSlide === index ? 'opacity-100' : 'opacity-0'">
                        <img :src="slide.img" class="w-full h-full object-cover">
                    </div>
                </template>
            </div>

            <!-- Dark Gradient Overlay (For High Readability of White Text) -->
            <div
                class="absolute inset-0 bg-gradient-to-r from-slate-950 via-slate-950/80 to-slate-950/20 md:to-transparent z-10 pointer-events-none">
            </div>

            <!-- Floating Vertical Controls (Right Side) -->
            <div class="absolute right-6 top-1/2 -translate-y-1/2 flex flex-col items-center gap-3 z-30">
                @if($isEditable)
                    <button @click="document.getElementById('hero-file-input-' + activeSlide).click()"
                        class="h-9 w-9 rounded-full bg-emerald-500 hover:bg-emerald-600 text-white shadow-xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 border border-emerald-400/50"
                        title="Change Current Slide Background Image">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4.5 w-4.5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor" stroke-width="2.5">
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

                <button @click="activeSlide = (activeSlide - 1 + slides.length) % slides.length"
                    class="h-9 w-9 rounded-full bg-white/90 hover:bg-white text-slate-900 shadow-xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 border border-slate-200/50">
                    <span class="text-xs font-black">↑</span>
                </button>

                <!-- Bullet Indicators -->
                <div class="flex flex-col gap-2 py-1">
                    <template x-for="(slide, index) in slides" :key="index">
                        <button @click="activeSlide = index" class="rounded-full transition-all duration-300"
                            :class="activeSlide === index ? 'h-4 w-1.5 bg-theme-primary' : 'h-1.5 w-1.5 bg-white/40 hover:bg-white'"></button>
                    </template>
                </div>

                <button @click="activeSlide = (activeSlide + 1) % slides.length"
                    class="h-9 w-9 rounded-full bg-white/90 hover:bg-white text-slate-900 shadow-xl flex items-center justify-center transition-all hover:scale-110 active:scale-95 border border-slate-200/50">
                    <span class="text-xs font-black">↓</span>
                </button>
            </div>

            <!-- Content Container -->
            <div class="max-w-7xl mx-auto w-full px-6 relative z-20 min-h-[380px] md:min-h-[460px] flex items-center">
                <div class="relative w-full min-h-[280px] md:min-h-[320px] flex items-center">
                    <template x-for="(slide, index) in slides" :key="index">
                        <div x-show="activeSlide === index" x-transition:enter="transition ease-out duration-700 delay-300"
                            x-transition:enter-start="opacity-0 translate-y-6"
                            x-transition:enter-end="opacity-100 translate-y-0"
                            x-transition:leave="transition ease-in duration-300 absolute inset-0 flex flex-col justify-center"
                            class="space-y-4 max-w-2xl">

                            <div
                                class="inline-flex items-center gap-2 px-3 py-1 rounded-xl bg-white/10 backdrop-blur-md border border-white/20 text-white self-start">
                                <span
                                    class="px-2 py-0.5 rounded bg-theme-primary text-[8px] font-black uppercase tracking-wider"
                                    x-text="slide.badge"></span>
                                <span class="text-[9px] font-black uppercase tracking-widest text-slate-200"
                                    x-text="slide.badgeText"></span>
                            </div>

                            <h1
                                class="text-3xl md:text-5xl lg:text-6xl font-black tracking-tight text-white leading-[0.95] select-none uppercase">
                                <span x-text="slide.title1"></span><br>
                                <span x-text="slide.title2"></span><br>
                                <span
                                    class="text-transparent bg-clip-text bg-gradient-to-r from-theme-primary to-theme-secondary"
                                    x-text="slide.accent"></span><br>
                                <span x-text="slide.title3"></span>
                            </h1>

                            <p class="text-xs md:text-sm text-theme-secondary max-w-lg leading-relaxed font-medium"
                                x-text="slide.desc"></p>


                        </div>
                    </template>
                </div>
            </div>
        </section>
    @endif

    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0)
        <section id="about" class="py-16 md:py-12 px-6 bg-white border-b border-slate-100 relative overflow-hidden" x-data="{
                activePillar: 0
            }">

            <!-- Background vectors -->
            <div
                class="absolute -top-20 -left-20 w-72 h-72 bg-slate-50/60 rounded-full filter blur-2xl pointer-events-none">
            </div>
            <div
                class="absolute -bottom-20 -right-20 w-72 h-72 bg-slate-50/60 rounded-full filter blur-2xl pointer-events-none">
            </div>

            <div class="max-w-7xl mx-auto space-y-6 relative z-10">
                <!-- Header -->
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest dynamic-editable"
                        data-key="about_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_badge'] ?? 'About Our Academy' !!}
                    </span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable"
                        data-key="about_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_title'] ?? 'Our Core Pillars' !!}
                    </h2>
                    <p class="text-xs text-slate-500 dynamic-editable" data-key="about_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['about_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow.' !!}
                    </p>
                </div>

                <!-- Split Grid -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 items-stretch">
                    <!-- Left: Campus Image Showcase -->
                    <div
                        class="lg:col-span-5 relative rounded-[2.5rem] overflow-hidden group min-h-[350px] md:min-h-[420px] shadow-xl">
                        <img src="https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80"
                            class="absolute inset-0 w-full h-full object-cover group-hover:scale-105 transition-transform duration-700">
                        <div class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent">
                        </div>

                        <!-- Decorative Glass Badge over the Image -->
                        <div
                            class="absolute bottom-6 left-6 right-6 p-5 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl text-white">
                            <span class="text-[9px] font-black uppercase tracking-widest text-slate-200 block mb-1">Noble
                                Academy</span>
                            <h4 class="text-xs font-black tracking-tight leading-snug">Inspiring Generations of Thinkers and
                                Creators.</h4>
                        </div>
                    </div>

                    <!-- Right: Accordion Pillar List -->
                    <div class="lg:col-span-7 flex flex-col justify-center gap-4">
                        @php $idx = 0; @endphp

                        <!-- Vision Pillars -->
                        @foreach($visionItems as $item)
                            <div @mouseenter="activePillar = {{ $idx }}" @click="activePillar = {{ $idx }}"
                                class="group border rounded-3xl p-5 cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col justify-between"
                                :class="activePillar === {{ $idx }} 
                                     ? 'bg-slate-50/80 border-slate-350 shadow-md translate-x-2' 
                                     : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50/30'">

                                <div class="flex items-start gap-4">
                                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center text-lg border transition-all duration-500"
                                        :class="activePillar === {{ $idx }} 
                                             ? 'bg-white border-slate-200 shadow-sm rotate-6 scale-110 text-theme-primary' 
                                             : 'bg-slate-50 border-slate-100 text-slate-400 group-hover:rotate-3'">
                                        <span>🔭</span>
                                    </div>

                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-[9px] font-black uppercase tracking-wider text-slate-400">VISION</span>
                                            <span
                                                class="text-xs font-black text-slate-300 group-hover:text-slate-500 transition-colors">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800 transition-colors"
                                            :class="activePillar === {{ $idx }} ? 'text-theme-primary' : 'group-hover:text-slate-900'">
                                            {!! $item['title'] !!}
                                        </h3>
                                    </div>
                                </div>

                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                    :class="activePillar === {{ $idx }} ? 'max-h-[120px] mt-4 opacity-100' : 'max-h-0 opacity-0'">
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium mb-3">
                                        {!! $item['desc'] !!}
                                    </p>
                                </div>
                            </div>
                            @php $idx++; @endphp
                        @endforeach

                        <!-- Mission Pillars -->
                        @foreach($missionItems as $item)
                            <div @mouseenter="activePillar = {{ $idx }}" @click="activePillar = {{ $idx }}"
                                class="group border rounded-3xl p-5 cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col justify-between"
                                :class="activePillar === {{ $idx }} 
                                     ? 'bg-slate-50/80 border-slate-350 shadow-md translate-x-2' 
                                     : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50/30'">

                                <div class="flex items-start gap-4">
                                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center text-lg border transition-all duration-500"
                                        :class="activePillar === {{ $idx }} 
                                             ? 'bg-white border-slate-200 shadow-sm rotate-6 scale-110 text-emerald-600' 
                                             : 'bg-slate-50 border-slate-100 text-slate-400 group-hover:rotate-3'">
                                        <span>🚀</span>
                                    </div>

                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-[9px] font-black uppercase tracking-wider text-slate-400">MISSION</span>
                                            <span
                                                class="text-xs font-black text-slate-300 group-hover:text-slate-500 transition-colors">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800 transition-colors"
                                            :class="activePillar === {{ $idx }} ? 'text-emerald-600' : 'group-hover:text-slate-900'">
                                            {!! $item['title'] !!}
                                        </h3>
                                    </div>
                                </div>

                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                    :class="activePillar === {{ $idx }} ? 'max-h-[120px] mt-4 opacity-100' : 'max-h-0 opacity-0'">
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium mb-3">
                                        {!! $item['desc'] !!}
                                    </p>
                                </div>
                            </div>
                            @php $idx++; @endphp
                        @endforeach

                        <!-- Values Pillars -->
                        @foreach($valuesItems as $item)
                            <div @mouseenter="activePillar = {{ $idx }}" @click="activePillar = {{ $idx }}"
                                class="group border rounded-3xl p-5 cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col justify-between"
                                :class="activePillar === {{ $idx }} 
                                     ? 'bg-slate-50/80 border-slate-350 shadow-md translate-x-2' 
                                     : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50/30'">

                                <div class="flex items-start gap-4">
                                    <div class="h-10 w-10 rounded-2xl flex items-center justify-center text-lg border transition-all duration-500"
                                        :class="activePillar === {{ $idx }} 
                                             ? 'bg-white border-slate-200 shadow-sm rotate-6 scale-110 text-theme-secondary' 
                                             : 'bg-slate-50 border-slate-100 text-slate-400 group-hover:rotate-3'">
                                        <span>🛡️</span>
                                    </div>

                                    <div class="flex-1 space-y-1">
                                        <div class="flex items-center justify-between">
                                            <span
                                                class="text-[9px] font-black uppercase tracking-wider text-slate-400">VALUES</span>
                                            <span
                                                class="text-xs font-black text-slate-300 group-hover:text-slate-500 transition-colors">{{ str_pad($idx + 1, 2, '0', STR_PAD_LEFT) }}</span>
                                        </div>
                                        <h3 class="text-sm font-black text-slate-800 transition-colors"
                                            :class="activePillar === {{ $idx }} ? 'text-theme-secondary' : 'group-hover:text-slate-900'">
                                            {!! $item['title'] !!}
                                        </h3>
                                    </div>
                                </div>

                                <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                    :class="activePillar === {{ $idx }} ? 'max-h-[120px] mt-4 opacity-100' : 'max-h-0 opacity-0'">
                                    <p class="text-[11px] text-slate-500 leading-relaxed font-medium mb-3">
                                        {!! $item['desc'] !!}
                                    </p>
                                </div>
                            </div>
                            @php $idx++; @endphp
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!--Archivment SECTION -->
    @if($hasAchievements || $isEditable)
        <section id="achievements"
            class="py-10 md:py-14 px-6 bg-[#fafbfd] border-b border-slate-100 relative overflow-hidden">

            <!-- Ambient Background -->
            <div
                class="absolute top-0 left-1/2 -translate-x-1/2 w-[500px] h-[500px] bg-theme-primary/5 rounded-full blur-[120px]">
            </div>
            <div class="absolute bottom-0 right-0 w-96 h-96 bg-indigo-100/40 rounded-full blur-3xl"></div>

            <div class="max-w-7xl mx-auto relative z-10">

                <!-- Header -->
                <div class="text-center max-w-xl mx-auto mb-10">
                    <span
                        class="inline-block text-[10px] font-black text-theme-primary uppercase tracking-[0.3em] mb-2 dynamic-editable"
                        data-key="achieve_badge" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_badge'] ?? 'Our Milestones' !!}
                    </span>

                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight dynamic-editable"
                        data-key="achieve_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_title'] ?? 'Recent Achievements' !!}
                    </h2>

                    <p class="mt-2 text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="achieve_desc"
                        contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                        {!! $settings['achieve_desc'] ?? 'Proud moments demonstrating our dedication to academic and athletic excellence.' !!}
                    </p>
                </div>


                <!-- Achievement Timeline -->
                <div class="relative">

                    <!-- Center Line -->
                    <div
                        class="hidden md:block absolute top-14 left-1/2 -translate-x-1/2 w-[2px] h-[65%] bg-gradient-to-b from-theme-primary/30 via-slate-200 to-transparent">
                    </div>


                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <!-- CARD -->
                        @if(!empty($settings['ach1_title']))
                            <div class="group relative animate-[float_5s_ease-in-out_infinite]">

                                <!-- Number Bubble -->
                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20 
                                                    w-10 h-10 rounded-full bg-white border border-slate-200 
                                                    flex items-center justify-center shadow-md
                                                    group-hover:rotate-[360deg] transition duration-700">

                                    <span class="text-xs font-black text-theme-primary">
                                        01
                                    </span>
                                </div>


                                <div class="relative bg-white rounded-[2rem] p-5 pt-8 
                                                    border border-slate-200
                                                    shadow-[0_15px_35px_rgba(15,23,42,0.04)]
                                                    overflow-hidden
                                                    hover:-translate-y-1.5
                                                    transition-all duration-500">


                                    <!-- Hover Gradient -->
                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 
                                                        transition duration-500
                                                        bg-gradient-to-br from-amber-50/60 via-transparent to-transparent">
                                    </div>


                                    <div class="relative z-10 space-y-3.5">


                                        <div class="w-10 h-10 rounded-xl 
                                                            bg-amber-50 border border-amber-100
                                                            flex items-center justify-center text-xl
                                                            shadow-inner
                                                            group-hover:scale-110 group-hover:rotate-6
                                                            transition duration-500">

                                            🏆

                                        </div>


                                        <div>
                                            <h3 class="text-sm font-black text-slate-800 tracking-tight group-hover:text-theme-primary transition dynamic-editable"
                                                data-key="ach1_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach1_title'] ?? 'Best School Award 2025' !!}
                                            </h3>

                                            <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                                data-key="ach1_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach1_desc'] ?? 'Named "State’s Most Innovational Education Center" for integrating interactive smart panels in 100% of classrooms.' !!}
                                            </p>
                                        </div>


                                        <!-- Achievement Meter -->
                                        <div class="pt-3 border-t border-slate-100">

                                            <div class="flex justify-between text-[8px] 
                                                                font-black uppercase tracking-widest text-slate-400">

                                                <span>Milestone Reach</span>

                                                <span class="text-amber-500 opacity-0 group-hover:opacity-100 transition">
                                                    Completed
                                                </span>

                                            </div>


                                            <div class="mt-2 h-1 bg-slate-100 rounded-full overflow-hidden">

                                                <div class="h-full w-0 bg-gradient-to-r from-amber-400 to-amber-500
                                                                    group-hover:w-full transition-all duration-1000">
                                                </div>

                                            </div>

                                        </div>


                                    </div>

                                </div>

                            </div>
                        @endif





                        <!-- CARD 2 -->
                        @if(!empty($settings['ach2_title']))
                            <div class="group relative animate-[float_5s_ease-in-out_1s_infinite]">


                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20 
                                                    w-10 h-10 rounded-full bg-white border border-slate-200 
                                                    flex items-center justify-center shadow-md
                                                    group-hover:rotate-[360deg] transition duration-700">

                                    <span class="text-xs font-black text-indigo-500">
                                        02
                                    </span>

                                </div>



                                <div class="relative bg-white rounded-[2rem] p-5 pt-8 
                                                    border border-slate-200
                                                    shadow-[0_15px_35px_rgba(15,23,42,0.04)]
                                                    overflow-hidden
                                                    hover:-translate-y-1.5
                                                    transition-all duration-500">


                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 
                                                        transition duration-500
                                                        bg-gradient-to-br from-indigo-50/60">
                                    </div>


                                    <div class="relative z-10 space-y-3.5">


                                        <div class="w-10 h-10 rounded-xl 
                                                            bg-indigo-50 border border-indigo-100
                                                            flex items-center justify-center text-xl
                                                            group-hover:scale-110 group-hover:rotate-6
                                                            transition duration-500">

                                            🎓

                                        </div>


                                        <div>
                                            <h3 class="text-sm font-black text-slate-800 tracking-tight group-hover:text-theme-primary transition dynamic-editable"
                                                data-key="ach2_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach2_title'] ?? '100% Board Exam Success' !!}
                                            </h3>

                                            <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                                data-key="ach2_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach2_desc'] ?? 'For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45% scoring distinctions.' !!}
                                            </p>
                                        </div>



                                        <div class="pt-3 border-t border-slate-100">

                                            <div
                                                class="flex justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">

                                                <span>Milestone Reach</span>

                                                <span class="text-indigo-500 opacity-0 group-hover:opacity-100 transition">
                                                    Completed
                                                </span>

                                            </div>


                                            <div class="mt-2 h-1 bg-slate-100 rounded-full overflow-hidden">

                                                <div class="h-full w-0 bg-gradient-to-r from-theme-primary to-theme-secondary
                                                                    group-hover:w-full transition-all duration-1000">
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>
                            </div>
                        @endif

                        <!-- CARD 3 -->
                        @if(!empty($settings['ach3_title']))
                            <div class="group relative animate-[float_5s_ease-in-out_2s_infinite]">

                                <div class="absolute -top-5 left-1/2 -translate-x-1/2 z-20 
                                                    w-10 h-10 rounded-full bg-white border border-slate-200 
                                                    flex items-center justify-center shadow-md
                                                    group-hover:rotate-[360deg] transition duration-700">

                                    <span class="text-xs font-black text-emerald-500">
                                        03
                                    </span>

                                </div>


                                <div class="relative bg-white rounded-[2rem] p-5 pt-8 
                                                    border border-slate-200
                                                    shadow-[0_15px_35px_rgba(15,23,42,0.04)]
                                                    overflow-hidden
                                                    hover:-translate-y-1.5
                                                    transition-all duration-500">


                                    <div class="absolute inset-0 opacity-0 group-hover:opacity-100 
                                                        transition duration-500
                                                        bg-gradient-to-br from-emerald-50/60">
                                    </div>


                                    <div class="relative z-10 space-y-3.5">


                                        <div class="w-10 h-10 rounded-xl 
                                                            bg-emerald-50 border border-emerald-100
                                                            flex items-center justify-center text-xl
                                                            group-hover:scale-110 group-hover:rotate-6
                                                            transition duration-500">

                                            🏅

                                        </div>


                                        <div>
                                            <h3 class="text-sm font-black text-slate-800 tracking-tight group-hover:text-theme-primary transition dynamic-editable"
                                                data-key="ach3_title" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach3_title'] ?? 'National Sports Champions' !!}
                                            </h3>

                                            <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium dynamic-editable"
                                                data-key="ach3_desc" contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                                                {!! $settings['ach3_desc'] ?? 'Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports Championship.' !!}
                                            </p>
                                        </div>


                                        <div class="pt-3 border-t border-slate-100">

                                            <div
                                                class="flex justify-between text-[8px] font-black uppercase tracking-widest text-slate-400">

                                                <span>Milestone Reach</span>

                                                <span class="text-emerald-500 opacity-0 group-hover:opacity-100 transition">
                                                    Completed
                                                </span>

                                            </div>


                                            <div class="mt-2 h-1 bg-slate-100 rounded-full overflow-hidden">

                                                <div class="h-full w-0 bg-gradient-to-r from-emerald-400 to-emerald-500
                                                                    group-hover:w-full transition-all duration-1000">
                                                </div>

                                            </div>

                                        </div>

                                    </div>

                                </div>

                        @endif


                        </div>

                    </div>

                </div>


                <style>
                    @keyframes float {

                        0%,
                        100% {
                            transform: translateY(0);
                        }

                        50% {
                            transform: translateY(-12px);
                        }
                    }
                </style>

        </section>
    @endif


    <!-- GALLERY SECTION -->

    @if($hasGallery)
        <section id="gallery" class="py-6 md:py-8 px-6 bg-white border-b border-slate-100" x-data='{ 
                                      items: {{ json_encode($galleryItems) }},
                             filteredItems() {
                                 return this.items;
                             },
                             spotlightIndex: 0
                         }'>

            <div class="max-w-7xl mx-auto space-y-8">
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest">Visual Tour</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
                    <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of our Academy.</p>
                </div>



                <!-- Spotlight Grid Container -->
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">
                    <!-- Left: Big Showcase Spotlight Frame -->
                    <div
                        class="lg:col-span-7 relative rounded-[2.5rem] overflow-hidden min-h-[350px] md:min-h-[450px] bg-slate-900 shadow-xl group">
                        <template x-for="(item, index) in filteredItems()" :key="item.id">
                            <div x-show="spotlightIndex === index" x-transition:enter="transition ease-out duration-500"
                                x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100"
                                x-transition:leave="transition ease-in duration-300 absolute inset-0"
                                x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
                                class="absolute inset-0 w-full h-full">
                                <img :src="item.img"
                                    class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-1000">



                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-950/90 via-slate-950/20 to-transparent">
                                </div>

                                <!-- Overlay Info -->
                                <div class="absolute bottom-8 left-8 right-8 space-y-2">
                                    <span
                                        class="px-2.5 py-0.5 rounded-lg bg-theme-primary text-[8px] font-black uppercase tracking-widest text-white inline-block"
                                        x-text="item.category"></span>
                                    <h3 class="text-lg md:text-xl font-black text-white tracking-tight" x-text="item.title">
                                    </h3>
                                </div>
                            </div>
                        </template>
                    </div>

                    <!-- Right: Thumbnails Filmstrip List -->
                    <div class="lg:col-span-5 flex flex-col justify-between gap-4">
                        <div class="space-y-3 max-h-[450px] overflow-y-auto overflow-x-hidden py-1 pl-1 pr-2.5">
                            <template x-for="(item, index) in filteredItems()" :key="item.id">
                                <div @click="spotlightIndex = index" @mouseenter="spotlightIndex = index"
                                    class="group flex items-center gap-4 p-3 rounded-2xl border cursor-pointer transition-all duration-300 bg-white"
                                    :class="spotlightIndex === index 
                                                 ? 'border-slate-350 bg-slate-50 shadow-sm translate-x-1.5' 
                                                 : 'border-slate-100 hover:border-slate-200 hover:bg-slate-50/50'">

                                    <!-- Small image thumbnail -->
                                    <div class="h-16 w-20 rounded-xl overflow-hidden bg-slate-100 flex-shrink-0 relative">
                                        <img :src="item.img" class="w-full h-full object-cover">
                                        <div
                                            class="absolute inset-0 bg-slate-950/10 group-hover:bg-transparent transition-colors">
                                        </div>
                                    </div>

                                    <!-- Metadata -->
                                    <div class="flex-1 min-w-0">
                                        <span
                                            class="text-[8px] font-black text-theme-primary uppercase tracking-widest block"
                                            x-text="item.category"></span>
                                        <h4 class="text-xs font-black text-slate-800 truncate" x-text="item.title"></h4>
                                    </div>

                                    <!-- Indicator Arrow -->
                                    <span
                                        class="h-6 w-6 rounded-lg bg-slate-50 text-slate-400 flex items-center justify-center text-[10px] font-bold group-hover:bg-theme-primary group-hover:text-white transition-all duration-300"
                                        :class="spotlightIndex === index ? 'bg-theme-primary text-white rotate-90 scale-105' : ''">
                                        →
                                    </span>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    @endif

    <!-- EVENTS SECTION -->
    @if($hasEvents)
        <section id="events" class="py-10 md:py-7 px-6 bg-[#fafbfd] border-b border-slate-100" x-data="{
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
            }" x-init="updateItemsPerPage(); window.addEventListener('resize', () => { updateItemsPerPage(); currentIndex = Math.min(currentIndex, maxIndex); })">
            <div class="max-w-7xl mx-auto space-y-8">
                <div class="text-center space-y-2 max-w-xl mx-auto">
                    <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest">Upcoming Activities</span>
                    <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                    <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and educational programs.</p>
                </div>

                <!-- Events Carousel Viewport -->
                <div class="relative w-full overflow-hidden p-1">
                    <div class="flex transition-transform duration-500 ease-out"
                        :style="`transform: translateX(-${currentIndex * (100 / events.length)}%); width: ${events.length * (100 / itemsPerPage)}%;`"
                        style="width: 100%;">
                        <template x-for="(event, index) in events" :key="index">
                            <div class="px-3 flex-shrink-0 transition-all duration-500 ease-out"
                                :style="`width: ${100 / events.length}%;`">
                                <div class="relative bg-white border border-slate-200 rounded-[2rem] p-5 group hover:shadow-[0_20px_40px_rgba(15,23,42,0.06)] hover:-translate-y-1 transition-all duration-500 flex flex-col justify-between overflow-hidden h-full w-full">
                                    <!-- Top Hover Accent Line -->
                                    <div class="absolute top-0 left-0 w-full h-[3px] transition-transform duration-500 origin-left scale-x-0 group-hover:scale-x-100"
                                        :class="{
                                            'bg-indigo-500': index % 3 === 0,
                                            'bg-emerald-500': index % 3 === 1,
                                            'bg-sky-500': index % 3 === 2
                                        }">
                                    </div>

                                    <!-- Top Row: Typographic Date & Category Tag -->
                                    <div class="flex justify-between items-start gap-2">
                                        <div class="flex items-center gap-2.5">
                                            <span class="text-4xl font-black tracking-tighter text-slate-200 transition-colors duration-500 select-none font-outfit"
                                                :class="{
                                                    'group-hover:text-indigo-600': index % 3 === 0,
                                                    'group-hover:text-emerald-600': index % 3 === 1,
                                                    'group-hover:text-sky-600': index % 3 === 2
                                                }"
                                                x-text="event.day"></span>
                                            <div class="flex flex-col leading-none">
                                                <span class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400" x-text="event.month"></span>
                                                <span class="text-[7.5px] font-bold text-slate-400/80 mt-0.5" x-text="event.year"></span>
                                            </div>
                                        </div>
                                        <span class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider border"
                                            :class="{
                                                'bg-indigo-55 text-indigo-600 border-indigo-100/50': index % 3 === 0,
                                                'bg-emerald-50 text-emerald-600 border-emerald-100/50': index % 3 === 1,
                                                'bg-sky-50 text-sky-600 border-sky-100/50': index % 3 === 2
                                            }"
                                            x-text="event.tag"></span>
                                    </div>

                                    <!-- Middle Content: Title, Description & Speaker -->
                                    <div class="my-4 space-y-2 flex-1 flex flex-col justify-between">
                                        <div class="space-y-1">
                                            <h3 class="text-xs font-black text-slate-800 transition-colors duration-300 font-outfit"
                                                :class="{
                                                    'group-hover:text-indigo-600': index % 3 === 0,
                                                    'group-hover:text-emerald-600': index % 3 === 1,
                                                    'group-hover:text-sky-600': index % 3 === 2
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

                                    <!-- Bottom Content: Metadata -->
                                    <div class="pt-3 border-t border-slate-100 flex justify-between items-center text-[8.5px] font-bold text-slate-400 gap-2">
                                        <span class="flex items-center gap-1.5 min-w-0">
                                            <span class="text-[9px] shrink-0">📍</span> <span class="truncate" x-text="event.location"></span>
                                        </span>
                                        <span class="flex items-center gap-1.5 shrink-0">
                                            <span class="text-[9px] shrink-0">🕒</span> <span x-text="event.time"></span>
                                        </span>
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
        </section>
    @endif

    <!-- FOOTER -->
    <footer class="bg-slate-50 text-slate-500 py-12 px-6 border-t border-slate-100 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 border-b border-slate-200/80 pb-8">
            <div class="space-y-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight">
                    {!! ($institute && isset($institute->institute_name)) ? ($institute->institute_name) : 'NOBLE<span class="text-theme-primary">ACADEMY</span>' !!}
                </span>
                <p class="text-xs text-slate-500 leading-relaxed dynamic-editable" data-key="footer_desc"
                    contenteditable="{{ $isEditable ? 'true' : 'false' }}">
                    {!! $settings['footer_desc'] ?? 'Combining academic innovation with core values to empower the leaders of tomorrow since 2012.' !!}
                </p>
            </div>

            <div>
                <h4 class="text-slate-900 font-extrabold text-xs uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="text-xs space-y-2 font-medium">
                    @if($hasHeroSlides || $isEditable)
                        <li>
                            <a href="#home"
                                class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                                Home
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if(count($visionItems) > 0 || count($missionItems) > 0 || count($valuesItems) > 0 || $isEditable)
                        <li>
                            <a href="#about"
                                class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                                About Us
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if($hasAchievements)
                        <li>
                            <a href="#achievements"
                                class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                                Achievements
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                    @if($hasGallery)
                        <li>
                            <a href="#gallery"
                                class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                                Gallery
                                <span
                                    class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                            </a>
                        </li>
                    @endif
                </ul>
            </div>

            <div>
                <h4 class="text-slate-900 font-extrabold text-xs uppercase tracking-wider mb-4">Contact Info</h4>
                <ul class="text-xs space-y-3 font-semibold">
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📧</span>
                        <span class="dynamic-editable" data-key="footer_email"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_email'] ?? 'admissions@nobleacademy.edu' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📞</span>
                        <span class="dynamic-editable" data-key="footer_phone"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_phone'] ?? '+91 98765 43210' !!}</span>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📍</span>
                        <span class="dynamic-editable" data-key="footer_address"
                            contenteditable="{{ $isEditable ? 'true' : 'false' }}">{!! $settings['footer_address'] ?? 'Ahmedabad, Gujarat, India' !!}</span>
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 font-medium">
            <p>&copy; {{ date('Y') }}
               Tuoora.
                All rights reserved.
            </p>

            <!-- Interactive Social Media Badges -->
            <div class="flex gap-2.5 mt-4 md:mt-0">
                @if(!empty($content->facebook))
                <a href="{{ $content->facebook }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#1877F2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1877F2]/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M22 12c0-5.52-4.48-10-10-10S2 6.48 2 12c0 4.84 3.44 8.87 8 9.8V15H8v-3h2V9.5C10 7.57 11.57 6 13.5 6H16v3h-2c-.55 0-1 .45-1 1v2h3v3h-3v6.95c4.56-.93 8-4.96 8-9.75z"/></svg>
                </a>
                @endif
                @if(!empty($content->twitter))
                <a href="{{ $content->twitter }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#1DA1F2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1DA1F2]/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                </a>
                @endif
                @if(!empty($content->linkedin))
                <a href="{{ $content->linkedin }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#0A66C2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#0A66C2]/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M19 0h-14c-2.761 0-5 2.239-5 5v14c0 2.761 2.239 5 5 5h14c2.762 0 5-2.239 5-5v-14c0-2.761-2.238-5-5-5zm-11 19h-3v-11h3v11zm-1.5-12.268c-.966 0-1.75-.779-1.75-1.75s.784-1.75 1.75-1.75 1.75.779 1.75 1.75-.784 1.75-1.75 1.75zm13.5 12.268h-3v-5.604c0-3.368-4-3.113-4 0v5.604h-3v-11h3v1.765c1.396-2.586 7-2.777 7 2.476v6.759z"/></svg>
                </a>
                @endif
                @if(!empty($content->instagram))
                <a href="{{ $content->instagram }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-gradient-to-tr hover:from-purple-600 hover:to-orange-500 hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-pink-500/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.051.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                </a>
                @endif
                @if(!empty($content->youtube))
                <a href="{{ $content->youtube }}" target="_blank"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#FF0000] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#FF0000]/10">
                    <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="M23.498 6.163a3.003 3.003 0 00-2.11-2.11C19.518 3.545 12 3.545 12 3.545s-7.518 0-9.388.507a3.003 3.003 0 00-2.11 2.11C0 8.033 0 12 0 12s0 3.967.502 5.837a3.003 3.003 0 002.11 2.11c1.87.507 9.388.507 9.388.507s7.518 0 9.388-.507a3.003 3.003 0 002.11-2.11C24 15.967 24 12 24 12s0-3.967-.502-5.837zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg>
                </a>
                @endif
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
                    class="fixed bottom-6 right-6 z-50 flex items-center justify-center h-10 w-10 bg-theme-primary hover:bg-indigo-700 text-white rounded-2xl shadow-lg shadow-indigo-600/20 transition-all duration-300 hover:scale-105 active:scale-95 group">
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
        const menuLinks = document.querySelectorAll('.menu-link');
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