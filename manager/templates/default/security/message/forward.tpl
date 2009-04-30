<form id="forward_form" action="{$_config.connectors_url}security/message.php" method="post" onsubmit="return false;">
	<table class="classy classynoborder">
		<tbody>
			<tr>
				<th><label for="m_fwd_subject">{$_lang.messages_subject}</label>:</th>
				<td class="x-form-element"><input type="text" name="subject" id="m_fwd_subject" size="50" class="textfield" modx:allowblank="0" /></td>
			</tr>
			<tr>
				<th><label for="m_fwd_recipient">{$_lang.messages_send_to}</label>:</th>
				<td class="x-form-element">
					<input checked="checked" type="radio" name="type" id="m_fwd_type1" value="user" class="radio" onclick="Messages.changeType('user','m_fwd_');" /> {$_lang.messages_user}
					&nbsp;<input type="radio" name="type" id="m_fwd_type2" value="role" class="radio" onclick="Messages.changeType('role','m_fwd_');" /> {$_lang.messages_role}
					&nbsp;<input type="radio" name="type" id="m_fwd_type3" value="all" class="radio" onclick="Messages.changeType('all','m_fwd_');" /> {$_lang.messages_all}		
					<div id="m_fwd_udiv">
						<select name="user" id="m_fwd_user" class="combobox">
						{foreach from=$users item=user}
							<option value="{$user->id}">{$user->username}</option>
						{/foreach}
						</select>
					</div>	
					<div id="m_fwd_rdiv">
						<select name="role" id="m_fwd_role" class="combobox">
				    	{foreach from=$roles item=role}
					        <option value="{$role->id}">{$role->name}</option>
						{/foreach}
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="m_fwd_text">{$_lang.messages_message}</label>:</th>
				<td class="x-form-element">
					<textarea id="m_fwd_text" name="message" cols="50" rows="5" class="textarea" modx:width="500" modx:grow="1"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</form>
