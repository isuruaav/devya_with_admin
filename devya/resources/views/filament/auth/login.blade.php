<x-filament-panels::page.simple>

    <div class="ayu-login-page">

        {{-- Main content --}}
        <div class="ayu-login-container">

            {{-- Login Card --}}
            <div class="ayu-login-card">

                {{-- Logo --}}
                <div class="ayu-logo-wrapper">
                    <img
                        src="{{ asset(config('branding.logo_path')) }}"
                        alt="{{ config('branding.company_name') }} logo"
                        class="ayu-logo"
                        width="88"
                        height="88"
                        fetchpriority="high"
                    >
                </div>

                {{-- Brand --}}
                <div class="ayu-brand">
                    <h1>{{ config('branding.company_name') }}</h1>

                    <p>
                        {{ config('branding.hospital_label') }}
                    </p>
                    <span class="ayu-tagline">{{ config('branding.tagline') }}</span>
                </div>

                {{-- Heading --}}
                <div class="ayu-heading">
                    <h2>
                        <i class="fa-solid fa-lock mr-1 text-sm text-emerald-600" data-tooltip="Secure Staff Authentication" aria-hidden="true"></i>
                        Staff sign in
                    </h2>
                    <p>Manage appointments and patient care.</p>
                </div>

                {{-- Login Form --}}
                <form
                    wire:submit="authenticate"
                    id="form"
                    class="ayu-login-form"
                >

                    {{ $this->form }}

                    <button
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="authenticate"
                        class="ayu-login-button"
                        data-tooltip="Sign in to Hospital Management System"
                    >

                        <span
                            wire:loading.remove
                            wire:target="authenticate"
                        >
                            Sign In
                        </span>

                        <span
                            wire:loading
                            wire:target="authenticate"
                            class="ayu-loading"
                        >

                            <i class="fa-solid fa-circle-notch fa-spin mr-2" aria-hidden="true"></i>

                            Signing in...

                        </span>

                        <i
                            wire:loading.remove
                            wire:target="authenticate"
                            class="fa-solid fa-arrow-right ayu-arrow"
                            aria-hidden="true"
                        ></i>

                    </button>

                </form>

                {{-- Footer --}}
                <div class="ayu-footer">

                    <div class="ayu-security">

                        <svg
                            viewBox="0 0 24 24"
                            fill="none"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="1.8"
                                d="M12 3l7 4v5c0 4.5-3 7.9-7 9-4-1.1-7-4.5-7-9V7l7-4z"
                            />
                        </svg>

                        <span>
                            Secure Administration Portal
                        </span>

                    </div>

                    <p>
                        &copy; {{ date('Y') }} {{ config('branding.company_name') }}
                    </p>

                    <a class="ayu-website-link" href="{{ config('branding.website_url') }}">
                        Visit {{ config('branding.website_label') }}
                        <span aria-hidden="true">&nearr;</span>
                    </a>

                </div>

            </div>

        </div>

    </div>


    <style>

        /* =========================================================
           FULL SCREEN FILAMENT SIMPLE PAGE
        ========================================================== */

        html,
        body {
            min-height: 100% !important;
        }

        body.fi-body {
            margin: 0 !important;
        }

        .fi-simple-layout {
            position: relative !important;
            width: 100% !important;
            min-height: 100vh !important;
            overflow: hidden !important;
            background: transparent !important;
        }

        .fi-simple-header { display: none !important; }

        .fi-simple-main-ctn {
            position: relative !important;
            z-index: 2 !important;
            width: 100% !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            display: flex !important;
            align-items: center !important;
            justify-content: center !important;
        }

        .fi-simple-main {
            position: relative !important;
            width: 100% !important;
            max-width: none !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
            background: transparent !important;
            box-shadow: none !important;
            border: 0 !important;
            ring: 0 !important;
        }

        .fi-simple-page {
            width: 100% !important;
            max-width: none !important;
            min-height: 100vh !important;
            margin: 0 !important;
            padding: 0 !important;
        }


        /* =========================================================
           MAIN BACKGROUND
        ========================================================== */

        .ayu-login-page {
            --ayu-page-background: #fdfcf8;
            --ayu-card-background: #ffffff;
            --ayu-card-border: #dce7d7;
            --ayu-text: #1b2a22;
            --ayu-brand: #173f2b;
            --ayu-gold: #a96f20;
            --ayu-muted: #68746d;
            --ayu-input-background: #ffffff;
            --ayu-input-border: #cbd5ce;

            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;

            background:
                radial-gradient(ellipse at 10% 15%, rgba(123, 157, 114, 0.18), transparent 50%),
                radial-gradient(ellipse at 90% 85%, rgba(221, 180, 96, 0.20), transparent 50%),
                var(--ayu-page-background);
            color: var(--ayu-text);
            color-scheme: light;
        }

        html.dark .ayu-login-page {
            --ayu-page-background: #0a2117;
            --ayu-card-background: #152c20;
            --ayu-card-border: #3c4b42;
            --ayu-text: #edf4ef;
            --ayu-brand: #dce7d7;
            --ayu-gold: #f4d58d;
            --ayu-muted: #b1c0b7;
            --ayu-input-background: #18201b;
            --ayu-input-border: #53645a;

            color-scheme: dark;
        }


        /* =========================================================
           LOGIN CONTAINER
        ========================================================== */

        .ayu-login-container {
            position: relative;
            z-index: 5;
            width: 100%;
            max-width: 490px;
            padding: 28px 18px;
        }


        /* =========================================================
           LOGIN CARD
        ========================================================== */

        .ayu-login-card {
            position: relative;
            width: 100%;
            padding: 38px 34px 30px;

            background: var(--ayu-card-background);

            border: 1px solid var(--ayu-card-border);
            border-top: 3px solid #c9933a;
            border-radius: 28px;

            box-shadow:
                0 28px 70px rgba(13, 56, 37, 0.12),
                0 8px 24px rgba(13, 56, 37, 0.05);

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }


        /* =========================================================
           LOGO
        ========================================================== */

        .ayu-logo-wrapper {
            width: 112px;
            height: 112px;
            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;

            border-radius: 28px;

            box-shadow:
                0 10px 30px rgba(169, 111, 32, 0.12);

            border: 1px solid #ddb460;
        }

        .ayu-logo {
            width: 88px;
            height: 88px;
            object-fit: contain;
            border-radius: 12px;
        }


        /* =========================================================
           BRAND
        ========================================================== */

        .ayu-brand {
            margin-top: 18px;
            text-align: center;
        }

        .ayu-brand h1 {
            margin: 0;

            font-family: Georgia, 'Times New Roman', serif;
            font-size: 30px;
            line-height: 1.2;
            font-weight: 800;

            letter-spacing: 0.06em;

            color: var(--ayu-brand);
        }

        .ayu-brand p {
            max-width: 320px;
            margin: 8px auto 0;

            font-size: 13px;
            line-height: 1.7;

            color: var(--ayu-muted);
        }

        .ayu-tagline {
            display: inline-block;
            margin-top: 12px;
            color: var(--ayu-gold);
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.16em;
            text-transform: uppercase;
        }

        /* =========================================================
           HEADING
        ========================================================== */

        .ayu-heading {
            margin-top: 30px;
            margin-bottom: 22px;
        }

        .ayu-heading h2 {
            margin: 0;

            font-size: 20px;
            line-height: 1.3;
            font-weight: 700;

            color: var(--ayu-text);
        }

        .ayu-heading p {
            margin: 6px 0 0;

            font-size: 14px;
            line-height: 1.6;

            color: var(--ayu-muted);
        }


        /* =========================================================
           FORM
        ========================================================== */

        .ayu-login-form {
            width: 100%;
        }

        .ayu-login-form .fi-sc-component-ctn {
            margin-bottom: 16px;
        }


        /* =========================================================
           INPUTS
        ========================================================== */

        .ayu-login-form .fi-input-wrp,
        html.dark .ayu-login-form .fi-input-wrp {
            background: var(--ayu-input-background) !important;
            border-color: var(--ayu-input-border) !important;
        }

        .ayu-login-form label,
        html.dark .ayu-login-form label,
        .ayu-login-form .fi-fo-field-wrp-label span,
        html.dark .ayu-login-form .fi-fo-field-wrp-label span {
            color: var(--ayu-text) !important;
        }

        .ayu-login-form input,
        html.dark .ayu-login-form input {
            border-radius: 13px !important;
            color: var(--ayu-text) !important;
            -webkit-text-fill-color: var(--ayu-text) !important;
        }

        .ayu-login-form input::placeholder {
            color: var(--ayu-muted) !important;
        }

        .ayu-login-form input:autofill {
            box-shadow: 0 0 0 100px var(--ayu-input-background) inset !important;
        }


        /* =========================================================
           BUTTON
        ========================================================== */

        .ayu-login-button {
            width: 100%;
            min-height: 52px;

            display: flex;
            align-items: center;
            justify-content: center;
            gap: 9px;

            margin-top: 20px;

            border: 0;
            border-radius: 14px;

            background:
                linear-gradient(
                    135deg,
                    #235b3d 0%,
                    #173f2b 55%,
                    #0d3825 100%
                );

            color: white;

            font-size: 14px;
            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 10px 24px rgba(13, 56, 37, 0.20);

            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease,
                opacity 0.18s ease;
        }

        .ayu-login-button:hover {
            transform: translateY(-1px);

            box-shadow:
                0 14px 28px rgba(13, 56, 37, 0.28);
        }

        .ayu-login-button:focus-visible,
        .ayu-website-link:focus-visible {
            outline: 3px solid #ddb460;
            outline-offset: 4px;
        }
        .ayu-login-button:active {
            transform: translateY(0);
        }

        .ayu-login-button:disabled {
            opacity: 0.65;
            cursor: not-allowed;
            transform: none;
        }

        .ayu-arrow {
            width: 18px;
            height: 18px;
        }

        .ayu-loading {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .ayu-spinner {
            width: 18px;
            height: 18px;
            animation: ayu-spin 0.8s linear infinite;
        }

        @keyframes ayu-spin {
            to {
                transform: rotate(360deg);
            }
        }


        /* =========================================================
           FOOTER
        ========================================================== */

        .ayu-footer {
            margin-top: 25px;
            text-align: center;
        }

        .ayu-security {
            display: inline-flex;
            align-items: center;
            gap: 6px;

            color: var(--ayu-brand);

            font-size: 12px;
            font-weight: 600;
        }

        .ayu-security svg {
            width: 16px;
            height: 16px;
        }

        .ayu-footer p {
            margin: 8px 0 0;

            font-size: 11px;

            color: var(--ayu-muted);
        }

        .ayu-website-link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            margin-top: 16px;
            color: var(--ayu-brand);
            font-size: 12px;
            text-underline-offset: 4px;
        }

        .ayu-website-link:hover {
            text-decoration: underline;
        }

        @media (prefers-reduced-motion: reduce) {
            .ayu-login-button {
                transition: none;
            }
        }

        /* =========================================================
           MOBILE
        ========================================================== */

        @media (max-width: 640px) {

            .ayu-login-container {
                max-width: 430px;
                padding: 18px;
            }

            .ayu-login-card {
                padding: 30px 22px 24px;
                border-radius: 24px;
            }

            .ayu-logo-wrapper {
                width: 88px;
                height: 88px;
                border-radius: 22px;
            }

            .ayu-logo {
                width: 68px;
                height: 68px;
            }

            .ayu-brand h1 {
                font-size: 26px;
            }

            .ayu-heading h2 {
                font-size: 20px;
            }

        }

    </style>

</x-filament-panels::page.simple>
