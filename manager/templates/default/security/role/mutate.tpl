{include file='security/role/_javascript.tpl'}

{modblock name='ab'}{/modblock}

<form id="mutate_role" action="{$_config.connectors_url}security/role.php" method="post" onsubmit="return false;">
<input type="hidden" name="mode" value="{$smarty.get.a}" />
<input type="hidden" name="id" value="{$smarty.get.id}" />

<div id="tabs_div">

<div id="tab_information" class="padding x-hide-display">
{include file='security/role/sections/information.tpl'}
</div>
<div id="tab_permissions" class="padding x-hide-display">
{include file='security/role/sections/permissions.tpl'}
</div>
{modblock name='othertabs'}{/modblock}

</div>

</form>

