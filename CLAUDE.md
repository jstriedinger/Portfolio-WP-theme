# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Commands

```bash
npm run dev    # Webpack watch + BrowserSync at http://127.0.0.1:3000 (proxies https://jstriedinger.local/)
npm run prod   # Production Webpack build
npm run scss   # Standalone SCSS watch (rarely needed — webpack dev covers this)
```

No manual refresh needed during dev — BrowserSync auto-reloads on any `.php` file change.

## Architecture

This is a custom WordPress theme. There is no separate plugin — all shortcodes, AJAX handlers, and enqueue logic live in `functions.php`.

### Build pipeline

- Entry: `src/js/app.js` + `src/sass/index.scss`
- Output: `assets/js/main.js` + `assets/main.css`
- Bulma is imported selectively in `src/sass/index.scss` (only needed components) with CSS variable overrides via `@forward ... with ()`
- Fonts are copied from `src/fonts/` → `assets/webfonts/`
- jQuery is loaded externally (CDN) and declared as a webpack external — do not bundle it

### Custom post type: `project`

Filtered by WordPress tags (not categories). All metadata comes from ACF (`get_fields()`). ACF field definitions are version-controlled in `acf-json/`.

**`preview` group** (drives the masonry grid card):
- `preview_video` — mp4 URL; plays on hover (`has-video`) or auto-plays (`video_only`)
- `preview_role`, `preview_tech` — overlay text; `preview_tech` is a multi-select of devicon slugs (`unity-plain`, `unrealengine-original`, `javascript-plain`, `php-plain`)
- `grid_width` — integer 1–10 mapping to CSS `grid-span-{n}` class (widescreen only; default 4 columns out of 12)
- `external_link` — overrides the card `<a>` href
- `without_link` — renders card with no anchor at all

**Single project page** (`single-project.php`) fields: `summary`, `contributions`, `links` (repeater of platform URLs — Steam/Epic/itch.io detected by URL string), `video_trailer` (oEmbed), `gif_preview`.

### Masonry grid

`[projects_grid]` shortcode renders `.masonry-grid.projects-grid` in PHP (`functions.php`). The JS in `app.js` (`initMasonry`) takes over layout for browsers without native CSS Grid masonry support:

- Mobile: 1 column
- Tablet/Desktop: 2 columns
- Widescreen (≥1150px): 12-column grid; each item spans `data-columns` columns (from `grid_width` ACF field, default 4)
- `featured-first` class: forces first two items to span 6 columns each (50/50)
- `same-height-first` class: equalises height of the first two items using image aspect ratio

Browsers with native `grid-template-rows: masonry` support skip the JS layout entirely — CSS handles it via `@supports`.

### JS initialisation flow (`src/js/app.js`)

1. `DOMContentLoaded` → `initLoadingScreen()`
2. Loading screen waits for all `<img>` to load (5s safety timeout)
3. GSAP fades out loading overlay, then calls `startMainAnimations()`
4. `startMainAnimations()` runs entrance animations, then calls `initMasonry()`, `initVideoHover()`, `initCircularText()`

### SCSS structure

- `src/sass/index.scss` — main entry; imports Bulma selectively, Font Awesome, Google Fonts, then local partials
- `src/sass/_variables.scss` — palette, Bulma overrides
- `src/sass/_tipography.scss` — custom font faces (Canela, LDW)
- `src/sass/_elements.scss` — component styles
- `src/sass/_base.scss` — base/reset overrides

### Shortcodes

| Shortcode | Key attributes |
|---|---|
| `[projects_grid]` | `tag`, `posts_per_page`, `featured_first`, `same_height_first` |
| `[blog_posts]` | `posts_per_page`, `orderby`, `order`, `category` |

### AJAX

`get_projects` action (used by legacy JS grid, still wired up) — nonce-protected, accepts `category` (tag slug or `0` for all).
