<style>
/* ==========================================================================
   SAPA SOSIAL - Futuristic Admin Theme
   Colors: Biru Donker (#060d19, #0a1628), Hijau (#10b981, #059669), Putih (#ffffff)
   Aesthetic: Modern, Cyber-Glassmorphism, High-Tech Futuristic Gradients
   ========================================================================== */

/* --- Root Variables & Color Tokens --- */
:root {
    --theme-navy-950: #040810;
    --theme-navy-900: #060d19;
    --theme-navy-850: #081222;
    --theme-navy-800: #0b182d;
    --theme-navy-700: #0f223f;
    --theme-navy-600: #17325b;
    --theme-emerald-500: #10b981;
    --theme-emerald-400: #34d399;
    --theme-emerald-300: #6ee7b7;
    --theme-cyan-400: #22d3ee;
    --theme-cyan-500: #06b6d4;
    --theme-white: #ffffff;
    --theme-glow-emerald: 0 0 20px rgba(16, 185, 129, 0.35);
    --theme-glow-cyan: 0 0 20px rgba(6, 182, 212, 0.30);
    --theme-gradient-primary: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%);
    --theme-gradient-navy: linear-gradient(180deg, #060d19 0%, #0a1628 50%, #050a12 100%);
    --theme-gradient-accent: linear-gradient(90deg, #10b981 0%, #06b6d4 50%, #3b82f6 100%);
    --theme-glass-border: rgba(16, 185, 129, 0.16);
}

/* --- Brand Logo Sizing & Alignment (Ensures perfect proportions in all viewports) --- */
.fi-logo {
    display: inline-flex !important;
    align-items: center !important;
    height: auto !important;
    max-height: 2.25rem !important;
    overflow: visible !important;
}

.fi-custom-brand-logo {
    display: inline-flex !important;
    align-items: center !important;
    gap: 0.65rem !important;
    height: 100% !important;
    max-height: 2.25rem !important;
    vertical-align: middle !important;
}

.fi-brand-icon-box {
    width: 32px !important;
    min-width: 32px !important;
    max-width: 32px !important;
    height: 32px !important;
    min-height: 32px !important;
    max-height: 32px !important;
    flex-shrink: 0 !important;
}

.fi-brand-icon-box svg {
    width: 16px !important;
    min-width: 16px !important;
    max-width: 16px !important;
    height: 16px !important;
    min-height: 16px !important;
    max-height: 16px !important;
    display: block !important;
}

/* Light mode brand typography */
html:not(.dark) .fi-brand-title {
    color: #0b1a30 !important;
    background: linear-gradient(135deg, #091322 0%, #064e3b 50%, #059669 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
}

html:not(.dark) .fi-brand-subtitle {
    color: #059669 !important;
}

/* Dark mode brand typography */
html.dark .fi-brand-title {
    color: #ffffff !important;
    background: linear-gradient(135deg, #ffffff 0%, #a7f3d0 50%, #34d399 100%) !important;
    -webkit-background-clip: text !important;
    -webkit-text-fill-color: transparent !important;
}

html.dark .fi-brand-subtitle {
    color: #34d399 !important;
}

/* --- Ambient Futuristic Canvas Background (Dark Mode) --- */
html.dark body,
html.dark .fi-body {
    background-color: var(--theme-navy-900) !important;
    background-image: 
        radial-gradient(circle at 90% -5%, rgba(16, 185, 129, 0.12) 0%, transparent 45%),
        radial-gradient(circle at 5% 25%, rgba(6, 182, 212, 0.08) 0%, transparent 40%),
        radial-gradient(circle at 80% 85%, rgba(37, 99, 235, 0.10) 0%, transparent 50%),
        radial-gradient(circle at 20% 95%, rgba(16, 185, 129, 0.06) 0%, transparent 45%) !important;
    background-attachment: fixed !important;
    color: #e2e8f0;
}

/* Light Mode Ambient Glow (Clean Crisp Pearl with Cyan/Mint Ambient Light) */
html:not(.dark) body,
html:not(.dark) .fi-body {
    background-color: #f8fafc !important;
    background-image: 
        radial-gradient(circle at 95% 0%, rgba(16, 185, 129, 0.08) 0%, transparent 40%),
        radial-gradient(circle at 5% 20%, rgba(6, 182, 212, 0.06) 0%, transparent 35%),
        radial-gradient(circle at 85% 85%, rgba(30, 58, 138, 0.05) 0%, transparent 45%) !important;
    background-attachment: fixed !important;
}

/* --- Futuristic Sidebar --- */
.fi-sidebar {
    border-right: 1px solid rgba(16, 185, 129, 0.15) !important;
    box-shadow: 4px 0 25px rgba(0, 0, 0, 0.3) !important;
}

html.dark .fi-sidebar {
    background: linear-gradient(180deg, #050b14 0%, #081324 45%, #050c18 100%) !important;
}

html:not(.dark) .fi-sidebar {
    background: linear-gradient(180deg, #ffffff 0%, #f1f5f9 100%) !important;
    border-right: 1px solid rgba(16, 185, 129, 0.2) !important;
}

.fi-sidebar-header {
    border-bottom: 1px solid rgba(16, 185, 129, 0.14) !important;
    position: relative;
}

.fi-sidebar-header::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 10%;
    right: 10%;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.5), rgba(6, 182, 212, 0.5), transparent);
}

/* Navigation Group Header */
.fi-sidebar-group-label {
    text-transform: uppercase !important;
    letter-spacing: 0.12em !important;
    font-size: 0.68rem !important;
    font-weight: 700 !important;
    color: rgba(52, 211, 153, 0.9) !important;
    display: flex !important;
    align-items: center !important;
    gap: 0.5rem !important;
    padding-top: 1rem !important;
}

.fi-sidebar-group-label::before {
    content: '';
    display: inline-block;
    width: 4px;
    height: 4px;
    border-radius: 9999px;
    background-color: var(--theme-emerald-400);
    box-shadow: 0 0 6px var(--theme-emerald-400);
}

/* Navigation Items */
.fi-sidebar-item {
    margin: 2px 0 !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
}

.fi-sidebar-item-btn {
    border-radius: 0.75rem !important;
    transition: all 0.25s ease !important;
    position: relative !important;
    overflow: hidden !important;
}

.fi-sidebar-item-btn:hover {
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.10) 0%, rgba(6, 182, 212, 0.05) 100%) !important;
    transform: translateX(3px) !important;
}

/* Active Navigation Item */
.fi-sidebar-item.fi-active .fi-sidebar-item-btn,
.fi-sidebar-item-active .fi-sidebar-item-btn {
    background: linear-gradient(90deg, rgba(16, 185, 129, 0.22) 0%, rgba(6, 182, 212, 0.10) 100%) !important;
    border-left: 3px solid var(--theme-emerald-400) !important;
    border-radius: 4px 0.75rem 0.75rem 4px !important;
    box-shadow: 0 0 20px rgba(16, 185, 129, 0.25), inset 0 0 12px rgba(16, 185, 129, 0.10) !important;
}

.fi-sidebar-item.fi-active .fi-sidebar-item-label,
.fi-sidebar-item-active .fi-sidebar-item-label {
    color: #ffffff !important;
    font-weight: 600 !important;
    text-shadow: 0 0 10px rgba(16, 185, 129, 0.4) !important;
}

.fi-sidebar-item.fi-active .fi-sidebar-item-icon,
.fi-sidebar-item-active .fi-sidebar-item-icon {
    color: var(--theme-emerald-400) !important;
    filter: drop-shadow(0 0 6px rgba(16, 185, 129, 0.6)) !important;
}

/* --- Topbar (Glassmorphism & Neon Horizon Line) --- */
.fi-topbar {
    position: relative !important;
    backdrop-filter: blur(18px) saturate(180%) !important;
    -webkit-backdrop-filter: blur(18px) saturate(180%) !important;
    border-bottom: 1px solid rgba(16, 185, 129, 0.16) !important;
}

html.dark .fi-topbar {
    background: rgba(6, 13, 25, 0.82) !important;
}

html:not(.dark) .fi-topbar {
    background: rgba(255, 255, 255, 0.88) !important;
    border-bottom: 1px solid rgba(16, 185, 129, 0.2) !important;
}

/* Glowing Neon Horizon Line under Topbar */
.fi-topbar::after {
    content: '';
    position: absolute;
    bottom: -1px;
    left: 0;
    right: 0;
    height: 1.5px;
    background: linear-gradient(90deg, 
        transparent 0%, 
        rgba(16, 185, 129, 0.4) 25%, 
        rgba(6, 182, 212, 0.6) 50%, 
        rgba(59, 130, 246, 0.4) 75%, 
        transparent 100%
    );
    pointer-events: none;
}

/* --- Futuristic Cards, Sections, & Panels --- */
.fi-section,
.fi-wi-widget,
.fi-ta-ctn {
    border-radius: 1.1rem !important;
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
}

html.dark .fi-section,
html.dark .fi-wi-widget,
html.dark .fi-ta-ctn {
    background: linear-gradient(145deg, rgba(10, 20, 36, 0.88) 0%, rgba(6, 13, 24, 0.94) 100%) !important;
    border: 1px solid rgba(16, 185, 129, 0.15) !important;
    box-shadow: 0 10px 30px -10px rgba(0, 0, 0, 0.7), 0 0 15px -3px rgba(16, 185, 129, 0.05) !important;
    backdrop-filter: blur(14px) !important;
}

html:not(.dark) .fi-section,
html:not(.dark) .fi-wi-widget,
html:not(.dark) .fi-ta-ctn {
    background: rgba(255, 255, 255, 0.95) !important;
    border: 1px solid rgba(16, 185, 129, 0.18) !important;
    box-shadow: 0 10px 25px -8px rgba(16, 185, 129, 0.08), 0 2px 8px rgba(0, 0, 0, 0.04) !important;
}

/* Subtle Top Accent Highlight for Sections */
html.dark .fi-section::before,
html.dark .fi-wi-widget::before {
    content: '';
    position: absolute;
    top: 0;
    left: 1.5rem;
    right: 1.5rem;
    height: 1px;
    background: linear-gradient(90deg, transparent, rgba(16, 185, 129, 0.4), rgba(6, 182, 212, 0.4), transparent);
    pointer-events: none;
    border-radius: 9999px;
}

/* Hover Elevation on Widgets */
.fi-wi-stats-overview-stat {
    border-radius: 1rem !important;
    transition: all 0.3s ease !important;
    overflow: hidden !important;
    position: relative !important;
}

.fi-wi-stats-overview-stat:hover {
    transform: translateY(-3px) !important;
    box-shadow: 0 14px 28px rgba(0, 0, 0, 0.4), 0 0 20px rgba(16, 185, 129, 0.18) !important;
}

html.dark .fi-wi-stats-overview-stat {
    background: linear-gradient(135deg, rgba(12, 24, 43, 0.90) 0%, rgba(7, 15, 28, 0.95) 100%) !important;
    border: 1px solid rgba(16, 185, 129, 0.18) !important;
}

/* --- High-Tech Buttons --- */
/* Primary Action Button (Futuristic Emerald-Cyan Gradient) */
.fi-btn-color-primary,
button[type="submit"].fi-btn {
    background: var(--theme-gradient-primary) !important;
    color: #ffffff !important;
    font-weight: 600 !important;
    letter-spacing: 0.02em !important;
    border: 1px solid rgba(255, 255, 255, 0.22) !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 4px 18px 0 rgba(16, 185, 129, 0.40) !important;
    transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1) !important;
    position: relative !important;
    overflow: hidden !important;
}

.fi-btn-color-primary:hover,
button[type="submit"].fi-btn:hover {
    box-shadow: 0 6px 25px 0 rgba(16, 185, 129, 0.65), 0 0 15px rgba(6, 182, 212, 0.4) !important;
    transform: translateY(-1.5px) !important;
    filter: brightness(1.08) !important;
}

.fi-btn-color-primary:active,
button[type="submit"].fi-btn:active {
    transform: translateY(0) !important;
    filter: brightness(0.95) !important;
}

/* Secondary / Gray Buttons */
html.dark .fi-btn-color-gray {
    background: rgba(14, 28, 50, 0.7) !important;
    border: 1px solid rgba(16, 185, 129, 0.16) !important;
    color: #e2e8f0 !important;
    border-radius: 0.75rem !important;
    backdrop-filter: blur(8px) !important;
}

html.dark .fi-btn-color-gray:hover {
    background: rgba(20, 38, 66, 0.9) !important;
    border-color: rgba(16, 185, 129, 0.35) !important;
    color: #ffffff !important;
}

/* --- Tables & Data Grids --- */
.fi-ta-header-cell {
    font-weight: 700 !important;
    font-size: 0.75rem !important;
    letter-spacing: 0.08em !important;
    text-transform: uppercase !important;
}

html.dark .fi-ta-header-cell {
    color: rgba(148, 163, 184, 0.95) !important;
    background: rgba(7, 16, 30, 0.92) !important;
    border-bottom: 1px solid rgba(16, 185, 129, 0.16) !important;
}

.fi-ta-row {
    transition: background-color 0.2s ease !important;
}

html.dark .fi-ta-row:hover {
    background-color: rgba(16, 185, 129, 0.06) !important;
}

html:not(.dark) .fi-ta-row:hover {
    background-color: rgba(16, 185, 129, 0.04) !important;
}

html.dark .fi-ta-row {
    border-bottom: 1px solid rgba(16, 185, 129, 0.08) !important;
}

/* --- Futuristic Badges --- */
.fi-badge {
    border-radius: 9999px !important;
    font-weight: 600 !important;
    letter-spacing: 0.03em !important;
    backdrop-filter: blur(6px) !important;
    transition: all 0.2s ease !important;
}

html.dark .fi-badge-color-primary,
html.dark .fi-badge-color-success {
    background: rgba(16, 185, 129, 0.14) !important;
    border: 1px solid rgba(16, 185, 129, 0.35) !important;
    color: #6ee7b7 !important;
    box-shadow: 0 0 10px rgba(16, 185, 129, 0.15) !important;
}

html.dark .fi-badge-color-info {
    background: rgba(6, 182, 212, 0.14) !important;
    border: 1px solid rgba(6, 182, 212, 0.35) !important;
    color: #67e8f9 !important;
    box-shadow: 0 0 10px rgba(6, 182, 212, 0.15) !important;
}

html.dark .fi-badge-color-warning {
    background: rgba(245, 158, 11, 0.14) !important;
    border: 1px solid rgba(245, 158, 11, 0.35) !important;
    color: #fcd34d !important;
}

html.dark .fi-badge-color-danger {
    background: rgba(244, 63, 94, 0.14) !important;
    border: 1px solid rgba(244, 63, 94, 0.35) !important;
    color: #fda4af !important;
}

/* --- Form Fields & Inputs --- */
.fi-input-wrp {
    border-radius: 0.75rem !important;
    transition: all 0.2s ease !important;
}

html.dark .fi-input-wrp {
    background: rgba(8, 17, 30, 0.7) !important;
    border: 1px solid rgba(16, 185, 129, 0.18) !important;
}

html.dark .fi-input-wrp:focus-within {
    border-color: var(--theme-emerald-400) !important;
    box-shadow: 0 0 0 2px rgba(16, 185, 129, 0.25), 0 0 15px rgba(16, 185, 129, 0.2) !important;
}

/* --- Modals & Dropdown Panels --- */
.fi-modal-window,
.fi-dropdown-panel {
    border-radius: 1.25rem !important;
    backdrop-filter: blur(24px) saturate(180%) !important;
    -webkit-backdrop-filter: blur(24px) saturate(180%) !important;
}

html.dark .fi-modal-window,
html.dark .fi-dropdown-panel {
    background: linear-gradient(145deg, rgba(10, 21, 38, 0.96) 0%, rgba(6, 13, 24, 0.98) 100%) !important;
    border: 1px solid rgba(16, 185, 129, 0.22) !important;
    box-shadow: 0 25px 60px -15px rgba(0, 0, 0, 0.85), 0 0 25px rgba(16, 185, 129, 0.10) !important;
}

/* ==========================================================================
   FUTURISTIC LOGIN PAGE (BRIGHT, MODERN, FRESH & HIGH-CONTRAST)
   ========================================================================== */

/* Light Mode: Bright, Fresh & Modern Luminous Background */
html:not(.dark) .fi-simple-layout {
    position: relative !important;
    background-color: #f8fafc !important;
    background-image: 
        radial-gradient(circle 800px at 90% 10%, rgba(16, 185, 129, 0.14) 0%, transparent 60%),
        radial-gradient(circle 700px at 10% 20%, rgba(6, 182, 212, 0.10) 0%, transparent 55%),
        radial-gradient(circle 750px at 50% 95%, rgba(59, 130, 246, 0.08) 0%, transparent 60%),
        linear-gradient(180deg, #f0fdf4 0%, #f8fafc 40%, #f1f5f9 100%) !important;
    min-height: 100vh !important;
}

/* Light Mode: Elevated Frosted White Card */
html:not(.dark) .fi-simple-main {
    backdrop-filter: blur(20px) !important;
    -webkit-backdrop-filter: blur(20px) !important;
    background: rgba(255, 255, 255, 0.96) !important;
    border: 1px solid rgba(16, 185, 129, 0.22) !important;
    border-radius: 1.5rem !important;
    box-shadow: 
        0 20px 45px -10px rgba(16, 185, 129, 0.14),
        0 10px 25px -5px rgba(15, 23, 42, 0.08),
        0 0 0 1px rgba(255, 255, 255, 0.9) inset !important;
    position: relative !important;
    overflow: hidden !important;
}

/* Top Gradient Accent Line */
.fi-simple-main::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 4px;
    background: linear-gradient(90deg, #059669 0%, #10b981 35%, #06b6d4 70%, #3b82f6 100%);
}

/* Light Mode: Heading High-Contrast */
html:not(.dark) .fi-simple-header-heading {
    color: #0b1a30 !important;
    font-size: 1.65rem !important;
    font-weight: 800 !important;
    letter-spacing: -0.025em !important;
    margin-top: 0.75rem !important;
    margin-bottom: 0.5rem !important;
}

/* Light Mode: Form Field Labels High-Contrast */
html:not(.dark) .fi-fo-field-label,
html:not(.dark) .fi-fo-field-label-content {
    color: #0f172a !important;
    font-weight: 600 !important;
    font-size: 0.875rem !important;
    letter-spacing: 0.01em !important;
}

html:not(.dark) .fi-fo-field-label-required-mark {
    color: #dc2626 !important;
    font-weight: 700 !important;
    margin-left: 2px !important;
}

/* Light Mode: Form Inputs Crisp & Clear */
html:not(.dark) .fi-simple-main .fi-input-wrp {
    background: #ffffff !important;
    border: 1px solid rgba(148, 163, 184, 0.6) !important;
    border-radius: 0.75rem !important;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04) !important;
    transition: all 0.2s ease !important;
}

html:not(.dark) .fi-simple-main .fi-input-wrp:focus-within {
    border-color: #10b981 !important;
    box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.22), 0 1px 4px rgba(16, 185, 129, 0.15) !important;
}

html:not(.dark) .fi-simple-main .fi-input {
    color: #0f172a !important;
    font-weight: 500 !important;
}

/* Light Mode: Checkbox & Remember Me */
html:not(.dark) .fi-simple-main input[type="checkbox"] {
    border-color: #94a3b8 !important;
    accent-color: #10b981 !important;
}

/* Submit Button in Login Card */
.fi-simple-main button[type="submit"].fi-btn {
    background: linear-gradient(135deg, #059669 0%, #10b981 50%, #06b6d4 100%) !important;
    color: #ffffff !important;
    font-weight: 700 !important;
    font-size: 0.95rem !important;
    letter-spacing: 0.02em !important;
    border-radius: 0.75rem !important;
    padding: 0.7rem 1.5rem !important;
    box-shadow: 0 6px 20px -2px rgba(16, 185, 129, 0.45) !important;
    transition: all 0.25s ease !important;
}

.fi-simple-main button[type="submit"].fi-btn:hover {
    box-shadow: 0 8px 25px 0 rgba(16, 185, 129, 0.65), 0 0 15px rgba(6, 182, 212, 0.35) !important;
    transform: translateY(-1.5px) !important;
}

/* Dark Mode Fallback for Login Portal */
html.dark .fi-simple-layout {
    background-color: var(--theme-navy-950) !important;
    background-image: 
        radial-gradient(circle 800px at 50% 30%, rgba(16, 185, 129, 0.14) 0%, transparent 60%),
        radial-gradient(circle 600px at 85% 10%, rgba(6, 182, 212, 0.10) 0%, transparent 50%),
        radial-gradient(circle 700px at 15% 85%, rgba(30, 58, 138, 0.18) 0%, transparent 55%),
        linear-gradient(180deg, #040810 0%, #071120 50%, #040810 100%) !important;
}

html.dark .fi-simple-main {
    backdrop-filter: blur(24px) !important;
    background: linear-gradient(145deg, rgba(11, 23, 42, 0.92) 0%, rgba(6, 14, 26, 0.96) 100%) !important;
    border: 1px solid rgba(16, 185, 129, 0.25) !important;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.9), 0 0 40px -5px rgba(16, 185, 129, 0.2) !important;
}

html.dark .fi-simple-header-heading {
    color: #ffffff !important;
    text-shadow: 0 0 15px rgba(255, 255, 255, 0.2) !important;
}

html.dark .fi-fo-field-label,
html.dark .fi-fo-field-label-content {
    color: #f1f5f9 !important;
    font-weight: 600 !important;
}

html.dark .fi-simple-main .fi-input-wrp {
    background: rgba(8, 17, 30, 0.8) !important;
    border: 1px solid rgba(16, 185, 129, 0.25) !important;
}

html.dark .fi-simple-main .fi-input {
    color: #ffffff !important;
}

/* --- Sleek Cyberpunk Scrollbar --- */
::-webkit-scrollbar {
    width: 6px;
    height: 6px;
}

::-webkit-scrollbar-track {
    background: var(--theme-navy-950);
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(180deg, #059669 0%, #0369a1 100%);
    border-radius: 9999px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(180deg, #10b981 0%, #0284c7 100%);
}
</style>
