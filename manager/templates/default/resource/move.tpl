{literal}
<script type="text/javascript">
// <![CDATA[
parent.tree.ca = 'move';

function setMoveValue(pId, pName) {
    if (pId==0 || checkParentChildRelation(pId, pName)) {
        $('new_parent').value = pId;
        $('parentName').innerHTML = '{/literal}{$_lang.new_parent}{literal}: <strong>'+pId+'</strong> ('+pName+')';
    }
}

// check if the selected parent is a child of this document
function checkParentChildRelation(pId, pName) {
    var sp;
    var id = $('id').value;
    var tdoc = parent.tree.document;
    var pn = $('node'+pId);
	var pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pId) : tdoc.all["node"+pId];
    if (pn.id.substr(4) == id) {
        alert('{/literal}{$_lang.illegal_parent_self}{literal}');
        return;
    }
    else {
        while (pn.p>0) {
            pn = (tdoc.getElementById) ? tdoc.getElementById("node"+pn.p) : tdoc.all["node"+pn.p];
            if (pn.id.substr(4) == id) {
                alert('{/literal}{$_lang.illegal_parent_child}{literal}');
                return;
            }
        }
    }
    return true;
}

function movedocument() { return false; }
// ]]>
</script>
{/literal}



<div class="subTitle">
	<span class="right">
		<img src="media/style/{$_config.manager_theme}/images/_tx_.gif" width="1" height="5" />
		<br />{$_lang.move_document_title}
	</span>

	{actionButtons data=$modActionButtons}
</div>

<div class="sectionHeader">{$_lang.move_document_title}</div>
<div class="sectionBody">

<p id="errormsg"></p>

<p>{$_lang.move_document_message}</p>

<form method="post" action="{$_config.connectors_url}resource/move.php" id="move_document">
<input type="hidden" name="a" id="a" value="52" />
<input type="hidden" name="id" id="id" value="{$document->id}" />
<input type="hidden" name="idshow" id="idshow" value="{$document->id}" />

{$_lang.document_to_be_moved}: <strong>{$document->id}: "{$document->pagetitle}"</strong> 
<br />

<span id="parentName" class="warning">{$_lang.move_document_new_parent}</span>
<br />
<input type="hidden" name="new_parent" id="new_parent" value="" />
<br />
<input type="hidden" name="save" value="Move" style="display:none" />

</form>
</div>
