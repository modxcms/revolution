<div id="modx-panel-resource-div"></div>
<div id="modx-resource-tvs-div">{$tvOutput}</div>
{$onDocFormPrerender}
{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}