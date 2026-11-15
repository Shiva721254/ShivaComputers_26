# Shiva Computers Website

A bilingual PHP 8.2+ website for ShivaComputers.nl, built with layered MVC, Composer and FastRoute. See `docs/ARCHITECTURE.md` for the design and `SPECIFICATION.md` for scope.

## Start locally

Docker installs the Composer packages inside the image, so PHP is not needed on your computer.

```bash
docker compose up --build -d
```

Open http://localhost:8080/en or http://localhost:8080/nl. Stop with `docker compose down`. Rebuild with `--build` after changing `composer.json`.

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

- `/` redirects to `/en` (English is the default; Dutch is at `/nl`)
- `/nl`, `/en`
- `/{language}/services`, `/portfolio`, `/about`, `/contact`, `/privacy`, `/remote-support`
- `/hulp` and `/help` short links for remote support (see below)

Routes are defined in `config/routes.php`.

## Products: main menu and home page cards

The main menu shows the four products first, then Portfolio, About and Contact (Home is the logo). The home page shows the same four as cards below the hero. Both come from `src/Enums/Product.php`, so changing the order there changes both.

| # | Product | Link | Text keys |
|---|---|---|---|
| 1 | Software & websites | `/{language}/services` | `products.software.*`, `nav.product.software` |
| 2 | Shiva Rescue OS | `https://rescue.shivacomputers.nl/{language}` | `products.rescue.*`, `nav.product.rescue` |
| 3 | Shiva Assist | `https://assist.shivacomputers.nl/{language}` | `products.assist.*`, `nav.product.assist` |
| 4 | Remote help | `/{language}/remote-support` | `products.remote.*`, `nav.product.remote` |

The subdomain addresses are set under `product_sites` in `config/app.php`. Locally, point them at the Docker sites with `RESCUE_SITE_URL=http://localhost:8082` and `ASSIST_SITE_URL=http://localhost:8083` (plain `http://localhost` is accepted only when `APP_ENV` is `development`). Below 1100 px the menu becomes a "Menu" button (`<details>`, no JavaScript).

When `site.css` changes, raise the `?v=` number on the stylesheet link in `src/Views/Layouts/main.php`, so browsers load the new version.

## Remote support

`/{language}/remote-support` explains the remote computer help service. The sessions themselves run in the separate support portal (`shiva-remote-portal`, on the VPS at `remote.shivacomputers.nl`). This website only links to it, so it stays free of cookies, forms and a database.

| Setting `remote_support_url` in `config/app.php` | Page shows | `/hulp` and `/help` open |
|---|---|---|
| `null` (default) | A call button with the business phone number | The remote-support page |
| `'https://remote.shivacomputers.nl'` | An "Enter your code" button | The portal in Dutch or English |

Only an `https` address without a path is accepted, so the short links cannot be misused as an open redirect. Set it after the portal has passed its production gate. The environment variable `REMOTE_SUPPORT_URL` overrides the setting, for example `http://localhost:8081` when the portal lab runs next to this site.

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
