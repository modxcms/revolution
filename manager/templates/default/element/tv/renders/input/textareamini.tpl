<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	class="textarea"
	cols="40" rows="5"
	onchange="MODx.fireResourceFormChange();"
>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'textarea'
    ,applyTo: 'tv{$tv->id}'
    ,width: 300
    ,grow: true
{literal}
});
{/literal}
</script>