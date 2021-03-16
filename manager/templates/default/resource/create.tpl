<div id="modx-panel-resource-div"></div>
<div id="modx-resource-tvs-div" class="modx-resource-tab x-form-label-left x-panel">{$tvOutput|default}</div>
{foreach from=$hidden item=tv name='tv'}
    <input type="hidden" id="tvdef{$tv->id}" value="{$tv->default_text|escape}" />
    {$tv->get('formElement')}
{/foreach}

{$onDocFormPrerender|default}
{if $resource->richtext AND $_config.use_editor}
    {$onRichTextEditorInit|default}
{/if}
