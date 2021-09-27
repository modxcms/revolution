<form id="install" action="?" method="post">
    <input type="hidden" name="language" value="{$current}">
    <input type="hidden" name="proceed" value="next">

    <div class="content-wrap">

        <div class="welcome_text">
            <h2>{$_lang.welcome}</h2>
            <p>{$_lang.welcome_message}</p>
        </div>

        {if $restarted}
        <br class="clear" />
        <br class="clear" />
        <p class="note">{$_lang.restarted_msg}</p>
        {/if}

        <div class="select_lang">

            <p class="title">{$_lang.choose_language}: <br>

            <div class="languages">
                {foreach from=$popular item=language}
                    <label class="language language--popular">
                        <button class="language-button {if $language.code EQ $current}language-button--active{/if}" type="button" data-setlanguage="{$language.code}">
                            <span class="language__native">{if !$language.native}{$language.name}{else}{$language.native}{/if}</span>
                            <span class="language__name">{$language.name} <span class="language__name__code">{$language.code}</span></span>
                        </button>
                    </label>
                {/foreach}
                {foreach from=$others item=language}
                    <label class="language language--other">
                        <button class="language-button {if $language.code EQ $current}language-button--active{/if}"" type="button" data-setlanguage="{$language.code}">
                            <span class="language__native">{if !$language.native}{$language.name}{else}{$language.native}{/if}</span>
                            <span class="language__name">{$language.name} <span class="language__name__code">{$language.code}</span></span>
                        </button>
                    </label>
                {/foreach}
            </div>
            <button class="select_lang__toggle select_lang__toggle--all" id="toggle--all" type="button"><span>{$_lang.all_languages}</span></button>
            <button class="select_lang__toggle select_lang__toggle--popular" id="toggle--popular" type="button"><span>{$_lang.only_popular}</span></button>
        </div>
    </div>

    <script>
        // Helpers
        var showElement = function (e) {
            e.style.display = 'block';
        }
        var hideElement = function (e) {
            e.style.display = 'none';
        }
        // State
        var languagesMode = 'popular' // or all
        var selectedLanguage = '{$current}'
        // Interactive elements
        var form = document.getElementById('install')
        var showAllLanguagesButton = document.getElementById('toggle--all')
        var showPopularLanguagesButton = document.getElementById('toggle--popular')
        var setLanguageButtons = document.querySelectorAll('[data-setlanguage]')
        // Updating DOM dependings on State
        function renderLanguagesForm() {
            var languageInput = document.querySelector('input[name="language"]')
            languageInput.value = selectedLanguage
            setLanguageButtons.forEach(function (button) {
                button.classList.remove('language-button--active')
                if (button.getAttribute('data-setlanguage') === selectedLanguage) {
                    button.classList.add('language-button--active')
                }
            })
            switch (languagesMode) {
                case 'all': {
                    showElement(showPopularLanguagesButton)
                    hideElement(showAllLanguagesButton)
                    document.querySelectorAll('.language--other').forEach(function (e) { showElement(e);})
                    break;
                }
                case 'popular': {
                    showElement(showAllLanguagesButton)
                    hideElement(showPopularLanguagesButton)
                    document.querySelectorAll('.language--other').forEach(function (e) { hideElement(e); })
                    break;
                }
            }
        }
        // Event handlers
        form.addEventListener('keypress', function(event) {
            // Form is not submitting if language button is focused
            if (event.keyCode == 13) {
                form.submit();
            }
        });
        setLanguageButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                selectedLanguage = button.getAttribute('data-setlanguage')
                renderLanguagesForm()
            })
        })
        showAllLanguagesButton.addEventListener('click', function(event) {
            languagesMode = 'all'
            renderLanguagesForm()
        })
        showPopularLanguagesButton.addEventListener('click', function(event) {
            languagesMode = 'popular'
            renderLanguagesForm()
        })
    </script>

    <div class="setup_navbar">
        <button type="submit" id="modx-next" class="button" autofocus="autofocus">
            {$_lang.next} <i class="fas fa-chevron-right"></i>
        </button>
    </div>

</form>
