<form id="install" action="?action=complete" method="post">
<div class="setup_body">
	<h2>{$_lang.thank_installing}{$app_name}.</h2>

    {if $errors}
    <div class="note">
    <h3>{$_lang.cleanup_errors_title}</h3>
        {foreach from=$errors item=error}
            <p>{$error}</p><hr />
        {/foreach}
    </div>
    <br />
    {/if}
	<p>{$_lang.please_select_login}</p>
</div>

<div class="setup_navbar">
    <label>
        <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.login}" autofocus="autofocus" />
    </label>
    <br />
    <span class="cleanup">
        <input type="checkbox" value="1" id="cleanup" name="cleanup"{if $cleanup} checked="checked"{/if} />
        <label for="cleanup"></label>
        <span class="cleanup_text">{$_lang.delete_setup_dir}</span>
    </span>
</div>
</form>
