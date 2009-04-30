<form id="read_form">
	<table class="classy classynoborder">
		<tbody>
			<tr>
				<th><label for="m{$message->id}_reply_subject">{$_lang.messages_subject}</label>:</th>
				<td><input type="text" name="subject" id="m_read_subject" value="" size="50" readonly="readonly" class="textfield" /></td>
			</tr>
			<tr>
				<th><label for="m_read_text">{$_lang.messages_message}</label>:</th>
				<td><textarea id="m_read_text" name="message" cols="50" rows="5" readonly="readonly" class="textarea" modx:width="500"></textarea></td>
			</tr>
		</tbody>
	</table>
</form>