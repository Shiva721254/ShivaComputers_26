# Shiva Computers — Phase 1

A bilingual PHP 8.2+ MVC starter for ShivaComputers.nl. No Composer packages, JavaScript frameworks or database are required for this milestone. See `SPECIFICATION.md` for scope.

## Start locally

**Docker** (recommended):

```bash
docker compose up --build -d
```

Open http://localhost:8080/nl or http://localhost:8080/en. Stop with `docker compose down`.

**PHP CLI** (without Docker):

```bash
APP_ENV=development php -S localhost:8080 -t public public/index.php
```

On Windows PowerShell, run `$env:APP_ENV="development"` first, then the `php -S` command.

## Routes

- `/` redirects to `/nl`
- `/nl`, `/en`
- `/{lang}/services`, `/portfolio`, `/about`, `/contact`, `/privacy`

## Project structure

- `public/`: the only web-accessible folder (front controller, `.htaccess`, CSS, images)
- `src/`: router, translator, view renderer and controller
- `views/`: page templates and the shared layout
- `translations/`: Dutch and English dictionaries
- `data/projects.php`: portfolio projects
- `config/app.php`: site settings and contact details

## Before go-live

1. Fill in the contact details in `config/app.php`.
2. Review all text, project statements, `Almere` and the privacy notice.
3. Set `content_approved` to `true`. This removes the preview notice and allows search engines to index the pages.
4. Keep `APP_ENV` unset on the server, so errors and screenshot placeholders stay hidden.

## Deployment on STRATO

1. In the hosting panel verify the package's PHP version, SSL and available SSH/SFTP, and set PHP >= 8.2.
2. Preferred approach: configure the domain's **document root** (domain redirect / directory target) to the `public/` directory of the uploaded project. This keeps `src/`, `data/`, `views/`, and `translations/` outside the web root.
3. Upload project source using **SFTP**. Check that `public/.htaccess` is present and Apache URL rewriting works.
4. Test `/nl`, `/en`, portfolio links, and HTTPS, then enable the HTTPS redirect in `public/.htaccess`. If the web root cannot target `public/`, do **not** upload the entire project into a publicly served directory without restructuring and access restrictions.
5. Do not deploy unfinished privacy/contact content or placeholder previews as final production content.
6. GitHub Actions deployment is not configured in this initial phase; add it only after SSH/SFTP secrets, target directory and rollback strategy have been verified. Keep credentials in GitHub Secrets, not code.

## Notes

Project names and repository links were checked against the public repositories. Descriptions, images, ownership rights and production suitability require review before launch.
The contact page intentionally has no live form, to avoid an insecure or unconfigured form endpoint.
