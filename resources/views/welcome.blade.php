<!DOCTYPE html>
<html lang="en" dir="ltr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <title>Real Estate Management</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* Prevent horizontal scroll caused by transforms/images */
        html, body {
            overflow-x: hidden;
        }

        /* --- Animations --- */
        @keyframes zoom {
            0% { transform: scale(1); }
            50% { transform: scale(1.06); }
            100% { transform: scale(1); }
        }
        @keyframes fadeInUp {
            0% { opacity: 0; transform: translateY(18px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        @keyframes gradientMove {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* --- Hero specifics --- */
        .hero-img {
            will-change: transform;
            transform-origin: center;
            animation: zoom 20s ease-in-out infinite;
            display: block;
            max-width: none;    /* prevent image from shrinking/resizing leading to gaps */
            min-width: 100%;
            min-height: 100%;
            object-fit: cover;
            backface-visibility: hidden;
            -webkit-backface-visibility: hidden;
            -webkit-transform-style: preserve-3d;
            /* keep element contained (overflow-x hidden on body handles extra edges) */
        }

        .hero-content {
            opacity: 0;
            transform: translateY(18px);
            animation: fadeInUp 900ms ease-out 300ms forwards;
        }

        .hero-overlay-gradient {
            background: linear-gradient(120deg, rgba(99,102,241,0.12), rgba(59,130,246,0.08) 40%, rgba(139,92,246,0.06));
            background-size: 200% 200%;
            animation: gradientMove 12s ease infinite;
            mix-blend-mode: overlay;
            pointer-events: none;
        }

        /* Button micro-interactions */
        .btn-cta {
            transition: transform 220ms cubic-bezier(.2,.9,.2,1), box-shadow 220ms;
        }
        .btn-cta:focus {
            outline: none;
            box-shadow: 0 6px 30px rgba(99,102,241,0.18);
        }
        .btn-cta:hover {
            transform: translateY(-4px) scale(1.02);
            box-shadow: 0 12px 40px rgba(59,130,246,0.18);
        }

        /* small utility for better text shadows on big headings */
        .text-glow {
            text-shadow: 0 6px 20px rgba(0,0,0,0.45);
        }

        /* ensure hero content stays readable on very small screens */
        @media (max-width: 420px) {
            .hero-title { font-size: 1.6rem; }
            .hero-sub { font-size: 0.95rem; }
        }
    </style>
</head>

<body class="bg-gray-50 font-sans antialiased">

    {{-- Loader --}}
    @include('dashboard.partials.loader')

    <!-- Hero Section -->
    <section id="hero" class="relative w-full h-screen flex items-center justify-center overflow-hidden">
        <!-- Background Image (fills screen) -->
        <img
            id="hero-img"
            src="{{ asset('Estate.webp') }}"
            alt="Real estate background"
            class="absolute inset-0 w-full h-full object-cover z-0 hero-img"
            data-parallax
            />

        <!-- Animated subtle gradient overlay to spice it up -->
        <div class="absolute inset-0 hero-overlay-gradient z-5"></div>

        <!-- Dark overlay for contrast -->
        <div class="absolute inset-0 bg-black bg-opacity-35 z-10"></div>

        <!-- Content -->
        <div class="relative z-20 text-center px-6 max-w-4xl hero-content">
            <h1 class="hero-title text-4xl sm:text-5xl lg:text-6xl font-extrabold text-white mb-4 text-glow">
                Manage Your Properties Easily
            </h1>
            <p class="hero-sub text-lg sm:text-xl text-white mb-8 drop-shadow-md">
                Track properties, bookings, and clients all in one place.
            </p>

            <!-- Buttons: stacked on small screens, inline on sm+ -->
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                <!-- Primary: Login -->
                <a href="{{ route('login') }}"
                   class="inline-flex items-center justify-center bg-indigo-600 text-white font-bold px-8 py-4 rounded-full text-lg shadow-xl btn-cta focus:outline-none">
                    Login Now
                </a>

                <!-- Secondary: Our Team (button-like) -->
<a href="{{ route('team.index') }}" target="_blank" rel="noopener"
   class="group inline-flex items-center justify-center gap-3
          border-[3.5px] border-white/40

          bg-white/10 backdrop-blur
          text-white font-bold
          px-8 py-4 rounded-full text-lg
          shadow-lg
          btn-cta
          focus:outline-none focus:ring-2 focus:ring-white/70">

    Our Team

    <svg xmlns="http://www.w3.org/2000/svg"
         class="w-5 h-5 text-white transition-transform duration-300 group-hover:translate-x-1"
         fill="none" viewBox="0 0 24 24"
         stroke="currentColor" stroke-width="2"
         stroke-linecap="round" stroke-linejoin="round">
        <path d="M13 7l5 5m0 0l-5 5m5-5H6"/>
    </svg>
</a>

            </div>

        </div>


        <!-- Decorative floating cards (subtle, accessible: hidden for very small screens) -->
        <div aria-hidden="true" class="hidden lg:block absolute -left-20 -bottom-10 transform rotate-6 opacity-60 z-0">
            <div class="w-56 h-36 rounded-2xl bg-white bg-opacity-6 backdrop-blur p-4 shadow-2xl"></div>
        </div>
    </section>


    {{-- Hide Loader after page load + Parallax effect --}}
    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('loader-root');
            if (loader) {
                // keep loader visible a bit longer for nicer UX (2.2s), then fade out smoothly
                const extraVisibleMs = 2200; // زيادتك للودر شوي — تقدر تغير الرقم لو تحب زيادة/نقص
                setTimeout(() => {
                    loader.style.transition = 'opacity 400ms ease';
                    loader.style.opacity = '0';
                    setTimeout(() => {
                        loader.style.display = 'none';
                    }, 450);
                }, extraVisibleMs);
            }

            // Fade-in of hero content is handled by CSS animation (animate on load)
        });

        // Parallax effect: translateY the hero image slightly on scroll
        (function () {
            const img = document.getElementById('hero-img');
            if (!img) return;

            // For performance, throttle using requestAnimationFrame
            let latestScroll = 0;
            let ticking = false;

            function onScroll() {
                latestScroll = window.scrollY || window.pageYOffset;
                requestTick();
            }

            function requestTick() {
                if (!ticking) {
                    requestAnimationFrame(update);
                }
                ticking = true;
            }

            function update() {
                // small parallax only on larger screens
                const maxTranslate = 40; // px
                const screenWidth = window.innerWidth || document.documentElement.clientWidth;
                let factor = screenWidth > 1024 ? 0.18 : (screenWidth > 640 ? 0.08 : 0); // reduce on mobile
                const translate = Math.min(maxTranslate, latestScroll * factor);
                // Apply slight upward movement to image to create depth
                // Preserve existing animation scale by only setting translate + scale
                img.style.transform = `translateY(${translate * -1}px) scale(1.02)`;
                ticking = false;
            }

            window.addEventListener('scroll', onScroll, { passive: true });

            // Ensure initial position is set
            update();

            // Optional: subtle parallax tilt on pointer move (desktop only)
            function onPointerMove(e) {
                if (window.innerWidth < 1024) return;
                const rect = img.getBoundingClientRect();
                const cx = rect.left + rect.width / 2;
                const cy = rect.top + rect.height / 2;
                const dx = (e.clientX - cx) / rect.width;
                const dy = (e.clientY - cy) / rect.height;
                const rotateY = dx * 3; // degrees
                const rotateX = dy * -3; // degrees

                // Append rotation while keeping translate/scale already applied in update()
                // Read current transform to preserve translate/scale if present
                const baseTransform = img.style.transform.replace(/ rotateX\([^)]+\)| rotateY\([^)]+\)/g, '');
                img.style.transform = `${baseTransform} rotateX(${rotateX}deg) rotateY(${rotateY}deg)`;
            }
            function onPointerEnter() { /* noop */ }
            function onPointerLeave() {
                // remove any rotateX/rotateY while preserving translate/scale
                img.style.transform = img.style.transform.replace(/ rotateX\([^)]+\)| rotateY\([^)]+\)/g, '');
            }

            if (window.matchMedia('(pointer: fine)').matches) {
                img.addEventListener('pointermove', onPointerMove);
                img.addEventListener('pointerenter', onPointerEnter);
                img.addEventListener('pointerleave', onPointerLeave);
            }
        })();
    </script>

</body>
</html>
