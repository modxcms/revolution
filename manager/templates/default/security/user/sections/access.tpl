<p>{$_lang.access_permissions_user_message}</p>

<ul class="no_list">
{foreach from=$usergroups item=group}
<li>
	<label>
		<input type="checkbox" name="user_groups[]" value="{$group->id}" {if in_array($group->id, $groupsarray)}checked="checked"{/if} />
		{$group->name}
	</label>
</li>
{/foreach}
</ul>
<span id="user_groups_error" class="error"></span>