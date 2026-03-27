<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    <style>
        /* Selaras dengan layouts.auth: gray-100 / dark:gray-900 + kartu gray-800 */
        :root {
            --bg: #f3f4f6;
            --fg: #1f2937;
            --muted: #4b5563;
            --card: #ffffff;
            --border: rgba(229, 231, 235, 1);
            --accent: #2563eb;
            --accent-contrast: #ffffff;
            --img-bg: #e5e7eb;
        }

        @media (prefers-color-scheme: dark) {
            :root {
                --bg: #111827;
                --fg: #f3f4f6;
                --muted: #9ca3af;
                --card: #1f2937;
                --border: #374151;
                --accent: #2563eb;
                --accent-contrast: #ffffff;
                --img-bg: #111827;
            }
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: 'Instrument Sans', ui-sans-serif, system-ui, sans-serif;
            font-size: 13px;
            line-height: 1.5;
            background: var(--bg);
            color: var(--fg);
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 1.5rem;
        }

        @media (min-width: 1024px) {
            body {
                padding: 2rem;
                justify-content: center;
            }
        }

        .shell {
            width: 100%;
            max-width: 56rem;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 1.5rem;
            flex-wrap: wrap;
        }

        .brand {
            font-weight: 600;
            font-size: 0.95rem;
            letter-spacing: -0.02em;
        }

        nav {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        nav a {
            display: inline-block;
            padding: 0.35rem 1.1rem;
            border-radius: 0.125rem;
            font-size: 0.875rem;
            text-decoration: none;
            color: var(--fg);
            border: 1px solid transparent;
        }

        nav a:hover {
            border-color: var(--border);
            color: var(--fg);
        }

        nav a.nav-solid {
            background: var(--accent);
            color: var(--accent-contrast);
            border-color: var(--accent);
        }

        nav a.nav-solid:hover {
            filter: brightness(1.08);
        }

        .card {
            display: grid;
            grid-template-columns: 1fr;
            width: 100%;
            border-radius: 0.5rem;
            overflow: hidden;
            border: 1px solid var(--border);
            box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.08), 0 2px 4px -2px rgb(0 0 0 / 0.06);
        }

        @media (prefers-color-scheme: dark) {
            .card {
                box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.25), 0 2px 4px -2px rgb(0 0 0 / 0.2);
            }
        }

        @media (min-width: 1024px) {
            .card {
                grid-template-columns: minmax(0, 1fr) 438px;
            }
        }

        .card-text {
            order: 2;
            padding: 2.5rem 1.5rem 3rem;
            background: var(--card);
        }

        @media (min-width: 1024px) {
            .card-text {
                order: 0;
                padding: 5rem 2.5rem;
                border-radius: 0.5rem 0 0 0.5rem;
            }
        }

        .card-text h1 {
            margin: 0 0 0.5rem;
            font-size: 1.125rem;
            font-weight: 600;
        }

        .card-text p {
            margin: 0 0 1rem;
            color: var(--muted);
        }

        .card-text ul {
            margin: 0 0 1.25rem;
            padding-left: 1.15rem;
            color: var(--muted);
        }

        .card-text li {
            margin-bottom: 0.35rem;
        }

        .cta {
            display: flex;
            flex-wrap: wrap;
            gap: 0.75rem;
        }

        .cta a {
            display: inline-block;
            padding: 0.5rem 1.25rem;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            background: var(--accent);
            color: var(--accent-contrast);
            border: 1px solid var(--accent);
        }

        .cta a.secondary {
            background: transparent;
            color: var(--fg);
            border-color: var(--border);
        }

        .cta a.secondary:hover {
            background: rgba(255, 255, 255, 0.06);
        }

        @media (prefers-color-scheme: dark) {
            .cta a.secondary:hover {
                background: rgba(255, 255, 255, 0.08);
            }
        }

        .card-visual {
            order: 1;
            position: relative;
            min-height: 240px;
            background: var(--img-bg);
        }

        @media (min-width: 1024px) {
            .card-visual {
                order: 1;
                min-height: 100%;
            }
        }

        .card-visual img {
            width: 100%;
            height: 100%;
            min-height: 260px;
            object-fit: cover;
            display: block;
        }

        @media (min-width: 1024px) {
            .card-visual img {
                min-height: 100%;
            }
        }

        .spacer {
            height: 3.5rem;
            display: none;
        }

        @media (min-width: 1024px) {
            .spacer {
                display: block;
            }
        }
    </style>
</head>

<body>
    <div class="shell">
        <header>
            <span class="brand">{{ config('app.name') }}</span>
            @if (Route::has('login'))
                <nav>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="nav-solid">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}">Log in</a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="nav-solid">Register</a>
                        @endif
                    @endauth
                </nav>
            @endif
        </header>

        <main class="card">
            <div class="card-text">
                <h1>Sistem manajemen sekolah</h1>
                <p>
                    <strong>{{ config('app.name') }}</strong> membantu mengelola data sekolah secara terpusat: pengguna
                    dan hak akses, master data (tingkat, kelas, mata pelajaran, tahun ajaran), penjadwalan, serta
                    aktivitas akademik seperti jadwal mengajar, nilai, dan absensi.
                </p>
                <ul>
                    <li>Manajemen peran &amp; izin (RBAC)</li>
                    <li>Master data dan tahun ajaran untuk penjadwalan</li>
                    <li>Jadwal guru, nilai, absensi, dan rekap terkait</li>
                </ul>
                <div class="cta">
                    @auth
                        <a href="{{ url('/dashboard') }}">Buka dashboard</a>
                    @else
                        @if (Route::has('login'))
                            <a href="{{ route('login') }}">Masuk</a>
                        @endif
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="secondary">Daftar</a>
                        @endif
                    @endauth
                </div>
            </div>
            <div class="card-visual" aria-hidden="true">
                <img src="{{ asset('images/welcome-classroom.png') }}"
                    alt="Ruangan kelas dengan papan tulis dan meja belajar">
            </div>
        </main>
    </div>

    @if (Route::has('login'))
        <div class="spacer"></div>
    @endif
</body>

</html>
