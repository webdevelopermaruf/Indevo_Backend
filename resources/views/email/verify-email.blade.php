<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background-color: #f4f4f5;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 2rem;
        }
        .card {
            background: #ffffff;
            border-radius: 12px;
            border: 1px solid #e4e4e7;
            padding: 2.5rem 2rem;
            max-width: 420px;
            width: 100%;
            text-align: center;
        }
        .icon {
            width: 48px;
            height: 48px;
            background: #eff6ff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.25rem;
        }
        h1 {
            font-size: 20px;
            font-weight: 500;
            color: #18181b;
            margin-bottom: 0.5rem;
        }
        .subtitle {
            font-size: 14px;
            color: #71717a;
            margin-bottom: 1.75rem;
            line-height: 1.6;
        }
        .subtitle strong { color: #18181b; font-weight: 500; }
        .code {
            background: #f4f4f5;
            border: 1px solid #e4e4e7;
            border-radius: 8px;
            padding: 1rem 1.5rem;
            font-size: 28px;
            font-weight: 500;
            letter-spacing: 0.25em;
            color: #18181b;
            font-family: 'Courier New', monospace;
            margin-bottom: 1.75rem;
        }
        .footer {
            font-size: 12px;
            color: #a1a1aa;
            line-height: 1.6;
        }
    </style>
</head>
<body>
<div class="card">
    <div class="icon">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none"
             stroke="#013119" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="16" rx="2"/>
            <path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>
        </svg>
    </div>

    <h1>Verify your email</h1>

    <p class="subtitle">
        Enter the verification code we sent to
        <strong>{{ $email }}</strong>
    </p>

    <div class="code">{{ $code }}</div>

    <p class="footer">
        This code expires in 15 minutes. If you did not request this,
        you can safely ignore this email.
    </p>
</div>
</body>
</html>
