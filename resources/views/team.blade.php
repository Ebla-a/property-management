<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>Our Team — Real Estate Management</title>
    <link rel="icon" type="image/png" href="{{ asset('/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        /* --- Custom animations & utility tweaks --- */
        @keyframes floaty {
            0% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0.9;
            }

            50% {
                transform: translateY(-18px) translateX(6px) scale(1.02);
                opacity: 1;
            }

            100% {
                transform: translateY(0) translateX(0) scale(1);
                opacity: 0.95;
            }
        }

        @keyframes slideUpFade {
            0% {
                transform: translateY(28px) scale(.995);
                opacity: 0;
                filter: blur(4px);
            }

            100% {
                transform: translateY(0) scale(1);
                opacity: 1;
                filter: blur(0);
            }
        }

        @keyframes shimmer {
            0% {
                background-position: -200% 0;
            }

            100% {
                background-position: 200% 0;
            }
        }

        @keyframes pulse-soft {
            0% {
                transform: scale(1);
                opacity: 1;
            }

            50% {
                transform: scale(1.02);
                opacity: .98;
            }

            100% {
                transform: scale(1);
                opacity: 1;
            }
        }

        @keyframes avatar-tilt {
            0% {
                transform: translateY(0) rotate(0);
            }

            50% {
                transform: translateY(-6px) rotate(-1deg);
            }

            100% {
                transform: translateY(0) rotate(0);
            }
        }

        /* subtle horizontal drifting for background blobs (separate animations for variety) */
        @keyframes blob-move-1 {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .75;
            }

            25% {
                transform: translate3d(18px, -8px, 0) scale(1.03);
                opacity: .85;
            }

            50% {
                transform: translate3d(0, -16px, 0) scale(1.05);
                opacity: .9;
            }

            75% {
                transform: translate3d(-18px, -8px, 0) scale(1.03);
                opacity: .85;
            }

            100% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .75;
            }
        }

        @keyframes blob-move-2 {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .6;
            }

            30% {
                transform: translate3d(-22px, 12px, 0) scale(1.02);
                opacity: .7;
            }

            60% {
                transform: translate3d(18px, -6px, 0) scale(1.04);
                opacity: .78;
            }

            100% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .6;
            }
        }

        @keyframes blob-move-3 {
            0% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .65;
            }

            20% {
                transform: translate3d(10px, 18px, 0) scale(1.01);
                opacity: .72;
            }

            50% {
                transform: translate3d(-14px, -14px, 0) scale(1.03);
                opacity: .8;
            }

            80% {
                transform: translate3d(12px, 6px, 0) scale(1.02);
                opacity: .74;
            }

            100% {
                transform: translate3d(0, 0, 0) scale(1);
                opacity: .65;
            }
        }

        .floating-blob {
            filter: blur(30px);
            opacity: .7;
        }

        /* assign distinct movement animations */
        .floating-blob-1 {
            animation: blob-move-1 10s ease-in-out infinite;
        }

        .floating-blob-2 {
            animation: blob-move-2 13s ease-in-out infinite;
            animation-delay: 0.8s;
        }

        .floating-blob-3 {
            animation: blob-move-3 11.5s ease-in-out infinite;
            animation-delay: 1.6s;
        }

        .card-appear {
            animation: slideUpFade .7s cubic-bezier(.2, .9, .2, 1) both;
            will-change: transform, opacity;
        }

        .shimmer {
            background: linear-gradient(90deg, rgba(255, 255, 255, 0.02), rgba(255, 255, 255, 0.18), rgba(255, 255, 255, 0.02));
            background-size: 200% 100%;
            animation: shimmer 2.6s linear infinite;
            -webkit-background-clip: text;
            background-clip: text;
            color: transparent;
            -webkit-text-fill-color: transparent;
        }

        /* avatar gentle motion */
        .avatar-animate {
            animation: avatar-tilt 6s ease-in-out infinite;
            transform-origin: center;
        }

        /* subtle continuous pulse on CTA and badges */
        .pulse-soft {
            animation: pulse-soft 6s ease-in-out infinite;
        }

        .tilt {
            transition: transform .45s cubic-bezier(.2, .9, .2, 1), box-shadow .45s ease;
            transform-origin: center;
        }

        .tilt:hover {
            transform: perspective(1000px) rotateX(3deg) rotateY(-6deg) translateY(-8px) scale(1.03);
            box-shadow: 0 24px 60px rgba(79, 70, 229, 0.12);
        }

        .focus-ring:focus-visible {
            outline: 3px solid rgba(99, 102, 241, .22);
            outline-offset: 4px;
        }

        /* role badge (kept white, subtle) */
        .role-badge {
            background: #ffffff;
            color: #3730a3;
            /* indigo-700 */
            border: 1px solid rgba(79, 70, 229, 0.08);
            box-shadow: 0 6px 20px rgba(99, 102, 241, 0.06);
            transition: transform .32s cubic-bezier(.2, .9, .2, 1), box-shadow .32s;
        }

        .role-badge:hover {
            transform: translateY(-4px);
            box-shadow: 0 20px 50px rgba(99, 102, 241, 0.10);
        }

        @media (prefers-reduced-motion: reduce) {

            .floating-blob-1,
            .floating-blob-2,
            .floating-blob-3,
            .card-appear,
            .shimmer,
            .avatar-animate,
            .pulse-soft {
                animation: none !important;
            }
        }
    </style>
</head>

<body class="antialiased bg-gradient-to-b from-indigo-50 via-white to-indigo-50 min-h-screen text-gray-800">

    <!-- decorative background blobs (animated movement) -->
    <div aria-hidden="true" class="pointer-events-none fixed inset-0 -z-10">
        <div class="absolute -left-28 -top-20 w-72 h-72 rounded-full bg-indigo-400 opacity-30 floating-blob floating-blob-1"
            style="background: radial-gradient(circle at 20% 30%, #6366f1, transparent 40%);"></div>

        <div class="absolute right-0 top-40 w-96 h-96 rounded-full bg-indigo-300 opacity-20 floating-blob floating-blob-2"
            style="background: radial-gradient(circle at 70% 40%, #4f46e5, transparent 35%);"></div>

        <div class="absolute -right-20 bottom-10 w-80 h-80 rounded-full bg-indigo-200 opacity-30 floating-blob floating-blob-3"
            style="background: radial-gradient(circle at 40% 60%, #7c3aed, transparent 45%);"></div>
    </div>

    <header class="py-8">
        <div class="container mx-auto px-6 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <!-- replaced logo with /logo.png -->
                <img src="{{ asset('logo.png') }}" alt="Logo" class="w-12 h-12 rounded-lg object-cover shadow-lg" />

                <div>
                    <h1 class="text-2xl font-extrabold tracking-tight text-indigo-900">Real Estate Management</h1>
                    <p class="text-sm text-indigo-600/80">Our team — crafted with care</p>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <!-- GitHub repo link (icon) -->
                <a href="https://github.com/Ebla-a/property-management.git" target="_blank" rel="noopener noreferrer"
                    class="inline-flex items-center gap-2 px-1 py-1 rounded-full border-indigo-300 shadow-sm hover:shadow-md focus-ring"
                    title="View repository on GitHub">
                    <!-- GitHub SVG -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8 text-indigo-700" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
                        <path d="M12 .5C5.73.5.75 5.48.75 11.75c0 4.92 3.17 9.1 7.57 10.57.55.1.75-.24.75-.53 0-.26-.01-1.12-.02-2.03-3.08.67-3.73-1.49-3.73-1.49-.5-1.27-1.22-1.61-1.22-1.61-.99-.68.08-.67.08-.67 1.1.08 1.68 1.13 1.68 1.13.97 1.66 2.55 1.18 3.17.9.1-.7.38-1.18.69-1.45-2.46-.28-5.05-1.23-5.05-5.47 0-1.21.43-2.2 1.13-2.98-.11-.28-.49-1.4.11-2.93 0 0 .92-.29 3.01 1.13.87-.24 1.8-.36 2.73-.36.93 0 1.86.12 2.73.36 2.09-1.42 3.01-1.13 3.01-1.13.6 1.53.22 2.65.11 2.93.7.78 1.13 1.77 1.13 2.98 0 4.25-2.6 5.19-5.08 5.47.39.34.73 1.02.73 2.06 0 1.49-.01 2.69-.01 3.06 0 .29.2.64.76.53 4.4-1.48 7.56-5.66 7.56-10.57C23.25 5.48 18.27.5 12 .5z" />
                    </svg>
                </a>
                <a href="{{ url('/') }}" class="px-4 py-2 rounded-full bg-white/70 hover:bg-white shadow-md text-indigo-700 font-semibold focus-ring">Home</a>

                <a href="{{ route('login') }}" class="px-4 py-2 rounded-full bg-indigo-600 text-white font-semibold shadow-lg hover:scale-[1.02] focus-ring pulse-soft">Login</a>
            </div>
        </div>
    </header>

    <main class="container mx-auto px-6 pb-20">
        <section class="text-center max-w-3xl mx-auto">
            <h2 class="text-4xl sm:text-5xl font-extrabold text-indigo-900 mb-4 shimmer">Meet the Dream Team</h2>
            <p class="text-indigo-700/80 mb-8 leading-relaxed">Talented crew combining design and backend craftsmanship focused on shipping reliable real-estate experiences.</p>

            <div class="mt-6 inline-flex gap-3 items-center">
                @php
                // members array with explicit emails and github links (no default fallback)
                $members = $team ?? [
                ['name' => 'Ebla Ali', 'role' => 'Team Leader & Backend Developer', 'initials' => 'EA', 'email' => 'eblaali520@gmail.com', 'github' => 'https://github.com/Ebla-a'],
                ['name' => 'Hasan Dayoub', 'role' => 'Assistant', 'initials' => 'HD'],
                ['name' => 'Ameen Fadel', 'role' => 'Backend Developer & Designer', 'initials' => 'AF'],
                ['name' => 'Enas Alhamwi', 'role' => 'Backend Developer', 'initials' => 'EE'],
                ['name' => 'Abdullah Shuraitah', 'role' => 'Backend Developer', 'initials' => 'AS'],
                ['name' => 'Wajd Heshme', 'role' => 'Frontend Developer & Software Engineer', 'initials' => 'WH', 'image' => 'wajd.webp', 'email' => 'wajdtitos@gmail.com', 'github' => 'https://github.com/WajdMHeshme'],
                ];
                @endphp

                <div class="px-4 py-2 bg-indigo-50 border border-indigo-100 rounded-full text-indigo-700 font-medium shadow-sm">
                    {{ count($members) }} Members
                </div>
            </div>
        </section>

        <!-- Team grid -->
        <section id="team-grid" class="mt-12 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            @php use Illuminate\Support\Str; @endphp

            @foreach($members as $i => $m)
            <article
                class="card-appear tilt bg-white rounded-2xl p-6 shadow-xl border border-transparent hover:shadow-2xl hover:border-indigo-100 transform-gpu will-change-transform flex flex-col"
                style="animation-delay: {{ 120 * $i }}ms;"
                tabindex="0"
                aria-labelledby="member-{{ $i }}-name">
                <div class="flex items-start gap-4">
                    <!-- avatar -->
                    <div class="flex-shrink-0">
                        @php $github = $m['github'] ?? null; @endphp

                        @if(!empty($github))
                        {{-- wrap avatar (image or placeholder) with link if github exists --}}
                        <a href="{{ $github }}" target="_blank" rel="noopener noreferrer" aria-label="Open {{ $m['name'] }}'s GitHub" class="inline-block rounded-full focus-ring">
                            @if(!empty($m['image']))
                            <img src="{{ asset($m['image']) }}" alt="{{ $m['name'] }}" class="w-20 h-20 rounded-full object-cover shadow-md avatar-animate">
                            @else
                            <div class="w-20 h-20 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md avatar-animate"
                                style="background: linear-gradient(135deg, rgba(79,70,229,.95), rgba(99,102,241,.95));">
                                <span>{{ $m['initials'] ?? strtoupper(substr($m['name'],0,1)) }}</span>
                            </div>
                            @endif
                        </a>
                        @else
                        {{-- no github: just render image or placeholder --}}
                        @if(!empty($m['image']))
                        <img src="{{ asset($m['image']) }}" alt="{{ $m['name'] }}" class="w-20 h-20 rounded-full object-cover shadow-md avatar-animate">
                        @else
                        <div class="w-20 h-20 rounded-full flex items-center justify-center text-white font-bold text-xl shadow-md avatar-animate"
                            style="background: linear-gradient(135deg, rgba(79,70,229,.95), rgba(99,102,241,.95));">
                            <span>{{ $m['initials'] ?? strtoupper(substr($m['name'],0,1)) }}</span>
                        </div>
                        @endif
                        @endif
                    </div>

                    <!-- main info -->
                    <div class="flex-1">
                        <h3 id="member-{{ $i }}-name" class="text-lg font-bold text-indigo-900">{{ $m['name'] }}</h3>
                        <p class="text-indigo-600/80 mt-1">{{ $m['role'] }}</p>

                        <!-- email only (show only when provided; no default emails) -->
                        <div class="mt-4">
                            <div class="inline-flex items-center gap-2 text-sm text-indigo-700/90 bg-indigo-50 px-3 py-1 rounded-full border border-indigo-100">
                                <!-- mail icon -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 8.5v7A2.5 2.5 0 0 0 5.5 18h13A2.5 2.5 0 0 0 21 15.5v-7A2.5 2.5 0 0 0 18.5 6h-13A2.5 2.5 0 0 0 3 8.5z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.5L12 13 3 8.5" />
                                </svg>

                                @if(!empty($m['email']))
                                <a href="mailto:{{ $m['email'] }}" class="hover:underline">{{ $m['email'] }}</a>
                                @else
                                <span class="text-sm text-indigo-400/80">—</span>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- removed Experience section as requested -->
            </article>
            @endforeach
        </section>

        <!-- CTA / footer -->
        <section class="mt-16 text-center">
            <p class="text-indigo-700/80 mb-6">Want to collaborate? <a href="mailto:team@example.com" class="text-indigo-600 font-semibold underline pulse-soft">Drop us a line</a></p>
        </section>
    </main>

    <footer class="py-8 border-t border-indigo-100">
        <div class="container mx-auto px-6 text-center text-sm text-indigo-600/70">
            © {{ date('Y') }} Real Estate Management
        </div>
    </footer>

    <!-- small script: subtle tilt on pointer move for cards (enhanced effect) -->
    <script>
        if (window.matchMedia('(pointer: fine)').matches) {
            document.querySelectorAll('.tilt').forEach(card => {
                card.addEventListener('pointermove', e => {
                    const rect = card.getBoundingClientRect();
                    const px = (e.clientX - rect.left) / rect.width;
                    const py = (e.clientY - rect.top) / rect.height;
                    const rotY = (px - 0.5) * 12; // rotateY
                    const rotX = (py - 0.5) * -7; // rotateX
                    card.style.transform = `perspective(1200px) rotateX(${rotX}deg) rotateY(${rotY}deg) translateY(-8px) scale(1.03)`;
                });
                card.addEventListener('pointerleave', () => {
                    card.style.transform = '';
                });
            });
        }
    </script>
</body>

</html>
