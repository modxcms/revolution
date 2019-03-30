<form id="install" action="?" method="post">

<div>
    <h2>{$_lang.welcome}</h2>
    {$_lang.welcome_message}
    <br />
</div>

{if $restarted}
    <br class="clear" />
    <br class="clear" />
    <p class="note">{$_lang.restarted_msg}</p>
{/if}

<div class="setup_navbar" style="border-top: 0;">
    <p class="title">{$_lang.choose_language}:
        <select name="language" autofocus="autofocus">
            {$languages}
    	</select>
    </p>
</div>

<div class="content_footer">
    <input type="submit" name="proceed" id="btn_next" class="button" value="{$_lang.next} >" />
</div>

</form>