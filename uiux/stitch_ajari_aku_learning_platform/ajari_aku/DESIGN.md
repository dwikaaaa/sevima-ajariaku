---
name: Ajari Aku
colors:
  surface: '#e9fef8'
  surface-dim: '#caded9'
  surface-bright: '#e9fef8'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#e3f8f3'
  surface-container: '#def2ed'
  surface-container-high: '#d8ede7'
  surface-container-highest: '#d2e7e2'
  on-surface: '#0d1f1c'
  on-surface-variant: '#404846'
  inverse-surface: '#223430'
  inverse-on-surface: '#e1f5f0'
  outline: '#717976'
  outline-variant: '#c0c8c4'
  surface-tint: '#3c665c'
  primary: '#002821'
  on-primary: '#ffffff'
  primary-container: '#123f36'
  on-primary-container: '#7eaa9e'
  inverse-primary: '#a3d0c3'
  secondary: '#006d3d'
  on-secondary: '#ffffff'
  secondary-container: '#97f3b5'
  on-secondary-container: '#047240'
  tertiary: '#331e00'
  on-tertiary: '#ffffff'
  tertiary-container: '#503100'
  on-tertiary-container: '#dd910e'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#beecdf'
  primary-fixed-dim: '#a3d0c3'
  on-primary-fixed: '#00201a'
  on-primary-fixed-variant: '#234e45'
  secondary-fixed: '#9af6b8'
  secondary-fixed-dim: '#7ed99e'
  on-secondary-fixed: '#00210f'
  on-secondary-fixed-variant: '#00522d'
  tertiary-fixed: '#ffddb6'
  tertiary-fixed-dim: '#ffb959'
  on-tertiary-fixed: '#2a1800'
  on-tertiary-fixed-variant: '#643f00'
  background: '#e9fef8'
  on-background: '#0d1f1c'
  surface-variant: '#d2e7e2'
typography:
  headline-xl:
    fontFamily: Plus Jakarta Sans
    fontSize: 40px
    fontWeight: '800'
    lineHeight: 52px
    letterSpacing: -0.02em
  headline-xl-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 30px
    fontWeight: '800'
    lineHeight: 38px
    letterSpacing: -0.015em
  headline-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.01em
  headline-lg-mobile:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '700'
    lineHeight: 32px
    letterSpacing: -0.01em
  headline-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 22px
    fontWeight: '600'
    lineHeight: 30px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '600'
    lineHeight: 26px
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 17px
    fontWeight: '400'
    lineHeight: 26px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 15px
    fontWeight: '400'
    lineHeight: 24px
  body-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 13px
    fontWeight: '400'
    lineHeight: 20px
  label-lg:
    fontFamily: Inter
    fontSize: 15px
    fontWeight: '600'
    lineHeight: 22px
    letterSpacing: 0.01em
  label-md:
    fontFamily: Inter
    fontSize: 13px
    fontWeight: '600'
    lineHeight: 18px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Inter
    fontSize: 11px
    fontWeight: '700'
    lineHeight: 16px
    letterSpacing: 0.04em
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  gutter: 1rem
  gutter-tablet: 1.5rem
  gutter-desktop: 2rem
  margin: 1rem
  margin-tablet: 2rem
  margin-desktop: 3rem
  space-xs: 0.25rem
  space-sm: 0.5rem
  space-md: 1rem
  space-lg: 1.5rem
  space-xl: 2.25rem
---

## Brand & Style

This design system is tailored for an educational volunteer mentoring platform bridging the gap between remote, rural learners and passionate volunteer educators across the archipelago. The visual style embodies an **approachable, optimistic, and radically accessible modern minimalism** infused with organic warmth.

### Emotional Response & Personality
- **Nurturing & Trustworthy:** Grounded, calm tones evoke stability, safety, and community trust.
- **Empowering & Friendly:** Warm visual cues and inviting rounded geometry make digital interaction unintimidating for digital newcomers.
- **Dignified & Purposeful:** Avoids condescending or overly childish motifs; centers on respect, mutual growth, and intellectual curiosity.

### Design Principles
- **Low Cognitive Load:** Clear visual pathways, concise single-action focus, unambiguous labeling, and zero visual clutter.
- **Radical Accessibility:** Explicit AAA-compliant high-contrast pairings, oversized tap surfaces, and multi-modal feedback (combining iconography, text, and color cues).
- **Network & Low-Tier Device Empathy:** Lightweight vector-based visual cues, zero reliance on heavy blurs or graphic textures, and crystal-clear structural separation.

## Colors

The palette establishes an organic, nature-inspired foundation of deep forest pine with lively mints and joyful, sunlit amber highlights.

### Color Roles
- **Primary (`#123F36` - Forest Pine):** Anchors core actions, structural navigation, prominent headers, and high-impact containers. Provides high-contrast stability.
- **Secondary (`#2E8B57` - Emerald Sea Green):** Applied to active states, progress indicators, connectivity statuses, and affirmative actions. Paired with soft tint backgrounds (`#E8F5E9` - Mint Soft) for interactive chips and tags.
- **Tertiary (`#E59819` - Warm Honey Amber):** Reserved for badges, mentoring streaks, certificates, ratings, and student achievements. Inspires optimism without triggering warning or error connotations.
- **Neutral Foreground (`#0D1F1C` - Deep Evergreen Ink):** Supreme-contrast dark text replacement for harsh black, retaining maximum legibility across lower-tier display panels.
- **Neutral Surfaces (`#FFFFFF` and `#F8FAF9`):** Pure white for focal cards and primary sheets; soft organic off-white for wide canvases and backdrops to alleviate eye strain in long reading sessions.
- **Borders & Dividers (`#E0E7E4`):** Soft, tinted outlines that cleanly separate modules without high visual noise.

## Typography

The type system balances the welcoming, human geometry of **Plus Jakarta Sans** for headlines and instructional body text with the ultra-clear, systematic legibility of **Inter** for compact metadata, statuses, and badges.

### Typographic Hierarchy Rules
- Headlines maintain tight line heights and balanced negative letter-spacing for concise, scannable directives.
- Body text retains relaxed leading (`1.6x` line-height on `body-md` and `body-lg`) to facilitate easy parsing for emerging readers and students on low-resolution displays.
- Minimum interactive text size on mobile surfaces is bounded to `13px` (`label-md` or `body-sm`) to ensure effortless comprehension.

## Layout & Spacing

A 4-column layout is used on mobile, moving to an 8-column layout on tablet, and a 12-column layout on desktop.

### Layout Rules
- **Touch Priority:** All interactive elements must maintain a minimum target area of 48px by 48px on touch viewports, isolated by at least `space-sm` from adjacent click paths.
- **Section Rhythm:** Canvas sections feature generous outer margins to isolate focal modules. Content reflow prioritizes stacked single-column layouts on mobile viewports under 600px width.

## Elevation & Depth

This design system favors **crisp, clean boundaries over deep drop shadows**, avoiding dark artificial blurs that can distort under direct sunlight on budget mobile screens.

### Depth Tiers
- **Flat Tier (0dp):** The foundation canvas (`#F8FAF9`) relies on subtle 1px outlines (`#E0E7E4`) rather than shadows to delineate boundaries.
- **Raised Tier (Level 1):** Interactive cards and floating panels use `#FFFFFF` backgrounds bordered with a crisp `1px solid #E0E7E4` and an extremely diffused, tinted ambient lift: `0 2px 8px rgba(18, 63, 54, 0.05)`.
- **Overlay & Navigation Tier (Level 2):** Floating Action Buttons (FAB), sticky learning session bars, and modal dialogs utilize an elevated anchor: `0 8px 24px rgba(18, 63, 54, 0.12)`.

## Shapes

The shape architecture relies on friendly, curved geometry that softens the visual weight of dense educational content. 

### Corner Radius Guidelines
- **Controls & Buttons:** 0.75rem (`12px`) to 1rem (`16px`) corners, generating rounded, friendly tap targets.
- **Cards & Modals:** 1rem (`rounded-lg`) to 1.5rem (`rounded-xl`), creating distinct, pillowy modules that visually separate mentoring content chunks.
- **Status Chips & Profile Badges:** Fully rounded pill-shapes (`9999px`) to distinguish tags from tapable rectangular components.

## Components

### Buttons
- **Primary:** Forest Pine (`#123F36`) solid background with pure white typography (`#FFFFFF`), bold weight, and a minimum height of 48px (56px for main onboarding tasks). Hover/Active transitions darken to `#0A2822`.
- **Secondary:** Mint Soft tint (`#E8F5E9`) with dark Forest Pine text (`#123F36`) and a subtle 1px border (`#C8E6C9`).
- **Accent (Celebration / Action):** Warm Honey Amber (`#E59819`) solid background with dark ink text (`#0D1F1C`) for session starts, schedule confirmations, and milestone claims.

### Form Inputs & Text Fields
- Large 48px height with a solid white interior, rounded 12px corners, and a 1.5px `#E0E7E4` border.
- Floating helper hints positioned clearly above fields to assist users unfamiliar with digital web forms.
- On focus: Border sharpens to 2px `#2E8B57` with an ambient mint glow outline (`rgba(46, 139, 87, 0.15)`).

### Chips & Badges
- Pill-shaped (`rounded-full`) tags displaying subject categories, student grades, and mentor specialties.
- Subjects: `#E8F5E9` background with `#123F36` text.
- Achievement / Streak Badges: `#FEF7E6` background with `#E59819` text and a small leading icon.

### Cards (Mentorship & Classroom Modules)
- White container (`#FFFFFF`) with 16px to 24px inner padding, `rounded-xl` corners, and a clean 1px border (`#E0E7E4`).
- Header areas integrate high-contrast titles with teacher profile avatars, complemented by prominent badges indicating internet bandwidth compatibility (e.g., "Audio Only / Low Data Mode").

### Checkboxes & Radios
- Enlarged 24x24px dimensions with a generous 48x48px tap frame.
- Unchecked: 2px border in `#789088`. Checked: Solid `#123F36` fill housing a crisp white checkmark.

### Additional Product Components
- **Low-Bandwidth Toggle Banner:** A persistent, high-visibility banner informing rural learners if they are running in lite/low-bandwidth mode with data-saving indicators.
- **Schedule Confirmation Drawer:** A simplified slide-up drawer with clear daytime/clock visual indicators, making lesson times easily understandable across different regional time zones.