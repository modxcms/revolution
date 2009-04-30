{include file='header.tpl'}
<h2>{$_lang.connection_title}</h2>

<h3>{$_lang.connection_connection_and_login_information}</h3>

<p>{$_lang.connection_connection_note}</p>

<input type="hidden" id="installmode" name="installmode" value="{$installmode}" />
{if $config.unpacked EQ 1}
<input type="hidden" id="unpacked" name="unpacked" value="1" />
{/if}
{if $config.inplace EQ 1}
<input type="hidden" id="inplace" name="inplace" value="1" />
{/if}

<div class="labelHolder">
	<label for="database_name">{$_lang.connection_database_name}</label>
	<input id="database_name" value="{$config.dbase}" name="database_name" />
	&nbsp;<span class="field_error" id="database_name_error"></span>
</div>
<div class="labelHolder">
	<label for="tableprefix">{$_lang.connection_table_prefix}</label>
	<input id="tableprefix" value="{$config.table_prefix}" name="tableprefix" />
	&nbsp;<span class="field_error" id="tableprefix_error"></span>
</div>
{if $installmode EQ 0}
<div class="labelHolder">
	<label for="database_collation">{$_lang.connection_collation}</label>
	<input id="database_collation" value="{$config.database_collation}" name="database_collation" />
	&nbsp;<span class="field_error" id="database_collation_error"></span>
</div>
{else}
<div class="labelHolder">
	<label for="database_connection_charset">{$_lang.connection_character_set}</label>
	<input id="database_connection_charset" value="{$config.database_connection_charset}" name="database_connection_charset" />
	&nbsp;<span class="field_error" id="database_connection_charset_error"></span>
</div>
{/if}
<br />
<p>{$_lang.connection_database_info}</p>
<br />
<div class="labelHolder">
	<label for="databasehost">{$_lang.connection_database_host}</label>
	<input id="databasehost" value="{$config.database_server}" name="databasehost" />
	&nbsp;<span class="field_error" id="databasehost_error"></span>
</div>
<div class="labelHolder">
	<label for="databaseloginname">{$_lang.connection_database_login}</label>
	<input id="databaseloginname" name="databaseloginname" value="{$config.database_user}" />
	&nbsp;<span class="field_error" id="databaseloginname_error"></span>
</div>
<div class="labelHolder">
	<label for="databaseloginpassword">{$_lang.connection_database_pass}</label>
	<input id="databaseloginpassword" type="password" name="databaseloginpassword"  value="{$config.database_password}" />
	&nbsp;<span class="field_error" id="database_password_error"></span>
</div>
<div class="labelHolder">
	<label for="cmdtest">&nbsp;</label>
	<input type="button" name="cmdtest" id="cmdtest" value="{$_lang.connection_test_connection}" style="width:130px" onclick="return FormHandler.send($('install'), 'test_connection', installHandler);" />
</div>

{if $installmode EQ 0}
<div id="AUH">
    <p class="title">{$_lang.connection_default_admin_user}</p>
	<p>{$_lang.connection_default_admin_note}</p>

	<div class="labelHolder">
		<label for="cmsadmin">{$_lang.connection_default_admin_login}</label>
		<input type="text" name="cmsadmin" id="cmsadmin" value="{$config.cmsadmin}" />
		&nbsp;<span class="field_error" id="cmsadmin_error"></span>
	</div>
	<div class="labelHolder">
		<label for="cmsadminemail">{$_lang.connection_default_admin_email}</label>
		<input type="text" name="cmsadminemail" id="cmsadminemail" value="{$config.cmsadminemail}" />
		&nbsp;<span class="field_error" id="cmsadminemail_error"></span>
	</div>
	<div class="labelHolder">
		<label for="cmspassword">{$_lang.connection_default_admin_password}</label>
		<input type="password" id="cmspassword" name="cmspassword" value="" />
		&nbsp;<span class="field_error" id="cmspassword_error"></span>
	</div>
	<div class="labelHolder">
		<label for="cmspasswordconfirm">{$_lang.connection_default_admin_password_confirm}</label>
		<input type="password" id="cmspasswordconfirm" name="cmspasswordconfirm" value="" />
		&nbsp;<span class="field_error" id="cmspasswordconfirm_error"></span>
	</div>
</div>
{/if}
<br />
{include file='footer.tpl'}