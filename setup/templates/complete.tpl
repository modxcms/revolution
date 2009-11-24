<form id="install" action="?action=complete" method="post">
<div>
	<h2>{$_lang.thank_installing}{$app_name}.</h2>

    {if $errors}<p class="error">{$errors}</p>{/if}

	<p>{$_lang.please_select_login}:</p>
</div>
<br />

<div class="setup_navbar">
	<label><input type="submit" id="modx-next" name="proceed" value="{$_lang.login}" /></label>
	<span class="cleanup">
	  <label>
	    <input type="checkbox" value="1" id="cleanup" name="cleanup" /> {$_lang.delete_setup_dir}
	  </label>
    </span>
</div>
</form>