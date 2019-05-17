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
            {foreach from=$languages item=language}
                <div class="language {if $language.code EQ $current}selected{/if}" data-code="{$language.code}">
                    <input type="radio" class="hide" name="language" value="{$language.code}">
                    <div class="wrap">
                        <div class="native">{if !$language.native}{$language.name}{else}{$language.native}{/if}</div>
                        <div class="name">{$language.name} <strong>{$language.code}</strong></div>
                    </div>
                </div>
            {/foreach}
            </div>
        </div>
    </div>

    <div class="setup_navbar">
        <input type="submit" name="proceed" id="modx-next" class="button" value="{$_lang.next} &#xf054;" />
    </div>

</form>
