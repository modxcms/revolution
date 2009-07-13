<div id="modx-panel-weblink-div"></div>

{include file='resource/_javascript.tpl'}

{modblock name='ab'}{/modblock}

{$onDocFormPrerender}
<input type="hidden" class="hidden" id="id" name="id" value="{$resource->id}" />
<input type="hidden" class="hidden" id="parent" name="parent" value="{$resource->parent}" />

<!-- BEGIN TOP PANE -->

<!-- START Template Variables -->
<div id="modx-tab-tvs" class="padding x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->

<!-- START Access Permissions -->
<div id="modx-tab-access" class="padding x-hide-display">
	<h2>{$_lang.security}</h2>
	
	<p>{$_lang.resource_access_message}</p>
	<div id="modx-grid-resource-security"></div>
</div>
<!-- END Access Permissions -->
	
{modblock name='otherTabs'}{/modblock}
<!-- END TOP PANE -->

<br /><br />

{$onDocFormRender}