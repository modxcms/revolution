{modblock name='ab'}{/modblock}

<div class="padding">

<h2>{$_lang.import_site_resource}</h2>

<p>{$_lang.import_site_resource_message}</p>

<div id="modx-import-results"></div>

<form id="modx-import-site" method="post" action="{$_config.connectors_url}system/import/index.php" onsubmit="return false;">
<table class="classy">
<tbody>
<tr>
    <th style="width:300px;"><label for="modx-import-base-path">{$_lang.import_base_path}</label></th>
    <td class="x-form-element">
        <input id="modx-import-base-path" name="import_base_path" type="text" />
    </td>
</tr>
<tr class="odd">
    <th><label for="modx-import-resource-class">{$_lang.import_resource_class}</label></th>
    <td class="x-form-element">
        <input id="modx-import-resource-class" name="import_resource_class" type="text" />
    </td>
</tr>
<tr>
    <th><label for="modx-import-allowed-extensions">{$_lang.import_allowed_extensions}</label></th>
    <td class="x-form-element">
        <input id="modx-import-allowed-extensions" name="import_allowed_extensions" type="text" />
    </td>
</tr>
<tr class="odd">
    <th><label for="modx-import-element">{$_lang.import_element}</label></th>
    <td class="x-form-element">
        <input id="modx-import-element" name="import_element" type="text" />
    </td>
</tr>
<tr>
    <th><label for="modx-import-resource-tree">{$_lang.import_parent_document}</label></th>
    <td>
        {$_lang.import_parent_document_message}
    </td>
</tr>
</tbody>
</table>

<div style="padding: 1em;">
    <div id="modx-import-resource-tree" class="tree"></div>
    <br style="clear: right" />
</div>
<br style="clear: both" />

<input type="hidden" id="modx-import-parent" name="import_parent" />
<input type="hidden" id="modx-import-context" name="import_context" />


</form>

<script type="text/javascript" src="assets/modext/widgets/resource/modx.tree.resource.simple.js"></script>
<script type="text/javascript" src="assets/modext/sections/system/import/resource.js"></script>
