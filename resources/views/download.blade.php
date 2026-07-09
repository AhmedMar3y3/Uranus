<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="theme-color" content="#050816">
    <title>Download Uranus</title>
    @vite('resources/css/app.css')
</head>
<body class="download-page">
    <main class="download-shell">
        <section class="download-hero" aria-labelledby="download-title">
            <div class="cosmic-field" aria-hidden="true">
                <span class="star star-one"></span>
                <span class="star star-two"></span>
                <span class="star star-three"></span>
                <span class="star star-four"></span>
                <span class="orbit orbit-one"></span>
                <span class="orbit orbit-two"></span>
            </div>

            <div class="hero-copy">
                <p class="eyebrow">Private conversations, launched beautifully</p>
                <h1 id="download-title">Uranus</h1>
                <p class="hero-lede">
                    A sleek chat experience built for real connections, realtime presence,
                    secure messaging, and a calmer way to stay close to your people.
                </p>

                <div class="hero-actions">
                    <a class="download-button" href="{{ asset('uranus.apk') }}" download>
                        <span class="button-glow"></span>
                        Install APK
                    </a>
                    @if ($apkSize)
                        <span class="apk-meta">Android package · {{ $apkSize }}</span>
                    @endif
                </div>
            </div>

            <div class="planet-stage" aria-hidden="true">
                <div class="planet">
                    <span class="planet-shine"></span>
                    <span class="planet-ring"></span>
                    <span class="planet-ring planet-ring-back"></span>
                </div>
                <div class="phone-card">
                    <div class="phone-top"></div>
                    <div class="message message-primary"></div>
                    <div class="message message-secondary"></div>
                    <div class="message message-tertiary"></div>
                    <div class="presence-row">
                        <span></span>
                        <span></span>
                        <span></span>
                    </div>
                </div>
            </div>
        </section>

        <section class="feature-grid" aria-label="Uranus features">
            <article class="feature-card">
                <span class="feature-icon">01</span>
                <h2>Realtime Chat</h2>
                <p>Conversations feel alive with instant messages, typing states, delivery, and seen updates.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">02</span>
                <h2>Private By Design</h2>
                <p>Encrypted message payloads keep the backend focused on transport, not reading your words.</p>
            </article>
            <article class="feature-card">
                <span class="feature-icon">03</span>
                <h2>Friends & Presence</h2>
                <p>Send requests, manage friendships, and see when the people you care about are around.</p>
            </article>
        </section>
    </main>
</body>
</html>
