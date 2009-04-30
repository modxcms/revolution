{include file='header.tpl'}
<script type="text/javascript" src="assets/js/bsn.AutoSuggest_2.1.js"></script>
<h2>{$_lang.context_installation}</h2>

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

<h3>{$_lang.context_web_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
	<label for="context_web_path">{$_lang.context_web_path}:</label>
	<input type="text" id="context_web_path" name="context_web_path" value="{$context_web_path}" style="width:350px" onchange="$('context_web_path_toggle').checked=true;" />
	<input type="checkbox" id="context_web_path_toggle" name="context_web_path_toggle" value="1" {$context_web_path_checked} style="width: 30px;" onclick="if (!this.checked) $('context_web_path').value = '{$context_web_path}';" />
</div>
<div class="labelHolder">
	<label for="context_web_url">{$_lang.context_web_url}:</label>	
	<input type="text" id="context_web_url" name="context_web_url" value="{$context_web_url}" style="width:350px" onchange="$('context_web_url_toggle').checked=true;" />
	<input type="checkbox" id="context_web_url_toggle" name="context_web_url_toggle" value="1" {$context_web_url_checked}style="width:30px" onclick="if (!this.checked) $('context_web_url').value = '{$context_web_url}';" />
</div>
<br />


<h3>{$_lang.context_connector_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
	<label for="context_connectors_path">{$_lang.context_connector_path}:</label>	
	<input type="text" id="context_connectors_path" name="context_connectors_path" value="{$context_connectors_path}" style="width:350px" onchange="$('context_connectors_path_toggle').checked=true;" />
	<input type="checkbox" id="context_connectors_path_toggle" name="context_connectors_path_toggle" value="1" {$context_connectors_path_checked} style="width:30px" onclick="if (!this.checked) $('context_connectors_path').value = '{$context_connectors_path}';" />
</div>
<div class="labelHolder">
	<label for="context_connectors_url">{$_lang.context_connector_url}:</label>	
	<input type="text" id="context_connectors_url" name="context_connectors_url" value="{$context_connectors_url}" style="width:350px" onchange="$('context_connectors_url_toggle').checked=true;" />
	<input type="checkbox" id="context_connectors_url_toggle" name="context_connectors_url_toggle" value="1" {$context_connectors_url_checked}style="width:30px" onclick="if (!this.checked) $('context_connectors_url').value = '{$context_connectors_url}';" />
</div>
<br />


<h3>{$_lang.context_manager_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
	<label for="context_mgr_path">{$_lang.context_manager_path}:</label>	
	<input type="text" id="context_mgr_path" name="context_mgr_path" value="{$context_mgr_path}" style="width:350px" onchange="$('context_mgr_path_toggle').checked=true;" />
	<input type="checkbox" id="context_mgr_path_toggle" name="context_mgr_path_toggle" value="1" {$context_mgr_path_checked} style="width:30px;" onclick="if (!this.checked) $('context_mgr_path').value = '{$context_mgr_path}';" />
</div>
<div class="labelHolder">
	<label for="context_mgr_url">{$_lang.context_manager_url}:</label>	
	<input type="text" id="context_mgr_url" name="context_mgr_url" value="{$context_mgr_url}" style="width:350px" onchange="$('context_mgr_url_toggle').checked=true;" />
	<input type="checkbox" id="context_mgr_url_toggle" name="context_mgr_url_toggle" value="1" {$context_mgr_url_checked} style="width:30px;" onclick="if (!this.checked) $('context_mgr_url').value = '{$context_mgr_url}';" />
</div>
{literal}
<script type="text/javascript">
// <![CDATA[
var web_path_options = {
    script: "processors/connector.php?action=path_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_web_path = new _bsn.AutoSuggest('context_web_path', web_path_options);
var web_url_options = {
    script: "processors/connector.php?action=url_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_web_url = new _bsn.AutoSuggest('context_web_url', web_url_options);
var connectors_path_options = {
    script: "processors/connector.php?action=path_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_connectors_path = new _bsn.AutoSuggest('context_connectors_path', connectors_path_options);
var connectors_url_options = {
    script: "processors/connector.php?action=url_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_connectors_url = new _bsn.AutoSuggest('context_connectors_url', connectors_url_options);
var mgr_path_options = {
    script: "processors/connector.php?action=path_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_mgr_path = new _bsn.AutoSuggest('context_mgr_path', mgr_path_options);
var mgr_url_options = {
    script: "processors/connector.php?action=url_search&",
    varname: "search",
    json: true,
    timeout: 3200,
    method: 'POST'
};
var as_mgr_url = new _bsn.AutoSuggest('context_mgr_url', mgr_url_options);
// ]]>
</script>
{/literal}
<br />
{include file='footer.tpl'}