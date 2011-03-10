<textarea id="tv{$tv->id}" name="tv{$tv->id}" class="modx-richtext" {literal}onchange="MODx.fireResourceFormChange();"{/literal}>{$tv->get('value')|escape}</textarea>

<script type="text/javascript">
{literal}
Ext.onReady(function() {
    {/literal}
    MODx.makeDroppable(Ext.get('tv{$tv->id}'));
    {literal}
});{/literal}
</script>