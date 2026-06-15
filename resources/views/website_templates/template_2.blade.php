<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Noble Academy - Academic Excellence</title>
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
                        font-black
                    ">
                            N
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

                        <h1 class="
                        text-xl
                        font-black
                        tracking-tight
                        text-slate-900
                        leading-none
                    ">
                            NOBLE
                            <span class="text-theme-primary">
                                ACADEMY
                            </span>
                        </h1>


                        <p class="
                        text-[8px]
                        uppercase
                        tracking-[0.35em]
                        font-bold
                        text-slate-400
                        mt-1
                    ">
                            Excellence • Innovation • Future
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


                    <a href="#home" class="menu-link active">
                        Home
                    </a>


                    <a href="#about" class="menu-link">
                        About Us
                    </a>


                    <a href="#achievements" class="menu-link">
                        Achievements
                    </a>


                    <a href="#gallery" class="menu-link">
                        Gallery
                    </a>


                    <a href="#events" class="menu-link">
                        Events
                    </a>


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


                <a class="mobile-item" href="#home">
                    Home
                </a>

                <a class="mobile-item" href="#about">
                    About Us
                </a>

                <a class="mobile-item" href="#achievements">
                    Achievements
                </a>

                <a class="mobile-item" href="#gallery">
                    Gallery
                </a>

                <a class="mobile-item" href="#events">
                    Events
                </a>


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

    <!-- HERO SECTION -->
    <section id="home" class="relative min-h-[450px] md:min-h-[550px] flex items-center bg-slate-950 overflow-hidden"
        x-data="{
            activeSlide: 0,
            slides: [
                {
                    img: 'https://images.unsplash.com/photo-1562774053-701939374585?auto=format&fit=crop&w=800&q=80',
                    badge: 'NOBLE',
                    badgeText: 'Academy Campus',
                    title1: 'Empowering',
                    title2: 'Minds.',
                    accent: 'Shaping',
                    title3: 'Futures.',
                    desc: 'Welcome to Noble Academy. We offer a world-class environment fostering academic brilliance and leadership traits.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1522202176988-66273c2fd55f?auto=format&fit=crop&w=1920&q=80',
                    badge: 'ACADEMICS',
                    badgeText: 'Innovative Programs',
                    title1: 'Innovative',
                    title2: 'Academic',
                    accent: 'Interactive',
                    title3: 'Learning.',
                    desc: 'Dynamic curricula paired with hands-on lab experiments, empowering students with the skills for tomorrow.'
                },
                {
                    img: 'https://images.unsplash.com/photo-1517486808906-6ca8b3f04846?auto=format&fit=crop&w=1920&q=80',
                    badge: 'INFRA',
                    badgeText: 'State-Of-The-Art Labs',
                    title1: 'Modern',
                    title2: 'Classrooms.',
                    accent: 'Future-Ready',
                    title3: 'Infrastructure.',
                    desc: 'Explore our spacious modern classrooms, fully integrated computer hubs, science labs, and lush sports grounds.'
                }
            ]
        }">

        <!-- Cross-Fading Background Images -->
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

    <!-- ABOUT US SECTION -->
    <section id="about" class="py-16 md:py-12 px-6 bg-white border-b border-slate-100 relative overflow-hidden" x-data="{
            activePillar: 0,
            pillars: [
                {
                    num: '01',
                    icon: '🔭',
                    badge: 'VISION',
                    title: 'Nurturing Leaders Since 2012',
                    desc: 'To establish a global standard in education that balances academic rigor with creative expression, cultivating visionary leaders of tomorrow.',
                    focus: 'Holistic Growth • Integrated Technology • Creative Innovation',
                    textCol: 'text-theme-primary'
                },
                {
                    num: '02',
                    icon: '🚀',
                    badge: 'MISSION',
                    title: 'Fostering Excellence & Integrity',
                    desc: 'To provide a stimulating learning environment where students excel academically, develop strong moral values, and become responsible global citizens.',
                    focus: 'Qualified Mentors • Student curriculum • Civic Foundations',
                    textCol: 'text-emerald-600'
                },
                {
                    num: '03',
                    icon: '🛡️',
                    badge: 'VALUES',
                    title: 'Our Core Pillars of Success',
                    desc: 'We are anchored in key moral and academic tenets that guide every lesson, interaction, and milestone achieved within our campus.',
                    focus: 'Unyielding Integrity • Empathetic Collaboration • Inquisitive Mindsets',
                    textCol: 'text-theme-secondary'
                }
            ]
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
                <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest">About Our
                    Academy</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Our Foundational Framework
                </h2>
                <p class="text-xs text-slate-500">Combining academic innovation with core values to empower the leaders
                    of tomorrow.</p>
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
                    <template x-for="(pillar, index) in pillars" :key="index">
                        <div @mouseenter="activePillar = index" @click="activePillar = index"
                            class="group border rounded-3xl p-5 cursor-pointer transition-all duration-300 relative overflow-hidden flex flex-col justify-between"
                            :class="activePillar === index 
                                 ? 'bg-slate-50/80 border-slate-350 shadow-md translate-x-2' 
                                 : 'bg-white border-slate-100 hover:border-slate-200 hover:bg-slate-50/30'">

                            <div class="flex items-start gap-4">
                                <div class="h-10 w-10 rounded-2xl flex items-center justify-center text-lg border transition-all duration-500"
                                    :class="activePillar === index 
                                         ? 'bg-white border-slate-200 shadow-sm rotate-6 scale-110 ' + pillar.textCol 
                                         : 'bg-slate-50 border-slate-100 text-slate-400 group-hover:rotate-3'">
                                    <span x-text="pillar.icon"></span>
                                </div>

                                <div class="flex-1 space-y-1">
                                    <div class="flex items-center justify-between">
                                        <span class="text-[9px] font-black uppercase tracking-wider text-slate-400"
                                            x-text="pillar.badge"></span>
                                        <span
                                            class="text-xs font-black text-slate-300 group-hover:text-slate-500 transition-colors"
                                            x-text="pillar.num"></span>
                                    </div>
                                    <h3 class="text-sm font-black text-slate-800 transition-colors"
                                        :class="activePillar === index ? pillar.textCol : 'group-hover:text-slate-900'"
                                        x-text="pillar.title"></h3>
                                </div>
                            </div>

                            <div class="transition-all duration-500 ease-in-out overflow-hidden"
                                :class="activePillar === index ? 'max-h-[120px] mt-4 opacity-100' : 'max-h-0 opacity-0'">
                                <p class="text-[11px] text-slate-500 leading-relaxed font-medium mb-3"
                                    x-text="pillar.desc"></p>

                                <div class="pt-2 border-t border-slate-200/60">
                                    <span
                                        class="text-[8px] font-black text-slate-400 uppercase tracking-widest block mb-0.5">Key
                                        Focus Areas</span>
                                    <span class="text-[10px] font-extrabold" :class="pillar.textCol"
                                        x-text="pillar.focus"></span>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </section>

    <!--Archivment SECTION -->
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
                <span class="inline-block text-[10px] font-black text-theme-primary uppercase tracking-[0.3em] mb-2">
                    Our Milestones
                </span>

                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">
                    Recent Achievements
                </h2>

                <p class="mt-2 text-xs text-slate-500 leading-relaxed">
                    Proud moments demonstrating our dedication to academic and athletic excellence.
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

                                    <h3 class="text-sm font-black text-slate-800 tracking-tight 
                                group-hover:text-theme-primary transition">

                                        Best School Award 2025

                                    </h3>


                                    <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium">

                                        Named "State’s Most Innovational Education Center" for integrating interactive
                                        smart panels in 100% of classrooms.

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





                    <!-- CARD 2 -->
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

                                    <h3 class="text-sm font-black text-slate-800 tracking-tight 
                                group-hover:text-theme-primary transition">

                                        100% Board Exam Success

                                    </h3>


                                    <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium">

                                        For 8 consecutive years, our senior batch students have achieved a 100% pass
                                        rate with over 45% scoring distinctions.

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





                    <!-- CARD 3 -->
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

                                    <h3 class="text-sm font-black text-slate-800 tracking-tight 
                                group-hover:text-theme-primary transition">

                                        National Sports Champions

                                    </h3>


                                    <p class="mt-1.5 text-[11px] text-slate-500 leading-relaxed font-medium">

                                        Our athletic team brought home 4 Gold and 2 Silver medals from the All-India
                                        Inter-School Sports Championship.

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

                    </div>


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


    <!-- GALLERY SECTION -->
    <section id="gallery" class="py-6 md:py-8 px-6 bg-white border-b border-slate-100" x-data="{ 
                 activeFilter: 'all',
                 spotlightIndex: 0,
                 items: [
                     { id: 1, title: 'Quantum Physics Lab', category: 'academics', img: 'https://images.unsplash.com/photo-1507679799987-c73779587ccf?auto=format&fit=crop&w=800&q=80' },
                     { id: 2, title: 'Smart Infrastructure Campus', category: 'infrastructure', img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=800&q=80' },
                { id: 3, title: 'National Hockey League', category: 'sports', img: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=800&q=80' },
                     { id: 4, title: 'Digital Music Festival', category: 'co-curricular', img: 'https://images.unsplash.com/photo-1511671782779-c97d3d27a1d4?auto=format&fit=crop&w=800&q=80' },
                     { id: 5, title: 'Modern Library Center', category: 'infrastructure', img: 'https://images.unsplash.com/photo-1521587760476-6c12a4b040da?auto=format&fit=crop&w=800&q=80' },
                     { id: 6, title: 'Robotics Seminar', category: 'academics', img: 'https://images.unsplash.com/photo-1581092921461-eab62e97a780?auto=format&fit=crop&w=800&q=80' }
                 ],
                 filteredItems() {
                     return this.items.filter(item => this.activeFilter === 'all' || item.category === this.activeFilter);
                 },
                 init() {
                     this.$watch('activeFilter', value => {
                         this.spotlightIndex = 0;
                     });
                 }
             }">

        <div class="max-w-7xl mx-auto space-y-8">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest">Visual
                    Index</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
                <p class="text-xs text-slate-500">A high-contrast lens into the active learning nodes at Noble Academy.
                </p>
            </div>

            <!-- Filter Buttons -->
            <div class="flex flex-wrap justify-center gap-2 max-w-xl mx-auto">
                <button @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'bg-theme-primary text-white shadow-md' : 'bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                    All Nodes
                </button>
                <button @click="activeFilter = 'academics'"
                    :class="activeFilter === 'academics' ? 'bg-theme-primary text-white shadow-md' : 'bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                    Academics
                </button>
                <button @click="activeFilter = 'infrastructure'"
                    :class="activeFilter === 'infrastructure' ? 'bg-theme-primary text-white shadow-md' : 'bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                    Infrastructure
                </button>
                <button @click="activeFilter = 'sports'"
                    :class="activeFilter === 'sports' ? 'bg-theme-primary text-white shadow-md' : 'bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                    Sports
                </button>
                <button @click="activeFilter = 'co-curricular'"
                    :class="activeFilter === 'co-curricular' ? 'bg-theme-primary text-white shadow-md' : 'bg-slate-50 border border-slate-200 text-slate-500 hover:text-slate-900'"
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all duration-300">
                    Co-Curricular
                </button>
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

    <!-- EVENTS SECTION -->
    <section id="events" class="py-10 md:py-7 px-6 bg-[#fafbfd] border-b border-slate-100">
        <div class="max-w-7xl mx-auto space-y-8">
            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-extrabold text-theme-primary uppercase tracking-widest">Upcoming
                    Activities</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and
                    educational programs.</p>
            </div>

            <!-- Typographic Cards Grid (No Drawers) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1: Global Alumni Summit 2026 -->
                <div
                    class="relative bg-white border border-slate-200 rounded-[2rem] p-6 group hover:shadow-[0_20px_40px_rgba(15,23,42,0.06)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col justify-between overflow-hidden min-h-[310px]">
                    <!-- Top Hover Accent Line -->
                    <div
                        class="absolute top-0 left-0 w-full h-[3px] bg-indigo-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                    </div>

                    <!-- Top Row: Typographic Date & Category Tag -->
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <span
                                class="text-5xl font-black tracking-tighter text-slate-200 group-hover:text-indigo-600 transition-colors duration-500 select-none">25</span>
                            <div class="flex flex-col leading-none">
                                <span
                                    class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">JUN</span>
                                <span class="text-[7px] font-bold text-slate-400/80 mt-0.5">2026</span>
                            </div>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-indigo-50 text-indigo-600 border border-indigo-100/50">Networking</span>
                    </div>

                    <!-- Middle Content: Title & Description -->
                    <div class="my-5 space-y-1.5">
                        <h3
                            class="text-xs font-black text-slate-800 group-hover:text-indigo-600 transition-colors duration-300">
                            Global Alumni Summit 2026
                        </h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                            Connecting current students with notable alumni across top tech companies & research hubs
                            worldwide for mentorship.
                        </p>
                    </div>

                    <!-- Bottom Content: Metadata & Occupancy -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">📍</span> HALL D
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">🕒</span> 09:00 AM - 04:00 PM
                            </span>
                        </div>

                        <!-- Mini progress bar -->
                        <div class="space-y-1">
                            <div
                                class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                <span>Slot Occupancy</span>
                                <span class="text-indigo-500 font-extrabold">88% Filled</span>
                            </div>
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-indigo-500 w-[88%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Sustainability & Green Initiative -->
                <div
                    class="relative bg-white border border-slate-200 rounded-[2rem] p-6 group hover:shadow-[0_20px_40px_rgba(15,23,42,0.06)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col justify-between overflow-hidden min-h-[310px]">
                    <!-- Top Hover Accent Line -->
                    <div
                        class="absolute top-0 left-0 w-full h-[3px] bg-emerald-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                    </div>

                    <!-- Top Row: Typographic Date & Category Tag -->
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <span
                                class="text-5xl font-black tracking-tighter text-slate-200 group-hover:text-emerald-600 transition-colors duration-500 select-none">10</span>
                            <div class="flex flex-col leading-none">
                                <span
                                    class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">JUL</span>
                                <span class="text-[7px] font-bold text-slate-400/80 mt-0.5">2026</span>
                            </div>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100/50">Ecology</span>
                    </div>

                    <!-- Middle Content: Title & Description -->
                    <div class="my-5 space-y-1.5">
                        <h3
                            class="text-xs font-black text-slate-800 group-hover:text-emerald-600 transition-colors duration-300">
                            Sustainability & Green Initiative
                        </h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                            A student-led program launching campus solar grids and active waste-recycling workshops.
                        </p>
                    </div>

                    <!-- Bottom Content: Metadata & Occupancy -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">📍</span> ECO LAB
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">🕒</span> 10:00 AM - 02:00 PM
                            </span>
                        </div>

                        <!-- Mini progress bar -->
                        <div class="space-y-1">
                            <div
                                class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                <span>Slot Occupancy</span>
                                <span class="text-emerald-500 font-extrabold">65% Filled</span>
                            </div>
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-emerald-500 w-[65%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Art & Film Showcase (Aura 2026) -->
                <div
                    class="relative bg-white border border-slate-200 rounded-[2rem] p-6 group hover:shadow-[0_20px_40px_rgba(15,23,42,0.06)] hover:-translate-y-1.5 transition-all duration-500 flex flex-col justify-between overflow-hidden min-h-[310px]">
                    <!-- Top Hover Accent Line -->
                    <div
                        class="absolute top-0 left-0 w-full h-[3px] bg-sky-500 scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left">
                    </div>

                    <!-- Top Row: Typographic Date & Category Tag -->
                    <div class="flex justify-between items-start">
                        <div class="flex items-center gap-3">
                            <span
                                class="text-5xl font-black tracking-tighter text-slate-200 group-hover:text-sky-600 transition-colors duration-500 select-none">05</span>
                            <div class="flex flex-col leading-none">
                                <span
                                    class="text-[9px] font-extrabold uppercase tracking-widest text-slate-400">AUG</span>
                                <span class="text-[7px] font-bold text-slate-400/80 mt-0.5">2026</span>
                            </div>
                        </div>
                        <span
                            class="px-2 py-0.5 rounded text-[8px] font-black uppercase tracking-wider bg-sky-50 text-sky-600 border border-sky-100/50">Exhibition</span>
                    </div>

                    <!-- Middle Content: Title & Description -->
                    <div class="my-5 space-y-1.5">
                        <h3
                            class="text-xs font-black text-slate-800 group-hover:text-sky-600 transition-colors duration-300">
                            Art & Film Showcase (Aura 2026)
                        </h3>
                        <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                            Exhibition of student-produced documentaries, canvas installations, and classical acoustic
                            music.
                        </p>
                    </div>

                    <!-- Bottom Content: Metadata & Occupancy -->
                    <div class="pt-4 border-t border-slate-100 space-y-3">
                        <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">📍</span> THEATRE
                            </span>
                            <span class="flex items-center gap-1.5">
                                <span class="text-[10px]">🕒</span> 04:30 PM - 09:00 PM
                            </span>
                        </div>

                        <!-- Mini progress bar -->
                        <div class="space-y-1">
                            <div
                                class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                <span>Slot Occupancy</span>
                                <span class="text-sky-500 font-extrabold">92% Filled</span>
                            </div>
                            <div class="h-1 w-full bg-slate-100 rounded-full overflow-hidden">
                                <div class="h-full bg-sky-500 w-[92%] rounded-full"></div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="bg-slate-50 text-slate-500 py-12 px-6 border-t border-slate-100 relative">
        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-3 gap-8 mb-8 border-b border-slate-200/80 pb-8">
            <div class="space-y-4">
                <span class="text-2xl font-black text-slate-900 tracking-tight">NOBLE<span
                        class="text-theme-primary">ACADEMY</span></span>
                <p class="text-xs text-slate-500 leading-relaxed">Combining academic innovation with core values to
                    empower the leaders of tomorrow since 2012.</p>
            </div>

            <div>
                <h4 class="text-slate-900 font-extrabold text-xs uppercase tracking-wider mb-4">Quick Links</h4>
                <ul class="text-xs space-y-2 font-medium">
                    <li>
                        <a href="#home"
                            class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                            Home
                            <span
                                class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#about"
                            class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                            About Us
                            <span
                                class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#achievements"
                            class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                            Achievements
                            <span
                                class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                    <li>
                        <a href="#gallery"
                            class="relative py-1 hover:text-theme-primary transition-colors duration-300 group inline-block">
                            Gallery
                            <span
                                class="absolute bottom-0 left-0 w-0 h-[1.5px] bg-theme-primary transition-all duration-300 group-hover:w-full"></span>
                        </a>
                    </li>
                </ul>
            </div>

            <div>
                <h4 class="text-slate-900 font-extrabold text-xs uppercase tracking-wider mb-4">Contact Info</h4>
                <ul class="text-xs space-y-3 font-semibold">
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📧</span>
                        <a href="mailto:admissions@nobleacademy.edu"
                            class="hover:underline">admissions@nobleacademy.edu</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📞</span>
                        <a href="tel:+919876543210" class="hover:underline">+91 98765 43210</a>
                    </li>
                    <li class="flex items-center gap-2.5 text-slate-500 hover:text-slate-800 transition-colors">
                        <span
                            class="h-6 w-6 rounded-lg bg-white border border-slate-200 flex items-center justify-center text-[10px]">📍</span>
                        <a href="https://maps.google.com" target="_blank" class="hover:underline">Ahmedabad, Gujarat,
                            India</a>
                    </li>
                </ul>
            </div>
        </div>

        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row items-center justify-between text-xs text-slate-400 font-medium">
            <p>&copy; {{ date('Y') }} Noble Academy. All rights reserved.</p>

            <!-- Interactive Social Media Badges -->
            <div class="flex gap-2.5 mt-4 md:mt-0">
                <a href="#"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#1877F2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1877F2]/10">
                    <span class="font-bold text-[10px]">FB</span>
                </a>
                <a href="#"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#1DA1F2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#1DA1F2]/10">
                    <span class="font-bold text-[10px]">𝕏</span>
                </a>
                <a href="#"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#0A66C2] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#0A66C2]/10">
                    <span class="font-bold text-[10px]">IN</span>
                </a>
                <a href="#"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-gradient-to-tr hover:from-purple-600 hover:to-orange-500 hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-pink-500/10">
                    <span class="font-bold text-[10px]">IG</span>
                </a>
                <a href="#"
                    class="h-8 w-8 rounded-xl bg-white border border-slate-200 hover:bg-[#FF0000] hover:text-white text-slate-500 flex items-center justify-center transition-all duration-300 hover:rotate-6 shadow-sm hover:shadow-[#FF0000]/10">
                    <span class="font-bold text-[10px]">YT</span>
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
</body>

</html>