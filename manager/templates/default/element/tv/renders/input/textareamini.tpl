<textarea id="tv{$tv->id}" name="tv{$tv->id}"
	class="textarea"
	cols="40" rows="5"
>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
// <![CDATA[
{literal}
MODx.load({
{/literal}
    xtype: 'textarea'
    ,applyTo: 'tv{$tv->id}'
    ,width: 300
    ,grow: true
    ,enableKeyEvents: true
{literal}
    ,listeners: { 'keydown': { fn:MODx.fireResourceFormChange, scope:this}}
});
{/literal}
// ]]>
</script>