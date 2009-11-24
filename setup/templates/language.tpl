<form id="install" action="?" method="post">

<div class="right">
    <img src="{$_lang.img_box}" alt="{$_lang.app_motto}" />
</div>
<img src="{$_lang.img_splash}" alt="{$_lang.modx_install}" />

<div class="setup_navbar">
	<p class="title">{$_lang.choose_language}:
		<select name="language">
		{$languages}
    	</select>
	</p>

    <input type="submit" name="proceed" value="{$_lang.select}" />
</div>
</form>