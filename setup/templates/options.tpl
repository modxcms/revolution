{include file='header.tpl'}
<h2>{$_lang.options_title}</h2>

<hr />

<table class="options">
<tbody>
<tr>
	<th style="width: 43%;">
		<img src="assets/images/im_new_inst.gif" width="32" height="32" alt="" />
		
		<label>
			<input type="radio" name="installmode" id="installmode0" value="0" {if $installmode EQ 0} checked="checked"{/if} />
			{$_lang.options_new_installation}
		</label>
	</th>
	<td>
		{$_lang.options_install_new_copy} {$app_name} - 
		<strong>{$_lang.options_install_new_note}</strong>
	</td>
</tr>
{if $installmode GT 0}
<tr>
	<th>&nbsp;</th>
	<td style="background: #fffdbb; padding:0 1em; border:2px solid #CBD499">
		<h3>{$_lang.options_important_upgrade}</h3>
		<p>{$_lang.options_important_upgrade_note}</p>
	</td>
</tr>
{/if}
<tr>
	<th>
		<img src="assets/images/im_inst_upgrade.gif" width="32" height="32" alt=""/>
   		
		<label>
			<input type="radio" name="installmode" id="installmode1" value="{$installmode}"{if $installmode LT 1} disabled="disabled"{/if}{if $installmode GT 0} checked="checked"{/if} />
			{$_lang.options_upgrade_existing}
		</label>
	</th>
	<td>{$_lang.options_upgrade_existing_note}</td>
</tr>
<tr>
	<th>
		<img src="assets/images/im_inst_upgrade.gif" width="32" height="32" alt="" />
		<label>
			<input type="radio" name="installmode" id="installmode2" value="{$installmode}"{if $installmode LT 1} disabled="disabled"{/if} />
			{$_lang.options_upgrade_advanced}
		</label>
	</th>
	<td>{$_lang.options_upgrade_advanced_note}</td>
</tr>
{if $smarty.const.MODX_SETUP_KEY EQ '@traditional' AND $unpacked EQ 1 AND $files_exist EQ 1}
<input type="hidden" name="unpacked" id="unpacked" value="1" />
<input type="hidden" name="inplace" id="inplace" value="1" />
{else}
<tr>
	<th style="padding-top: 3em;">
		<label>
			<input type="checkbox" name="unpacked" id="unpacked" value="1"{if $unpacked EQ 0} disabled="disabled"{/if}{if $unpacked EQ 1} checked="checked"{/if} />
			{$_lang.options_core_unpacked}
		</label>
	</th>
	<td style="padding-top: 3em;">{$_lang.options_core_unpacked_note}</td>
</tr>
<tr>
    <th>
		<label>
	   		<input type="checkbox" name="inplace" id="inplace" value="1"{if $files_exist EQ 0} disabled="disabled"{/if}{if $files_exist EQ 1} checked="checked"{/if} />
			{$_lang.options_core_inplace}
		</label>
	</th>
    <td>{$_lang.options_core_inplace_note}</td>
</tr>
{/if}
</tbody>
</table>
<br />
{include file='footer.tpl'}
