document.addEventListener('DOMContentLoaded', function () {
    // Break out of frames
    if (top.frames.length !== 0) {
        top.location = self.document.location;
    }

    var forgotPassBtn = document.getElementById('modx-fl-link'),
        backToLoginBtn = document.getElementById('modx-fl-back-to-login-link'),
        loginForm = document.getElementById('modx-login-form'),
        loginFormUser = document.getElementById('modx-login-username'),
        resetForm = document.getElementById('modx-forgot-login-form'),
        resetFormUser = document.getElementById('modx-login-username-reset'),
        languageSelector = document.getElementById('modx-login-language-select'),
        helpTrigger = document.getElementById('modx-login-help-trigger'),
        helpBlock = document.getElementById('modx-login-help-block'),
        errors = document.querySelectorAll('.is-error'),
        successes = document.querySelectorAll('.is-success');

    // When clicking on the forgot password button, swap out the forms
    if (forgotPassBtn) {
        forgotPassBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addClass(loginForm, 'is-hidden');
            removeClass(loginForm, 'is-visible');
            addClass(resetForm, 'is-visible');
            removeClass(resetForm, 'is-hidden');
            resetFormUser.focus();
            removeMessages();
            return false;
        });
    }

    // Also swap out in the reverse direction when clicking the back to login button
    if (backToLoginBtn) {
        backToLoginBtn.addEventListener('click', function (e) {
            e.preventDefault();
            addClass(loginForm, 'is-visible');
            removeClass(loginForm, 'is-hidden');
            addClass(resetForm, 'is-hidden');
            removeClass(resetForm, 'is-visible');
            loginFormUser.focus();
            removeMessages();
            return false;
        });
    }

    // Change language
    if (languageSelector) {
        languageSelector.addEventListener('change', function (e) {
            var params = {};
            location.search.substr(1).split('&').forEach(function (item) {
                if (item != '') {
                    params[item.split('=')[0]] = item.split('=')[1]
                }
            });
            params['manager_language'] = e.target.value;
            var url = [];
            for (var i in params) {
                if (params.hasOwnProperty(i)) {
                    url.push(i + '=' + params[i]);
                }
            }
            document.location = document.location.pathname + '?' + url.join('&');
        });
    }

    if (helpTrigger && helpBlock) {
        helpTrigger.addEventListener('click', function (e) {
            e.preventDefault();
            if (hasClass(helpBlock, 'is-visible')) {
                removeClass(helpBlock, 'is-visible');
            } else {
                addClass(helpBlock, 'is-visible');
            }
            removeMessages();
            return false;
        });
    }

    function addClass(el, className) {
        if (el.classList) {
            el.classList.add(className);
        }
        else if (!hasClass(el, className)) {
            el.className += ' ' + className;
        }
    }

    function removeClass(el, className) {
        if (el.classList) {
            el.classList.remove(className);
        }
        else {
            el.className = el.className.replace(new RegExp('\\b' + className + '\\b', 'g'), '');
        }
    }

    function hasClass(el, className) {
        if (el.classList) {
            return el.classList.contains(className);
        } else {
            el.className.match(new RegExp('\\b' + className + '\\b', 'g'));
        }
    }

    function removeMessages() {
        errors.forEach(function(item) {
            item.remove()
        });
        successes.forEach(function(item) {
            item.remove()
        });
    }
});