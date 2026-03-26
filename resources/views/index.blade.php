<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Curio API Lessons</title>
    <style>
        body {
            background-color: #121212;
            color: #e0e0e0;
            font-family: "Fira Code", monospace;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }

        .container {
            text-align: center;
            max-width: 800px;
        }

        h1 {
            font-size: 2.5rem;
            color: #00ff99;
            text-shadow: 0 0 10px #00ff99, 0 0 20px #00ff99;
            margin-bottom: 30px;
            letter-spacing: 2px;
        }

        ul {
            list-style: none;
            padding: 0;
        }

        li {
            margin: 15px 0;
        }

        a:not(.subtle) {
            text-decoration: none;
            color: #00bcd4;
            font-size: 1.2rem;
            padding: 10px 15px;
            border: 1px solid #00bcd4;
            border-radius: 6px;
            transition: all 0.3s ease;
            display: inline-block;
        }

        a.subtle {
            text-decoration: none;
            color: #777;
            font-size: 0.9rem;
            padding: 5px 10px;
        }

        a:hover {
            background-color: #00bcd4;
            color: #121212;
            box-shadow: 0 0 15px #00bcd4, 0 0 30px #00bcd4;
            transform: scale(1.05);
        }

        footer {
            margin-top: 40px;
            font-size: 0.9rem;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container">
        <h1 id="title">⚡ Curio API Playground ⚡</h1>
        <ul>
            <li><a href="{{ url('/weer/NL/Amsterdam') }}"
                   target="_blank">🌦 Weather (NL/Amsterdam)</a></li>
            <li><a href="{{ url('/currencyconverter/EUR/USD/100') }}"
                   target="_blank">💱 Currency Converter (EUR → USD)</a></li>
            <li><a href="{{ url('/currencyconverter') }}"
                   target="_blank">💹 List Currencies</a></li>
            <li><a href="{{ url('/quote') }}"
                   target="_blank">💬 Random Quote</a></li>
        </ul>
        <footer>
            Open-source @ <a href="https://github.com/curio-team/native-api"
               class="subtle"
               target="_blank">curio-team/native-api</a>
        </footer>
    </div>

    <script>
        // Animate the title with a typing effect
        const title = document.getElementById("title");
        const text = title.innerHTML;
        let i = 0;
        title.innerHTML = "";

        function typeWriter() {
            if (i < text.length) {
                // Add characters one by one, preserving spaces as &nbsp;
                if (text.charAt(i) === " ") {
                    title.innerHTML += "&nbsp;";
                } else {
                    title.innerHTML += text.charAt(i);
                }
                i++;
                setTimeout(typeWriter, 80);
            }
        }
        typeWriter();
    </script>
</body>

</html>