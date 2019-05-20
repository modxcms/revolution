<form id="install" action="?" method="post">

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
                    <label class="language popular" for="language_{$language.code}">
                        <input type="radio" class="hide" name="language" id="language_{$language.code}" value="{$language.code}" {if $language.code EQ $current}checked="checked"{/if}>
                        <span class="wrap">
                            <span class="native">{if !$language.native}{$language.name}{else}{$language.native}{/if}</span>
                            <span class="name">{$language.name} <strong>{$language.code}</strong></span>
                        </span>
                    </label>
                {/foreach}
                {foreach from=$others item=language}
                    <label class="language other" for="language_{$language.code}">
                        <input type="radio" class="hide" name="language" id="language_{$language.code}" value="{$language.code}" {if $language.code EQ $current}checked="checked"{/if}>
                        <span class="wrap">
                        <span class="native">{if !$language.native}{$language.name}{else}{$language.native}{/if}</span>
                        <span class="name">{$language.name} <strong>{$language.code}</strong></span>
                    </span>
                    </label>
                {/foreach}
            </div>
            <span class="toggle all"><span>{$_lang.all_languages}</span></span>
            <span class="toggle pop"><span>{$_lang.only_popular}</span></span>
        </div>
    </div>

    <script>
        var show = function (e) { e.style.display = 'block'; };
        var hide = function (e) { e.style.display = 'none'; };
        var all = document.querySelector('.toggle.all');
        var pop = document.querySelector('.toggle.pop');
        all.onclick = function() { hide(all); show(pop); document.querySelectorAll('.language.other').forEach(function (e) { show(e);}); };
        pop.onclick = function() { hide(pop); show(all); document.querySelectorAll('.language.other').forEach(function (e) { hide(e); }); };
    </script>

    <div class="setup_navbar">
        <input type="submit" name="proceed" id="modx-next" class="button" value="{$_lang.next} &#xf054;" />
    </div>

</form>
