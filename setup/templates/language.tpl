<form id="install" action="?" method="post">

{if $restarted}
    <br class="clear" />
    <br class="clear" />
    <p class="note">{$_lang.restarted_msg}</p>
{/if}

<div class="setup_navbar" style="border-top: 0;">
    <p class="title">{$_lang.choose_language}:
        <select name="language">
            {$languages}
    	</select>
    </p>

    <input type="submit" name="proceed" value="{$_lang.select}" />
</div>
</form>