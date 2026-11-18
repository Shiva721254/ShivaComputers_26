# shiva-nav: the shared Shiva Computers menu

One top bar for all three sites, so visitors see the same menu everywhere:

| Site | Folder on STRATO | Current-page keys it passes |
|---|---|---|
| shivacomputers.nl | `shivacomputers-live` | `home`, `services`, `portfolio`, `remote`, `about`, `contact` |
| rescue.shivacomputers.nl | `rescue-site` | `rescue` |
| assist.shivacomputers.nl | `assist-site` | `assist` |

```
Home | Software & websites ▾ | Computer help ▾ | About | Contact | language
         ├ Services             ├ Shiva Rescue OS
         └ Portfolio            ├ Shiva Assist
                                └ Remote help
```

## Files (identical in every site)

| File | Purpose |
|---|---|
| `shiva-nav/menu.php` | The menu: items, groups, labels and descriptions in English and Dutch |
| `shiva-nav/Navigation.php` | Builds links for one site and language; links to the same site stay relative |
| `shiva-nav/header.php` | The top bar markup. Dropdowns are `<details>`, so no JavaScript is needed |
| `public/assets/shiva-nav/shiva-nav.css` | Styles, all prefixed `snav-` so they never clash with the site |
| `public/assets/shiva-nav/logo.svg` | The Shiva Computers logo |

## Changing the menu

1. Edit the files in **ShivaComputers_26** (the source of truth).
2. Copy `shiva-nav/` and `public/assets/shiva-nav/` to `assist-site` and `rescue-site`, replacing the old copies.
3. If the change is visible, raise the `?v=` number where each site links `shiva-nav.css`.
4. Run the tests in all three sites. Each has tests for its menu, so a missing copy shows up.

Raise `Navigation::VERSION` for every change, so you can see at a glance (in the page source, `data-snav-version`) whether all three sites run the same version.

## Using it in a site

```php
require_once $root . '/shiva-nav/Navigation.php';

$navigation = new ShivaNav\Navigation(
    ['main' => 'https://shivacomputers.nl', 'rescue' => 'https://rescue.shivacomputers.nl', 'assist' => 'https://assist.shivacomputers.nl'],
    'assist',                     // this site
    $isDevelopment,               // allows http://localhost addresses for local Docker
);
$shivaNav = $navigation->build($language, 'assist', $languageLinks);
require $root . '/shiva-nav/header.php';
```

`$languageLinks` lists the current page in the other languages: `[['code' => 'nl', 'name' => 'Nederlands', 'title' => 'Bekijk deze pagina in het Nederlands', 'url' => '/nl/pricing']]`. One language gives a link; two or more give a dropdown.
