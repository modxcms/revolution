<form id="welcome" action="?action=welcome" method="post">

    <div class="content-wrap">

        <h2>{$_lang.config_key}</h2>
        <p>{$_lang.config_key_override}</p>

        <div class="labelHolder">
            <label for="config_key">{$_lang.config_key}</label>
            <input type="text" name="config_key" id="config_key" value="{$config_key|escape}" style="width:250px" />

            {if $writableError}
            <span class="field_error">{$_lang.config_not_writable_err}</span>
            {/if}
        </div>
    </div>

    <div class="setup_navbar">
       <input type="button" onclick="MODx.go('language');" value="&#xf053; {$_lang.back}" id="modx-back" class="button">
       <input type="submit" name="proceed" value="{$_lang.next} &#xf054;" id="modx-next" class="button" autofocus="autofocus">
    </div>
</form>
