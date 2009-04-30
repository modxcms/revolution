{modblock name='ab'}{/modblock}

<div class="padding">

<h2>{$_lang.import_site_html}</h2>

<p>{$_lang.import_site_message}</p>

<div id="modx-import-results"></div>


<form id="modx-import-site" method="post" action="{$_config.connectors_url}system/import/html.php" onsubmit="return false;">
<table class="classy">
<tbody>
<tr>
    <th><label for="modx-import-element">{$_lang.import_enter_root_element}{$_lang.import_element}</label></th>
    <td class="x-form-element">
        <input id="modx-import-element" name="import_element" type="text" />
    </td>
</tr>
<tr class="odd">
    <th><label for="modx-ih-resource-tree">{$_lang.import_parent_document}</label></th>
    <td>
        {$_lang.import_use_doc_tree}
    </td>
</tr>
</tbody>
</table>

<div style="padding: 1em;">
    <div id="modx-ih-resource-tree" class="tree"><div id="modx-ih-resource-tree-tb"></div></div>
    <br style="clear: right" />
</div>
<br style="clear: both" />

<input type="hidden" id="modx-import-parent" name="import_parent" />
<input type="hidden" id="modx-import-context" name="import_context" />


</form>

<script type="text/javascript" src="assets/modext/widgets/resource/modx.tree.resource.simple.js"></script>
<script type="text/javascript" src="assets/modext/sections/system/import/html.js"></script>

