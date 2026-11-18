<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - Akses Ditolak</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            min-height: 100vh;
            width: 100vw;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            background: radial-gradient(circle at top right, #eef2ff 0%, #f8fafc 45%, #fff1f2 100%);
            position: relative;
            color: #1e293b;
        }

        /* Background Floating Shapes */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            filter: blur(70px);
            z-index: 0;
            opacity: 0.55;
            animation: orbFloat 10s ease-in-out infinite alternate;
        }

        .orb-1 {
            width: 380px;
            height: 380px;
            background: #fecdd3;
            top: -80px;
            left: -80px;
        }

        .orb-2 {
            width: 420px;
            height: 420px;
            background: #dbeafe;
            bottom: -100px;
            right: -80px;
            animation-delay: -5s;
        }

        @keyframes orbFloat {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(30px, 25px) scale(1.08); }
        }

        /* Main Container */
        .error-wrapper {
            position: relative;
            z-index: 2;
            max-width: 560px;
            width: 92%;
            text-align: center;
            padding: 2.5rem 2rem;
            background: rgba(255, 255, 255, 0.78);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.9);
            border-radius: 28px;
            box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.08);
            animation: cardEntrance 0.65s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }

        @keyframes cardEntrance {
            from {
                opacity: 0;
                transform: translateY(28px) scale(0.96);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        /* Animated Shield / Lock Icon */
        .lock-stage {
            position: relative;
            width: 110px;
            height: 110px;
            margin: 0 auto 1.25rem;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .pulse-ring {
            position: absolute;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: rgba(239, 68, 68, 0.16);
            animation: pulseRing 2.4s cubic-bezier(0.24, 0, 0.38, 1) infinite;
        }

        .pulse-ring:nth-child(2) {
            animation-delay: 1.2s;
        }

        @keyframes pulseRing {
            0% {
                transform: scale(0.75);
                opacity: 0.85;
            }
            100% {
                transform: scale(1.55);
                opacity: 0;
            }
        }

        .lock-badge {
            position: relative;
            width: 82px;
            height: 82px;
            border-radius: 24px;
            background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
            color: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2.4rem;
            box-shadow: 0 14px 28px -6px rgba(220, 38, 38, 0.42);
            animation: lockShakeFloat 3.5s ease-in-out infinite;
        }

        @keyframes lockShakeFloat {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-5px) rotate(-3deg); }
            50% { transform: translateY(0) rotate(0deg); }
            75% { transform: translateY(-4px) rotate(3deg); }
        }

        /* 403 Typography */
        .error-code {
            font-size: clamp(4.2rem, 10vw, 5.8rem);
            font-weight: 800;
            line-height: 1;
            letter-spacing: -0.04em;
            background: linear-gradient(135deg, #0f172a 20%, #dc2626 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            margin-bottom: 0.4rem;
        }

        .error-title {
            font-size: 1.45rem;
            font-weight: 700;
            color: #0f172a;
            margin-bottom: 0.65rem;
        }

        .error-desc {
            font-size: 0.95rem;
            color: #64748b;
            line-height: 1.6;
            margin-bottom: 1.4rem;
        }

        /* User Info Pill */
        .user-context-pill {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: #f1f5f9;
            border: 1px solid #e2e8f0;
            color: #475569;
            font-size: 0.8rem;
            font-weight: 600;
            padding: 0.45rem 1rem;
            border-radius: 999px;
            margin-bottom: 1.75rem;
        }

        /* Action Buttons */
        .btn-action-group {
            display: flex;
            gap: 0.75rem;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-back {
            padding: 0.65rem 1.35rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border: 1px solid #cbd5e1;
            background: #ffffff;
            color: #334155;
            transition: all 0.2s ease;
        }

        .btn-back:hover {
            background: #f8fafc;
            border-color: #94a3b8;
            transform: translateY(-2px);
        }

        .btn-dashboard {
            padding: 0.65rem 1.45rem;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
            color: #ffffff;
            box-shadow: 0 10px 20px -6px rgba(37, 99, 235, 0.4);
            transition: all 0.2s ease;
        }

        .btn-dashboard:hover {
            transform: translateY(-2px);
            box-shadow: 0 14px 24px -6px rgba(37, 99, 235, 0.5);
            color: #ffffff;
        }
    </style>
</head>
<body>

    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>

    <div class="error-wrapper">
        <div class="lock-stage">
            <div class="pulse-ring"></div>
            <div class="pulse-ring"></div>
            <div class="lock-badge">
                <i class="bi bi-shield-lock-fill"></i>
            </div>
        </div>

        <div class="error-code">403</div>
        <h1 class="error-title">Akses Halaman Ditolak</h1>
        <p class="error-desc">
            {{ $exception->getMessage() ?: 'Maaf, akun Anda tidak memiliki hak akses untuk membuka halaman / fitur ini.' }}
        </p>

        @auth
        <div class="user-context-pill">
            <i class="bi bi-person-badge text-danger"></i>
            <span>
                {{ auth()->user()->name ?? auth()->user()->username }} 
                &bull; Cabang: <strong>{{ auth()->user()->cabang ?? '-' }}</strong> 
                &bull; Level: <strong>{{ auth()->user()->level ?? '-' }}</strong>
            </span>
        </div>
        @endauth

        <div class="btn-action-group">
            <button type="button" onclick="window.history.back()" class="btn btn-back">
                <i class="bi bi-arrow-left me-1"></i> Kembali
            </button>
            <a href="{{ route('dashboard') }}" class="btn btn-dashboard">
                <i class="bi bi-grid-fill me-1"></i> Ke Dashboard
            </a>
        </div>
    </div>

</body>
</html>