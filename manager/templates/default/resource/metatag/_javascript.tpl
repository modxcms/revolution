{literal}
<script type="text/javascript">
// <![CDATA[
// meta tag rows
var tagRows = []; // stores tag information in 2D array. 2nd array = 0-name,1-tag,2-value,3-http_equiv

function checkForm() {
	var requireConfirm=false;
	var deleteList='';
	{/literal}
	{foreach from=$metatags item=mt}
	if($('delete{$mt->id}').checked) {literal}{{/literal}
		requireConfirm = true;
		deleteList = deleteList + "\n - {$mt->keyword|escape:'javascript'}";
	{literal}}{/literal}
	{/foreach}{literal}
	if(requireConfirm) {
		var agree = confirm("{/literal}{$_lang.confirm_delete_keywords}{literal}\n"+deleteList);
		if(agree) {
			return true;
		} else {
			return false;
		}
	}
	return true;
}

function addTag() {
	var f = $('form_metatag');
	if (!f) return;
	if (!f.tagname.value) alert("{/literal}{$_lang.require_tagname}{literal}");
	else if(!f.tagvalue.value) alert("{/literal}{$_lang.require_tagvalue}{literal}");
	else {
		f.op.value=(f.cmdsavetag.value=="{/literal}{$_lang.save_tag}{literal}") ? 'edttag':'addtag';
		f.submit();
	}
}

function editTag(id){
	var opt;
	var f = $('form_metatag');
	if(!f) return;
	f.tagname.value = tagRows[id][0];
	f.tagvalue.value= tagRows[id][2];
	for(i=0;i<f.tag.options.length;i++) {
		opt = f.tag.options[i];
		tagkey = tagRows[id][1]+';'+tagRows[id][3]; // combine tag and style to make key
		if(opt.value == tagkey) {
			opt.selected = true;
			break;
		}
	}
	f.id.value = id;
	f.cmdsavetag.value = '{/literal}{$_lang.save_tag}{literal}';
	f.cmdcanceltag.style.visibility = 'visible';
	f.tagname.focus();
}

function cancelTag(id){
	var opt;
	var f = $('form_metatag');
	if(!f) return;
	f.tagname.value = '';
	f.tagvalue.value = '';
	f.tag.options[0].selected = true;
	f.id.value = '';
	f.cmdsavetag.value = '{/literal}{$_lang.add_tag}{literal}';
	f.cmdcanceltag.style.visibility = 'hidden';
}

function deleteTag() {
	var f = $('form_metatag');
	if(!f) return;
	else if(confirm("{/literal}{$_lang.confirm_delete_tags}{literal}")) {
		f.op.value = 'deltag';
		f.submit();
	}
}
// ]]>
</script>
{/literal}