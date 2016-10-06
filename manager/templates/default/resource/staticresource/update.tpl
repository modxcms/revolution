<div id="modx-panel-static-div"></div>
<div id="modx-resource-tvs-div" class="modx-resource-tab x-form-label-left x-panel">{$tvOutput|default}</div>

{$onDocFormPrerender}
{if $resource->richtext AND $_config.use_editor}
    {$onRichTextEditorInit}
{/if}
