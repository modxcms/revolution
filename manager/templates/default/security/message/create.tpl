<form id="new_message_form" action="{$_config.connectors_url}security/message.php" method="post" onsubmit="return false;">
	<table class="classy classynoborder">
		<tbody>
			<tr>
				<th><label for="mn_subject">{$_lang.messages_subject}</label>:</th>
				<td class="x-form-element"><input type="text" name="subject" id="mn_subject" value="" size="50"class="textfield" modx:allowblank="0" /></td>
			</tr>
			<tr>
				<th><label for="mn_recipient">{$_lang.messages_send_to}</label>:</th>
				<td class="x-form-element">
					<input checked="checked" type="radio" name="type" id="mn_type1" value="user" class="radio" onclick="Messages.changeType('user','mn_');" /> {$_lang.messages_user}
					&nbsp;<input type="radio" name="type" id="mn_type2" value="role" class="radio" onclick="Messages.changeType('role','mn_');" /> {$_lang.messages_role}
					&nbsp;<input type="radio" name="type" id="mn_type3" value="all" class="radio" onclick="Messages.changeType('all','mn_');" /> {$_lang.messages_all}			
					<div id="mn_udiv">	
						<select name="user" id="mn_user" class="combobox">
						{foreach from=$users item=user}
							<option value="{$user->id}">{$user->username}</option>
						{/foreach}
						</select>
					</div>
					<div id="mn_rdiv">
						<select name="role" id="mn_role" class="combobox">
				    	{foreach from=$roles item=role}
					        <option value="{$role->id}">{$role->name}</option>
						{/foreach}
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="mn_text">{$_lang.messages_message}</label>:</th>
				<td class="x-form-element">
					<textarea id="mn_text" name="message" cols="50" rows="5" class="textarea" modx:width="500" modx:grow="1"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</form>