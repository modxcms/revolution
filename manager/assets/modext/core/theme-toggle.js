/**
 * Manager theme switcher (light/dark/system).
 *
 * The effective theme is applied as early as possible by an inline script in
 * header.tpl (before any CSS is requested) to avoid a flash of the wrong
 * theme. This script only wires up the popover UI, keeps it in sync with the
 * resolved theme, listens for OS-level changes while in "system" mode, and
 * persists the user's preference (mode, not resolved theme) via the
 * Security/Profile/UpdateTheme processor.
 */
(function () {
    const STORAGE_KEY = 'modx_manager_theme_mode';
    const ALLOWED = ['light', 'dark', 'system'];
    const ROOT_SELECTOR = '#modx-theme-toggle';

    let systemChangeHandler = null;
    let saveInFlight = false;
    let pendingMode = null;

    function normalizeMode(mode) {
        return ALLOWED.indexOf(mode) !== -1 ? mode : 'light';
    }

    function prefersColorSchemeDark() {
        return !!(window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches);
    }

    function resolveTheme(mode) {
        return mode === 'system' ? (prefersColorSchemeDark() ? 'dark' : 'light') : mode;
    }

    function getRoot() {
        return document.querySelector(ROOT_SELECTOR);
    }

    function getCurrentMode() {
        const root = getRoot();
        return normalizeMode(root ? root.getAttribute('data-theme-mode') : 'light');
    }

    function applyTheme(mode) {
        const normalized = normalizeMode(mode);
        const resolved = resolveTheme(normalized);
        const html = document.documentElement;
        const root = getRoot();

        html.setAttribute('data-theme', resolved);
        if (root) {
            root.setAttribute('data-theme-mode', normalized);
        }

        try {
            window.localStorage.setItem(STORAGE_KEY, normalized);
        } catch (e) {
            /* localStorage unavailable (private mode, quota) - not fatal */
        }

        updateMenuState(normalized);
        updateTriggerIcon(resolved);
        watchSystemPreference(normalized);
    }

    function updateTriggerIcon(resolvedTheme) {
        const root = getRoot();
        if (!root) {
            return;
        }
        const sunIcon = root.querySelector('[data-theme-icon="light"]');
        const moonIcon = root.querySelector('[data-theme-icon="dark"]');
        if (sunIcon && moonIcon) {
            const showMoon = resolvedTheme === 'dark';
            moonIcon.style.display = showMoon ? '' : 'none';
            sunIcon.style.display = showMoon ? 'none' : '';
        }
    }

    function updateMenuState(mode) {
        const root = getRoot();
        if (!root) {
            return;
        }
        const items = root.querySelectorAll('[data-theme-option]');
        items.forEach((item) => {
            const isSelected = item.getAttribute('data-theme-option') === mode;
            item.setAttribute('aria-checked', isSelected ? 'true' : 'false');
            item.classList.toggle('is-selected', isSelected);
        });
    }

    function watchSystemPreference(mode) {
        if (!window.matchMedia) {
            return;
        }
        const mq = window.matchMedia('(prefers-color-scheme: dark)');

        if (systemChangeHandler) {
            if (mq.removeEventListener) {
                mq.removeEventListener('change', systemChangeHandler);
            } else if (mq.removeListener) {
                mq.removeListener(systemChangeHandler);
            }
            systemChangeHandler = null;
        }

        if (mode !== 'system') {
            return;
        }

        systemChangeHandler = function () {
            applyTheme('system');
        };
        if (mq.addEventListener) {
            mq.addEventListener('change', systemChangeHandler);
        } else if (mq.addListener) {
            mq.addListener(systemChangeHandler);
        }
    }

    function getConnectorUrl() {
        if (typeof MODx === 'undefined' || !MODx.config) {
            return '';
        }
        return MODx.config.connector_url || MODx.config.connectors_url || '';
    }

    function saveThemePreference(mode, callback, isRetry) {
        const connectorUrl = getConnectorUrl();
        const authToken = (typeof MODx !== 'undefined' && MODx.siteId) ? MODx.siteId : '';

        if (!connectorUrl) {
            callback(false);
            return;
        }

        if (!authToken) {
            /* MODx.siteId is assigned inside the Ext.onReady callback in
               registerBaseScripts(); on a very fast first click it may not be
               set yet. Give it one short retry before giving up. */
            if (isRetry) {
                callback(false);
                return;
            }
            window.setTimeout(() => saveThemePreference(mode, callback, true), 150);
            return;
        }

        const params = {
            action: 'Security/Profile/UpdateTheme',
            value: mode,
            HTTP_MODAUTH: authToken
        };
        const body = Object.keys(params).map((key) => {
            return encodeURIComponent(key) + '=' + encodeURIComponent(params[key] || '');
        }).join('&');

        const xhr = new XMLHttpRequest();
        xhr.open('POST', connectorUrl, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState !== 4) {
                return;
            }
            let ok = xhr.status >= 200 && xhr.status < 300;
            if (ok) {
                try {
                    const json = JSON.parse(xhr.responseText);
                    if (json && json.success === false) {
                        ok = false;
                    }
                } catch (e) {
                    ok = false;
                }
            }
            callback(ok);
        };
        xhr.onerror = function () {
            callback(false);
        };
        xhr.send(body);
    }

    function notifyError() {
        const message = (typeof MODx !== 'undefined' && MODx.lang && MODx.lang.theme_save_error)
            ? MODx.lang.theme_save_error
            : 'Your theme preference could not be saved. Please try again.';
        if (typeof MODx !== 'undefined' && MODx.msg && MODx.msg.status) {
            MODx.msg.status({ title: '', message, delay: 4000 });
        }
    }

    function selectMode(mode) {
        const normalized = normalizeMode(mode);
        const previousMode = getCurrentMode();

        if (normalized === previousMode && !saveInFlight) {
            closeMenu();
            return;
        }

        applyTheme(normalized);
        closeMenu();

        if (saveInFlight) {
            pendingMode = normalized;
            return;
        }

        saveInFlight = true;
        saveThemePreference(normalized, function onSaved(ok) {
            saveInFlight = false;

            if (!ok) {
                applyTheme(previousMode);
                notifyError();
            }

            if (pendingMode !== null) {
                const next = pendingMode;
                pendingMode = null;
                selectMode(next);
            }
        });
    }

    function isMenuOpen() {
        const root = getRoot();
        return !!(root && root.classList.contains('is-open'));
    }

    function openMenu() {
        const root = getRoot();
        if (!root) {
            return;
        }
        root.classList.add('is-open');
        const trigger = root.querySelector('[data-theme-trigger]');
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'true');
        }
        const selected = root.querySelector('[data-theme-option].is-selected') || root.querySelector('[data-theme-option]');
        if (selected) {
            selected.focus();
        }
        document.addEventListener('click', onOutsideClick, true);
        document.addEventListener('keydown', onKeyDown, true);
    }

    function closeMenu(returnFocus) {
        const root = getRoot();
        if (!root) {
            return;
        }
        root.classList.remove('is-open');
        const trigger = root.querySelector('[data-theme-trigger]');
        if (trigger) {
            trigger.setAttribute('aria-expanded', 'false');
            if (returnFocus) {
                trigger.focus();
            }
        }
        document.removeEventListener('click', onOutsideClick, true);
        document.removeEventListener('keydown', onKeyDown, true);
    }

    function onOutsideClick(e) {
        const root = getRoot();
        if (root && !root.contains(e.target)) {
            closeMenu();
        }
    }

    function onKeyDown(e) {
        const root = getRoot();
        if (!root) {
            return;
        }
        const items = Array.prototype.slice.call(root.querySelectorAll('[data-theme-option]'));
        const activeIndex = items.indexOf(document.activeElement);

        switch (e.key) {
            case 'Escape':
                e.preventDefault();
                closeMenu(true);
                break;
            case 'ArrowDown':
                e.preventDefault();
                items[(activeIndex + 1 + items.length) % items.length].focus();
                break;
            case 'ArrowUp':
                e.preventDefault();
                items[(activeIndex - 1 + items.length) % items.length].focus();
                break;
            case 'Tab':
                closeMenu();
                break;
            default:
                break;
        }
    }

    function bindMenu() {
        const root = getRoot();
        if (!root) {
            return;
        }

        const trigger = root.querySelector('[data-theme-trigger]');
        if (trigger) {
            trigger.addEventListener('click', function (e) {
                e.preventDefault();
                if (isMenuOpen()) {
                    closeMenu();
                } else {
                    openMenu();
                }
            });
        }

        const items = root.querySelectorAll('[data-theme-option]');
        items.forEach((item) => {
            item.addEventListener('click', function (e) {
                e.preventDefault();
                selectMode(item.getAttribute('data-theme-option'));
            });
            item.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    selectMode(item.getAttribute('data-theme-option'));
                }
            });
        });
    }

    function init() {
        const root = getRoot();
        if (!root) {
            return;
        }

        const initialMode = normalizeMode(root.getAttribute('data-theme-mode'));
        updateMenuState(initialMode);
        updateTriggerIcon(document.documentElement.getAttribute('data-theme') || resolveTheme(initialMode));
        watchSystemPreference(initialMode);
        bindMenu();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
}());
