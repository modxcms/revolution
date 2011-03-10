<div id="modx-panel-resource-div"></div>

<div id="modx-resource-tvs-div">{$tvOutput}</div>

{include file='resource/_javascript.tpl'}

{$onDocFormPrerender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}