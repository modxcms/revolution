{include file='resource/metatag/_javascript.tpl'}

<form id="form_metatag" method="post" action="{$_config.connectors_url}resource/metatag.php" onsubmit="return checkForm();">
<input type="hidden" name="a" value="82" />
<input type="hidden" name="op" value="82" />
<input type="hidden" name="id" value="" />
<br />
<!-- META tags -->
<div class="sectionHeader">{$_lang.metatags}</div>
<div class="sectionBody">
	{$_lang.metatag_intro}
	<br /><br />
	<div class="searchbar">
	<table style="width: 70%; ">
	<tbody>
	<tr>
		<td>
			<label for="add_tagname">{$_lang.name}</label>: 
			<input type="text" name="tagname" id="add_tagname" size="15" />
		</td>
		<td>
			<label for="add_tag">{$_lang.tag}</label>: 
			<select name="tag" id="add_tag">
        		<optgroup label="Named Meta Content">
        			<option value="abstract;0">abstract</option>
        			<option value="author;0">author</option>
        			<option value="classification;0">classification</option>
        			<option value="copyright;0">copyright</option>
        			<option value="description;0">description</option>
        			<option value="designer;0">designer</option>
        			<option value="distribution;0">distribution</option>
        			<option value="expires;1">expires</option>
        			<option value="generator;0">generator</option>
        			<option value="googlebot;0">googlebot</option>
        			<option value="keywords;0">keywords</option>
        			<option value="MSSmartTagsPreventParsing;0">MSSmartTagsPreventParsing</option>
        			<option value="owner;0">owner</option>
        			<option value="rating;0">rating</option>
        			<option value="refresh;0">refresh</option>
        			<option value="reply-to;0">reply-to</option>
        			<option value="revisit-after;0">revist-after</option>
        			<option value="robots;0">robots</option>
        			<option value="subject;0">subject</option>
        			<option value="title;0">title</option>
                </optgroup>
			    <optgroup label="HTTP-Header Equivalents">
        			<option value="content-language;1">content-language</option>
        			<option value="content-type;1">content-type</option>
        			<option value="expires;1">expires</option>
        			<option value="imagetoolbar;1">imagetoolbar</option>
        			<option value="pics-label;1">pics-label</option>
        			<option value="pragma;1">pragma</option>
        			<option value="refresh;1">refresh</option>
        			<option value="set-cookie;1">set-cookie</option>
        		</optgroup>
			</select>
		</td>
		<td>
			<label for="add_tagvalue">{$_lang.value}</label>: 
			<input type="text" name="tagvalue" id="add_tagvalue" size="20" />
		</td>
		<td>
			<input type="button" value="{$_lang.add_tag}" name="cmdsavetag" onclick="addTag()" id="add_save" /> 
			<input style="visibility:hidden;" type="button" value="{$_lang.cancel}" name="cmdcanceltag" onclick="cancelTag()" id="add_cancel" />
		</td>
	</tr>
	<tr>
		<td colspan="4">{$_lang.metatag_notice}</td>
	</tr>
	</tbody>
	</table>
	</div>
	
	
	<br />
	
	
	<div>
	<table class="grid">
	<thead>
	<tr>
		<th style="width: 50px;">{$_lang.delete}</th>
		<th>{$_lang.name}</th>
		<th>{$_lang.tag}</th>
		<th>{$_lang.value}</th>
	</tr>
	</thead>
	<tbody>
	{foreach from=$metatags item=mt}
	<tr>
		<td style="text-align:center;">
			<input name="tag[]" type="checkbox" value="{$mt->id}" />
			<img src="{$smarty.const.MODX_MANAGER_URL}media/style/{$_config.manager_theme}/images/icons/comment.gif" width="16" height="16" align="absmiddle" /></a>
		
		</td>
		<td>
			<a href="#" title="{$_lang.click_to_edit_title}" onclick="editTag({$mt->id});">{$mt->name}</a>
			<span style="display:none;">
			<script type="text/javascript">tagRows["{$mt->id}"]=["{$mt->name}","{$mt->tag}","{$mt->tagvalue}","{$mt->http_equiv}"];</script>
			</span>
		
		</td>
		<td>{$mt->tag}</td>
		<td>{$mt->tagvalue}</td>
	</tr>
	{foreachelse}
	<tr>
		<td colspan="4">{$_lang.no_records_found}</td>
	</tr>
	{/foreach}
	</tbody>
	</table>
	
	</div>
	
	<input type="button" name="cmddeltag" value="{$_lang.delete_tags}" onclick="deleteTag();" style="margin: 1em;" />
</div>
</form>