<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex, nofollow">
    <title>@yield('code') — SekolahKu</title>
    <style>
        * { box-sizing: border-box; }
        body { margin: 0; min-height: 100vh; display: grid; place-items: center; padding: 24px; background: #f3f4f6; color: #111827; font-family: Arial, sans-serif; }
        main { width: min(100%, 520px); padding: 40px; border: 1px solid #e5e7eb; border-radius: 18px; background: #fff; box-shadow: 0 12px 32px rgba(17, 24, 39, .08); text-align: center; }
        .brand { display: inline-flex; align-items: center; gap: 10px; margin-bottom: 30px; font-size: 15px; font-weight: 700; }
        .brand-mark { display: grid; width: 36px; height: 36px; place-items: center; border-radius: 11px; background: #111827; color: #fff; }
        .code { margin: 0; color: #6b7280; font-size: 13px; font-weight: 700; letter-spacing: .16em; }
        h1 { margin: 12px 0; font-size: clamp(24px, 5vw, 32px); letter-spacing: -.03em; }
        .message { margin: 0 auto; max-width: 390px; color: #6b7280; font-size: 15px; line-height: 1.65; }
        .actions { display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; margin-top: 28px; }
        .button { display: inline-flex; min-height: 42px; align-items: center; justify-content: center; padding: 0 16px; border: 1px solid #111827; border-radius: 9px; background: #111827; color: #fff; font-size: 14px; font-weight: 600; text-decoration: none; }
        .button.secondary { background: #fff; color: #111827; border-color: #d1d5db; }
        .button:focus-visible { outline: 3px solid #9ca3af; outline-offset: 3px; }
        @media (max-width: 480px) { main { padding: 30px 22px; } }
    </style>
</head>

<body>
    @php
        $referer = request()->headers->get('referer');
        $refererParts = $referer ? parse_url($referer) : [];
        $refererParts = is_array($refererParts) ? $refererParts : [];
        $refererScheme = strtolower($refererParts['scheme'] ?? '');
        $refererPort = $refererParts['port'] ?? ($refererScheme === 'https' ? 443 : 80);
        $requestPort = request()->getPort();
        $previousPageIsSafe = isset($refererParts['host'])
            && in_array($refererScheme, ['http', 'https'], true)
            && $refererScheme === request()->getScheme()
            && strtolower($refererParts['host']) === strtolower(request()->getHost())
            && ! array_key_exists('user', $refererParts)
            && ! array_key_exists('pass', $refererParts)
            && (int) $refererPort === $requestPort
            && ($refererParts['path'] ?? '/') !== request()->getPathInfo();
        $backUrl = $previousPageIsSafe ? $referer : url('/');
    @endphp

    <main>
        <div class="brand"><span class="brand-mark" aria-hidden="true">S</span><span>SekolahKu</span></div>
        <p class="code">@yield('code')</p>
        <h1>@yield('title')</h1>
        <p class="message">@yield('message')</p>
        <div class="actions">
            <a class="button" href="{{ $backUrl }}">{{ $previousPageIsSafe ? 'Kembali' : 'Ke Dashboard' }}</a>
            @if ($previousPageIsSafe)
                <a class="button secondary" href="{{ url('/') }}">Ke Dashboard</a>
            @endif
        </div>
    </main>
</body>

</html>
