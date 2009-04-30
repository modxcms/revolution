<div class="fakefieldset">
<table class="standard">
<tbody>
<tr>
    <th><label for="name">{$_lang.role_name}</label></th>
	<td class="x-form-element">
		<input name="name" id="name" type="text" class="textfield" modx:width="300" modx:allowblank="0" modx:maxlength="50" value="{$role->name}" onchange="documentDirty=true;" />
		<span id="name_error" class="error"></span>
	</td>
</tr>
<tr>
	<th><label for="description">{$_lang.document_description}</label></th>
	<td class="x-form-element">
		<input name="description" id="description" type="text" class="textfield" modx:width="300" modx:maxlength="255" value="{$role->description}" onchange="documentDirty=true;" />
	</td>
</tr>
</tbody>
</table>
</div>