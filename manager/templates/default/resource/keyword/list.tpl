{include file='resource/keyword/_javascript.tpl'}

<form id="form_keyword" method="post" action="{$_config.connectors_url}resource/keyword.php" onsubmit="return checkForm();">
<input type="hidden" name="a" value="82" />
<input type="hidden" name="op" value="82" />
<input type="hidden" name="id" value="" />
<br />
<div class="sectionHeader">{$_lang.keywords}</div>
<div class="sectionBody">
	
	<p>{$_lang.keywords_intro}</p>
	<br />

<table class="records" style="width: 100%;">
<thead>
<tr>
	<th style="width: 50px;">{$_lang.delete}</th>
	<th>{$_lang.keyword}</th>
	<th>{$_lang.rename}</th>
</tr>
</thead>
<tbody>
{foreach from=$keywords item=kw}
<tr class="{cycle values=',odd'}">
	<td style="text-align:center;">
		<input id="delete{$kw->id}" name="delete_keywords[{$kw->id}]" type="checkbox" />
	</td>
	<td>
		<a onclick="modifyKeyword({$kw->id});" style="cursor:pointer" href="javascript:void(0);">{$kw->keyword}</a>
	</td>
	<td>
		<input type="hidden" name="orig_keywords[keyword{$kw->id}]" value="{$kw->keyword}" />
		<input type="text" name="rename_keywords[keyword{$kw->id}]" value="{$kw->keyword}" style="width:100%;" />
	</td>
</tr>
{foreachelse}
<tr>
	<td colspan="3">{$_lang.no_keywords_found}</td>
</tr>
{/foreach}
</tbody>
</table>

<table style="margin-top: 1em;">
<tbody>
<tr>
	<td>
		<input type="submit" value="{$_lang.save_all_changes}" onsubmit="return checkForm();" />
	</td>
	<td align="right">{$_lang.new_keyword}</td>
	<td>
		<input type="text" name="new_keyword" value="" size="30" />
	</td>
</tr>
</tbody>
</table>
</div>
</form>
