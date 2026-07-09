<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Curio API Playground</title>
    <link rel="icon" href="{{ asset('assets/favicon.png') }}" type="image/png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@400;500;600;700&family=Google+Sans+Code&display=swap"
          rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-surface font-sans text-ink/80">
    <div class="mx-auto flex min-h-screen max-w-3xl flex-col items-center justify-center px-6 py-16 text-center">
        <img src="{{ asset('assets/logo.png') }}"
             alt="Curio"
             class="mb-6 h-auto w-24 max-w-full">

        <h1 class="text-3xl font-semibold text-ink">Curio API Playground</h1>
        <p class="mt-2 text-sm text-muted">A handful of small endpoints for testing and learning.</p>

        <ul class="mt-10 grid w-full gap-4 sm:grid-cols-2">
            <li>
                <a href="{{ url('/weer/NL/Amsterdam') }}"
                   target="_blank"
                   class="card-interactive no-underline!">
                    <span class="badge">Weather</span>
                    <p class="mt-1 font-medium text-ink">🌦 Weather (NL/Amsterdam)</p>
                </a>
            </li>
            <li>
                <a href="{{ url('/currencyconverter/EUR/USD/100') }}"
                   target="_blank"
                   class="card-interactive no-underline!">
                    <span class="badge">Currency</span>
                    <p class="mt-1 font-medium text-ink">💱 Convert EUR &rarr; USD</p>
                </a>
            </li>
            <li>
                <a href="{{ url('/currencyconverter') }}"
                   target="_blank"
                   class="card-interactive no-underline!">
                    <span class="badge">Currency</span>
                    <p class="mt-1 font-medium text-ink">💹 List Currencies</p>
                </a>
            </li>
            <li>
                <a href="{{ url('/quote') }}"
                   target="_blank"
                   class="card-interactive no-underline!">
                    <span class="badge">Quotes</span>
                    <p class="mt-1 font-medium text-ink">💬 Random Quote</p>
                </a>
            </li>
        </ul>

        <footer class="mt-12 text-sm text-muted">
            Open-source @
            <a href="https://github.com/curio-team/native-api"
               target="_blank"
               class="text-link">curio-team/native-api</a>
        </footer>
    </div>
</body>

</html>
