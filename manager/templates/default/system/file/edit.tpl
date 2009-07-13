<div class="padding">

<h2>{$_lang.file_edit}</h2>

<div id="panel-file-edit-div"></div>

<script type="text/javascript" src="assets/modext/sections/system/file/edit.js"></script>
{literal}
<script type="text/javascript">
Ext.onReady(function() {
    MODx.load({
        xtype: 'page-file-edit'
        ,file: "{/literal}{$file}{literal}"
    });
});
</script>
{/literal}

</div>