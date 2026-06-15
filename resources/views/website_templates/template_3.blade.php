<!DOCTYPE html>
<html lang="en" class="scroll-smooth scroll-pt-16">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    <header
        x-data="{ scrolled: false }"
        @scroll.window="scrolled = (window.pageYOffset > 25)"
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
                    z-10
                    ">
                        N
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
                    <h1 class="
                    text-lg
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
                    text-[7px]
                    uppercase
                    tracking-[0.35em]
                    font-black
                    text-slate-400
                    mt-1
                    ">
                        Excellence • Innovation • Future
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
                 }"
                 x-on:scroll-active-tab.window="activeTab = $event.detail"
                 @mouseleave="hoverTab = null">
                
                <!-- Sliding Pill Background Indicator -->
                <div class="absolute h-8 bg-white rounded-full shadow-sm border border-slate-200/60 transition-all duration-300 ease-[cubic-bezier(0.25,1,0.5,1)]"
                     :style="`width: ${$el.querySelector('[href=\'' + (hoverTab || activeTab) + '\']')?.offsetWidth || 0}px; left: ${$el.querySelector('[href=\'' + (hoverTab || activeTab) + '\']')?.offsetLeft || 0}px;`"
                     x-show="hoverTab || activeTab">
                </div>

                <a href="#home" @click="setTab('#home')" @mouseenter="hoverTab = '#home'"
                   class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                   :class="activeTab === '#home' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                    Home
                </a>
                <a href="#about" @click="setTab('#about')" @mouseenter="hoverTab = '#about'"
                   class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                   :class="activeTab === '#about' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                    About Us
                </a>
                <a href="#achievements" @click="setTab('#achievements')" @mouseenter="hoverTab = '#achievements'"
                   class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                   :class="activeTab === '#achievements' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                    Achievements
                </a>
                <a href="#gallery" @click="setTab('#gallery')" @mouseenter="hoverTab = '#gallery'"
                   class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                   :class="activeTab === '#gallery' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                    Gallery
                </a>
                <a href="#events" @click="setTab('#events')" @mouseenter="hoverTab = '#events'"
                   class="premium-nav relative z-10 px-4 py-1.5 text-xs font-extrabold font-outfit rounded-full transition-colors duration-250"
                   :class="activeTab === '#events' ? 'text-theme-primary' : 'text-slate-500 hover:text-slate-950'">
                    Events
                </a>
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

            <a class="mobile-nav-item" href="#home">
                Home
            </a>

            <a class="mobile-nav-item" href="#about">
                About Us
            </a>

            <a class="mobile-nav-item" href="#achievements">
                Achievements
            </a>

            <a class="mobile-nav-item" href="#gallery">
                Gallery
            </a>

            <a class="mobile-nav-item" href="#events">
                Events
            </a>

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

    <!-- HERO IMAGE SLIDER SECTION (EMERALD THEME) -->
    <section id="home"
        x-bind:class="'relative min-h-[460px] md:min-h-[560px] flex items-center overflow-hidden glass ' + (darkMode === 'dark' ? 'bg-slate-950 text-white' : 'bg-white text-slate-800')"
        @mousemove="handleParallax($event)" x-data="{ 
            darkMode: 'dark',
            activeSlide: 0, 
            progress: 0,
            parallaxX: 0,
            parallaxY: 0,
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
                    img: 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?auto=format&fit=crop&w=1600&q=80',
                    badge: 'Future Leaders',
                    title: 'Cultivating Global Leaders',
                    desc: 'Encouraging critical thinking, cross-cultural collaboration, and moral integrity to shape tomorrow\'s pioneers.'
                }
            ],
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

    <!-- Theme Toggle Button -->
    <div class="absolute top-4 left-4 z-20">
        <button @click="darkMode = (darkMode === 'dark' ? 'light' : 'dark')"
            class="px-3 py-1 bg-gray-200 dark:bg-gray-800 text-gray-800 dark:text-gray-200 rounded-md transition">
            @{{ darkMode === 'dark' ? 'Light Mode' : 'Dark Mode' }}
        </button>
    </div>

    <!-- ABOUT US SECTION -->
    <section id="about" class="relative py-16 md:py-7 bg-white border-b border-slate-100 overflow-hidden">

        <!-- Background -->
        <div class="absolute top-0 right-0 w-[400px] h-[400px] bg-slate-100 rounded-full blur-3xl opacity-50"></div>


        <div class="max-w-5xl mx-auto px-6 relative z-10">


            <!-- Header -->
            <div class="max-w-xl mb-8">

                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary">
                    About Our Academy
                </span>


                <h2 class="mt-3 text-3xl md:text-4xl font-black text-slate-900">
                    Our Core Pillars
                </h2>


                <p class="mt-3 text-xs text-slate-500 leading-relaxed">
                    Combining academic innovation with core values to empower the leaders of tomorrow.
                </p>

            </div>




            <!-- Pillars -->
            <div class="space-y-6">



                <!-- Vision -->
                <div class="pillar-row group">


                    <div class="pillar-number">
                        01
                    </div>


                    <div class="pillar-line"></div>


                    <div class="pillar-card">


                        <div class="flex items-start justify-between">


                            <div>

                                <div class="flex items-center gap-3">

                                    <span class="pillar-icon bg-emerald-50 text-theme-primary">
                                        🔭
                                    </span>


                                    <span class="pillar-label text-theme-primary">
                                        Vision
                                    </span>


                                </div>



                                <h3 class="pillar-title">
                                    Nurturing Leaders Since 2012
                                </h3>


                            </div>



                        </div>



                        <p class="pillar-desc">

                            To establish a global standard in education that balances academic rigor with creative
                            expression, cultivating visionary leaders of tomorrow.

                        </p>




                        <div class="pillar-footer">

                            <span>
                                Key Focus
                            </span>


                            <p class="text-theme-primary">
                                Holistic Growth • Integrated Technology • Creative Innovation
                            </p>

                        </div>


                    </div>


                </div>





                <!-- Mission -->
                <div class="pillar-row group">


                    <div class="pillar-number">
                        02
                    </div>


                    <div class="pillar-line"></div>


                    <div class="pillar-card">


                        <div>

                            <div class="flex items-center gap-3">

                                <span class="pillar-icon bg-teal-50 text-teal-600">
                                    🚀
                                </span>


                                <span class="pillar-label text-teal-500">
                                    Mission
                                </span>


                            </div>



                            <h3 class="pillar-title">
                                Fostering Excellence & Integrity
                            </h3>


                        </div>




                        <p class="pillar-desc">

                            To provide a stimulating learning environment where students excel academically, develop
                            strong moral values, and become responsible global citizens.

                        </p>




                        <div class="pillar-footer">

                            <span>
                                Key Focus
                            </span>


                            <p class="text-teal-600">
                                Qualified Mentors • Student curriculum • Civic Foundations
                            </p>


                        </div>


                    </div>


                </div>






                <!-- Values -->
                <div class="pillar-row group">


                    <div class="pillar-number">
                        03
                    </div>


                    <div class="pillar-line"></div>


                    <div class="pillar-card">


                        <div>


                            <div class="flex items-center gap-3">


                                <span class="pillar-icon bg-emerald-50 text-emerald-600">
                                    🛡️
                                </span>


                                <span class="pillar-label text-emerald-500">
                                    Values
                                </span>


                            </div>



                            <h3 class="pillar-title">
                                Our Core Pillars of Success
                            </h3>


                        </div>




                        <p class="pillar-desc">

                            We are anchored in key moral and academic tenets that guide every lesson, interaction,
                            and milestone achieved within our campus.

                        </p>




                        <div class="pillar-footer">


                            <span>
                                Key Focus
                            </span>


                            <p class="text-emerald-600">
                                Unyielding Integrity • Empathetic Collaboration • Inquisitive Mindsets
                            </p>


                        </div>


                    </div>


                </div>




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

    <!-- ACHIEVEMENTS SECTION -->
   <section id="achievements"
class="relative py-10 md:py-12 bg-[#fafbfd] border-b border-slate-100 overflow-hidden">


    <!-- Background Elements -->
    <div class="absolute -top-40 -right-40 w-[450px] h-[450px] bg-theme-primary/5 rounded-full blur-3xl"></div>

    <div class="absolute -bottom-40 -left-40 w-[450px] h-[450px] bg-emerald-100/40 rounded-full blur-3xl"></div>



    <div class="max-w-5xl mx-auto px-6 relative z-10">



        <!-- Header -->
        <div class="text-center max-w-xl mx-auto mb-8">


            <span class="
            text-[10px]
            font-black
            uppercase
            tracking-[0.35em]
            text-theme-primary
            ">
                Our Milestones
            </span>


            <h2 class="
            mt-3
            text-3xl
            md:text-4xl
            font-black
            text-slate-900
            ">
                Recent Achievements
            </h2>


            <p class="
            mt-3
            text-xs
            text-slate-500
            ">
                Proud moments demonstrating our dedication to academic and athletic excellence.
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

                                <span class="achievement-tag">
                                    Award
                                </span>


                                <h3>
                                    Best School Award 2025
                                </h3>

                            </div>


                        </div>



                        <p>

                            Named "State’s Most Innovational Education Center" for integrating interactive smart panels in
                            100% of classrooms.

                        </p>


                    </div>


                </div>


            </div>









            <!-- ITEM 2 -->

            <div class="
            achievement-item
            md:flex
            md:justify-end
            ">


                <div class="md:w-1/2 md:pl-8">



                    <div class="achievement-card">


                        <span class="achievement-number">
                            02
                        </span>



                        <div class="flex items-center gap-4">


                            <div class="
                            achievement-icon
                            bg-teal-50
                            text-teal-600
                            ">
                                🎓
                            </div>


                            <div>

                                <span class="achievement-tag text-teal-500">
                                    Academics
                                </span>


                                <h3>
                                    100% Board Exam Success
                                </h3>

                            </div>


                        </div>



                        <p>

                            For 8 consecutive years, our senior batch students have achieved a 100% pass rate with over 45%
                            scoring distinctions.

                        </p>


                    </div>


                </div>


            </div>









            <!-- ITEM 3 -->


            <div class="
            achievement-item
            md:flex
            md:justify-start
            ">


                <div class="md:w-1/2 md:pr-8">



                    <div class="achievement-card">


                        <span class="achievement-number">
                            03
                        </span>



                        <div class="flex items-center gap-4">


                            <div class="
                            achievement-icon
                            bg-emerald-50
                            text-emerald-600
                            ">
                                🏅
                            </div>


                            <div>

                                <span class="achievement-tag text-emerald-500">
                                    Sports
                                </span>


                                <h3>
                                    National Sports Champions
                                </h3>

                            </div>


                        </div>



                        <p>

                            Our athletic team brought home 4 Gold and 2 Silver medals from the All-India Inter-School Sports
                            Championship.

                        </p>


                    </div>


                </div>


            </div>




        </div>


    </div>





<style>


/* Card */

.achievement-card{
position:relative;
background:white;
border:1px solid #e2e8f0;
border-radius:20px;
padding:18px 22px;
overflow:hidden;
transition:.5s;
animation:fadeUp .8s ease both;
}

.achievement-card:hover{
transform: translateY(-4px) scale(1.01);
box-shadow: 0 15px 40px rgba(15,23,42,.06);
border-color:#cbd5e1;
}

/* Shine */

.achievement-card::before{
content:"";
position:absolute;
top:0;
left:-120%;
width:80%;
height:100%;
background: linear-gradient(120deg, transparent, rgba(255,255,255,.8), transparent);
transition:.8s;
}

.achievement-card:hover::before{
left:120%;
}

.achievement-number{
position:absolute;
right:16px;
top:6px;
font-size:48px;
font-weight:950;
color:#f1f5f9;
transition:.5s;
}

.achievement-card:hover .achievement-number{
color:#e2e8f0;
transform:scale(1.1);
}

.achievement-icon{
height:40px;
width:40px;
border-radius:10px;
display:flex;
align-items:center;
justify-content:center;
font-size:18px;
transition:.5s;
}

.achievement-card:hover .achievement-icon{
transform: rotate(12deg) scale(1.1);
}

.achievement-tag{
font-size:9px;
font-weight:900;
text-transform:uppercase;
letter-spacing:.25em;
color:var(--theme-primary);
}

.achievement-card h3{
margin-top:4px;
font-size:14px;
font-weight:950;
color:#0f172a;
}

.achievement-card p{
margin-top:10px;
font-size:11px;
line-height:1.6;
font-weight:500;
color:#64748b;
}

@keyframes fadeUp{
from{
opacity:0;
transform:translateY(20px);
}
to{
opacity:1;
transform:translateY(0);
}
}

@keyframes line{
from{
height:0;
}
to{
height:100%;
}
}

.animate-line{
animation:line 4s infinite alternate;
}

@media(max-width:768px){
.achievement-number{
font-size:36px;
}
}



</style>


</section>

    <!-- GALLERY SECTION -->
    <section id="gallery" class="py-10 md:py-12 bg-white border-b border-slate-100" x-data="{ 
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

        <div class="max-w-5xl mx-auto px-6 space-y-8">

            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary">Visual Tour</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Campus Gallery</h2>
                <p class="text-xs text-slate-500">A glimpse into the daily life, activities, and infrastructure of Noble Academy.</p>
            </div>

            <!-- Filter Buttons Navigation -->
            <div class="flex flex-wrap items-center justify-center gap-1.5 bg-slate-50 p-1.5 rounded-2xl max-w-fit mx-auto border border-slate-100">
                <button @click="activeFilter = 'all'"
                    :class="activeFilter === 'all' ? 'bg-white text-slate-900 shadow-sm border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-wider border border-transparent">
                    All
                </button>
                <button @click="activeFilter = 'academics'"
                    :class="activeFilter === 'academics' ? 'bg-white text-slate-900 shadow-sm border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-wider border border-transparent">
                    Academics
                </button>
                <button @click="activeFilter = 'infrastructure'"
                    :class="activeFilter === 'infrastructure' ? 'bg-white text-slate-900 shadow-sm border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-wider border border-transparent">
                    Infrastructure
                </button>
                <button @click="activeFilter = 'sports'"
                    :class="activeFilter === 'sports' ? 'bg-white text-slate-900 shadow-sm border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-wider border border-transparent">
                    Sports
                </button>
                <button @click="activeFilter = 'co-curricular'"
                    :class="activeFilter === 'co-curricular' ? 'bg-white text-slate-900 shadow-sm border-slate-200/50' : 'text-slate-500 hover:text-slate-800'"
                    class="px-4 py-1.5 rounded-xl text-[10px] font-black transition-all uppercase tracking-wider border border-transparent">
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
                        @click="lightboxImg = item.img; lightboxTitle = item.title; lightboxTag = item.tag; lightboxOpen = true"
                        class="group relative rounded-3xl overflow-hidden border border-slate-100 aspect-[4/3] bg-slate-50 cursor-pointer shadow-sm hover:shadow-lg transition-shadow duration-300">

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
                                    <span class="text-[7.5px] font-black tracking-widest text-theme-primary uppercase" x-text="item.tag"></span>
                                    <h4 class="text-white font-bold text-xs leading-tight" x-text="item.title"></h4>
                                </div>
                                <div class="h-7 w-7 bg-white/10 group-hover:bg-theme-primary rounded-xl flex items-center justify-center text-white transition-colors duration-300 flex-shrink-0 ml-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5 transform group-hover:translate-x-0.5 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                    </svg>
                                </div>
                            </div>
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
                        <span class="text-[10px] font-black text-theme-primary uppercase tracking-[0.2em]" x-text="lightboxTag"></span>
                        <h3 class="text-white text-base font-black" x-text="lightboxTitle"></h3>
                    </div>
                </div>
            </div>
        </template>
    </section>

    <!-- EVENTS SECTION -->
    <section id="events" class="py-10 md:py-12 bg-[#fafbfd] border-b border-slate-100">
        <div class="max-w-5xl mx-auto space-y-8">

            <div class="text-center space-y-2 max-w-xl mx-auto">
                <span class="text-[10px] font-black uppercase tracking-[0.35em] text-theme-primary">Upcoming Activities</span>
                <h2 class="text-2xl md:text-3xl font-black text-slate-900 tracking-tight">Events Calendar</h2>
                <p class="text-xs text-slate-500">Stay updated with our upcoming seminars, cultural functions, and educational programs.</p>
            </div>

            <!-- Typographic Cards Grid (No Drawers) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

                <!-- Card 1: Global Alumni Summit 2026 -->
                <div class="event-ticket group hover:border-theme-primary/30">
                    <!-- Ticket Notches -->
                    <div class="event-ticket-notch-l"></div>
                    <div class="event-ticket-notch-r"></div>

                    <!-- Top Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Date Stamp Box -->
                            <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-xl w-12 h-12 p-1 group-hover:bg-theme-primary group-hover:text-white group-hover:border-transparent transition-all duration-500">
                                <span class="text-lg font-black tracking-tighter leading-none">25</span>
                                <span class="text-[8px] font-black uppercase tracking-widest leading-none mt-1 opacity-70">JUN</span>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="text-[8px] font-bold text-slate-400">Year 2026</span>
                                <span class="text-[7px] font-black uppercase tracking-wider text-theme-primary bg-emerald-50 px-1.5 py-0.5 rounded-md mt-1">Networking</span>
                            </div>
                        </div>
                        <!-- Event Icon Badge -->
                        <div class="h-8 w-8 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-theme-primary/10 group-hover:text-theme-primary transition-colors duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Dashed Ticket Separator -->
                    <div class="my-4 border-t-2 border-dashed border-slate-100 relative z-10"></div>

                    <!-- Middle Content -->
                    <div class="flex-grow flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h3 class="text-xs font-black text-slate-800 group-hover:text-theme-primary transition-colors duration-300">
                                Global Alumni Summit 2026
                            </h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                Connecting current students with notable alumni across top tech companies & research hubs worldwide for mentorship.
                            </p>
                        </div>

                        <!-- Bottom Details -->
                        <div class="pt-4 flex flex-col gap-3">
                            <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                                <span class="flex items-center gap-1">📍 Main Auditorium</span>
                                <span class="flex items-center gap-1">🕒 10:00 AM</span>
                            </div>

                            <!-- Mini progress bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                    <span>Slot Occupancy</span>
                                    <span class="text-theme-primary font-extrabold">88% Filled</span>
                                </div>
                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-theme-primary rounded-full transition-all duration-1000 w-[88%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 2: Sustainability & Green Initiative -->
                <div class="event-ticket group hover:border-teal-500/30">
                    <!-- Ticket Notches -->
                    <div class="event-ticket-notch-l"></div>
                    <div class="event-ticket-notch-r"></div>

                    <!-- Top Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Date Stamp Box -->
                            <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-xl w-12 h-12 p-1 group-hover:bg-teal-500 group-hover:text-white group-hover:border-transparent transition-all duration-500">
                                <span class="text-lg font-black tracking-tighter leading-none">10</span>
                                <span class="text-[8px] font-black uppercase tracking-widest leading-none mt-1 opacity-70">JUL</span>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="text-[8px] font-bold text-slate-400">Year 2026</span>
                                <span class="text-[7px] font-black uppercase tracking-wider text-teal-600 bg-teal-50 px-1.5 py-0.5 rounded-md mt-1">Ecology</span>
                            </div>
                        </div>
                        <!-- Event Icon Badge -->
                        <div class="h-8 w-8 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-teal-50 group-hover:text-teal-600 transition-colors duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Dashed Ticket Separator -->
                    <div class="my-4 border-t-2 border-dashed border-slate-100 relative z-10"></div>

                    <!-- Middle Content -->
                    <div class="flex-grow flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h3 class="text-xs font-black text-slate-800 group-hover:text-teal-600 transition-colors duration-300">
                                Sustainability & Green Initiative
                            </h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                A campus-wide campaign featuring organic plantation drives, renewable energy workshop models, and zero-waste goals.
                            </p>
                        </div>

                        <!-- Bottom Details -->
                        <div class="pt-4 flex flex-col gap-3">
                            <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                                <span class="flex items-center gap-1">📍 Science Block</span>
                                <span class="flex items-center gap-1">🕒 09:30 AM</span>
                            </div>

                            <!-- Mini progress bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                    <span>Slot Occupancy</span>
                                    <span class="text-teal-600 font-extrabold">65% Filled</span>
                                </div>
                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-teal-500 rounded-full transition-all duration-1000 w-[65%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card 3: Art & Film Showcase (Aura 2026) -->
                <div class="event-ticket group hover:border-emerald-500/30">
                    <!-- Ticket Notches -->
                    <div class="event-ticket-notch-l"></div>
                    <div class="event-ticket-notch-r"></div>

                    <!-- Top Row -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <!-- Date Stamp Box -->
                            <div class="flex flex-col items-center justify-center bg-slate-50 border border-slate-100 rounded-xl w-12 h-12 p-1 group-hover:bg-emerald-600 group-hover:text-white group-hover:border-transparent transition-all duration-500">
                                <span class="text-lg font-black tracking-tighter leading-none">05</span>
                                <span class="text-[8px] font-black uppercase tracking-widest leading-none mt-1 opacity-70">AUG</span>
                            </div>
                            <div class="flex flex-col leading-tight">
                                <span class="text-[8px] font-bold text-slate-400">Year 2026</span>
                                <span class="text-[7px] font-black uppercase tracking-wider text-emerald-600 bg-emerald-50 px-1.5 py-0.5 rounded-md mt-1">Exhibition</span>
                            </div>
                        </div>
                        <!-- Event Icon Badge -->
                        <div class="h-8 w-8 bg-slate-50 border border-slate-100 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-colors duration-500">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>
                    </div>

                    <!-- Dashed Ticket Separator -->
                    <div class="my-4 border-t-2 border-dashed border-slate-100 relative z-10"></div>

                    <!-- Middle Content -->
                    <div class="flex-grow flex flex-col justify-between">
                        <div class="space-y-1.5">
                            <h3 class="text-xs font-black text-slate-800 group-hover:text-emerald-600 transition-colors duration-300">
                                Art & Film Showcase (Aura 2026)
                            </h3>
                            <p class="text-[10.5px] text-slate-500 leading-relaxed font-medium">
                                Exhibition of student-produced documentaries, canvas installations, and classical acoustic music.
                            </p>
                        </div>

                        <!-- Bottom Details -->
                        <div class="pt-4 flex flex-col gap-3">
                            <div class="flex justify-between items-center text-[9px] font-bold text-slate-400">
                                <span class="flex items-center gap-1">📍 Creative Arts Center</span>
                                <span class="flex items-center gap-1">🕒 02:00 PM</span>
                            </div>

                            <!-- Mini progress bar -->
                            <div class="space-y-1">
                                <div class="flex justify-between text-[7px] font-black uppercase tracking-wider text-slate-500">
                                    <span>Slot Occupancy</span>
                                    <span class="text-emerald-600 font-extrabold">92% Filled</span>
                                </div>
                                <div class="w-full h-1 bg-slate-100 rounded-full overflow-hidden">
                                    <div class="h-full bg-emerald-500 rounded-full transition-all duration-1000 w-[92%]"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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

            .event-ticket-notch-l, .event-ticket-notch-r {
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

    <!-- FOOTER SECTION -->
    <div class="glowing-footer-border"></div>
    <footer class="bg-slate-950 text-slate-400 py-10 border-t border-slate-900/60 relative overflow-hidden">
        <!-- Ambient background aura -->
        <div class="absolute -top-32 -left-32 w-80 h-80 bg-theme-primary/5 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-32 -right-32 w-80 h-80 bg-emerald-500/5 rounded-full blur-3xl pointer-events-none"></div>

        <div class="max-w-5xl mx-auto px-6 grid grid-cols-1 md:grid-cols-3 gap-8 relative z-10">
            <div class="space-y-3">
                <a href="#home" class="flex items-center gap-2 group">
                    <span class="text-lg font-black tracking-tight text-white transition-all duration-300 group-hover:text-theme-primary">
                        NOBLE<span class="text-theme-primary group-hover:text-white transition-colors">ACADEMY</span>
                    </span>
                </a>
                <p class="text-[11px] leading-relaxed text-slate-500">
                    Empowering students through innovative education, holistic value-building, and robust global mentorship programs.
                </p>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Quick Links</h4>
                <div class="flex flex-col space-y-1.5 text-[11px]">
                    <a href="#home" class="footer-link hover:text-white transition">Home</a>
                    <a href="#about" class="footer-link hover:text-white transition">About Us</a>
                    <a href="#achievements" class="footer-link hover:text-white transition">Achievements</a>
                    <a href="#gallery" class="footer-link hover:text-white transition">Gallery</a>
                    <a href="#events" class="footer-link hover:text-white transition">Events</a>
                </div>
            </div>

            <div class="space-y-3">
                <h4 class="text-[11px] font-black uppercase tracking-[0.25em] text-white">Contact Info</h4>
                <div class="space-y-2 text-[11px] text-slate-500">
                    <div class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📍</span>
                        <span class="leading-snug">Education Valley</span>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📞</span>
                        <span>+1 (555) 019-2834</span>
                    </div>
                    <div class="flex items-center gap-2.5 bg-slate-900/30 border border-slate-900/50 rounded-xl p-2 hover:border-slate-800 transition duration-300">
                        <span class="text-xs">📧</span>
                        <span class="truncate">info@nobleacademy.edu</span>
                    </div>
                </div>
            </div>

          
        </div>

        <div class="max-w-5xl mx-auto px-6 mt-8 pt-6 border-t border-slate-900/80 flex flex-col md:flex-row justify-between items-center gap-4 text-[10.5px] text-slate-600 font-medium relative z-10">
            <p>&copy; {{ date('Y') }} Noble Academy. All rights reserved.</p>
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
                0% { background-position: 0% 50%; }
                100% { background-position: 200% 50%; }
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
</body>

</html>