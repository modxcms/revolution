<form id="install" action="?action=welcome" method="post">
<div>
    <h2>{$_lang.welcome}</h2>
    {$_lang.welcome_message}
    <br />
</div>
<div>
    <h3>{$_lang.config_key}</h3>
    <p><small>{$_lang.config_key_override}</small></p>
    <div class="labelHolder">
        <label for="config_key">{$_lang.config_key}</label>
        <input type="text" name="config_key" id="config_key" value="{$config_key}" style="width:250px" />
        <br />
        {if $writableError}
        <span class="field_error">{$_lang.config_not_writable_err}</span>
        {/if}
    </div>
</div>

<div class="setup_navbar">
    <input type="submit" name="proceed" value="{$_lang.next}" />
</div>
</form>