<style>
    body {
        min-height: 100vh;
        margin: 0;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, "Inter", sans-serif;
        background: radial-gradient(circle at top, #0f172a 0%, #020617 45%, #020617 100%);
        color: #f9fafb;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    .fc-login-wrapper {
        width: 100%;
        max-width: 480px;
        padding: 2.5rem 1.5rem;
    }
    .fc-login-card {
        background: rgba(15, 23, 42, 0.95);
        border-radius: 24px;
        border: 1px solid rgba(148, 163, 184, 0.35);
        box-shadow: 0 24px 80px rgba(15, 23, 42, 0.85);
        padding: 2.5rem 2rem;
    }
    .fc-logo-badge {
        width: 56px;
        height: 56px;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 1.5rem;
        color: #020617;
        box-shadow: 0 8px 24px rgba(34, 197, 94, 0.4);
        margin: 0 auto 1.5rem;
    }
    .fc-title {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        text-align: center;
    }
    .fc-subtitle {
        font-size: 0.95rem;
        color: #9ca3af;
        margin-bottom: 2rem;
        text-align: center;
        line-height: 1.55;
    }
    .fc-form-group {
        margin-bottom: 1.25rem;
    }
    .fc-form-label {
        display: block;
        font-size: 0.8rem;
        font-weight: 600;
        color: #e5e7eb;
        margin-bottom: 0.5rem;
    }
    .fc-form-input {
        width: 100%;
        padding: 0.75rem 1rem;
        border-radius: 12px;
        background: rgba(5, 6, 8, 0.8);
        border: 1px solid rgba(148, 163, 184, 0.3);
        color: #f9fafb;
        font-size: 0.9rem;
        transition: all 0.2s ease-out;
        box-sizing: border-box;
    }
    .fc-form-input:focus {
        outline: none;
        border-color: #22c55e;
        box-shadow: 0 0 0 3px rgba(34, 197, 94, 0.1);
    }
    .fc-form-input::placeholder {
        color: #6b7280;
    }
    .fc-btn-primary {
        width: 100%;
        border: none;
        border-radius: 999px;
        padding: 0.85rem 1.6rem;
        font-size: 0.95rem;
        font-weight: 600;
        cursor: pointer;
        color: #020617;
        background: linear-gradient(135deg, #22c55e 0%, #16a34a 100%);
        box-shadow: 0 10px 30px rgba(34, 197, 94, 0.4);
        transition: all 0.2s ease-out;
        margin-top: 1.5rem;
    }
    .fc-btn-primary:hover {
        transform: translateY(-1px);
        box-shadow: 0 16px 45px rgba(34, 197, 94, 0.55);
        background: linear-gradient(135deg, #4ade80 0%, #22c55e 100%);
    }
    .fc-error-message {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        background: rgba(239, 68, 68, 0.1);
        border: 1px solid rgba(239, 68, 68, 0.3);
        color: #fca5a5;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    .fc-success-message {
        padding: 0.75rem 1rem;
        border-radius: 12px;
        background: rgba(34, 197, 94, 0.1);
        border: 1px solid rgba(34, 197, 94, 0.3);
        color: #86efac;
        font-size: 0.85rem;
        margin-bottom: 1rem;
    }
    .fc-link-text {
        text-align: center;
        margin-top: 1.5rem;
        font-size: 0.9rem;
        color: #9ca3af;
    }
    .fc-link-text a {
        color: #22c55e;
        text-decoration: none;
        font-weight: 500;
        transition: color 0.2s ease-out;
    }
    .fc-link-text a:hover {
        color: #4ade80;
    }
    .fc-forgot-link {
        display: block;
        text-align: right;
        margin-top: 0.5rem;
        font-size: 0.8rem;
    }
    .fc-forgot-link a {
        color: #22c55e;
        text-decoration: none;
    }
    .fc-forgot-link a:hover {
        color: #4ade80;
    }
</style>
