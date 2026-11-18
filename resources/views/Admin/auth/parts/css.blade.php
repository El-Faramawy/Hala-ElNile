<style>
    /* ==========================================================================
       Hala El Nile - Admin Login Stylesheet
       Theme: Luxury Deep Navy (#0b223d) & Italian Green (#008c45)
       ========================================================================== */

    :root {
        --hn-navy-dark: #071526;
        --hn-navy: #0b223d;
        --hn-navy-light: #16365c;
        --hn-green: #008c45;
        --hn-green-dark: #006b34;
        --hn-green-light: #05a854;
        --hn-gold: #d4af37;
        --hn-gold-light: #f6e8b8;
        --hn-red: #cd212a;
        --hn-border: #e2e8f0;
        --hn-text: #1e293b;
        --hn-muted: #64748b;
    }

    body.app {
        overflow-x: hidden;
        min-height: 100vh;
        background: var(--hn-navy-dark) !important;
        font-family: 'Cairo', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif !important;
    }

    .login-img {
        position: relative;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 40px 16px;
        background: radial-gradient(circle at 20% 20%, #16365c 0%, transparent 45%),
                    radial-gradient(circle at 80% 80%, rgba(0, 140, 69, 0.25) 0%, transparent 40%),
                    linear-gradient(135deg, #071526 0%, #0b223d 50%, #0f2d50 100%) !important;
        box-sizing: border-box;
        overflow: hidden;
    }

    /* Ambient glowing decorative circles */
    .hn-ambient-glow {
        position: absolute;
        border-radius: 50%;
        pointer-events: none;
        filter: blur(80px);
        opacity: 0.4;
    }

    .hn-ambient-1 {
        width: 420px;
        height: 420px;
        background: #008c45;
        top: -120px;
        right: -80px;
        animation: hnFloat 9s ease-in-out infinite alternate;
    }

    .hn-ambient-2 {
        width: 380px;
        height: 380px;
        background: #183b63;
        bottom: -100px;
        left: -60px;
        animation: hnFloat 11s ease-in-out infinite alternate-reverse;
    }

    .hn-ambient-3 {
        width: 250px;
        height: 250px;
        background: rgba(212, 175, 55, 0.25);
        top: 50%;
        left: 20%;
        transform: translateY(-50%);
        animation: hnFloat 13s ease-in-out infinite alternate;
    }

    @keyframes hnFloat {
        0% { transform: translateY(0) scale(1); }
        100% { transform: translateY(-25px) scale(1.08); }
    }

    /* Card Wrapper */
    .login-card-wrapper {
        position: relative;
        z-index: 10;
        width: 100%;
        max-width: 480px;
        margin: 0 auto;
        animation: hnSlideUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
    }

    @keyframes hnSlideUp {
        from { opacity: 0; transform: translateY(30px); }
        to   { opacity: 1; transform: translateY(0); }
    }

    /* Logo & Official Badge */
    .login-header-branding {
        text-align: center;
        margin-bottom: 24px;
    }

    .login-logo-img {
        max-height: 80px;
        width: auto;
        object-fit: contain;
        filter: drop-shadow(0 8px 16px rgba(0,0,0,0.35));
        transition: transform 0.3s ease;
    }

    .login-logo-img:hover {
        transform: scale(1.03);
    }

    .license-badge {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        margin-top: 12px;
        padding: 6px 16px;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(8px);
        border: 1px solid rgba(212, 175, 55, 0.4);
        border-radius: 999px;
        color: var(--hn-gold-light);
        font-size: 11.5px;
        font-weight: 600;
        box-shadow: 0 4px 14px rgba(0,0,0,0.25);
    }

    .license-badge i {
        color: var(--hn-gold);
    }

    /* Main Login Card */
    .login-card {
        background: #ffffff;
        border-radius: 20px;
        padding: 36px 32px;
        box-shadow: 0 20px 45px rgba(0, 0, 0, 0.35), 0 0 0 1px rgba(255, 255, 255, 0.08);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }

    .login-card-head {
        text-align: center;
        margin-bottom: 28px;
    }

    .login-shield-icon {
        width: 52px;
        height: 52px;
        margin: 0 auto 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(11, 34, 61, 0.08), rgba(0, 140, 69, 0.12));
        color: var(--hn-navy);
        font-size: 22px;
        border: 1px solid rgba(11, 34, 61, 0.12);
    }

    .login-card-title {
        font-size: 21px;
        font-weight: 800;
        color: var(--hn-navy);
        margin: 0 0 6px;
    }

    .login-card-subtitle {
        font-size: 13px;
        color: var(--hn-muted);
        margin: 0;
        font-weight: 500;
        line-height: 1.5;
    }

    /* Form Inputs */
    .form-group-custom {
        margin-bottom: 20px;
    }

    .form-label-custom {
        display: block;
        font-size: 13px;
        font-weight: 700;
        color: var(--hn-text);
        margin-bottom: 8px;
    }

    .input-wrap-custom {
        position: relative;
        display: flex;
        align-items: center;
    }

    .input-icon-custom {
        position: absolute;
        right: 14px;
        color: #94a3b8;
        font-size: 15px;
        pointer-events: none;
        transition: color 0.2s ease;
    }

    .input-control-custom {
        width: 100%;
        height: 48px;
        padding: 0 42px 0 16px;
        background: #f8fafc;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        font-size: 14px;
        color: var(--hn-text);
        font-family: inherit;
        font-weight: 500;
        transition: all 0.2s ease;
        outline: none;
        box-sizing: border-box;
    }

    .input-control-custom:focus {
        background: #ffffff;
        border-color: var(--hn-green);
        box-shadow: 0 0 0 4px rgba(0, 140, 69, 0.12);
    }

    .input-control-custom:focus ~ .input-icon-custom {
        color: var(--hn-green);
    }

    /* Password Toggle */
    .toggle-pass-btn {
        position: absolute;
        left: 12px;
        background: transparent;
        border: none;
        color: #94a3b8;
        font-size: 15px;
        cursor: pointer;
        padding: 6px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: color 0.2s ease;
    }

    .toggle-pass-btn:hover {
        color: var(--hn-navy);
    }

    /* Submit Button */
    .btn-submit-login {
        position: relative;
        width: 100%;
        height: 50px;
        margin-top: 10px;
        background: linear-gradient(135deg, var(--hn-navy) 0%, var(--hn-green) 100%);
        border: none;
        border-radius: 12px;
        color: #ffffff;
        font-size: 15.5px;
        font-weight: 700;
        font-family: inherit;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
        box-shadow: 0 8px 20px rgba(11, 34, 61, 0.25);
        transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
        overflow: hidden;
    }

    .btn-submit-login::before {
        content: '';
        position: absolute;
        top: 0; left: -100%;
        width: 100%; height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }

    .btn-submit-login:hover::before {
        left: 100%;
    }

    .btn-submit-login:hover {
        background: linear-gradient(135deg, var(--hn-navy-light) 0%, var(--hn-green-dark) 100%);
        transform: translateY(-2px);
        box-shadow: 0 12px 26px rgba(0, 140, 69, 0.35);
    }

    .btn-submit-login:active {
        transform: translateY(0);
        box-shadow: 0 4px 12px rgba(11, 34, 61, 0.2);
    }

    /* Footer */
    .login-footer {
        margin-top: 24px;
        text-align: center;
    }

    .login-footer-copy {
        font-size: 12.5px;
        color: rgba(255, 255, 255, 0.5);
        margin: 0;
        font-weight: 500;
    }

    /* Responsive */
    @media (max-width: 520px) {
        .login-card {
            padding: 28px 20px;
            border-radius: 16px;
        }

        .login-card-title {
            font-size: 19px;
        }

        .login-logo-img {
            max-height: 65px;
        }
    }
</style>
