<div id="modx-panel-weblink-div"></div>
<div id="modx-resource-tvs-div" class="modx-resource-tab x-form-label-left x-panel">{$tvOutput}</div>

{$onDocFormPrerender}
{if $resource->richtext AND $_config.use_editor}
    {$onRichTextEditorInit}
{/if}
