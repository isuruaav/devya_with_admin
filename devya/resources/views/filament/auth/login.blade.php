<x-filament-panels::page.simple>

    <div class="ayu-login-page">

        {{-- Main content --}}
        <div class="ayu-login-container">

            {{-- Login Card --}}
            <div class="ayu-login-card">

                {{-- Logo --}}
                <div class="ayu-logo-wrapper">
                    <img
                            src="{{ asset('images/logo.png') }}"
                            alt="DEVYA System"
                        class="ayu-logo"
                    >
                </div>

                {{-- Brand --}}
                <div class="ayu-brand">
                    <h1>DEVYA System</h1>

                    <p>
                        Ayurveda Hospital &amp; Wellness
                        Management System
                    </p>
                </div>

                {{-- Heading --}}
                <div class="ayu-heading">
                    <h2>Ayurvedic Hospital</h2>
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

                            <svg
                                class="ayu-spinner"
                                viewBox="0 0 24 24"
                                fill="none"
                            >
                                <circle
                                    cx="12"
                                    cy="12"
                                    r="9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    opacity="0.25"
                                />

                                <path
                                    d="M21 12a9 9 0 0 0-9-9"
                                    stroke="currentColor"
                                    stroke-width="3"
                                    stroke-linecap="round"
                                />
                            </svg>

                            Signing in...

                        </span>

                        <svg
                            wire:loading.remove
                            wire:target="authenticate"
                            class="ayu-arrow"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M13 7l5 5m0 0l-5 5m5-5H6"
                            />
                        </svg>

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
                        &copy; {{ date('Y') }} ISD Tech Hub (Pvt) Ltd
                    </p>

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
            --ayu-page-background: #eef2ef;
            --ayu-card-background: #ffffff;
            --ayu-card-border: #d8e0da;
            --ayu-text: #18231c;
            --ayu-brand: #065f46;
            --ayu-muted: #647168;
            --ayu-input-background: #ffffff;
            --ayu-input-border: #cbd5ce;

            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            min-height: 100vh;
            overflow: hidden;

            background: var(--ayu-page-background);
            color: var(--ayu-text);
            color-scheme: light;
        }

        html.dark .ayu-login-page {
            --ayu-page-background: #141a17;
            --ayu-card-background: #222b26;
            --ayu-card-border: #3c4b42;
            --ayu-text: #edf4ef;
            --ayu-brand: #8ee1ba;
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
            max-width: 470px;
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
            border-radius: 28px;

            box-shadow:
                0 35px 80px rgba(0, 0, 0, 0.22),
                0 12px 30px rgba(0, 0, 0, 0.10);

            backdrop-filter: blur(18px);
            -webkit-backdrop-filter: blur(18px);
        }


        /* =========================================================
           LOGO
        ========================================================== */

        .ayu-logo-wrapper {
            width: 100px;
            height: 100px;
            margin: 0 auto;

            display: flex;
            align-items: center;
            justify-content: center;

            background: white;

            border-radius: 26px;

            box-shadow:
                0 10px 30px rgba(6, 78, 59, 0.15);

            border: 1px solid #e5e7eb;
        }

        .ayu-logo {
            width: 76px;
            height: 76px;
            object-fit: contain;
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

            font-size: 29px;
            line-height: 1.2;
            font-weight: 800;

            letter-spacing: 0;

            color: var(--ayu-brand);
        }

        .ayu-brand p {
            max-width: 320px;
            margin: 8px auto 0;

            font-size: 13px;
            line-height: 1.7;

            color: var(--ayu-muted);
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

            font-size: 22px;
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
                    #047857 0%,
                    #059669 55%,
                    #0f766e 100%
                );

            color: white;

            font-size: 14px;
            font-weight: 700;

            cursor: pointer;

            box-shadow:
                0 10px 24px rgba(4, 120, 87, 0.28);

            transition:
                transform 0.18s ease,
                box-shadow 0.18s ease,
                opacity 0.18s ease;
        }

        .ayu-login-button:hover {
            transform: translateY(-1px);

            box-shadow:
                0 14px 28px rgba(4, 120, 87, 0.34);
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
