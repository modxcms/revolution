<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	cols="40" rows="15"
	{literal}onchange="MODx.fireResourceFormChange();"{/literal}
>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
{literal}
MODx.load({
{/literal}
    xtype: 'textarea'
    ,applyTo: 'tv{$tv->id}'
    ,value: '{$tv->get('value')|escape:'javascript'}'
    ,width: 300
    ,height: 140
{literal}
});
{/literal}
</script>