# Shiva Computers Website

A bilingual PHP 8.2+ website for ShivaComputers.nl, built with layered MVC, Composer and FastRoute. See `docs/ARCHITECTURE.md` for the design and `SPECIFICATION.md` for scope.

## Start locally

Docker installs the Composer packages inside the image, so PHP is not needed on your computer.

```bash
docker compose up --build -d
```

Open http://localhost:8080/nl or http://localhost:8080/en. Stop with `docker compose down`. Rebuild with `--build` after changing `composer.json`.

Port 8080 can only be used by one project at a time.

## Create or update composer.lock

Run this once, then commit `composer.lock`. It also creates `vendor/`, which Git ignores.

```powershell
docker run --rm -v "${PWD}:/app" composer:2 update --no-interaction --no-scripts --ignore-platform-reqs
```

## Run the tests

```bash
docker compose exec web vendor/bin/phpunit
```

## Routes

- `/` redirects to `/nl`
- `/nl`, `/en`
- `/{language}/services`, `/portfolio`, `/about`, `/contact`, `/privacy`

Routes are defined in `config/routes.php`.

## Before go-live

1. Review all contact details in `config/app.php`, all text, project statements and the privacy notice.
2. Set `content_approved` to `true`. This removes the preview notice and allows search engines to index the pages.
3. Keep `APP_ENV` unset on the server, so errors and screenshot placeholders stay hidden.

## Deployment on STRATO

1. In the hosting panel verify the package's PHP version, SSL and available SSH/SFTP, and set PHP >= 8.2.
2. Build production packages (no test tools):

   ```powershell
   docker run --rm -v "${PWD}:/app" composer:2 install --no-dev --optimize-autoloader --no-scripts --ignore-platform-reqs
   ```

3. Point the domain's **document root** at the project's `public/` directory. This keeps `src/`, `config/`, `data/`, `translations/` and `vendor/` outside the web root.
4. Upload the project, including `vendor/`, using **SFTP**. Check that `public/.htaccess` is present and URL rewriting works.
5. Test `/nl`, `/en`, portfolio links and HTTPS, then enable the HTTPS redirect in `public/.htaccess`. If the web root cannot target `public/`, do **not** upload the project into a publicly served directory without restructuring and access restrictions.
6. Do not deploy unfinished privacy/contact content or placeholder previews as final production content.
7. GitHub Actions deployment is not configured yet; add it only after SSH/SFTP secrets, target directory and rollback strategy have been verified. Keep credentials in GitHub Secrets, not code.

## Notes

Project names and repository links were checked against the public repositories. Descriptions, images, ownership rights and production suitability require review before launch.
The contact page intentionally has no live form, to avoid an insecure or unconfigured form endpoint.
