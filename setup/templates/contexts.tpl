<script src="assets/js/sections/contexts.js"></script>
<script>
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
<p><small>{$_lang.context_override}</small></p>

<h3>{$_lang.context_web_options}</h3>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
            <label for="context_web_path">{$_lang.context_web_path}:</label>
        </div>
        <div class="context-property_value">
            <input type="text" id="context_web_path" name="context_web_path" value="{$context_web_path}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_web_path_toggle" name="context_web_path_toggle" value="1" {$context_web_path_checked|default} />
                <label for="context_web_path_toggle"></label>
            </div>
        </div>
    </div>
</div>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
            <label for="context_web_url">{$_lang.context_web_url}:</label>
        </div>
        <div class="context-property_value">
            <input type="text" id="context_web_url" name="context_web_url" value="{$context_web_url}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_web_url_toggle" name="context_web_url_toggle" value="1" {$context_web_url_checked|default} />
                <label for="context_web_url_toggle"></label>
            </div>
        </div>
    </div>
</div>

<br />

<h3>{$_lang.context_connector_options}</h3>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
        <label for="context_connectors_path">{$_lang.context_connector_path}:</label>
    </div>
        <div class="context-property_value">
            <input type="text" id="context_connectors_path" name="context_connectors_path" value="{$context_connectors_path}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_connectors_path_toggle" name="context_connectors_path_toggle" value="1" {$context_connectors_path_checked|default} />
                <label for="context_connectors_path_toggle"></label>
            </div>
        </div>
    </div>
</div>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
        <label for="context_connectors_url">{$_lang.context_connector_url}:</label>
    </div>
        <div class="context-property_value">
            <input type="text" id="context_connectors_url" name="context_connectors_url" value="{$context_connectors_url}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_connectors_url_toggle" name="context_connectors_url_toggle" value="1" {$context_connectors_url_checked|default} />
                <label for="context_connectors_url_toggle"></label>
            </div>
        </div>
    </div>
</div>

<br />

<h3>{$_lang.context_manager_options}</h3>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
        <label for="context_mgr_path">{$_lang.context_manager_path}:</label>
    </div>
        <div class="context-property_value">
            <input type="text" id="context_mgr_path" name="context_mgr_path" value="{$context_mgr_path}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_mgr_path_toggle" name="context_mgr_path_toggle" value="1" {$context_mgr_path_checked|default} />
                <label for="context_mgr_path_toggle"></label>
            </div>
        </div>
    </div>
</div>

<div class="labelHolder">
    <div class="context-property">
        <div class="context-property_label">
        <label for="context_mgr_url">{$_lang.context_manager_url}:</label>
    </div>
        <div class="context-property_value">
            <input type="text" id="context_mgr_url" name="context_mgr_url" value="{$context_mgr_url}" />
            <div class="context-property_value_checkbox-wrapper">
                <input type="checkbox" id="context_mgr_url_toggle" name="context_mgr_url_toggle" value="1" {$context_mgr_url_checked|default} />
                <label for="context_mgr_url_toggle"></label>
            </div>
        </div>
    </div>
</div>

<br />

<div class="setup_navbar">
    <input type="submit" id="modx-next" class="button" name="proceed" value="{$_lang.next} &#xf054;" />
    <input type="button" onclick="MODx.go('database');" value="&#xf053; {$_lang.back}" id="modx-back" class="button" />
</div>
</form>
