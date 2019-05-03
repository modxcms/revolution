<script type="text/javascript" src="assets/js/sections/welcome.js"></script>
<form id="welcome" action="?action=welcome" method="post">

    <div class="content-wrap">

        {if $smarty.const.MODX_SETUP_KEY NEQ '@traditional@'}
        <p>{$_lang.config_key_change}</p>

        <div id="cck-div">
            <h3>{$_lang.config_key}</h3>
            <p>{$_lang.config_key_override}</p>
            <div class="labelHolder">
                <label for="config_key">{$_lang.config_key}</label>
                <input type="text" name="config_key" id="config_key" value="{$config_key|escape}" style="width:250px" />
                <br />
                {if $writableError}
                <span class="field_error">{$_lang.config_not_writable_err}</span>
                {/if}
            </div>
        </div>

        {/if}
    </div>

    <div class="setup_navbar">
        <input type="submit" name="proceed" value="{$_lang.next} >" id="modx-next" class="button" autofocus="autofocus" />
    </div>
</form>
