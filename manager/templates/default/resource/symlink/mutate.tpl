<div id="modx-panel-symlink"></div>

{include file='resource/_javascript.tpl'}
{modblock name='ab'}{/modblock}

{$onDocFormPrerender}
<!-- BEGIN TOP PANE -->

<!-- START Template Variables -->
<div id="modx-tab-tvs" class="padding x-hide-display">
{include file='resource/sections/tvs.tpl'}
</div>
<!-- END Template Variables -->

<!-- START Access Permissions -->
<div id="modx-tab-access" class="padding x-hide-display">
	<h2>{$_lang.security}</h2>
	
	<p>{$_lang.access_permissions_docs_message}</p>
	<div id="modx-grid-resource-security"></div>
</div>
<!-- END Access Permissions -->
	
{modblock name='otherTabs'}{/modblock}
<!-- END TOP PANE -->

<br /><br />

{$onDocFormRender}