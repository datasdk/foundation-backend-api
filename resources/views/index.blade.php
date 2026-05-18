@php
$logoPath = resource_path('images/datas_square.png');
$logoData = file_exists($logoPath) ? base64_encode(file_get_contents($logoPath)) : null;
@endphp

<!doctype html>

<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Datas Developer</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #07130f;
            --panel: rgba(255, 255, 255, 0.075);
            --panel-border: rgba(255, 255, 255, 0.14);
            --text: #f4fff9;
            --muted: #a8bbb2;
            --green: #00dc82;
            --cyan: #38d7ff;
            --violet: #9b8cff;
        }

```
    * {
        box-sizing: border-box;
    }

    body {
        min-height: 100vh;
        margin: 0;
        font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif;
        color: var(--text);
        background:
            radial-gradient(circle at 18% 16%, rgba(0, 220, 130, 0.26), transparent 32rem),
            radial-gradient(circle at 84% 18%, rgba(56, 215, 255, 0.14), transparent 28rem),
            radial-gradient(circle at 64% 86%, rgba(155, 140, 255, 0.14), transparent 30rem),
            linear-gradient(135deg, #06100d 0%, #0c1416 48%, #080b14 100%);
        overflow-x: hidden;
    }

    body::before {
        position: fixed;
        inset: 0;
        pointer-events: none;
        content: "";
        background-image:
            linear-gradient(rgba(255, 255, 255, 0.045) 1px, transparent 1px),
            linear-gradient(90deg, rgba(255, 255, 255, 0.045) 1px, transparent 1px);
        background-size: 48px 48px;
        mask-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.72), transparent 78%);
    }

    .shell {
        position: relative;
        z-index: 1;
        display: flex;
        min-height: 100vh;
        flex-direction: column;
        padding: 28px clamp(20px, 5vw, 72px);
    }

    .nav {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 20px;
    }

    .brand {
        display: inline-flex;
        align-items: center;
        gap: 12px;
        color: var(--text);
        font-size: 15px;
        font-weight: 700;
        text-decoration: none;
    }

    .brand img {
        width: 38px;
        height: 38px;
        border-radius: 8px;
        box-shadow: 0 0 34px rgba(0, 220, 130, 0.22);
    }

    .nav a:last-child {
        color: var(--muted);
        font-size: 14px;
        text-decoration: none;
    }

    main {
        display: grid;
        flex: 1;
        grid-template-columns: minmax(0, 1.03fr) minmax(280px, 0.72fr);
        align-items: center;
        gap: clamp(36px, 7vw, 96px);
        width: min(1160px, 100%);
        margin: 0 auto;
        padding: 64px 0 38px;
    }

    .eyebrow {
        display: inline-flex;
        width: fit-content;
        align-items: center;
        gap: 9px;
        margin-bottom: 22px;
        padding: 8px 12px;
        border: 1px solid rgba(0, 220, 130, 0.28);
        border-radius: 999px;
        background: rgba(0, 220, 130, 0.08);
        color: #bbf7d0;
        font-size: 13px;
        font-weight: 650;
    }

    .pulse {
        width: 8px;
        height: 8px;
        border-radius: 999px;
        background: var(--green);
        box-shadow: 0 0 18px var(--green);
    }

    h1 {
        max-width: 820px;
        margin: 0;
        font-size: clamp(46px, 8vw, 92px);
        line-height: 0.96;
        letter-spacing: 0;
    }

    .gradient-text {
        display: block;
        color: transparent;
        background: linear-gradient(90deg, var(--green), var(--cyan) 58%, #ffffff);
        -webkit-background-clip: text;
        background-clip: text;
    }

    .intro {
        max-width: 620px;
        margin: 24px 0 0;
        color: var(--muted);
        font-size: clamp(18px, 2vw, 21px);
        line-height: 1.65;
    }

    .actions {
        display: flex;
        flex-wrap: wrap;
        gap: 14px;
        margin-top: 34px;
    }

    .button {
        display: inline-flex;
        min-height: 48px;
        align-items: center;
        justify-content: center;
        border-radius: 8px;
        padding: 0 18px;
        font-size: 15px;
        font-weight: 750;
        text-decoration: none;
        transition: transform 160ms ease, border-color 160ms ease, background 160ms ease;
    }

    .button:hover {
        transform: translateY(-2px);
    }

    .button-primary {
        color: #001b10;
        background: var(--green);
        box-shadow: 0 18px 44px rgba(0, 220, 130, 0.25);
    }

    .button-secondary {
        border: 1px solid var(--panel-border);
        color: var(--text);
        background: rgba(255, 255, 255, 0.055);
        backdrop-filter: blur(18px);
    }

    .developer-panel {
        position: relative;
        overflow: hidden;
        border: 1px solid var(--panel-border);
        border-radius: 8px;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.11), rgba(255, 255, 255, 0.045));
        box-shadow: 0 24px 80px rgba(0, 0, 0, 0.32);
        backdrop-filter: blur(22px);
    }

    .developer-panel::before {
        position: absolute;
        inset: 0;
        content: "";
        background: linear-gradient(135deg, rgba(0, 220, 130, 0.16), transparent 38%, rgba(56, 215, 255, 0.1));
        pointer-events: none;
    }

    .panel-top {
        position: relative;
        display: flex;
        align-items: center;
        gap: 8px;
        height: 44px;
        padding: 0 15px;
        border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .dot {
        width: 10px;
        height: 10px;
        border-radius: 999px;
        background: rgba(255, 255, 255, 0.32);
    }

    .panel-body {
        position: relative;
        padding: clamp(22px, 4vw, 34px);
    }

    .logo-mark {
        width: 96px;
        height: 96px;
        border-radius: 8px;
        margin-bottom: 28px;
        box-shadow: 0 0 58px rgba(0, 220, 130, 0.24);
    }

    code {
        display: block;
        overflow-wrap: anywhere;
        color: #dfffee;
        font-family: "SFMono-Regular", Consolas, "Liberation Mono", monospace;
        font-size: 14px;
        line-height: 1.9;
    }

    code span {
        color: var(--green);
    }

    .doc-note {
        margin-top: 24px;
        color: #d7fff0;
        font-size: 15px;
        line-height: 1.55;
    }

    footer {
        color: rgba(244, 255, 249, 0.52);
        font-size: 13px;
    }

    @media (max-width: 820px) {
        .shell {
            padding: 22px;
        }

        main {
            grid-template-columns: 1fr;
            padding: 58px 0 34px;
        }

        .nav {
            align-items: flex-start;
        }

        .nav a:last-child {
            padding-top: 10px;
        }
    }

    @media (max-width: 480px) {
        .actions {
            flex-direction: column;
        }

        .button {
            width: 100%;
        }
    }
</style>
```

</head>
<body>
    <div class="shell">
        <nav class="nav" aria-label="Primary navigation">
            <a class="brand" href="{{ url('/') }}">
                @if ($logoData)
                    <img src="data:image/png;base64,{{ $logoData }}" alt="Datas logo">
                @endif
                <span>Datas</span>
            </a>
            <a href="https://datas.dk/developer">datas.dk/developer</a>
        </nav>

```
    <main>
        <section aria-labelledby="page-title">
            <div class="eyebrow"><span class="pulse"></span>Developer-first CMS platform</div>

            <h1 id="page-title">
                Build faster with
                <span class="gradient-text">Datas CMS</span>
            </h1>

            <p class="intro">
                A modern Laravel-based foundation for APIs, modules, and digital products.
                Clean, flexible, and built for teams that prioritize code-first development.
            </p>

            <div class="actions">
                <a class="button button-primary" href="https://datas.dk/developer">
                    Read documentation
                </a>

                <a class="button button-secondary" href="https://datas.dk/developer">
                    datas.dk/developer
                </a>
            </div>
        </section>

        <aside class="developer-panel" aria-label="Developer preview">
            <div class="panel-top">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
            </div>

            <div class="panel-body">
                @if ($logoData)
                    <img class="logo-mark" src="data:image/png;base64,{{ $logoData }}" alt="Datas logo">
                @endif

                <code>
                    <span>$</span> composer require datasdk/categories<br>
                    <span>$</span> php artisan module:enable Email<br>
                    <span>$</span> curl https://datas.dk/developer
                </code>

                <p class="doc-note">
                    Find guides, API references, and module documentation at datas.dk/developer.
                </p>
            </div>
        </aside>
    </main>

    <footer>
        Datas CMS · Documentation for builders
    </footer>
</div>
```

</body>
</html>
