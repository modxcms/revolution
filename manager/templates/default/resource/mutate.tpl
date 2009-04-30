<div id="modx-panel-resource"></div>

{include file='resource/_javascript.tpl'}
{modblock name='ab'}{/modblock}
{$onDocFormPrerender}
<!-- BEGIN TOP PANE -->

<!-- START Template Variables -->
<div id="modx-tab-tvs" class="x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->


{$onDocFormRender}

{if $resource->richtext AND $_config.use_editor}
{$onRichTextEditorInit}
{/if}