{include file='header.tpl'}

<h2>{$_lang.install_summary}</h2>
{if $failed}
<p>{$_lang.preinstall_failure}</p>
{else}
<p>{$_lang.preinstall_success}</p>
{/if}
<ul class="checklist">
{foreach from=$test item=result}
<li class="{$result.class}">{$result.msg}</li>
{/foreach}
</ul>

<input type="hidden" id="installmode" name="installmode" value="{$installmode}" />
{if $config.unpacked EQ 1}
<input type="hidden" id="unpacked" name="unpacked" value="1" />
{/if}
{if $config.inplace EQ 1}
<input type="hidden" id="inplace" name="inplace" value="1" />
{/if}
<input type="hidden" id="httpsport" name="httpsport" value="{$config.https_port}" />
<input type="hidden" id="cachedisabled" name="cachedisabled" value="{$config.cache_disabled}" />
<input type="hidden" id="database_name" value="{$config.dbase}" name="database_name" />
<input type="hidden" id="tableprefix" value="{$config.table_prefix}" name="tableprefix" />
{if $installmode EQ 0}
<input type="hidden" id="database_collation" value="{$config.database_collation}" name="database_collation" />
<input type="hidden" id="database_charset" value="{$config.database_charset}" name="database_charset" />
{/if}
<input type="hidden" id="database_connection_charset" value="{$config.database_connection_charset}" name="database_connection_charset" />
<input type="hidden" id="databasehost" value="{$config.database_server}" name="databasehost" />
<input type="hidden" id="databaseloginname" name="databaseloginname" value="{$config.database_user}" />
<input type="hidden" id="databaseloginpassword" name="databaseloginpassword"  value="{$config.database_password}" />
{if $installmode EQ 0}
<input type="hidden" id="cmsadmin" value="{$config.cmsadmin}" name="cmsadmin" />
<input type="hidden" id="cmsadminemail" value="{$config.cmsadminemail}" name="cmsadminemail" />
<input type="hidden" id="cmspassword" name="cmspassword" value="{$config.cmspassword}" />
<input type="hidden" id="cmspasswordconfirm" name="cmspasswordconfirm" value="{$config.cmspasswordconfirm}" />
{/if}
<input type="hidden" id="context_web_path" name="context_web_path" value="{$config.web_path}" />
<input type="hidden" id="context_web_path_toggle" name="context_web_path_toggle" value="{$config.web_path_auto}" />
<input type="hidden" id="context_web_url" name="context_web_url" value="{$config.web_url}" />
<input type="hidden" id="context_web_url_toggle" name="context_web_url_toggle" value="{$config.web_url_auto}" />
<input type="hidden" id="context_connectors_path" name="context_connectors_path" value="{$config.connectors_path}" />
<input type="hidden" id="context_connectors_path_toggle" name="context_connectors_path_toggle" value="{$config.connectors_path_auto}" />
<input type="hidden" id="context_connectors_url" name="context_connectors_url" value="{$config.connectors_url}" />
<input type="hidden" id="context_connectors_url_toggle" name="context_connectors_url_toggle" value="{$config.connectors_url_auto}" />
<input type="hidden" id="context_mgr_path" name="context_mgr_path" value="{$config.mgr_path}" />
<input type="hidden" id="context_mgr_path_toggle" name="context_mgr_path_toggle" value="{$config.mgr_path_auto}" />
<input type="hidden" id="context_mgr_url" name="context_mgr_url" value="{$config.mgr_url}" />
<input type="hidden" id="context_mgr_url_toggle" name="context_mgr_url_toggle" value="{$config.mgr_url_auto}" />

<br />

{include file='footer.tpl'}