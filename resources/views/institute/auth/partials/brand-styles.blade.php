{{-- Shared split-screen auth styles: left brand panel + right form side. --}}
<style>
    .auth-shell {
        position: relative;
        z-index: 10;
        width: 100%;
        min-height: 100vh;
        display: grid;
        grid-template-columns: 1.65fr 1fr;
    }

    /* ---------- Left brand panel ---------- */
    .auth-brand {
        position: relative;
        background: linear-gradient(150deg, #FF7A1A 0%, #FF6B00 45%, #E85D00 100%);
        color: #fff;
        padding: 3rem 3.25rem;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .auth-brand::before {
        content: "";
        position: absolute;
        inset: 0;
        background-image: radial-gradient(rgba(255, 255, 255, 0.16) 1.5px, transparent 1.5px);
        background-size: 26px 26px;
        opacity: 0.5;
    }

    .auth-brand::after {
        content: "";
        position: absolute;
        width: 440px;
        height: 440px;
        border-radius: 50%;
        background: radial-gradient(circle, rgba(255, 255, 255, 0.18), transparent 70%);
        top: -140px;
        right: -120px;
    }

    .auth-brand > * {
        position: relative;
        z-index: 1;
    }

    .brand-logo {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        background: #fff;
        border-radius: 14px;
        padding: 0.6rem 1.1rem;
        align-self: flex-start;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.14);
    }

    .brand-logo img {
        height: 34px;
        width: auto;
        object-fit: contain;
        display: block;
    }

    .brand-headline {
        margin-top: auto;
    }

    .brand-headline h2 {
        font-size: 2.1rem;
        font-weight: 800;
        line-height: 1.15;
        letter-spacing: -0.5px;
        margin-bottom: 0.85rem;
    }

    .brand-headline p {
        font-size: 0.95rem;
        font-weight: 400;
        line-height: 1.6;
        color: rgba(255, 255, 255, 0.92);
        max-width: 30rem;
    }

    .free-trial-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        background: rgba(255, 255, 255, 0.15);
        border: 1px solid rgba(255, 255, 255, 0.25);
        border-radius: 9999px;
        padding: 0.45rem 1rem;
        font-size: 0.78rem;
        font-weight: 700;
        color: #ffffff;
        margin-top: 1.25rem;
        backdrop-filter: blur(8px);
        -webkit-backdrop-filter: blur(8px);
        width: fit-content;
        align-self: flex-start;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .free-trial-badge .badge-dot {
        width: 7px;
        height: 7px;
        background-color: #10b981; /* Emerald-500 */
        border-radius: 50%;
        animation: pulse-dot 1.8s infinite ease-in-out;
    }

    @keyframes pulse-dot {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.4); opacity: 0.6; }
    }

    /* ---------- Modules grid ---------- */
    .module-grid {
        margin-top: 2.25rem;
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 0.85rem;
    }

    .module-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        background: rgba(255, 255, 255, 0.12);
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 12px;
        padding: 0.7rem 0.85rem;
        transition: transform 0.25s ease, background 0.25s ease;
    }

    .module-item:hover {
        transform: translateY(-2px);
        background: rgba(255, 255, 255, 0.18);
    }

    .module-item i {
        font-size: 1rem;
        width: 34px;
        height: 34px;
        border-radius: 9px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.2);
        flex-shrink: 0;
    }

    .module-item span {
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.01em;
    }

    /* ---------- Vertical steps (register) ---------- */
    .vsteps {
        margin-top: 2.25rem;
        display: flex;
        flex-direction: column;
    }

    .vstep {
        display: flex;
        gap: 1rem;
        position: relative;
        padding-bottom: 1.6rem;
    }

    .vstep:last-child {
        padding-bottom: 0;
    }

    .vstep::before {
        content: "";
        position: absolute;
        left: 17px;
        top: 38px;
        bottom: 0;
        width: 2px;
        background: rgba(255, 255, 255, 0.25);
    }

    .vstep:last-child::before {
        display: none;
    }

    .vstep-dot {
        width: 36px;
        height: 36px;
        border-radius: 50%;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 0.85rem;
        background: rgba(255, 255, 255, 0.15);
        border: 2px solid rgba(255, 255, 255, 0.4);
        color: #fff;
        transition: all 0.35s cubic-bezier(0.34, 1.56, 0.64, 1);
        z-index: 1;
    }

    .vstep-text h4 {
        font-size: 0.92rem;
        font-weight: 700;
        margin-bottom: 0.15rem;
    }

    .vstep-text p {
        font-size: 0.75rem;
        color: rgba(255, 255, 255, 0.82);
        font-weight: 400;
    }

    .vstep.is-active .vstep-dot {
        background: #fff;
        color: #FF6B00;
        border-color: #fff;
        transform: scale(1.08);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.18);
    }

    .vstep.is-done .vstep-dot {
        background: #10b981;
        border-color: #10b981;
        color: #fff;
    }

    .vstep.is-pending .vstep-text h4 {
        color: rgba(255, 255, 255, 0.7);
    }

    .brand-footer {
        margin-top: 2.5rem;
        font-size: 0.72rem;
        color: rgba(255, 255, 255, 0.78);
        font-weight: 500;
    }

    /* ---------- Right form side ---------- */
    .auth-form-side {
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2.5rem 2rem;
        min-height: 100vh;
    }

    .auth-form-inner {
        width: 100%;
        max-width: 380px;
        animation: authFadeIn 0.6s ease-out;
    }

    @keyframes authFadeIn {
        from { opacity: 0; transform: translateY(12px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .form-head {
        margin-bottom: 1.75rem;
    }

    .form-head h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1e293b;
        letter-spacing: -0.5px;
        margin-bottom: 0.35rem;
    }

    .form-head p {
        font-size: 0.85rem;
        color: #64748b;
        font-weight: 500;
        line-height: 1.5;
    }

    /* ---------- Responsive ---------- */
    @media (max-width: 980px) {
        .auth-shell {
            grid-template-columns: 1fr;
        }

        .auth-brand {
            padding: 1.75rem 1.75rem 2rem;
            min-height: auto;
        }

        .auth-brand .brand-headline {
            margin-top: 1.5rem;
        }

        .auth-brand .brand-headline h2 {
            font-size: 1.5rem;
        }

        .module-grid,
        .vsteps,
        .brand-footer,
        .auth-brand .brand-headline p {
            display: none;
        }

        .auth-form-side {
            min-height: auto;
            padding: 2rem 1.25rem 3rem;
        }
    }
</style>
