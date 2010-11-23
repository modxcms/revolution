<input id="tv{$tv->id}" name="tv{$tv->id}"
	type="text" class="textfield"
	value="{$tv->get('value')|escape}"
	{$style}
	tvtype="{$tv->type}"
/>

<script type="text/javascript">
// <![CDATA[
{literal}
var fld = MODx.load({
{/literal}
    xtype: 'numberfield'
    ,applyTo: 'tv{$tv->id}'
    ,width: '97%'
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
MODx.makeDroppable(fld);
{/literal}
// ]]>
</script>