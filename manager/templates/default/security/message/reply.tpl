<form id="reply_form" action="{$_config.connectors_url}security/message.php" method="post" onsubmit="return false;">
	<table class="classy classynoborder">
		<tbody>
			<tr>
				<th><label for="m_reply_subject">{$_lang.messages_subject}</label>:</th>
				<td class="x-form-element"><input type="text" name="subject" id="m_reply_subject" size="50" class="textfield" modx:allowblank="0" /></td>
			</tr>
			<tr>
				<th><label for="m_reply_user">{$_lang.messages_send_to}</label>:</th>
				<td class="x-form-element">
					<input checked="checked" type="radio" name="type" id="m_reply_type1" value="user" class="radio" onclick="Messages.changeType('user','m_reply_');" /> {$_lang.messages_user}
					&nbsp;<input type="radio" name="type" id="m_reply_type2" value="role" class="radio" onclick="Messages.changeType('role','m_reply_');" /> {$_lang.messages_role}
					&nbsp;<input type="radio" name="type" id="m_reply_type3" value="all" class="radio" onclick="Messages.changeType('all','m_reply_');" /> {$_lang.messages_all}		
					<div id="m_reply_udiv">
						<select name="user" id="m_reply_user" class="combobox">
						{foreach from=$users item=user}
							<option value="{$user->id}">{$user->username}</option>
						{/foreach}
						</select>
					</div>
					<div id="m_reply_rdiv">
						<select name="role" id="m_reply_role" class="combobox" style="display: none;">
				    	{foreach from=$roles item=role}
					        <option value="{$role->id}">{$role->name}</option>
						{/foreach}
						</select>
					</div>
				</td>
			</tr>
			<tr>
				<th><label for="m_reply_text">{$_lang.messages_message}</label>:</th>
				<td class="x-form-element">
					<textarea id="m_reply_text" name="message" cols="50" rows="5" class="textarea" modx:width="500" modx:grow="1"></textarea>
				</td>
			</tr>
		</tbody>
	</table>
</form>