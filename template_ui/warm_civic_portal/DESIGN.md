---
name: Warm Civic Portal
colors:
  surface: '#f7f9fb'
  surface-dim: '#d8dadc'
  surface-bright: '#f7f9fb'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f2f4f6'
  surface-container: '#eceef0'
  surface-container-high: '#e6e8ea'
  surface-container-highest: '#e0e3e5'
  on-surface: '#191c1e'
  on-surface-variant: '#3d4947'
  inverse-surface: '#2d3133'
  inverse-on-surface: '#eff1f3'
  outline: '#6d7a77'
  outline-variant: '#bcc9c6'
  surface-tint: '#006a61'
  primary: '#00685f'
  on-primary: '#ffffff'
  primary-container: '#008378'
  on-primary-container: '#f4fffc'
  inverse-primary: '#6bd8cb'
  secondary: '#565e74'
  on-secondary: '#ffffff'
  secondary-container: '#dae2fd'
  on-secondary-container: '#5c647a'
  tertiary: '#8d4b00'
  on-tertiary: '#ffffff'
  tertiary-container: '#b15f00'
  on-tertiary-container: '#fffbff'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#89f5e7'
  primary-fixed-dim: '#6bd8cb'
  on-primary-fixed: '#00201d'
  on-primary-fixed-variant: '#005049'
  secondary-fixed: '#dae2fd'
  secondary-fixed-dim: '#bec6e0'
  on-secondary-fixed: '#131b2e'
  on-secondary-fixed-variant: '#3f465c'
  tertiary-fixed: '#ffdcc3'
  tertiary-fixed-dim: '#ffb77d'
  on-tertiary-fixed: '#2f1500'
  on-tertiary-fixed-variant: '#6e3900'
  background: '#f7f9fb'
  on-background: '#191c1e'
  surface-variant: '#e0e3e5'
typography:
  display-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '700'
    lineHeight: 48px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 28px
    fontWeight: '700'
    lineHeight: 36px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 20px
    fontWeight: '600'
    lineHeight: 28px
  title-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 26px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 26px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '400'
    lineHeight: 22px
  label-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '600'
    lineHeight: 24px
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '600'
    lineHeight: 20px
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 18px
    letterSpacing: 0.02em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-desktop: 1.5rem
  margin: 1rem
  margin-tablet: 2rem
  margin-desktop: 3rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2rem
  space-2xl: 3rem
---

## Brand & Style

This design system establishes a warm, dignified, and approachable public service portal for the citizens of Kabupaten Blitar. The interface departs decisively from the cold, intimidating bureaucracy of traditional e-government portals, while intentionally avoiding playful tech-startup gimmicks.

The design movement combines **Civic Modernism** with human-centered warmth:
- **Atmosphere:** Reassuring, welcoming, transparent, and authoritative.
- **Visual Voice:** Orderly civic clarity paired with human-scale warmth. Information hierarchy is crystal clear to empower citizens under emotional or socio-economic stress (e.g., social aid recipients, persons with disabilities, elder citizens, and rural families).
- **Core Principles:** Dignity in public service, radical legibility, uncompromised accessibility (WCAG 2.1 AA/AAA compliance), and high-trust clarity across all socioeconomic backgrounds and mobile devices.

## Colors

The palette balances institutional integrity with a caring, community-oriented warmth.

- **Primary Teal / Emerald (`#0D9488` with `#0F766E` deep state):** Evokes health, renewal, public welfare, and communal balance. Used for primary CTAs, active status tabs, and positive structural accents.
- **Secondary Deep Navy Slate (`#0F172A`):** Anchors typographic hierarchy, institutional headers, navigational chrome, and structural dividers. Provides unmistakable authority without the harshness of pure black.
- **Accent Warm Amber / Gold (`#D97706` base, `#F59E0B` highlight):** Reserved for important civic notices, status updates needing attention (e.g., "Menunggu Verifikasi"), and secondary callouts. It delivers urgency without creating panic.
- **Neutral Canvas (`#F8FAFC` base surface, `#F1F5F9` nested surface):** A clean, warm off-white canvas that softens screen glare for low-cost displays and outdoor visibility.
- **Semantic Feedback:**
  - **Success (`#059669` / `#10B981`):** Verified data, approved aid allocations, active program cards.
  - **Danger / Urgent (`#DC2626` / `#EF4444`):** Verification rejected, missed deadlines, emergency social reports.
  - **Information (`#0284C7`):** General regulations, operational hours, requirements guidance.

Contrast ratios adhere strictly to a minimum of 4.5:1 for standard text and 3:1 for graphical elements and large headings against background surfaces.

## Typography

The design system uses **Plus Jakarta Sans** uniformly across display, body, and label roles. Its geometric underpinnings provide modern clarity, while humanist curves inject warmth and ease reading fatigue on handheld devices.

- **Baseline Body Size:** The minimum standard body copy is strictly 16px (`body-md`) with a relaxed line height of 26px to accommodate varied literacy levels and older eyes.
- **Small Text Guardrail:** The 12px token (`label-sm`) is strictly reserved for assistive captions, metadata, or timestamps and must always be paired with high-contrast text (`#475569` or darker).
- **Numerals:** Tabular figures are used for verification codes (NIK, No. KK), tracking numbers, and financial assistance amounts to ensure flawless scanning.

## Layout & Spacing

A mobile-first fluid grid prioritizes ergonomic one-handed interactions:

- **Mobile (< 640px):** 4-column layout with `1rem` (16px) margins and gutters. Key actions are docked to bottom action sheets or prominent inline cards within reach of the thumb zone.
- **Tablet (640px – 1024px):** 8-column layout with `2rem` outer margins. Service directories shift into a 2-column card orientation.
- **Desktop (> 1024px):** 12-column layout capped at a maximum container width of `1200px` to maintain comfortable eye-tracking lengths for civic text and instructions.

The vertical rhythm relies on an 8pt base grid. Margins between major sections use `space-2xl` (48px) to provide clear visual pauses between service categories, application steps, and verified announcements.

## Elevation & Depth

Visual depth is communicated through clean surface-layer separation and ambient, warm-tinted shadows:

- **Surface Tiers:**
  - `Base`: `#F8FAFC` (Canvas background)
  - `Surface Level 1`: `#FFFFFF` (Standard cards, service items, search bars)
  - `Surface Level 2`: `#F1F5F9` (Nested modules, informational callouts, table headers)
  - `Surface Highlight`: `#F0FDFA` (Active selections, verified states, teal tint)

- **Ambient Shadows:**
  - `Elevation 1 (Resting Cards)`: `0px 1px 3px rgba(15, 23, 42, 0.05), 0px 1px 2px rgba(15, 23, 42, 0.03)` with a subtle perimeter border (`1px solid #E2E8F0`).
  - `Elevation 2 (Interactive Hover / Priority Cards)`: `0px 4px 6px -1px rgba(15, 23, 42, 0.07), 0px 2px 4px -2px rgba(15, 23, 42, 0.05)`.
  - `Elevation 3 (Sticky Navbars, Modals & Sheets)`: `0px 10px 15px -3px rgba(15, 23, 42, 0.08), 0px 4px 6px -4px rgba(15, 23, 42, 0.03)`.

## Shapes

The interface balances soft civic approachability with structured administrative order:

- **Containers & Major Cards:** Use `rounded-xl` (16px) to frame service groups and verification steps gently.
- **Feature Cards & Hero Banners:** Use `rounded-2xl` (24px) for distinct landing components, social program announcements, and priority banners.
- **Interactive Controls (Inputs, Buttons, Dropdowns):** Built with `rounded-md` to `rounded-lg` (8px to 12px) to preserve distinct button affordance and contrast against rounded card frames.
- **Badges & Status Chips:** Full pill radius (`rounded-full`) to delineate metadata distinctly from rectangular interactive elements.

## Components

### Buttons & Touch Targets
- **Dimensions:** Strict minimum touch target height of 48px across all clickable surfaces (primary buttons, icon triggers, list carets).
- **Primary Action:** Solid Teal (`#0D9488`) with high-contrast white text (`#FFFFFF`), bold label typography, and focus ring with 3px `#99F6E4` offset. Hover state shifts to `#0F766E`.
- **Secondary Action:** White background with `#0F172A` text, bordered by `#CBD5E1`. Hover initiates `#F8FAFC`.
- **Emergency / Report Action:** Solid Crimson (`#DC2626`) reserved strictly for urgent social complaints or crisis interventions.

### Cards
- Constructed with `#FFFFFF` background, a 1px border of `#E2E8F0`, and `Elevation 1`.
- Top padding and internal spacing are kept at a generous 20px–24px.
- Status cards (e.g., "Status Bantuan PKH") include a 4px left-hand border accent matching the status color (Teal, Amber, or Emerald).

### Accessible Status Badges
- **Visual Rule:** Never rely on color alone. Every badge must contain both an SVG indicator icon and a clear text label.
- **Pill Format:**
  - *Diproses (In Review):* Amber background (`#FEF3C7`), text `#92400E`, accompanied by a clock icon.
  - *Disetujui (Approved):* Emerald background (`#D1FAE5`), text `#065F46`, accompanied by a check-circle icon.
  - *Ditolak / Perlu Perbaikan:* Rose background (`#FFE4E6`), text `#9F1239`, accompanied by an alert-circle icon.

### Form Inputs & Selectors
- Minimum input height of 50px with a persistent 16px body text size to prevent iOS auto-zoom behavior.
- Floating or distinct top-aligned labels (`label-md`) with explicit required indicators (`* Wajib`).
- High-visibility focus state: 2px border in `#0D9488` with a 3px soft teal glow outline.
- Clear error messages placed directly beneath the affected field, paired with an inline error icon.

### Lists & Service Directories
- Bordered row dividers with 16px vertical padding.
- Leading icon containers enclosed in soft-tinted squares (40x40px, rounded-lg) with high-contrast iconography.
- Trailing chevron indicators to signify navigable detail routes.

### NIK & Identity Search Verification Bar
- Specialized input component with clear segment formatting for 16-digit NIK inputs.
- Integrated search button, visual privacy toggle (masking NIK numbers), and inline help text reminding citizens of data confidentiality (UU PDP compliant).