# Architecture

## Concept

Each layer has one job and only talks to the layer below it. A change in one layer should not force changes in the others.

```text
Request
  → public/index.php          Front controller: the only entry point
  → Middleware                Security headers (later: CSRF)
  → Router (FastRoute)        URL → controller method
  → Controller                Handles the request and chooses the page
  → Service                   Business rules
  → Repository                Gets the data
  → data/*.php, config        Data source today (MySQL later)
  ← Models                    Typed data objects
  ← ViewModel + View          Prepares and renders HTML
  ← Response                  Status, headers and body
```

## Folders

| Folder | Responsibility | Example |
|---|---|---|
| `config/` | Settings, routes and object wiring | `routes.php`, `services.php` |
| `src/Framework/` | Reusable infrastructure | `Router`, `View`, `Container` |
| `src/Middleware/` | Runs around every request | `SecurityHeadersMiddleware` |
| `src/Controllers/` | HTTP handling, no business rules | `PortfolioController` |
| `src/Services/` | Business rules | `ProjectService::getFeatured()` |
| `src/Repositories/` | Data access | `FileProjectRepository` |
| `src/Models/` | Data objects | `Project` |
| `src/ViewModels/` | Data prepared for a view | `PageViewModel` |
| `src/Enums/` | Fixed sets of values | `Language`, `Page` |
| `src/Views/` | HTML only | `Portfolio/index.php` |

## Example: `/en/portfolio`

1. FastRoute matches the URL to `PortfolioController::index` and passes `language = en`.
2. The controller asks `IProjectService` for the projects.
3. `ProjectService` asks `IProjectRepository`.
4. `FileProjectRepository` reads `data/projects.php` and returns English `Project` models.
5. The controller renders `Portfolio/index` inside `Layouts/main` and returns a `Response`.

## Key decisions

- **Dependency injection:** classes receive their dependencies through the constructor instead of creating them with `new`. `config/services.php` is the one place that connects them.
- **Interfaces:** a controller depends on `IProjectService`, not a specific class. Tests can use a fake repository, and MySQL can be added by writing `MySqlProjectRepository` and changing one line in `config/services.php`.
- **Responses:** controllers return a `Response` instead of echoing, so tests can check the status, headers and HTML.
- **Enums:** `Language` and `Page` replace loose strings, so PHP rejects invalid values.

## Adding a page

1. Add a case to `src/Enums/Page.php`.
2. Add a route to `config/routes.php`.
3. Create the controller and register it in `config/services.php`.
4. Create the view in `src/Views/`.
5. Add its text to both `translations/nl.php` and `translations/en.php`.
6. Add a test in `tests/`.
