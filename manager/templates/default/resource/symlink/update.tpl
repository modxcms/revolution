<div id="modx-panel-symlink-div"></div>
<div id="modx-resource-tvs-div" class="modx-resource-tab x-form-label-left x-panel">{$tvOutput|default}</div>

{$onDocFormPrerender}
{if $resource->richtext AND $_config.use_editor}
    {$onRichTextEditorInit}
{/if}
