# Project Instructions

MODX Revolution 3.0.0 — open-source PHP CMS (GPL-2.0+). Architecture: **core** (PHP backend, xPDO ORM, Smarty), **manager** (ExtJS 3 admin UI), **setup** (web installer), **_build** (tests, Grunt/Sass build). This file is the single source of truth for AI assistants (Cursor, Claude Code). Follow paths, conventions, and DO NOT rules below.

---

## Table of Contents

1. [Tech Stack](#tech-stack)
2. [Project Structure](#project-structure)
3. [Tools and Scripts](#tools-and-scripts)
4. [Configuration Files](#configuration-files)
5. [Architecture](#architecture)
6. [Code Style and Conventions](#code-style-and-conventions)
7. [Testing Strategy](#testing-strategy)
8. [CI/CD](#cicd)
9. [Contributions and Branching](#contributions-and-branching)
10. [Quick Reference](#quick-reference)

---

## Tech Stack

- **Backend:** PHP >= 8.1, xPDO ~3.1.0 (ORM), Smarty ^4.0 (templates), Flysystem ^2.0 (filesystem), Guzzle ^7.3 (HTTP), PHPMailer ^6.0
- **Frontend (Manager UI):** ExtJS 3 (vendor — DO NOT modify), Sass (dart-sass via Grunt), FontAwesome
- **Code quality:** PHP_CodeSniffer (PSR12 + PHPCompatibility), ESLint 8 (airbnb-base)
- **Testing:** PHPUnit (requires MySQL), CodeCov
- **CI:** GitHub Actions (3 workflows)

Tailwind CSS and modern frontend frameworks are **not** used; Manager UI is ExtJS 3 + Sass.

---

## Project Structure

```
revolution/
├── core/                      # CMS core
│   ├── src/Revolution/        # PSR-4 PHP source (MODX\Revolution\*)
│   │   ├── Processors/        # AJAX handlers
│   │   ├── Controllers/       # Manager controllers
│   │   ├── Sources/           # Media sources
│   │   ├── Transport/         # Package transport
│   │   ├── ...
│   │   └── modX.php           # Main CMS class
│   ├── model/schema/          # xPDO XML schemas (DB models)
│   ├── lexicon/               # i18n translations (en/, ru/, ...)
│   ├── vendor/                # Composer dependencies
│   ├── config/                # Runtime config
│   ├── cache/                 # Cache directory
│   └── packages/              # Installed extras
├── manager/                   # Admin panel
│   ├── assets/
│   │   ├── ext3/              # ExtJS 3 (VENDOR — DO NOT MODIFY)
│   │   ├── modext/            # MODX ExtJS widgets
│   │   │   ├── core/          # Core JS utilities
│   │   │   ├── widgets/       # UI widgets
│   │   │   ├── sections/      # Page sections
│   │   │   ├── workspace/    # Workspace management
│   │   │   └── util/          # Utilities
│   │   ├── fileapi/           # File upload (VENDOR)
│   │   └── lib/               # Third-party JS (VENDOR)
│   ├── controllers/default/   # PHP controllers for manager pages
│   └── templates/default/     # Manager HTML templates
├── setup/                     # Web installer
│   ├── controllers/
│   ├── processors/
│   ├── includes/
│   ├── templates/
│   └── lang/
├── _build/                    # Build tools, tests, packaging
│   ├── test/                  # PHPUnit tests
│   │   ├── phpunit.xml
│   │   ├── Tests/
│   │   └── Support/
│   ├── templates/default/    # Grunt + Sass build (manager UI)
│   │   ├── Gruntfile.js
│   │   ├── package.json
│   │   └── sass/
│   ├── data/
│   └── resolvers/
├── connectors/                # AJAX connector entry points
├── .github/workflows/         # CI pipelines
├── composer.json
├── package.json
├── phpcs.xml
├── .eslintrc.js
├── .editorconfig
└── AGENTS.md                  # This file
```

---

## Tools and Scripts

### Composer (from project root)

| Script                  | Description                          |
| ----------------------- | ------------------------------------ |
| `composer phpunit`      | Run PHPUnit with coverage            |
| `composer phpcs`        | PHP_CodeSniffer check                |
| `composer phpcbf`       | PHP_CodeSniffer auto-fix             |
| `composer parse-schema` | Generate PHP models from XML schemas |

### npm (from project root)

| Script            | Description                             |
| ----------------- | --------------------------------------- |
| `npm run js:lint` | ESLint manager/assets and setup/assets  |

### Grunt (from `_build/templates/default/`)

| Command        | Description                                                  |
| -------------- | ------------------------------------------------------------ |
| `grunt build`  | Full build: copy deps → sass → postcss → concat → terser    |
| `grunt`       | Watch mode (sass + postcss)                                  |
| `grunt expand`| Build without minification                                  |

---

## Configuration Files

- **composer.json:** autoload PSR-4 `MODX\` → `core/src/`, vendor-dir: `core/vendor`
- **phpcs.xml:** PSR12 + PHPCompatibility; excluded: `ext3/`, `lexicon/`, `*/vendor/*`, `*/mysql/*.php`
- **.eslintrc.js:** airbnb-base, globals: `MODx`, `Ext`, `_`, indent: 4, max-len: 140; ignored: `ext3/`, `fileapi/`, `lib/`
- **.editorconfig:** UTF-8, LF, 4 spaces (2 for SCSS), trim whitespace
- **phpunit.xml** (`_build/test/`): bootstrap MODxTestHarness.php, coverage: `core/src/`

---

## Architecture

- **Namespace:** `MODX\Revolution\*` (PSR-4 → `core/src/Revolution/`)
- **ORM:** xPDO — XML schema in `core/model/schema/` → generated PHP model classes in `*/mysql/` subdirs. **NEVER** edit `*/mysql/*.php` files manually; use `composer parse-schema` to regenerate.
- **Processors:** `core/src/Revolution/Processors/` — AJAX request handlers. Extend `modProcessor`, `modObjectGetListProcessor`, etc.
- **Controllers:** `core/src/Revolution/Controllers/` (PHP) and `manager/controllers/default/` (manager page controllers)
- **Manager UI:** ExtJS 3. Custom widgets in `manager/assets/modext/`. All JS uses globals: `MODx`, `Ext`.
- **Templating:** Smarty for frontend rendering
- **Lexicons:** `core/lexicon/{lang}/*.inc.php` — i18n. Only `en/` is edited in repo; other languages via Crowdin.
- **Media Sources:** `core/src/Revolution/Sources/` — filesystem abstraction via Flysystem

---

## Code Style and Conventions

- **PHP:** PSR-12 enforced via phpcs.xml. PSR-4 namespacing.
- **JavaScript:** ESLint airbnb-base. Indent 4, max-len 140. Globals: `MODx`, `Ext`, `_`. Prefer arrow functions where appropriate.
- **SCSS:** Indent 2 (per .editorconfig). Compiled via Grunt.
- **Encoding:** UTF-8, LF line endings, final newline, trim trailing whitespace.

### DO NOT modify

- `manager/assets/ext3/` — ExtJS 3 vendor files
- `manager/assets/fileapi/` — FileAPI vendor files
- `manager/assets/lib/` — third-party JS libraries
- `*/mysql/*.php` — auto-generated xPDO model files (use `composer parse-schema` to regenerate)
- `core/lexicon/{non-en}/` — non-English lexicons (managed via Crowdin)
- `core/vendor/` — Composer dependencies

---

## Testing Strategy

- **PHPUnit:** config at `_build/test/phpunit.xml`, tests in `_build/test/Tests/`
- **Requires:** running MySQL instance
- **Setup:** copy `_build/test/properties.sample.inc.php` → `properties.inc.php`, configure DSN
- **Run:** `composer phpunit` from project root
- **Test suites:** Setup, Model (Dashboard, Element, Error, FormCustomization, Filters, Lexicon, Hashing, Mail, Registry, Request, Resource, Security, Sources, Transport, Validation), Controllers, Processors, Transport, Cases, Teardown
- **PHPCS:** `composer phpcs` / `composer phpcbf`
- **ESLint:** `npm run js:lint`

---

## CI/CD

| Workflow     | Trigger                                          | Description                                     |
| ------------ | ------------------------------------------------ | ----------------------------------------------- |
| `ci.yml`     | push/PR (except l10n branches, _build/templates) | PHP 8.1–8.5 matrix, MySQL 5.6, PHPUnit, CodeCov |
| `assets.yml` | push/PR changing `_build/templates/**`           | Node 14, npm install, grunt build               |
| `phpcs.yml`  | PR with `**.php` changes                        | PHP_CodeSniffer                                 |

---

## Contributions and Branching

- CLA required for PRs
- `3.x` — development branch for next major version
- Bug fixes go to current stable minor branch
- English lexicons only in GitHub; other languages via Crowdin
- Security issues: report to security@modx.com, **not** in public issues

---

## Quick Reference

### Adding a new Processor

1. Create PHP class in `core/src/Revolution/Processors/{domain}/`
2. Extend appropriate base class (`modProcessor`, `modObjectGetListProcessor`, etc.)
3. Register connector route if needed

### Updating DB schema

1. Edit XML schema in `core/model/schema/modx.mysql.schema.xml`
2. Run `composer parse-schema` to regenerate model classes
3. **NEVER** edit `*/mysql/*.php` files directly

### Building Manager UI

1. `cd _build/templates/default && npm install`
2. `grunt build` — full build (Sass → CSS, JS concat + minify)
3. `grunt` — watch mode for development
