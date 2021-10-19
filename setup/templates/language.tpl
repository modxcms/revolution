<form id="install" action="?" method="post">
    <input type="hidden" name="proceed" value="next">

    <div class="content-wrap">

        <div class="welcome_text">
            <h2>{$_lang.welcome}</h2>
            <div>{$_lang.welcome_message}</div>
        </div>

        {if $restarted}
            <br class="clear" />
            <br class="clear" />
            <p class="note">{$_lang.restarted_msg}</p>
        {/if}

        <div class="select_lang">

            <p class="title">{$_lang.choose_language}:</p>

            <div class="languages-wrapper">
                {foreach from=$popular item=language}
                    <label
                        class="languages-item languages-item--popular {if $language.code EQ $current}languages-item--active{/if}"
                        for="language_{$language.code}"
                    >
                        <input
                            type="radio"
                            class="languages-item__radio"
                            tabindex="0"
                            name="language"
                            id="language_{$language.code}"
                            value="{$language.code}"
                            {if $language.code EQ $current}checked="checked"{/if}
                        >
                        <div class="languages-item__inner">
                            <div class="languages-item__inner__native">
                                {if !$language.native}
                                    {$language.name}
                                {else}
                                    {$language.native}
                                {/if}
                            </div>
                            <div class="language__name">
                                {$language.name} <span class="languages-item__inner__code" aria-hidden="true">{$language.code}</span>
                            </div>
                        </div>
                    </label>
                {/foreach}
                {foreach from=$others item=language}
                    <label
                        class="languages-item languages-item--other"
                        for="language_{$language.code}"
                    >
                        <input
                            type="radio"
                            class="languages-item__radio"
                            tabindex="0"
                            name="language"
                            id="language_{$language.code}"
                            value="{$language.code}"
                            {if $language.code EQ $current}checked="checked"{/if}
                        >
                        <div class="languages-item__inner">
                            <div class="languages-item__inner__native">
                                {if !$language.native}
                                    {$language.name}
                                {else}
                                    {$language.native}
                                {/if}
                            </div>
                            <div class="language__name">
                                {$language.name} <span class="languages-item__inner__code" aria-hidden="true">{$language.code}</span>
                            </div>
                        </div>
                    </label>
                {/foreach}

                <div class="languages-item languages__toggler-wrapper">
                    <button class="languages__toggler languages__toggler--all" id="toggle--all" type="button">
                        <span>{$_lang.all_languages}</span>
                    </button>
                    <button class="languages__toggler languages__toggler--popular" id="toggle--popular" type="button" style="display: none;">
                        <span>{$_lang.only_popular}</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Helpers
        var showElement = function(e) {
            e.style.display = 'block';
        }
        var hideElement = function(e) {
            e.style.display = 'none';
        }

        // State
        var languageSelected = '{$current}'; // value passed from php-script
        var languagesMode = 'popular'; // 'popular' | 'all'
        var isSwitchingLanguageGroups = false;

        // Interactive elements
        var showAllLanguagesButton = document.getElementById('toggle--all');
        var showPopularLanguagesButton = document.getElementById('toggle--popular');

        // Updating DOM dependings on State
        function renderLanguagesForm() {
            var languageItems = document.querySelectorAll('.languages-item');
            for (var i = 0; i < languageItems.length; i++) {
                languageItems[i].classList.remove('languages-item--active');
                var radio = languageItems[i].querySelector('input');
                if (radio && radio.value === languageSelected) {
                    languageItems[i].classList.add('languages-item--active');
                }
            }

            switch (languagesMode) {
                case 'all': {
                    showElement(showPopularLanguagesButton);
                    hideElement(showAllLanguagesButton);
                    if (isSwitchingLanguageGroups) {
                        showPopularLanguagesButton.focus();
                        isSwitchingLanguageGroups = false;
                    }

                    var languageItemsOther = document.querySelectorAll('.languages-item--other');
                    for (var i = 0; i < languageItemsOther.length; i++) {
                        showElement(languageItemsOther[i]);
                    }
                    break;
                }
                case 'popular': {
                    showElement(showAllLanguagesButton);
                    hideElement(showPopularLanguagesButton);
                    if (isSwitchingLanguageGroups) {
                        showAllLanguagesButton.focus();
                        isSwitchingLanguageGroups = false;
                    }
                    var languageItemsOther = document.querySelectorAll('.languages-item--other');
                    for (var i = 0; i < languageItemsOther.length; i++) {
                        hideElement(languageItemsOther[i]);
                    }
                    break;
                }
            }
        }

        // Event handlers
        var languageItemsRadio = document.querySelectorAll('.languages-item__radio');
        for (var i = 0; i < languageItemsRadio.length; i++) {
            languageItemsRadio[i].addEventListener('change', function(e) {
                languageSelected = e.target.value;
                renderLanguagesForm();
            })
        }
        showAllLanguagesButton.addEventListener('click', function(event) {
            languagesMode = 'all';
            isSwitchingLanguageGroups = true;
            renderLanguagesForm();
        })
        showPopularLanguagesButton.addEventListener('click', function(event) {
            languagesMode = 'popular';
            isSwitchingLanguageGroups = true;
            renderLanguagesForm();
        })
    </script>

    <div class="setup_navbar">
        <button type="submit" id="modx-next" class="button" autofocus="autofocus">
            {$_lang.next} <i class="fas fa-chevron-right"></i>
        </button>
    </div>

</form>
