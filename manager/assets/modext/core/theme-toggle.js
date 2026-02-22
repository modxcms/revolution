/**
 * Manager theme toggle (light/dark/system).
 * Uses MODx.config.manager_dark_mode and Security/Profile/UpdateTheme.
 */
(function () {
    var STORAGE_KEY = 'modx_manager_theme';
    var ALLOWED = ['light', 'dark', 'system'];

    function getEffectiveTheme(configMode, prefersDark) {
        if (configMode === 'system') {
            return prefersDark ? 'dark' : 'light';
        }
        return configMode;
    }

    function prefersColorSchemeDark() {
        return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
    }

    function applyTheme(theme) {
        var root = document.documentElement;
        root.setAttribute('data-theme', theme);
        try {
            window.localStorage.setItem(STORAGE_KEY, theme);
        } catch (e) {}
        updateToggleIcons(theme);
    }

    function updateToggleIcons(theme) {
        var darkIcon = document.querySelector('#modx-theme-toggle [data-theme-icon="dark"]');
        var lightIcon = document.querySelector('#modx-theme-toggle [data-theme-icon="light"]');
        if (darkIcon && lightIcon) {
            if (theme === 'dark') {
                darkIcon.style.display = 'none';
                lightIcon.style.display = '';
            } else {
                darkIcon.style.display = '';
                lightIcon.style.display = 'none';
            }
        }
    }

    function saveThemePreference(value, callback) {
        var connectorUrl = (typeof MODx !== 'undefined' && MODx.config)
            ? (MODx.config.connector_url || MODx.config.connectors_url)
            : '';
        if (!connectorUrl) {
            if (callback) callback(false);
            return;
        }
        var url = connectorUrl;
        var params = {
            action: 'Security/Profile/UpdateTheme',
            value: value,
            HTTP_MODAUTH: MODx.config.auth || ''
        };
        var xhr = new XMLHttpRequest();
        xhr.open('POST', url, true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && callback) {
                var ok = xhr.status >= 200 && xhr.status < 300;
                try {
                    var json = JSON.parse(xhr.responseText);
                    if (json && json.success === false) ok = false;
                } catch (e) {}
                callback(ok);
            }
        };
        var body = Object.keys(params).map(function (k) {
            return encodeURIComponent(k) + '=' + encodeURIComponent(params[k] || '');
        }).join('&');
        xhr.send(body);
    }

    function init() {
        var configMode = (typeof MODx !== 'undefined' && MODx.config && MODx.config.manager_dark_mode)
            ? MODx.config.manager_dark_mode
            : 'light';
        var prefersDark = prefersColorSchemeDark();
        var effective = getEffectiveTheme(configMode, prefersDark);
        applyTheme(effective);

        var toggleBtn = document.querySelector('#modx-theme-toggle a');
        if (toggleBtn) {
            toggleBtn.addEventListener('click', function (e) {
                e.preventDefault();
                var root = document.documentElement;
                var current = root.getAttribute('data-theme') || 'light';
                var next = current === 'dark' ? 'light' : 'dark';
                applyTheme(next);
                saveThemePreference(next, function (ok) {
                    if (!ok && typeof MODx !== 'undefined' && MODx.msg) {
                        MODx.msg.status({
                            title: 'Theme',
                            message: 'Preference may not have been saved.'
                        });
                    }
                });
            });
        }

        if (configMode === 'system' && window.matchMedia) {
            var mq = window.matchMedia('(prefers-color-scheme: dark)');
            if (mq.addEventListener) {
                mq.addEventListener('change', function () {
                    applyTheme(getEffectiveTheme(configMode, prefersColorSchemeDark()));
                });
            } else if (mq.addListener) {
                mq.addListener(function () {
                    applyTheme(getEffectiveTheme(configMode, prefersColorSchemeDark()));
                });
            }
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();
