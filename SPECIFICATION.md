# Shiva Computers — Website Specification (Phase 1)

**Date:** 2026-11-03  
**Status:** Initial implementation foundation, not production-ready  
**Domain:** shivacomputers.nl  
**Host:** STRATO (existing Web Power Advance plan; verify package-level permissions)

## 1. Business positioning

Primary: software development, bespoke business applications, responsive web applications, professional websites and digital solutions. Secondary: registered computer maintenance/IT support; not foregrounded in the initial website. No fabricated client testimonials or project outcomes.

## 2. Audiences and conversions

Businesses, entrepreneurs and prospective software collaborators. Main visitor journeys: see services → inspect projects → contact; inspect GitHub work → contact.

## 3. Information architecture

Public routes have language prefixes `/nl` and `/en`: Home, Services, Portfolio, About, Contact, Privacy. Navigation and key copy translated in separate PHP dictionaries. Language switch retains the current route.

## 4. Visual system

Professional blue-and-white brand. Colors: navy #10233e, primary blue #1465df, light blue #eff6ff, background #f8fbff. System sans typography. Responsive grid, keyboard focus indicator, contrast-aware buttons, semantic landmarks.

## 5. Core features and acceptance

- **Routing:** clean SEO-friendly URLs with 404 on invalid page/language.
- **Bilingual:** correct `lang`, canonical and `hreflang` metadata; NL default.
- **Portfolio:** verified repo names stored in `data/projects.php`; GitHub links; screenshot placeholders only for local development.
- **Security:** web entry point in `public`; output escaping; no secrets committed; no incomplete contact handler.
- **Performance:** no external JavaScript, CDN assets, or tracking in Phase 1.
- **Accessibility:** semantic navigation/headings, mobile layout, keyboard-visible focus. Full accessibility audit pending.

## 5a. Architecture decision

The site uses layered MVC with Composer and FastRoute, replacing the earlier no-Composer rule. No external packages are loaded in the browser. Services and projects stay in PHP files behind repository interfaces, with no database yet. See `docs/ARCHITECTURE.md`.

## 6. Not yet implemented

Project-specific verified summaries, screenshots, business email, production privacy notice, cookie policy if required by later trackers, contact form + anti-spam + delivery, structured data, sitemap, robots, CMS, MySQL persistence, Google analytics, GitHub CI/CD and security testing.

## 7. STRATO hosting prerequisites

Verify PHP >= 8.2, rewrite support, mod_headers (optional), HTTPS, SFTP access, and domain-to-public document-root option on the subscribed plan. If public-directory mapping is restricted, adapt deployment layout rather than exposing application internals. Do not assume a paid plan includes all features on the current marketing page.

## 8. Milestones

1. Foundation and local preview (**this deliverable**)
2. Detailed content and repository inspection, project screenshots
3. Contact form with secure server-side validation and email delivery
4. Technical SEO, tests and accessibility fixes
5. STRATO staging, SSL, deployment process and go-live checklist

## 9. Proposed production acceptance criteria

All languages/pages load on desktop and mobile; no demo placeholders; published project statements verified; contact details and privacy policy approved; HTTPS enforced; no secret or private file accessible; backups/rollback documented; no broken portfolio links; clean 404 responses; user acceptance completed.
