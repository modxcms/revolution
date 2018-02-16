document.addEventListener('DOMContentLoaded', function() {
    // Break out of frames
    if (top.frames.length !== 0) {
        top.location=self.document.location;
    }

    var forgotPassBtn = document.getElementById('modx-fl-link'),
        backToLoginBtn = document.getElementById('modx-fl-back-to-login-link'),
        loginForm = document.getElementById('modx-login-form'),
        loginFormUser = document.getElementById('modx-login-username'),
        resetForm = document.getElementById('modx-forgot-login-form'),
        resetFormUser = document.getElementById('modx-login-username-reset'),
        languageSelector = document.getElementById('modx-login-language-select');

    // When clicking on the forgot password button, swap out the forms
    forgotPassBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addClass(loginForm, 'is-hidden');
        removeClass(loginForm, 'is-visible');
        addClass(resetForm, 'is-visible');
        removeClass(resetForm, 'is-hidden');
        resetFormUser.focus();
        return false;
    });

    // Also swap out in the reverse direction when clicking the back to login button
    backToLoginBtn.addEventListener('click', function(e) {
        e.preventDefault();
        addClass(loginForm, 'is-visible');
        removeClass(loginForm, 'is-hidden');
        addClass(resetForm, 'is-hidden');
        removeClass(resetForm, 'is-visible');
        loginFormUser.focus();
        return false;
    });

    languageSelector.addEventListener('change', function() {
        console.log(this);
        this.form.submit();
    });

    // Ext.get('modx-login-language-select').on('change',function(e,cb) {
    //     var p = MODx.getURLParameters();
    //     p.cultureKey = cb.value;
    //     location.href = '?'+Ext.urlEncode(p);
    // });

    function addClass(el, className) {
        if (el.classList) el.classList.add(className);
        else if (!hasClass(el, className)) el.className += ' ' + className;
    }

    function removeClass(el, className) {
        if (el.classList) el.classList.remove(className);
        else el.className = el.className.replace(new RegExp('\\b'+ className+'\\b', 'g'), '');
    }
});