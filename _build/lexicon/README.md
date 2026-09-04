# Core lexicon checker

Tooling for [issue #14512](https://github.com/modxcms/revolution/issues/14512).

## Run

Requires `_build/build.config.php` and `_build/build.properties.php` (see the sample files).

```bash
php _build/lexicon/checklexicon.php [language] [excludedFolders]
```

Default language is `en`.

## Reports

Generated files are gitignored (`_*.php` in this folder):

| File | Meaning |
|------|---------|
| `_missing.php` | Keys referenced in core but not defined |
| `_superfluous.php` | Keys defined but not found by static scan |
| `_variable.php` | Dynamic / concatenated key usage |
| `_duplicates_identical.php` | Same key + same value in multiple **core** topics |
| `_duplicates_conflict.php` | Same key with **different** values across **core** topics |

Cross-topic duplicate reports cover `core/lexicon/{lang}/` only. Setup language files are still scanned for missing/superfluous usage checks.

## Safety notes

- `_superfluous.php` is a **candidate list**, not an automatic delete list. Extras may call core lexicon keys that the scanner never sees.
- Prefer removing **identical** cross-topic duplicates first (keep the copy in `default` when the manager always loads that topic).
- Resolve `_duplicates_conflict.php` carefully: conflicting values usually mean a key is overloaded and should be renamed, not deleted.
- The CLI exits `0` after writing reports; use the report files (especially `_duplicates_conflict.php`) for review gates.
