<script type="text/javascript" src="assets/js/sections/contexts.js"></script>
<script type="text/javascript">
Ext.onReady(function() {literal}{{/literal}
    MODx.context_web_path = "{$context_web_path}";
    MODx.context_web_url = "{$context_web_url}";
    MODx.context_connectors_path = "{$context_connectors_path}";
    MODx.context_connectors_url = "{$context_connectors_url}";
    MODx.context_mgr_path = "{$context_mgr_path}";
    MODx.context_mgr_url = "{$context_mgr_url}";
{literal}}{/literal});
</script>
<form id="install" action="?action=contexts" method="post">

<h2 class="title">{$_lang.context_installation}</h2>

<h3>{$_lang.context_web_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
    <div class="col-1">
        <label for="context_web_path">{$_lang.context_web_path}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_web_path" name="context_web_path" value="{$context_web_path}" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_web_path_toggle" name="context_web_path_toggle" value="1" {$context_web_path_checked|default} />
        <label for="context_web_path_toggle"></label>
    </div>
</div>
<div class="labelHolder">
    <div class="col-1">
        <label for="context_web_url">{$_lang.context_web_url}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_web_url" name="context_web_url" value="{$context_web_url}" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_web_url_toggle" name="context_web_url_toggle" value="1" {$context_web_url_checked|default} onclick="if (!this.checked) Ext.get('context_web_url').set({literal}{{/literal} value: '{$context_web_url}' {literal}}{/literal});" />
        <label for="context_web_url_toggle"></label>
    </div>
</div>
<br />

<h3>{$_lang.context_connector_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
    <div class="col-1">
        <label for="context_connectors_path">{$_lang.context_connector_path}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_connectors_path" name="context_connectors_path" value="{$context_connectors_path}" onchange="$('context_connectors_path_toggle').checked=true;" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_connectors_path_toggle" name="context_connectors_path_toggle" value="1" {$context_connectors_path_checked|default} onclick="if (!this.checked) $('context_connectors_path').value = '{$context_connectors_path}';" />
        <label for="context_connectors_path_toggle"></label>
    </div>
</div>

<div class="labelHolder">
    <div class="col-1">
        <label for="context_connectors_url">{$_lang.context_connector_url}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_connectors_url" name="context_connectors_url" value="{$context_connectors_url}" onchange="$('context_connectors_url_toggle').checked=true;" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_connectors_url_toggle" name="context_connectors_url_toggle" value="1" {$context_connectors_url_checked|default} onclick="if (!this.checked) $('context_connectors_url').value = '{$context_connectors_url}';" />
        <label for="context_connectors_url_toggle"></label>
    </div>
</div>

<br />

<h3>{$_lang.context_manager_options}</h3>
<p><small>{$_lang.context_override}</small></p>

<div class="labelHolder">
    <div class="col-1">
        <label for="context_mgr_path">{$_lang.context_manager_path}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_mgr_path" name="context_mgr_path" value="{$context_mgr_path}" onchange="$('context_mgr_path_toggle').checked=true;" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_mgr_path_toggle" name="context_mgr_path_toggle" value="1" {$context_mgr_path_checked|default} onclick="if (!this.checked) $('context_mgr_path').value = '{$context_mgr_path}';" />
        <label for="context_mgr_path_toggle"></label>
    </div>
</div>

<div class="labelHolder">
    <div class="col-1">
        <label for="context_mgr_url">{$_lang.context_manager_url}:</label>
    </div>
    <div class="col-2">
        <input type="text" id="context_mgr_url" name="context_mgr_url" value="{$context_mgr_url}" onchange="$('context_mgr_url_toggle').checked=true;" />
    </div>
    <div class="col-3">
        <input type="checkbox" id="context_mgr_url_toggle" name="context_mgr_url_toggle" value="1" {$context_mgr_url_checked|default} onclick="if (!this.checked) $('context_mgr_url').value = '{$context_mgr_url}';" />
        <label for="context_mgr_url_toggle"></label>
    </div>
</div>

<br />

<div class="setup_navbar">
    <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.next} &#xf054;" />
    <button type="button" id="modx-back" class="button" onclick="MODx.go('database');">&#xf053; {$_lang.back}</button>
</div>
</form>
